<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Default_controller extends MX_Controller
{

    protected $aFields = array();
    public $activeModule = "";
    protected $aVars = array();
    protected $oUser;

    function __construct()
    {
        $this->load->helper('debug');
        $this->benchmark->mark('execution_time: conroller_start');
        if ($this->config->item('enable_profiler')) {
            $this->load->library('profiler');
        }
        parent::__construct();

        //$this->output->enable_profiler(TRUE);
        //$this->load->library('user_agent');

        $this->load->database();
        if ($this->config->item('core_autoupdate')){
            $this->load->library('Db_migration');
            $this->db_migration->set_file_list(array('install_model'));
            if (!$this->db_migration->is_latest('core')){
                mygoto('/install/setup/db_initialize');
            }
        };
        $this->load->library('Session');
        $this->load->helper('date');
        $this->load->library('MY_Smarty');
        $this->load->library('my_cache');
        //$this->load_variables();
        $this->load->library('Date_helper');
        $this->load->helper('url');
        $this->load->library('Translate', true);
        $this->load->library('messages');//before user Important !!
        $this->load->library('Frontend');
        $this->load->library('User');// here redirect if no login
        $this->user->assign_user($this->oUser);
        $this->load->library('Option');
        $this->load->library('Cron');
        $this->cron->run();

        $this->my_smarty->assign('oUser', $this->oUser);
        $this->my_smarty->assign('time', time());

        $this->frontend->setTemplateDir($this->option->get('template', 'default'));
        if($this->user->is_logged())
            $this->user_model->setLastAction($this->user->get_user_id());

        $sidebar = ($this->session->userdata('sidebar'))?$this->session->userdata('sidebar'):'friends';

        $this->my_smarty->assign('sidebar', $sidebar);

        //init_variables
    }

    function load_sidebar($type){
        $this->session->userdata('sidebar', $type);

        $response = array('response' => false, 'html' => array('data' => ''));

        switch ($type) {
            case 'search':
                $search = $this->input->get_post('search');

                $users = $this->user_model->getUsers($this->user->get_user_id(), $search);

                $this->my_smarty->assign('users', $users);
                $response['html'] = $this->frontend->fetch('sidebar/'.$type);
                break;
            default:
                if($this->user->is_logged()){
                    $friends = $this->user_model->getFriends($this->user->get_user_id());
                    $friends_req = $this->user_model->getFriendsReq($this->user->get_user_id());
                    $friends_online = $this->user_model->getFriendsOnline($this->user->get_user_id());

                    $this->my_smarty->assign('friends', $friends);
                    $this->my_smarty->assign('friends_req', $friends_req);
                    $this->my_smarty->assign('friends_online', $friends_online);

                    $response['html'] = $this->frontend->fetch('sidebar/'.$type);
                }
                break;
        }

        return $response;
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

