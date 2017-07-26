<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class User
{
    var $oUser;
    var $activeModule = "";
    private $CI;
    private $session;
    private $input;
    private $messages;
    private $permission;
    private $loaded_users = array();

    function __construct()
    {
        $this->CI =& get_instance();
        $this->session = &$this->CI->session;
        $this->input = &$this->CI->input;
        $this->messages = &$this->CI->messages;
        $this->translate =& $this->CI->translate;
        //$this->activeModule = &$this->CI->activeModule;
        $this->load_user();
        $this->CI->load->library('Permission');
        $this->permission = &$this->CI->permission;
        $this->permission->set_user($this->oUser);
//        $this->CI->load->module_model('user_model');
        $this->check_login(); // here redirect if no login

    }

    function check_perm($action, $module = '', $text = '')
    {
        return $this->permission->check($action, $module, $text);
    }

    private function get_vk_cookie(){
        $vk_conf = $this->CI->config->item('vk','social');
        $session = array();
        $member = FALSE;
        $valid_keys = array('expire', 'mid', 'secret', 'sid', 'sig');
        $app_cookie = get_cookie('vk_app_' . $vk_conf['client_id']);
        if ($app_cookie) {
            $session_data = explode ('&', $app_cookie, 10);
            foreach ($session_data as $pair) {
                list($key, $value) = explode('=', $pair, 2);
                if (empty($key) || empty($value) || !in_array($key, $valid_keys)) {
                    continue;
                }
                $session[$key] = $value;
            }
            foreach ($valid_keys as $key) {
                if (!isset($session[$key])) return $member;
            }
            ksort($session);

            $sign = '';
            foreach ($session as $key => $value) {
                if ($key != 'sig') {
                    $sign .= ($key . '=' . $value);
                }
            }
            $sign .= $vk_conf['client_secret'];
            $sign = md5($sign);
            if ($session['sig'] == $sign && $session['expire'] > time()) {
                $member = array(
                    'id' => intval($session['mid']),
                    'secret' => $session['secret'],
                    'sid' => $session['sid']
                );
            }
        }
        return $member;
    }

    function get_user_id(){
        if($this->session->userdata('user_id'))
            return $this->session->userdata('user_id');
        return false;
    }


    // need to update caching
    function load_user()
    {
        //$this->session->put('user_id',0);
        $user_id = $this->session->userdata('user_id');
        if (!empty($user_id) || $user_id === 0) {
            $this->oUser = $this->load_user_by_id($user_id);
            if (!empty($this->oUser)){
                $this->CI->frontend->add_body_class('logged_in');
            } else {
                $this->oUser = $this->load_user_by_id(0);
                $this->session->set_userdata('user_id', NULL);
            }
        } else {
            $this->oUser = $this->load_user_by_id(0);
        }
        //dump($this->oUser,0);
        if(isset($this->CI->date_helper))
            $this->CI->date_helper->setTimezone($this->oUser->timezone);

    }

    protected function t($code, $text)
    {
        return $this->translate->t($code, $text);
    }

    function assign_user(&$user)
    {
        $user = $this->oUser;
    }

    function get_current_user_data()
    {
        return $this->oUser;
    }

    function is_logged_in()
    {
        return (!empty($this->oUser->group) && $this->oUser->group !== 1);
    }

    function logout()
    {
        $this->session->set_userdata('user_id', NULL);
        //dump($this->session->get('ouser'));
    }

    private function load_user_by_id($id)
    {
        $_user = $anon = $this->get_anon_user();
        //TODO cache here
        if (!empty($id) && $id !== 0) {
            if (empty($this->loaded_users[$id])){
                $this->CI->load->model('user_model');
                $_user = $this->CI->user_model->getUserById($id);
                if (empty($_user)){
                    return $anon;
                }
                if (empty($_user->avatar)){
                    $_user->avatar = $anon->avatar;
                }
            } else {
                return $this->loaded_users[$id];
            }
        }

        $this->loaded_users[$id] = $_user;
        return $_user;

    }

    public function get_anon_user()
    {
        $oUser = new stdClass;
        $oUser->id = 0;
        $oUser->fname = $this->t('user_anon_name', 'Аноним');
        $oUser->login = $this->t('user_anon_nick', 'UFO');
        $oUser->surname = $this->t('user_anon_surname', 'Марсианин');
        $oUser->timezone = 'Europe/Kiev';
        $oUser->avatar = '/themes/images/no_avatar.png';
        $oUser->group = 1;
        return $oUser;
    }

    public function get_avatar(){
        return $this->oUser->avatar;
    }

    public function get_nick($id = false){
        if (!$id){
            $_user = $this->oUser;
        } else {
            $_user = $this->load_user_by_id($id);
        }
        if (!empty($_user->nick)) {
            return $_user->nick;
        } else {
            return $_user->name;
        }
    }

    public function get_uri($id = false){
        if (!$id){
            $_user = $this->oUser;
        } else {
            $_user = $this->load_user_by_id($id);
        }
        if (!empty($_user->uri_name)){
            return $_user->uri_name;
        } else {
            return $_user->id;
        }
    }

    public function get_user_by_id($id)
    {
        return $this->load_user_by_id($id);
    }

    public function get_user_by_email($email)
    {
        $_user = false;
        if (!empty($email)) {
            $this->CI->load->model('user_model');
            $_user = $this->CI->user_model->getUserByEmail($email);
            if (empty($_user)) {
                return false;
            }
        }
        return $_user;
    }

    function login($login, $pass)
    {
        $this->CI->load->model('user_model');
        $_oUser = $this->CI->user_model->login($login, $this->hash_pass($pass), $pass);
        if (!empty($_oUser)) {
            $this->oUser = $_oUser;
            $this->session->set_userdata('user_id', $_oUser->id);
            return true;
        } else {
            return false;
        }

    }

    function hash_pass($pass)
    {
        $result = $pass . $this->CI->config->item('pass_hash_salt');
        for ($i = 0; $i < $this->CI->config->item('pass_hash_iteration'); $i++) {
            $result = md5($result);
        }
        return md5(md5($pass));
        return $result;
    }

    function is_logged(){
        $is_logged = $this->session->userdata('user_id');
        if ($is_logged)
            return true;
        return false;
    }

    function check_login()
    {
        $this->CI->load->library('option');
        $is_logged = $this->session->userdata('user_id');
        $is_checker = $this->input->get_post('check_login');
        $curr_time = time();
        //need check login time
        if ($is_logged) {
            $this->oAdmin = $this->session->userdata('admin');
            $sess_time = $this->CI->option->get('logged_time');
            $admin_logged_time = $this->session->userdata('admin_logged_time');

            if (($admin_logged_time + $sess_time) < $curr_time) {
                $is_logged = false;
            }
        }

        if (!$is_logged) {
            /*
          $this->session->put('back_logged', false);
          if( !$this->isNoAuthPage()){
            if ($this->is_ajax()){
              $result = new StdClass;
              $result->logout = true;
              $this->returnJson($result);
              exit;
            } else {
              mygoto(base_url().'admin/login');
            }
          }      */
        } elseif ($this->is_ajax() && $is_checker) {
            $result = new StdClass;
            $result->logout = false;
            $this->returnJson($result);
            exit;
        } else {
            //update last time
            $this->load->module_model('admin', 'admin_model');
            $this->session->set_userdata('admin', $this->oAdmin);
            $this->session->set_userdata('admin_logged_time', time());
            $this->admin_model->save(array(
                'last_time' => $curr_time
            ), 'edit', $this->oAdmin->id);
        }
    }

    public function getData($name)
    {
        if (!empty($this->oUser->$name)) {
            return $this->oUser->$name;
        } else {
            return NULL;
        }

    }

    public function restore_pass($user)
    {
        if (empty($user)) {
            return false;
        }
        $hash = md5($user->id . uniqid() . time());
        $link = base_url() . 'users/reset_pass/' . $hash;
        $this->CI->my_smarty->assign('reset_link', $link);
        $this->CI->load->model('user_model');
        $this->CI->user_model->save_hash($user->id, $hash, 'restore_mail');
        $mail_message = $this->CI->frontend->get_mail_html('reset_pass');
        $this->CI->load->library('mailer');
        $addres_list = $this->CI->config->item('address','mailer');
        $subject = $this->translate->t('mail_reset_subject', '0vote восстановление пароля.');
        $this->CI->mailer->send_mail(
            $addres_list['site']['mail'],
            $addres_list['site']['name'],
            $user->email,
            $user->name,
            $subject,
            $mail_message
        );

        return true;
    }

    public function get_user_by_hash($hash, $key)
    {
        $_user = false;

        if (!empty($hash)) {
            $this->CI->load->model('user_model');
            $life_time = $this->CI->config->item('reset_pass', 'hash_life');
            $_user = $this->CI->user_model->getUserByHash($hash, $key, $life_time);
            if (empty($_user)) {
                return false;
            } else {
                return $this->get_user_by_id($_user->user_id);
            }
        }
        return $_user;
    }

    public function delete_hash_by_key($user, $key)
    {
        return $this->CI->user_model->deleteHash($user->id, $key);
    }

    public function update_pass($pass, $user = false)
    {
        if (empty($user)) {
            $user = $this->oUser;
        }
        $this->CI->load->model('user_model');
        $this->CI->user_model->update_user($user->id, array('pass' => $this->hash_pass($pass)));
        $this->CI->user_model->deleteHashByKey($user->id, 'restore_mail');
    }

    public function cron_clear_hash()
    {
        $life_time = $this->CI->config->item('reset_pass', 'hash_life');
        $this->CI->load->model('user_model');
        return $this->CI->user_model->deleteHashOutdated('restore_mail', $life_time);
    }

    public function get_user_by_url($user_inf)
    {
        $this->CI->load->model('user_model');
        return $this->CI->user_model->get_user_by_id_nick($user_inf);
    }

    public function register($user = false){
       // $this->input->_enable_xss = true;
        //$this->input->_sanitize_globals();
        $aData= array();
        if (!$user){
            $this->CI->load->library('fields');
            $this->CI->fields->addTab();
            $this->CI->fields->setTable('users');
            $this->CI->fields->init_model();
            $this->CI->fields->addField_text(array(
                'field' => 'user_email',
                'title' => $this->translate->t('user_email', 'Эл. почта'),
                'rules' => 'trim|required|valid_email|callback_unique[email]',
            ));
            $this->CI->fields->addField_text(array(
                'field' => 'user_name',
                'title' => $this->translate->t('user_name', 'Имя и фамилия'),
                'rules' => 'trim|required',
            ));
            $this->CI->fields->addField_text(array(
                'field' => 'user_login',
                'title' => $this->translate->t('user_login', 'Имя пользователя'),
                'rules' => 'trim|required|callback_unique[login]|alpha',
            ));
            $this->CI->fields->addField_text(array(
                'field' => 'user_password',
                'title' => $this->translate->t('user_password', 'Пароль'),
                'rules' => 'required',
            ));
        }
        //$pass_info = $this->input->get_post('password', true);
        //$this->CI->load->library('validation');
        //$this->CI->validation->addClassObject($this);

        //$this->CI->validation->set_rules('pass2', 'Подтверждение пароля', 'trim|required');
        //$this->CI->validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_unique[email]');
        //$this->CI->validation->set_rules('fullname', 'ФИО(имя)', 'trim|required');
        //$this->CI->fields->validate();
        //$is_validate = $this->CI->validation->run();
        $is_validate = $this->CI->fields->validate();
        if ($this->input->post('user_email') || !empty($user)) {
            if (empty($user)){
                $_aData = $this->CI->fields->get_fields_data();
                $_aData = $_aData['aData'];
                $_aData['user_name'] = explode(' ', $_aData['user_name']);
                if(count($_aData['user_name']) !== 2){
                    $is_validate = false;
                    $this->messages->add_error('validation1', 'Имя и фалмилия должны состоять из двух слов.');
                    return;
                }
                $aData = array(
                    'login' => $_aData['user_login'],
                    'fname' => $_aData['user_name'][0],
                    'lname' => $_aData['user_name'][1],
                    'email' => $_aData['user_email'],
                );
                $aData['timezone'] = 'Europe/Kiev';
                $aData['password'] = $this->hash_pass($_aData['user_password']);
            } else {
                $is_validate = true;
                $aData = $user;
                $aData['password'] = $this->hash_pass($this->randomPassword());
            }

            if ($is_validate) {
                $this->CI->load->model('user_model');
                //$aData['pass_info'] = $pass_info;
                if ($id = $this->CI->user_model->save_user($aData)) {
                    $this->messages->add_success('user_registration_success', 'Вы успешно зарегистрированы.');
                    $this->CI->load->library('mailer');
                    //$this->CI->user_model->del_user($id);

                    $this->session->set_userdata('user_id', $id);
                    $this->load_user();

                    $hash = md5($id. uniqid() . time());
                    $link = base_url() . 'users/confirm_email/' . $hash;
                    $this->CI->my_smarty->assign('confirm_link', $link);
                    $this->CI->user_model->save_hash($id, $hash, 'mail_confirm');
                    $mail_message = $this->CI->frontend->get_mail_html('mail_confirm');
                    $addres_list = $this->CI->config->item('address','mailer');
                    $subject = $this->translate->t('mail_confirm_subject', 'SL подтверждение почты.');
                    $this->CI->mailer->send_mail(
                        $addres_list['site']['mail'],
                        $addres_list['site']['name'],
                        $aData['email'],
                        $aData['fname'],
                        $subject,
                        $mail_message
                    );
                } else {
                    //$error_msg = "Произошла неизвестная ошибка. Попробуйте позже или свяжитесь с администратором.";
                }
            }


        }else{

        }

        //dump($is_validate);
        /*
        $aData = array(
            'nick' => $this->CI->validation->nick,
            'name' => $this->CI->validation->name,
            'password' => $this->CI->validation->password,
            'email' => $this->CI->validation->email,
            //'fullname' => $this->CI->validation->set_value('fullname'),
            'ip' => $this->session->get('ip_address'),
            'reg_ip' => $this->session->get('ip_address'),
            'last_time' => time(),
            'reg_time' => time(),
        );
        */
        /*
        if ($this->user_lib->is_duplicate($aData['email'],'email')){
            $this->messages->add_error('user_exist_email', 'Такой email уже используется.');
        }
        */


        return $aData;
    }

    private function validate_registration()
    {
        $this->CI->load->library('validation');
        $this->CI->validation->addClassObject($this);

        $aValRules = $this->getValidationFields();

        $this->CI->validation->set_fields($aValRules['fields']);
        $this->CI->validation->set_rules($aValRules['rules']);

        if ($this->CI->validation->run() == FALSE) {
            $aErrors = $this->CI->validation->get_errors_array();
            $this->CI->messages->add_tmessages_list('error', $aErrors, 'validation');
            return false;
        } else {
            return true;
        }
    }

    function randomPassword($length = 10) {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!@#$%*()_+-=,.?";
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, strlen($alphabet)-1);
            $pass[$i] = $alphabet[$n];
        }
        return implode($pass);
    }

}

