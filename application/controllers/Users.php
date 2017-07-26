<?php

class Users extends Default_controller
{

    function __construct()
    {
        parent::__construct();
        $this->setActiveModule('user');
        $this->frontend->add_body_class('user');
    }

    function index()
    {
        if (!$this->user->is_logged_in()) {
            mygoto('/users/login');
        } else {
            mygoto('/profile/');
        }
    }

    function login()
    {
        if ($this->user->is_logged_in()) {
            mygoto('/feed');
        }

        $aData = false;
        $form = $this->input->post();
        $destination = $this->input->get_post('destination');
        if(!$destination) $destination = '/feed';
        $this->frontend->add_body_class('login');
        $is_submit = $this->input->post('act');
        $this->frontend->setTitle($this->translate->t('user_title_login', 'Авторизация'));
        if ($is_submit == "login") {
            $aData = array('response' => false);
            $email = $this->input->get_post('user_email', true);
            $pass = $this->input->get_post('user_password', true);
            if ($this->user->login($email, $pass)) {
                $this->messages->add_message_next_time('success', 'user_login_greetings', 'Приветствуем вас.');
                $aData['response'] = true;
                $aData['text'] = $destination;
                if (!empty($destination)) {
                    //mygoto($destination);
                } else {
                    //mygoto(base_url());
                }
            } else {
                $aData['error'] = 'Неверный логин или пароль';
                $this->messages->add_error('user_wrong_login', 'Не правильный логин/пароль.');
            }
        }
        if($is_submit == "register"){
            $aData = array('response' => false);
            $this->load->library('messages');
            $data = $this->user->register();
            $mes = $this->messages->get_type_messages('error');
            if(!empty($mes)){
                $aData['error'] = $mes;
            }else{
                $aData['response'] = true;
                $aData['text'] = $destination;
            }
        }

        if($aData || $is_submit){
            echo json_encode($aData);
            return;
        }

        $this->my_smarty->assign('destination', $destination);
        $this->frontend->setLayout('welcome.tpl');
        $this->frontend->view('user/login');
    }

    function logout()
    {
        $this->user->logout();
        if (!$this->is_ajax()){
            if (!empty($_SERVER['HTTP_REFERER'])){
                mygoto($_SERVER['HTTP_REFERER']);
            } else {
                mygoto(base_url());
            }
        }
    }

    function restore_pass()
    {
        if ($this->user->is_logged_in()) {
            mygoto('/profile/');
        }
        $is_submit = $this->input->get_post('restore');
        $email = $this->input->get_post('email');
        $this->frontend->setTitle($this->translate->t('user_title_restore_pass', 'Пользователи - Восстановление Пароля'));
        if ($is_submit) {
            if ($email) {
                if ($user = $this->user->get_user_by_email($email)) {
                    $this->user->restore_pass($user);
                    $this->messages->add_success('user_reset_sent', 'Письмо с сылкой для сброса пароля отправлено на ваш email.');

                } else {
                    $this->messages->add_error('user_with_email_absent', 'Пользователь с таким email не найден.');
                }
            } else {
                $this->messages->add_error('empty_field_email', 'Заполните поле email.');
            }
        }

        $this->frontend->view('user/restore_pass');
    }

    function reset_pass($hash = '')
    {
        if (empty($hash)) {
            mygoto('/users/restore_pass/');
        }
        $is_submit = $this->input->get_post('restore');
        $pass = $this->input->get_post_def('pass', '');
        $pass2 = $this->input->get_post_def('pass2', '');
        $this->frontend->setTitle($this->translate->t('user_title_restore_pass', 'Пользователи - Восстановление Пароля'));
        $user = $this->user->get_user_by_hash($hash, 'restore_mail');

        if (empty($user)) {
            $this->messages->add_error('reset_pass_wrong_hash', 'Ссылка для сброса пароля устарела. Попробуйте еще раз или обратитесь в службу поддержки.');
        }

        if (!empty($user) && $is_submit) {
			$check_pass = $pass . $pass2;
            if (!empty($check_pass)) {
                if ($pass === $pass2) {
                    $this->user->update_pass($pass, $user);
                    $this->messages->add_message_next_time('success', 'user_reset_pass', 'Пароль обновлен.');
                    mygoto('/users/login/');
                } else {
                    $this->messages->add_error('field_pass_not_match', 'Пароли должны совпадать.');
                }
            } else {
                $this->messages->add_error('empty_field_pass', 'Заполните поле пароль.');
            }
        }

        $this->frontend->view('user/reset_pass');
    }



    function register()
    {
        if ($this->user->is_logged_in()) {
            mygoto('/feed');
        }

        $this->frontend->add_body_class('user user_register');
        $this->frontend->setTitle($this->translate->t('user_registration_title','Регистрация'));
        $aData = array();
        if ($this->input->post('submit')) {
            $aData = $this->user->register();
        }
        $this->my_smarty->assign('aData',$aData);
        $this->frontend->view('register');
    }

    function vk_auth(){
        $vk_config = $this->config->item('vk','social');
        if (isset($_GET['code'])) {
            $params = array(
                'client_id' => $vk_config['client_id'],
                'client_secret' => $vk_config['client_secret'],
                'code' => $_GET['code'],
                'redirect_uri' => $vk_config['redirect_url']
            );

            // get token and user email
            $token = array();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://oauth.vk.com/access_token' . '?' . http_build_query($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $token = curl_exec($ch);
            curl_close ($ch);
            $token = json_decode($token,true);

            if (!empty($token) && empty($token['error'])){
                $this->load->model('user_model');
                $user = $this->user_model->getDataByOrWhere(array(
                    'vk_id' => $token['user_id'],
                    'email' => $token['email'],
                ));

                if (empty($user[0])){
                    // get user data
                    $params = array(
                        'uids'         => $token['user_id'],
                        'fields'       => 'uid,first_name,last_name,sex,screen_name,bdate,city,country,photo_100,nickname,timezone',
                        'access_token' => $token['access_token'],
                    );

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://api.vk.com/method/users.get' . '?' . http_build_query($params));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    $userInfo = curl_exec($ch);
                    curl_close ($ch);
                    $userInfo = json_decode($userInfo, true);
                    //create new user
                    if (!empty($userInfo['response'][0]) && empty($userInfo['error'])){
                        $user = array(
                            'email' => $token['email'],
                            'name' => $userInfo['response'][0]['first_name'],
                            'surname' => $userInfo['response'][0]['last_name'],
                            'nick' => $userInfo['response'][0]['nickname'],
                            'uri_name' => $userInfo['response'][0]['screen_name'],
                            'vk_id' => $token['user_id'],
                            'birthday' => $userInfo['response'][0]['screen_name'],
                            'avatar' => $userInfo['response'][0]['photo_100'],
                            'timezone' => 'Europe/Kiev',
                        );
                        if (empty($userInfo['response'][0]['nickname'])){
                            $user['nick'] = $user['uri_name'];
                        }
                        $this->user->register($user);
                        mygoto('/profile');
                        die();
                    }
                } else {
                    if ($user[0]->email != $token['email']){
                        $this->user_model->save_user(array('email' => $token['email']), 'edit', $user[0]->id);
                    }
                    if ($user[0]->vk_id != $token['user_id']){
                        $this->user_model->save_user(array('vk_id' => $token['user_id']), 'edit', $user[0]->id);
                    }

                    // set user id to session
                    $this->session->put('user_id', $user[0]->id);
                }
                //dump('goto');
                mygoto($_GET['state']);
            }

        }
        //dump('go to / ');
        mygoto('/');

    }

    function fb_auth(){
        $fb_conf = $this->config->item('fb','social');
        $json_responce = new StdClass;
        $json_responce->update = false;
        $json_responce->redirect = false;
        $fb_access_token = get_cookie('fbsr_' . $fb_conf['app_id']);
        if ($fb_access_token){
            require_once(APPPATH.'libraries/facebook_sdk_v4/autoload.php');

            $fb = new Facebook\Facebook(array(
                'app_id' => $fb_conf['app_id'],
                'app_secret' => $fb_conf['app_secret'],
                'default_graph_version' => 'v2.2',
            ));

            $helper = $fb->getJavaScriptHelper();
            $accessToken = $helper->getAccessToken();

            //$accessToken = 'CAADcXCVpcdwBAN2ZAYK8wQBieR6mWr5yQ2yZBVCigKlKqZCYWQV4ZBBltRta0UcRKqwqUMXJM2L50BGjzSQgSk0T1tOVsHZBeCv1NdrhjnZAB56HjifXYT1fFZAgEBGdcRSZBZBzUevM0lbdk5VFfN84UoTDTNRVZCWZBDXjzc8M4fHKBZBNoAi8gAwYyiY8i7YkOBTJCZAbfznujvZBykZCtQTMcyY';
            $fb->setDefaultAccessToken($accessToken->getValue());
            //dump($accessToken->getValue(),0);
            //,user_birthday
            $response = $fb->get('/me?fields=id,name,first_name,last_name,link,gender,picture,verified,email,timezone');
            $userNode = $response->getGraphUser();

			$check_user_id = $userNode->getId();
            if (!empty($check_user_id)){
                $this->load->model('user_model');
                $user = $this->user_model->getDataByOrWhere(array(
                    'fb_id' => $userNode->getId(),
                    'email' => $userNode->getEmail(),
                ));
                if (empty($user[0])){
                    $user = array(
                        //$object->getProperty('name')
                        'email' => $userNode->getEmail(),
                        'name' => $userNode->getFirstName(),
                        'surname' => $userNode->getLastName(),
                        'fb_id' => $userNode->getId(),
                        'uri_name' => urlencode($userNode->getFirstName() . '_' . $userNode->getLastName()),
                        //'birthday' => $userInfo['response'][0]['screen_name'],
                        'timezone' => 'Europe/Kiev',
                    );
					$check_picture = $userNode->getPicture();
                    if (!empty($check_picture)){
                        $user['avatar'] = $userNode->getPicture()->getUrl();
                    }
                    $this->user->register($user);
                    $json_responce->redirect = '/profile';
                } else {
                    if ($user[0]->email != $userNode->getEmail()){
                        $this->user_model->save_user(array('email' => $userNode->getEmail()), 'edit', $user[0]->id);
                    }
                    if ($user[0]->fb_id != $userNode->getId()){
                        $this->user_model->save_user(array('fb_id' => $userNode->getId()), 'edit', $user[0]->id);
                    }
                    // set user id to session
                    $this->session->put('user_id', $user[0]->id);
                    $json_responce->update = true;
                }
            }
        }
        echo $this->returnJson($json_responce);
        die();
    }

    function feed(){
        mygoto('/@');
    }

    function profile($user_id = false)
    {
        $this->load->model('wall_model');

        //dump($this->oUser,0);

        if ($user_id) {
            $user = $this->user->get_user_by_url($user_id);
        } else {
            if(!$this->user->is_logged())
                mygoto('/');
            $user = $this->oUser;
        }
        if(!$user)
            return;

        $this->wall_model->user_id = $user->id;
        $wall = $this->wall_model->getPostsByWall($user->id);

        $this->frontend->setTitle($user->fname . ' ' . $user->lname);
        $this->my_smarty->assign('u', $user);
        $this->my_smarty->assign('wall', $wall);

        $this->frontend->view('user/profile');
    }
}