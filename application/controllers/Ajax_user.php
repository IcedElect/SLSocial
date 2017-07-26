<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_user extends Default_Controller {
	private $response = array('response' => false, 'html' => '');
	function __construct(){
		parent::__construct();
		$this->load->model('user_model');
		if(!$this->user->is_logged())
			exit;

		$this->user_id = $this->session->userdata('user_id');
	}

	public function index()
	{
		$this->frontend->view('welcome');
	}

    function sidebar($type){
        $sidebar = $this->load_sidebar($type);
        echo $sidebar['html']['data'];
    }

	function actions($id){
		$this->response['response'] = true;

		$this->friend_button($id);
		$this->response['html'] .= '<li><a href="javascript:void(0)"><i class="icon icon-flag"></i><span>Пожаловаться</span></a></li>';

		echo $this->frontend->returnJson($this->response);
	}

	function friend_button($id = false){
		if($id != $this->user_id){ // Если не моя страница
            if($this->user_model->checkFriends($this->user_id, $id)){ // Если друг
            	$this->response['html'] .= '<li><a href="javascript:void(0)" onclick="Users.delFriend(\''.$id.'\', this)"><i class="icon icon-user"></i><span>Удалить из друзей</span></a></li>';
            }else{ // Если не друг
            	if($this->user_model->checkReq($this->user_id, $id)){ // Если есть заявка в друзья
            		$this->response['html'] .= '<li><a href="javascript:void(0)" onclick="Users.reqCancel(\''.$id.'\', this)"><i class="icon icon-user"></i><span>Отменить заявку</span></a></li>';
            	}else{ // Если нет заявки в друзья
            		if($this->user_model->checkSubscribe($this->user_id, $id)){ // Если подписан
            			$this->response['html'] .= '<li><a href="javascript:void(0)" onclick="Users.unsubscribe(\''.$id.'\', this)"><i class="icon icon-user"></i><span>Отписаться</span></a></li>';
            		}else{ // Если не подписан
            			$perms = $this->user_model->getPerms($id);
                    	$perms = $perms[0];
            			if($perms->friends_type == 0){ // Если можно добавить в друзья
            				$this->response['html'] .= '<li><a href="javascript:void(0)" onclick="Users.sendReq(\''.$id.'\', this)"><i class="icon icon-user-add"></i><span>Добавить в друзья</span></a></li>';
            			}else{ // Если нельзя добавить в друзья
            				$this->response['html'] .= '<li><a href="javascript:void(0)" onclick="Users.subscribe(\''.$id.'\', this)"><i class="icon icon-user"></i><span>Подписаться</span></a></li>';
            			}
            		}
            	}
            }
        }else{ // Если моя страница

        }
	}

	//Доделать проверку на уже существующую заявку
	function send_req($id){
		$data = array('result' => false);
        if($id != $this->user_id){
            if(!$this->user_model->checkFriends($this->user_id, $id)){
                if($this->user_model->checkReq($id, $this->user_id)){
                    if($this->user_model->reqOk($id, $this->user_id)){
                        $this->user_model->subscribe($this->user_id, $id);
                        $data['result'] = true;
                        $data['text'] = 'Удалить из друзей';
                        $data['onclick'] = 'Users.delFriend(\''.$id.'\', this, true)';
                        $this->friend_button($id);
                    }else{
                        $data['error'] = 'Произошла ошибка.';
                    }
                }else{
                    $perms = $this->user_model->getPerms($id);
                    $perms = $perms[0];
                    if($perms->friends_type == 0){
                        if($this->user_model->sendReq($this->user_id, $id)){
                            $this->user_model->subscribe($this->user_id, $id);
                            $data['result'] = true;
                            $data['text'] = 'Отменить заявку';
                            $data['onclick'] = 'Users.reqCancel(\''.$id.'\', this)';
                            $this->friend_button($id);
                        }else{
                            $data['error'] = 'Произошла ошибка.';
                        }
                    }else{
                        $data['error'] = 'Этого пользователя нельзя добавить в друзья.';
                    }
                }
            }else{
                $data['error'] = 'Этот пользователь уже является вашим другом.';
            }
        }else{
            $data['error'] = 'Вы не можете добавить себя в друзья.';
        }
        echo $this->frontend->returnJson($this->response);return true;
        echo json_encode($data);
	}

	function req_cancel($id){
        $data = array('result' => false);
        if($id != $this->user_id){
            if($this->user_model->checkFriendsReq($this->user_id, $id)){
                if($this->user_model->reqCancel($this->user_id, $id)){
                    $data['result'] = true;
                    $data['text'] = 'Добавить в друзья';
                    $data['onclick'] = 'user_model.sendReq(\''.$id.'\', this)';
                    $this->friend_button($id);
                }else{
                    $data['error'] = 'Произошла ошибка.';
                }
            }else{
                $data['error'] = 'Такой заявки не существует.';
            }
        }else{
            $data['error'] = 'Вы не можете добавить себя в друзья.';
        }
        echo $this->frontend->returnJson($this->response);return true;
        echo json_encode($data);
    }

    function subscribe($id){
        $data = array('result' => false);
        if($id != $this->user_id){
            if($this->user_model->subscribe($this->user_id, $id)){
                $data['result'] = true;
                $data['text'] = 'Отписаться';
                $data['onclick'] = 'Users.unsubscribe(\''.$id.'\', this)';
                $this->friend_button($id);
            }else{
                $data['error'] = 'Произошла ошибка.';
            }
        }else{
            $data['error'] = 'Вы не можете подписаться на себя.';
        }
        echo $this->frontend->returnJson($this->response);return true;
        echo json_encode($data);
    }


    // Доделать проверку на возможность добавления в друзья
    function unsubscribe($id){
        $data = array('result' => false);
        if($id != $this->user_id){
            if($this->user_model->checkSubscribe($this->user_id, $id)){
                if($this->user_model->unsubscribe($this->user_id, $id)){
                    $data['result'] = true;
                    $data['text'] = 'Подписаться';
                    $data['onclick'] = 'Users.subscribe(\''.$id.'\', this, true)';
                    $this->friend_button($id);
                }else{
                    $data['error'] = 'Произошла ошибка.';
                }
            }else{
                $data['error'] = 'Такой заявки не существует.';
            }
        }else{
            $data['error'] = 'Вы не можете добавить себя в друзья.';
        }
        echo $this->frontend->returnJson($this->response);return true;
        echo json_encode($data);
    }

    function req_no($id){
        $data = array('result' => false);
        if($id != $this->user_id){
            if($this->user_model->checkReq($id, $this->user_id)){
                if($this->user_model->reqNo($id, $this->user_id)){
                    $data['result'] = true;
                }else{
                    $data['error'] = 'Произошла ошибка.';
                }
            }else{
                $data['error'] = 'Такой заявки не существует.';
            }
        }else{
            $data['error'] = 'Вы не можете добавить себя в друзья.';
        }
        echo $this->frontend->returnJson($this->response);return true;
        echo json_encode($data);
    }

    function req_ok($id){
        $data = array('result' => false);
        if($id != $this->user_id){
            if($this->user_model->checkReq($id, $this->user_id)){
                if($this->user_model->reqOk($id, $this->user_id)){
                    $this->user_model->subscribe($this->user_id, $id);
                    $data['result'] = true;
                }else{
                    $data['error'] = 'Произошла ошибка.';
                }
            }else{
                $data['error'] = 'Такой заявки не существует.';
            }
        }else{
            $data['error'] = 'Вы не можете добавить себя в друзья.';
        }
        echo $this->frontend->returnJson($this->response);return true;
        echo json_encode($data);
    }
    
    function del_friend($id){
        $data = array('result' => false);
        if($id != $this->user_id){
            if($this->user_model->checkFriends($this->user_id, $id)){
                if($this->user_model->delFriend($id, $this->user_id)){
                    $data['result'] = true;
                    $data['text'] = 'Добавить в друзья';
                    $data['onclick'] = 'Users.sendReq(\''.$id.'\', this)';
                    $this->friend_button($id);
                }else{
                    $data['error'] = 'Произошла ошибка.';
                }
            }else{
                $data['error'] = 'Вы не друзья.';
            }
        }else{
            $data['error'] = 'Вы не можете добавить себя в друзья.';
        }
        echo $this->frontend->returnJson($this->response);return true;
        echo json_encode($data);
    }
}
