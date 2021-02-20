<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	// 制限無くユーザ情報を取得する
	protected $fetchLimit = false;

	public function __construct()
  {
			parent::__construct();
			$this->load->library('session');
			$this->load->model('User_model');
  }

	// public function createUser()
	// {
	// 	$data = [];
	// 	$data["users"] = $this->User_model->fetchUsers( $this->fetchLimit );
	// 	$this->load->view('user_manage', $data);
	// }

}
