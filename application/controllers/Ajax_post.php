<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_post extends Default_Controller {
    private $response = array('response' => false, 'html' => '');
    function __construct(){
        parent::__construct();
        $this->load->model('wall_model', 'Posts');
        if(!$this->user->is_logged())
            exit;

        $this->user_id = $this->session->userdata('user_id');
    }

    public function index()
    {
        $this->frontend->view('welcome');
    }

    function like($id, $type){
        if(!$id)
            return;

        $this->load->model('default_model', 'Likes');
        $this->Likes->setTable('likes');

        $post = $this->Posts->getPost($id);

        $check = $this->Likes->getDataByWhere(array('author' => $this->user_id, 'item' => $id, 'type' => 'post'));
        if(isset($check[0]))
            $check = $check[0];

        //$notify_data = array('post_id' => $post['id'], 'text' => $post['content'], 'wall_id' => $post['wall_id'], 'type' => $type);
        //$notify = array('type' => 'post_like', 'from_user_id' => $this->user_id, 'to_user_id' => $post['author'], 'date' => time(), 'data' => json_encode($notify_data));
        if($check){
            if($check->value != $type){
                $this->Likes->saveWhere(array('author' => $this->user_id, 'item' => $id, 'type' => 'post'), array('value' => $type));
                //if($notify['to_user_id'] !== $this->user_id)
                    //@$this->Notifications->update($notify, $this->templater->element('mail/notify'));
            }else{
                $this->Likes->delWhere(array('author' => $this->user_id, 'item' => $id, 'type' => 'post'));
                $this->response['remove'] = true;
                //if($notify['to_user_id'] !== $this->user_id)
                    //@$this->Notifications->delete($notify);
            }
            $this->response['response'] = true;
        }else{
            if($this->Likes->save(array('author' => $this->user_id, 'item' => $id, 'value' => $type, 'type' => 'post', 'date' => time()), 'add')){
                //if($notify['to_user_id'] !== $this->user_id)
                    //@$this->Notifications->add($notify, $this->templater->element('mail/notify'));
                $this->response['response'] = true;
            }
        }
        
        if($this->response['response'] == true){

            $post = $this->Posts->getPost($id);
            if(isset($post[0])) $post = $post[0];
            $this->response['response'] = array('likes_count' => $post->likes_count, 'dislikes_count' => $post->dislikes_count, 'rating_count' => ($post->likes_count - $post->dislikes_count));
        }

        echo json_encode($this->response);
    }

}