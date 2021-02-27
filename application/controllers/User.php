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
		$data["doneRegister"] = false;
		if(!empty($_SESSION) && array_key_exists("doneRegister", $_SESSION)){
			$data["doneRegister"] = true;
			// 登録完了フラグを削除する
			unset($_SESSION["doneRegister"]);
		}
		$this->load->view("head", $headData);
		$this->load->view("home", $data);
	}

	public function register()
	{
		$data["errorMessage"] = "";
		$headData["pageName"] = "新規登録";
		if(empty($_POST)){
			$this->load->view("head", $headData);
			$this->load->view("user_register", $data);
			return; // 以降の処理を読み込ませない
		}

		// POSTされたとき
		$email = $this->input->post("email");

		// 既にユーザが存在すればエラーメッセージを出力する
		if( !empty($this->User_model->fetchUsersWithEmail($email)) ){
			$data["errorMessage"] = "既に存在するユーザです";
			$this->load->view("head", $headData);
			$this->load->view("user_register", $data);
			return; // 以降の処理を読み込ませない
		}

		$password = $this->input->post("password");
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$user = [
			"email" => $email,
			"password" => $hashedPassword,
		];
		$this->User_model->insert($user);

		// 登録完了フラグをセットする
		$_SESSION["doneRegister"] = true;

		// トップページに戻る
		header('location: /user-manager');
	}

	public function manage()
	{
		$data["users"] = $this->User_model->fetchUsers( $this->fetchLimit );
		$this->load->view("user_manage", $data);
	}

}
