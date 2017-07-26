<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Permission
{

    public $activeModule = "";
    private $CI;
    private $permissions_list = array();
    private $oUser;

    function __construct()
    {
        $this->CI =& get_instance();
        //$this->activeModule = &$this->CI->activeModule;
    }

    function set_user(&$user)
    {
        $this->oUser = &$user;
        $this->load_permissions();
    }

    function setActiveModule($module)
    {
        $this->activeModule = $module;

    }

    function check_action($action)
    {
        return $this->check($action, $this->activeModule);
    }

    function check_action_redirect($action)
    {
        if (!$this->check_action($action)) {
            $this->CI->frontend->redirect_with_destination('/users/login');
        }
    }

    function check($action = 'view', $module = 'main', $text = '')
    {
        if (empty($action)){
            $action = 'view';
        }

        if (empty($module)){
            if (!empty($this->activeModule)) {
                $module = $this->activeModule;
            } else {
                $module = 'main';
            }
        }

        if (empty($text)) {
            $text = $action;
        }

        if (!isset($this->permissions_list[$module][$action])) {
            //create and save new
            $this->CI->load->model('permissions_model');
            $new_permission['action'] = $action;
            $new_permission['module'] = $module;
            $new_permission['text'] = $text;
            $this->CI->permissions_model->save_entity($new_permission);
            $this->permissions_list[$module][$action] = false;
        }
        if ($this->oUser->id == 1) {
            return true;
        }
        return $this->permissions_list[$module][$action];

    }


    protected function load_permissions()
    {
        if (!empty($this->oUser)) {
            $this->CI->load->model('permissions_model');
            $_permissions = $this->CI->permissions_model->getForGroup($this->oUser->group);

            //TODO CACHE
            if (!empty($_permissions)) {
                foreach ($_permissions as $key => $value) {
                    $_value = $value->value;
                    if ($_value == NULL) {
                        $_value = false;
                    }
                    $this->permissions_list[$value->module][$value->action] = $_value;
                }
            }
        }
    }


    public function update_perm($enity_id, $group, $value)
    {
        return $this->CI->permissions_model->save_permission($enity_id, $group, $value);
        //clear cache
    }
}

