<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "third_party/Smarty-3.1.29/libs/Smarty.class.php";
//require_once "Smarty-3.0.8//libs/plugins/block.input_box.php";


/**
 * @file system/application/libraries/My_Smarty.php
 */
class MY_Smarty extends Smarty
{

    private $assigned = false;
    private $light_mode = false;

    function __construct()
    {

        parent::__construct();

        $CI_config =& get_config();

        $this->cache_lifetime = $CI_config['smarty_cache_live_time'];
        // absolute path prevents "template not found" errors
        $this->template_dir = $CI_config['smarty_template_dir'];
        $this->cache_dir = $CI_config['smarty_cache_dir'];
        $this->compile_dir = $CI_config['smarty_compile_dir'];
        $this->caching = $CI_config['smarty_caching'];
        $this->compile_check = true;
        $this->force_compile = false;

    }


    /**
     * @param $resource_name string
     * @param $params array holds params that will be passed to the template
     * @desc loads the template
     */
    function view($resource_name, $params = array(), $is_last = false)
    {

        $CI =& get_instance();
        $CI->benchmark->mark('execution_time: conroller_end');
        $CI->benchmark->mark('execution_time: smarty_start');


        if (is_array($params) && count($params)) {
            foreach ($params as $key => $value) {
                $this->assign($key, $value);
            }
        }

        // check if the template file exists.
        if (!is_file($this->template_dir[0] . $resource_name)) {
            show_error("template: [$this->template_dir][$resource_name] cannot be found.");
        }
        //if (!$this->light_mode){
        $this->assign_frontend();
        //}

        parent::display($resource_name);

        $CI->benchmark->mark('execution_time: smarty_end');
        $CI->benchmark->mark('my_mark_end');
        if ($CI->config->item('enable_profiler')) {
            echo $CI->profiler->run();
        }
        //$CI->frontend->show_elapsed_time('execution_time: smarty_start', 'execution_time: smarty_end');
        $CI->frontend->show_elapsed_time();

    }


    function assign_frontend($force = false, $no_flag = false)
    {
        $CI =& get_instance();
        $CI->messages->assign();
        $CI->frontend->aConf_to_smarty();
        $CI->frontend->body_class_to_smarty();
        $CI->frontend->css_files_smarty();
        $CI->frontend->js_files_smarty();
        $CI->frontend->meta_to_smarty();
        $CI->frontend->assign();
        if (!$this->light_mode && (!$this->assigned || $force)) {
            $CI->frontend->admin_menu();
            if (!$no_flag) {
                $this->assigned = true;
            }
        }
    }

    function light_mode($enable = true){
        $this->light_mode = $enable;
    }


} // END class smarty_library
