<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Frontend
{
    var $body_classes = array();
    var $aCssFiles = array();
    var $aJsFiles = array();
    var $aConf = array();
    var $aMeta = array();
    var $pageTitle = '';
    var $layout = 'main.tpl';
    var $mail_layout = 'mail_layout.tpl';

    var $template = 'Social';
    var $template_dir = 'themes/Social/';

    private $is_backend = false;
    private $CI;

    function __construct()
    {
        $this->CI =& get_instance();
        $this->config =& $this->CI->config;
        $this->translate =& $this->CI->translate;
        $this->uri =& $this->CI->uri;
        $this->my_cache =& $this->CI->my_cache;
        $this->my_smarty =& $this->CI->my_smarty;
        $this->menu =& $this->CI->menu;
        $this->aConf_init_default_conf();
        $this->user =& $this->CI->user;
        $this->setTitle('');

    }

    private function init_default_head()
    {
        $this->aConf['js_values'] = array('path' => $this->template_dir);

        $this->add_meta('content-type', 'content-type', 'text/html; charset=utf-8', 'http-equiv');
        if(!$this->is_backend){
            //$this->add_js('scripts/jquery.min.js');
            //$this->add_js('js/jquery/jquery-ui.min.1.11.1.js');
            //$this->add_js('js/jquery/jquery.cookie.js');
            $this->add_js('scripts/jquery.timeago.js');
            $this->add_js('scripts/jquery.timeago.ru.js');
            $this->add_js('scripts/nav.js');
            $this->add_js('scripts/popup.js');
            $this->add_js('scripts/users.js');
            $this->add_js('scripts/common.js');

            $this->add_css('fonts/fontello/css/fontello.css');
            $this->add_css('fonts/font-awesome-4.7.0/css/font-awesome.min.css');
            $this->add_css('styles/fonts.css');
            $this->add_css('styles/common.css');
        }
    }

    public function setTitle($title)
    {
        if ($this->is_backend) {
            $this->pageTitle = $title . $this->translate->t('admin_title_prefix', '');
            //$this->add_meta('content-type', 'content-type', 'text/html; charset=utf-8', 'http-equiv');
        } else {
            $this->pageTitle = $title;
        }
        $this->moduleTitle = $title;
        $this->setTextTitle($title);
    }

    public function setTextTitle($title)
    {
        $this->pageTextTitle = $title;
    }

    protected function t($code, $text)
    {
        $this->translate->t($code, $text);
    }

    function aConf_add($name, $val)
    {
        if ($name != 'js_values') {
            $this->aConf[$name] = $val;
        }
    }

    function aConf_to_smarty()
    {
        $this->my_smarty->assign('aConf', $this->aConf);
    }

    function aConf_init_default_conf()
    {
        $base_url = $this->config->item('base_url');
        $base_url = substr($base_url, 0, strlen($base_url) - 1);
        $curent_url = $this->uri->uri_string();
        if (empty($curent_url)) {
            $curent_url = base_url();
        }

        $this->aConf_add('base_url', base_url());
        //$this->aConf_add('base_url', $this->config->item('base_url'));
        $this->aConf_add('base_urlns', $base_url);
        $this->aConf_add('isBackend', false);
        $this->aConf_add('curent_url', $curent_url);
        $this->aConf_add('destination', $this->generate_destination());
        $this->aConf_add('enable_google', $this->config->item('enable_google'));
        $this->aConf_add('google_key', $this->config->item('google_key'));
        $this->aConf_add('vote_image_path', $this->config->item('vote_image_path'));
        $this->aConf_add('social', $this->config->item('social'));
        $this->aConf['js_values'] = array();
    }


    function add_body_class($class)
    {
        $this->body_classes[$class] = $class;
    }

    function body_class_to_smarty()
    {
        $_body_class = implode(' ', $this->body_classes);
        $this->my_smarty->assign('body_class', $_body_class);
    }

    function css_files_smarty()
    {
        if (!empty($this->aCssFiles)) {
            $result = array();
            foreach ($this->aCssFiles as $key => $file) {
                if ($file['auto_ver'] == true && $file['ver'] === false) {
                    $file['path'] .= '?v=' . $this->make_file_version($file['path']);
                }
                if ($file['ver']) {
                    $file['path'] .= '?v=' . $file['ver'];
                }
                $result[$file['place']][] = base_url() . $this->template_dir . $file['path'];

            }
            $this->my_smarty->assign('aCssFiles', $result);
        }
    }

    function js_files_smarty()
    {
        if (!empty($this->aJsFiles)) {
            $result = array();
            foreach ($this->aJsFiles as $key => $file) {
                if ($file['auto_ver'] == true && $file['ver'] === false) {
                    $file['path'] .= '?v=' . $this->get_file_version($file['path']);
                }
                if ($file['ver']) {
                    $file['path'] .= '?v=' . $file['ver'];
                }
                $result[$file['place']][] = base_url() . $this->template_dir . $file['path'];

            }
            $this->my_smarty->assign('aJsFiles', $result);
        }
    }

    function assign()
    {
        $this->my_smarty->assign('user', $this->user);
        $this->my_smarty->assign('is_backend', $this->is_backend);
    }

    function set_js_value($name, $value)
    {
        $this->aConf['js_values'][$name] = $value;
    }

    function add_js($path, $place = 'header', $ver = false, $auto_ver = true)
    {
        $this->aJsFiles[$path] = array('path' => $path, 'place' => $place, 'ver' => $ver, 'auto_ver' => $auto_ver);
    }

    function add_css($path, $place = 'header', $ver = false, $auto_ver = true)
    {
        $this->aCssFiles[$path] = array('path' => $path, 'place' => $place, 'ver' => $ver, 'auto_ver' => $auto_ver);
    }

    function get_file_version($path)
    {
        if (empty($this->fileVersions[$path])) {
            $this->aFileVersions = $this->my_cache->get('aFileVersions');
        }
        if (!isset($this->fileVersions[$path])) {
            $this->aFileVersions[$path] = $this->make_file_version($path);
            $this->my_cache->save('aFileVersions', $this->aFileVersions);
        }
        return $this->aFileVersions[$path];
    }

    function make_file_version($path)
    {
        if (file_exists($path)) {
            return substr(md5(filesize($path) . filemtime($path)), 0, 5);
        } else {
            return 0;
        }
    }

    function init_variables()
    {
        $this->load->model('default_model', 'variables_model');
        $this->variables_model->setTable($this->config->item('variables', 'tables'));
        $_vars = $this->variables_model->getAllData();
        $_aVars = array();
        foreach ($_vars as $key => $values) {
            $_aVars[$values->name] = $values->value;
        }
        $this->variables = $_aVars;
        $this->my_smarty->assign('aVariables', $this->variables);
    }

    function get_var($fp_name)
    {
        if (isset($this->variables[$fp_name])) {
            return $this->variables[$fp_name];
        }
        return false;
    }

    function add_meta($key, $property, $content, $prop_name = 'name')
    {
        $this->aMeta[$key] = array('prop_name' => $prop_name, 'property' => $property, 'content' => $content);
    }

    function set_metakeywords($keywords = '')
    {
        if (empty($keywords) & !empty($this->aMeta['keywords'])){
            unset($this->aMeta['keywords']);
        } else {
            $this->add_meta('keywords', 'keywords', $keywords);
        }
    }

    function set_metadescription($description = '')
    {
        if (empty($description) & !empty($this->aMeta['description'])){
            unset($this->aMeta['keywords']);
        } else {
            $this->add_meta('description', 'description', $description);
        }
    }

    function meta_to_smarty()
    {
        $this->my_smarty->assign('pageTextTitle', $this->pageTextTitle);
        $this->my_smarty->assign('moduleTitle', $this->moduleTitle);
        $this->my_smarty->assign('pageTitle', $this->pageTitle);
        $this->my_smarty->assign('aMeta', $this->aMeta);
    }

    function setTemplateDir($template)
    {
        $this->template_dir = 'application/themes/'.$template.'/';
        $this->my_smarty->setTemplateDir($this->template_dir);
        $this->my_smarty->addTemplateDir(APPPATH.'views');
    }

    function view($fp_page)
    {
        //dump($this->my_smarty->getTemplateDir());
        $this->init_default_head();

        $this->my_smarty->assign_frontend();
        if ($this->is_backend) {
            $fp_page = 'admin/' . $fp_page;
        }
        $page_content = $this->my_smarty->fetch($fp_page . '.tpl');
        $path = $this->template_dir;
        $this->my_smarty->assign('path', $path);
        $this->my_smarty->assign('content', $page_content);
        $this->my_smarty->view($this->layout);
    }

    function fetch($fp_page)
    {
        $response = array('response' => false);
        $this->my_smarty->assign_frontend();
        try {
            $response['data'] = $this->my_smarty->fetch($fp_page . '.tpl');
        } catch (SmartyCompilerException $e) {
            if($this->my_smarty->ext->loadPlugin->plugin_files['plugins_dir']){
                $response['error'] = 'insert';
                $response['file'] = $this->my_smarty->ext->loadPlugin->plugin_files['plugins_dir'];
            }
        }
        $response['response'] = true;
        return $response;
    }

    function show_elapsed_time($mark1 = 'total_execution_time_start', $mark2 = 'my_mark_end')
    {
        if ($this->CI->config->item('show_elapsed_time')) {
            $sec = $this->CI->benchmark->elapsed_time($mark1, $mark2);
            echo "<div id=\"elapsed_time\" class=\"elapsed_time\">" . $sec . " sec (" . ($sec * 1000) . " ms)</div>";
        }
    }

    function admin_menu()
    {
        if ($this->user->check_perm('admin_menu', 'main')) {
            //$menu = $this->menu->getMenuTreeByName('admin_menu');

            if (!empty($menu)) {
                //$this->my_smarty->assign('admin_menu', $menu);
                $this->my_smarty->assign('use_admin_menu', true);
            } else {
                $this->my_smarty->assign('use_admin_menu', false);
            }
        } else {
            $this->my_smarty->assign('use_admin_menu', false);
        }
    }

    function used_backend()
    {
        $this->add_body_class('admin_site');
        $this->aConf_add('isBackend', true);
        $this->is_backend = true;

        $this->template_dir = '/system/views/';
        $this->my_smarty->setTemplateDir(APPPATH.'views');

        $this->add_css('assets/css/backend.css');
        $this->add_css('assets/js/chosen/chosen.min.css');
        $this->add_css('assets/css/loader-style.css');
        $this->add_css('assets/css/bootstrap.min.css');
        $this->add_css('assets/css/bootstrap-theme.min.css');
        $this->add_css('assets/css/style.css');
        $this->add_css('assets/plugins/jquery-ui/jquery-ui.min.css');
        $this->add_css('assets/plugins/grid/css/ui.jqgrid.css');

        $this->add_js('assets/plugins/jquery-ui/jquery-ui.min.js');
        $this->add_js('assets/plugins/jquery-ui/jquery.mjs.nestedSortable.js');
        $this->add_js('assets/plugins/grid/js/i18n/grid.locale-ru.js');
        $this->add_js('assets/plugins/grid/js/jquery.jqGrid.js');
        $this->add_js('assets/js/chosen/chosen.jquery.min.js');
        $this->add_js('assets/js/custom/scriptbreaker-multiple-accordion-1.js', 'footer');
        $this->add_js('assets/js/newsticker/jquery.newsTicker.js', 'footer');
        $this->add_js('assets/js/slidebars/slidebars.min.js', 'footer');
        $this->add_js('assets/js/tip/jquery.tooltipster.js', 'footer');
        $this->add_js('assets/js/bootstrap.js', 'footer');
        $this->add_js('assets/js/main.js', 'footer');

        $this->add_meta('robots', 'robots', 'noindex,nofollow');
        $this->add_meta('cache_control', 'Cache-Control', ' no-store,no-cache', 'http-equiv');
        $this->disable_analytics();
    }

    function disable_robots_index()
    {
        $this->add_meta('robots', 'robots', 'noindex');
    }

    function disable_analytics()
    {
        $this->aConf_add('enable_google', false);
    }

    function redirect_with_destination($url)
    {
        mygoto($url . '?destination=' . $this->generate_destination());
    }

    function generate_destination()
    {
        $curent_url = $this->uri->uri_string();
        if (empty($curent_url)) {
            $curent_url = base_url();
        } else {
            $curent_url = '/' . $curent_url;
        }
        return $curent_url;
    }

    function returnJson($fp_Val)
    {
        if ($this->CI->config->item('use_php_json')) {
            return json_encode($fp_Val);
        } else {
            //$this->CI->load->library('Json');
            //echo $this->json->encode($fp_Val);
        }
    }

    function setLayout($tpl)
    {
        $this->layout = $tpl;
    }

    function messages_custom_place()
    {
        $this->aConf_add('move_messages', true);
    }

    function get_mail_html($fp_page)
    {
        $this->my_smarty->assign_frontend(false, true);

        $fp_page = 'mail/' . $fp_page;

        $page_content = $this->my_smarty->fetch($fp_page . '.tpl');

        $this->my_smarty->assign('message_body', $page_content);
        return $this->my_smarty->fetch($this->mail_layout);
    }

    function show_error($message, $error_code = false, $set_header = false)
    {
        if ($set_header && $error_code){
            $this->CI->output->set_status_header($error_code);
        }
        $this->my_smarty->assign('message', $message);
        $this->view('error');
    }

    function go_404(){
        mygoto('/error404');
    }

    function error_page($message,$code){
        $this->view('error');
    }

    function redirect_to_home()
    {
        mygoto('/');
    }

    function hide_title(){
        $this->my_smarty->assign('hideTitle',1);
    }

}