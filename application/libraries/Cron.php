<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Cron
{
    private $CI;
    private $mode;
    private $list = array();

    function __construct()
    {
        $this->CI =& get_instance();
        $this->mode = $this->CI->config->item('mode', 'cron');
    }

    /* Call list of functions in cron */
    /* New cron tasks should add here */
    function run_list_functions()
    {
        $this->run_function($this->CI->user, 'cron_clear_hash');
        $this->run_function($this->CI->locker, 'cron');
    }
    
    function run()
    {
        $this->init_values();
        if ($this->mode == 'web') {
            if ($this->need_web_run()) {
                $this->update_time('web_run');
                $this->run_list_functions();
            }
        }
        if ($this->mode == 'cli') {
            if ($this->CI->input->is_cli_request() && $this->is_time_to_run('cli_run')) {
                $this->update_time('cli_run');
                $this->run_list_functions();
            }
        }
    }

    function init_values()
    {
        $this->CI->load->model('cron_model');
        $list = $this->CI->cron_model->getAllFunctions();
        if (!empty($list)) {
            foreach ($list as $function) {
                $this->list[$function->name] = $function;
            }
        }
    }

    function is_time_to_run($function_name)
    {
        if (!empty($this->list[$function_name])) {
            $next_run = mysql_to_unix($this->list[$function_name]->last_run) + $this->list[$function_name]->interval;
            if ($this->list[$function_name]->active && $next_run <= time()) {
                return true;
            }
        } else {
            $this->CI->cron_model->add($function_name);
        }
        return false;
    }

    function need_web_run()
    {
        return $this->is_time_to_run('web_run');
    }

    function log($function_name, $result)
    {
        $this->update_time($function_name);
    }

    function update_time($function_name)
    {
        $this->CI->cron_model->update($this->list[$function_name]);
    }

    function run_function($object, $function)
    {
        $log_name = get_class($object) . '-' . $function;
        if ($this->is_time_to_run($log_name)) {
            $result = $object->$function();
            $this->log($log_name, $result);
        }
    }


}

