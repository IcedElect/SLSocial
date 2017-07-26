<?php

class Sidebar extends Default_controller
{

    function __construct()
    {
        parent::__construct();
        $this->setActiveModule('sidebar');
        $this->frontend->add_body_class('sidebar');
    }

    function friends(){
    	$this->load->model('user_model');
    	$friends = $this->user_model->getFriends($this->oUser->id);

    	$this->my_smarty->assign('friends_all', $friends);
    	$this->frontend->element('sidebar/friends');
    }
}