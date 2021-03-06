<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	// 制限無くユーザ情報を取得する
	protected $fetchLimit = false;

	public function __construct()
  {
			parent::__construct();
			$this->load->helper('functions');
			$this->load->library('session');
			$this->load->model('User_model');
  }

	/**
	 * ホーム画面
	 */
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

	/**
	 * 新規登録ページ
	 */
	public function register()
	{
		$headData["pageName"] = "新規登録";
		$this->load->view("head", $headData);

		$data["errorMessage"] = "";
		if(empty($_POST)){
			$this->load->view("user_register", $data);
			return; // 以降の処理を読み込ませない
		}

		// POSTされたとき
		$email = $this->input->post("email");

		$user = $this->User_model->fetchUsersWithEmail($email);

		// 既にユーザが存在すればエラーメッセージを出力する
		if( !is_null($user) ){
			$data["errorMessage"] = "既に存在するユーザです";
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
		redirect("/user-manager");
	}

	/**
	 * ログインページ
	 */
	public function login()
	{
		$headData["pageName"] = "ログイン";
		$this->load->view("head", $headData);

		$data["errorMessage"] = "";
		if(empty($_POST)){
			$this->load->view("user_login", $data);
			return; // 以降の処理を読み込ませない
		}

		$email = $this->input->post("email");
		$password = $this->input->post("password");

		$user = $this->User_model->fetchUsersWithEmail($email);

		// ユーザが存在しなければログインエラー
		if( empty($user) ){
			$data["errorMessage"] = "ユーザは存在しません";
			$this->load->view("user_login", $data);
			return; // 以降の処理を読み込ませない
		}

		// パスワードが一致しない場合
		if(!password_verify($password, $user["password"])){
			$data["errorMessage"] = "パスワードが一致しません";
			$this->load->view("user_login", $data);
			return; // 以降の処理を読み込ませない
		}

		// ログインする
		$_SESSION["user"] = $user["id"];

		// トップページに戻る
		redirect(
			"/user-manager/user/manage"
		);
	}

	/**
	 * マネージ画面（ダッシュボード）
	 */
	public function manage()
	{
		// ログイン状態でない場合はホームページにリダイレクト
		if( !isLoginUser() ){
			redirect("/user-manager");
		}
		$headData["pageName"] = "マネージ";
		$this->load->view("head", $headData);

		$user_id = $_SESSION["user"];
		$data["my"] = $this->User_model->fetchUsersWithId( $user_id );
		$data["users"] = $this->User_model->fetchUsers( $this->fetchLimit );
		$data["masterUserId"] = $this->User_model->fetchMasterUserId();
		$this->load->view("user_manage", $data);
	}

	/**
	 * マネージ画面（マイページ）
	 */
	public function mypage()
	{
		if( !isLoginUser() ){
			redirect("/user-manager");
		}

		$headData["pageName"] = "マネージ";
		$this->load->view("head", $headData);

		$user_id = $_SESSION["user"];
		$data["user"] = $this->User_model->fetchUsersWithId( $user_id );
		$data["masterUserId"] = $this->User_model->fetchMasterUserId();
		$this->load->view("user_mypage", $data);
	}

	/**
	 * ログアウト
	 */
	public function logout()
	{
		session_destroy();
		redirect("/user-manager");
	}

}
