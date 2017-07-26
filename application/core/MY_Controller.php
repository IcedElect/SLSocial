<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Controller extends MX_Controller
{

    protected $aFields = array();
    public $activeModule = "";
    protected $aVars = array();
    protected $oUser;

    function __construct()
    {
        $this->benchmark->mark('execution_time: conroller_start');
        if ($this->config->item('enable_profiler')) {
            $this->load->library('profiler');
        }
        parent::__construct();

        //$this->output->enable_profiler(TRUE);
        //$this->load->library('user_agent');
        $this->load->helper('date');
        $this->load->library('MY_Smarty');
        $this->load->library('my_cache');
        $this->load_variables();
        $this->load->library('Date_helper');
        $this->load->helper('url');
        $this->load->library('Translate');
        $this->load->library('messages');//before user Important !!
        $this->load->library('Frontend');
        $this->load->library('user');// here redirect if no login
        $this->user->assign_user($this->oUser);
        $this->load->library('Menu');
        $this->load->library('Locker');
        $this->load->library('Cron');
        $this->cron->run();


        //init_variables
    }

    function check_login()
    {
        $this->user->check_login();
    }

    protected function setActiveModule($fp_module = '')
    {
        $this->activeModule = $fp_module;
        $this->permission->setActiveModule($fp_module);
        $this->frontend->aConf_add('active_module', $fp_module);
        $this->frontend->add_body_class('page_' . $fp_module);
    }


    protected function returnJson($fp_Val)
    {
        if ($this->config->item('use_php_json')) {
            echo json_encode($fp_Val);
        } else {
            $this->load->library('Json');
            echo $this->json->encode($fp_Val);
        }
    }

    protected function getValidationFields()
    {
        $result = array();
        foreach ($this->aFields as $key => $value) {
            if ($value['validate']) {
                $result['rules'][$value['field']] = $value['rules'];
                $result['fields'][$value['field']] = $value['title'];
            }
        }
        return $result;
    }


    private function load_variables()
    {
        $this->load->model('default_model', 'variable_model');
        $this->variable_model->setTable($this->config->item('variables', 'tables'));
        $_aVars = $this->variable_model->getAllData();
        $this->oVars = array();
        if (!empty($_aVars)) {
            foreach ($_aVars as $key => $value) {
                $this->aVars[$value->name] = $value->value;
            }
        }
        //$this->my_smarty->assign('aVars',$this->aVars);
    }

    protected function get_variable($name, $value = '')
    {
        if (empty($name)) {
            return "";
        }
        if (empty($this->aVars[$name])) {
            return "";
        } else {
            return $this->aVars[$name];
        }
    }

    protected function is_ajax()
    {
        $is_ajax = $this->input->get_post('ajax');
        if (!empty($is_ajax) || $this->input->is_ajax_request()) {
            return true;
        }
        return false;
    }

    protected function translit($fp_str)
    {
        return $this->translate->translit($fp_str);
    }

    protected function release_locked_page($fp_page_id, $redirect = false)
    {
        return $this->locker->release_locked_page($fp_page_id);;
    }

    protected function t($str, $default_str)
    {
        return $this->translate->t($str, $default_str);
    }

    function redirect_https()
    {
        return isset($_SERVER['HTTPS']);
    }


}

