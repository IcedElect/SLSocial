<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Translate
{

    var $lang_code;
    var $aTranslate;
    var $CI;
    var $aLang_codes;
    var $no_db = false;

    function __construct($no_db = false)
    {
        $this->CI =& get_instance();
        $this->my_cache =& $this->CI->my_cache;
        $this->session =& $this->CI->session;
        $this->no_db = $no_db;
        if ($no_db){
            $this->CI->load->model('default_model', 'translate_model');
            $this->translate_model =& $this->CI->translate_model;
            $this->translate_model->setTable($this->CI->config->item('translate', 'tables'));
            $this->CI->load->model('language_model');
            $this->language_model =& $this->CI->language_model;
        }
        $this->init();
    }

    function set_lang_code($lang_code)
    {
        $this->lang_code = $lang_code;
        if ($this->check_lang_code($lang_code)) {
            $this->lang_code = $lang_code;
        } else {
            $this->lang_code = $this->get_default_lang_code();
        }
        $this->CI->config->set_item('curr_lang', $this->lang_code);
    }

    function get_default_lang_code()
    {
        return 'russian';
    }

    function check_lang_code($lang_code)
    {
        return !empty($this->aLang_codes[$lang_code]);
    }

    public function get_all_langs()
    {
        if (empty($this->aLang_codes)) {
            $lang_codes = $this->language_model->getAllData();
            foreach ($lang_codes as $key => $value) {
                $this->aLang_codes[$value->code] = $this->t('lang_' . $value->code, 'lang_' . $value->code);
            }
        }
        return $this->aLang_codes;
    }

    function get_lang_options()
    {
        $lang_codes = $this->get_all_langs();
        $options = array();
        foreach ($lang_codes as $key => $name) {
            $options[$key] = $name;
        }
        return $options;
    }

    function init()
    {
        $lang = $this->session->userdata('cur_lang');
        $this->set_lang_code($lang);
        return;
        if (!$this->no_db){
            $this->load_cur_language();
        } else {
            $file_list = array();
            foreach(scandir('system/language/' . $this->lang_code) as $file){
                if($file != '.' && $file != '..' && strpos($file,'.php')){
                    $_file = substr($file, 0, strpos($file, '_lang'));
                    $file_list[] = $_file;
                }
            }

            $this->CI->lang->load($file_list, $this->lang_code);
        }

    }

    function get_current_lang()
    {
        return $this->lang_code;
    }

    function load_cur_language()
    {
        //cache for 1 hour
        $this->aTranslate[$this->lang_code] = $this->my_cache->load_cache_val('lang_' . $this->lang_code, 3600, array($this, 'get_full_lang_translations'), $this->lang_code);
    }

    function get_full_lang_translations($lang_code)
    {
        if (!empty($this->aTranslate[$lang_code])) {
            return $this->aTranslate[$lang_code];
        } else {
            $where = array('lang_id' => $lang_code);
            $translate = $this->translate_model->getDatabyWhere($where);
            $result = array();
            if (!empty($translate)) {
                foreach ($translate as $key => $value) {
                    $result[$value->tr_code] = $value->tr_text;
                }
            }
            return $result;
        }
    }

    private function add_translate($str, $default_str)
    {
        $aData = array(
            'tr_code' => $str,
            'tr_text' => $default_str,
            'lang_id' => $this->get_default_lang_code(),
        );
        $this->clear_lang_cache($this->lang_code);
        if ($translate = $this->translate_model->getDataByWhere(array('tr_code' => $str))) {
        } else {
            $this->translate_model->save($aData, 'add');
        }
        $this->aTranslate[$this->lang_code][$str] = $default_str;
    }

    public function get_translate($str)
    {
        if (!empty($this->aTranslate[$this->lang_code][$str])) {
            return $this->aTranslate[$this->lang_code][$str];
        }
        return false;
    }

    public function t($str, $default_str)
    {
        if (empty($default_str)) {
            return '';
        }
        if ($this->no_db){
            $t = $this->CI->lang->line($str);
            return $t ? $t : $default_str;
        } else {
            $t = $this->get_translate($str);
            if (!$t) {
                $this->add_translate($str, $default_str);
                return $default_str;
            } else {
                return $t;
            }
        }
    }

    public function translit($fp_str)
    {
        $abc = array(
            "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D",
            "Е" => "E", "Ё" => "JO", "Ж" => "ZH",
            "З" => "Z", "И" => "I", "Й" => "JJ", "К" => "K", "Л" => "L",
            "М" => "M", "Н" => "N", "О" => "O", "П" => "P", "Р" => "R",
            "С" => "S", "Т" => "T", "У" => "U", "Ф" => "F", "Х" => "KH",
            "Ц" => "C", "Ч" => "CH", "Ш" => "SH", "Щ" => "SHH", "Ъ" => "'",
            "Ы" => "Y", "Ь" => "", "Э" => "E", "Ю" => "YU", "Я" => "YA",
            "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d",
            "е" => "e", "ё" => "jo", "ж" => "zh",
            "з" => "z", "и" => "i", "й" => "jj", "к" => "k", "л" => "l",
            "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
            "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "kh",
            "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "shh", "ъ" => "",
            "ы" => "y", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya"
        );
        return strtr($fp_str, $abc);
    }

    public function clear_lang_cache($lang_code)
    {
        $this->my_cache->delete('lang_' . $lang_code);
    }
}

