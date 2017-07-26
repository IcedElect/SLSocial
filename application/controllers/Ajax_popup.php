<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_popup extends Default_Controller {
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

	function confirm($type){
		$this->response = $this->frontend->fetch('popup/confirm_'.$type);
		echo $this->frontend->returnJson($this->response);
	}

}