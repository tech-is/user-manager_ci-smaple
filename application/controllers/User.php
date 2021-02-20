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

	public function index()
	{
		$headData["pageName"] = "ホーム";
		$this->load->view("head", $headData);
		$this->load->view("home");
	}

	public function register()
	{
		if(empty($_POST)){
			$headData["pageName"] = "新規登録";
			$this->load->view("head", $headData);
			$this->load->view("user_register");
			return;
		}

		// POSTされたとき
		$email = $this->input->post("email");
		$password = $this->input->post("password");
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$user = [
			"email" => $email,
			"password" => $hashedPassword,
		];
		$this->User_model->insert($user);
	}

	public function manage()
	{
		$data["users"] = $this->User_model->fetchUsers( $this->fetchLimit );
		$this->load->view("user_manage", $data);
	}

}
