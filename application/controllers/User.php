<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	// 制限無くユーザ情報を取得する
	protected $fetchLimit = false;
	protected $uploadDir = FCPATH."/public/upload/";
	protected $uploadUri = "/user-manager/public/upload/";

	public function __construct()
  {
		parent::__construct();
		$this->load->helper('functions');
		$this->load->library('session');
		$this->load->model('User_model');

		// 自動でサニタイズする
		_hMethod();
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
		$headData["pageName"] = "マネージ画面 | ホーム";
		$this->load->view("head", $headData);

		$data["doneDelete"] = false;

		// ユーザ削除完了フラグがある場合
		if(!empty($_SESSION) && array_key_exists("doneDelete", $_SESSION)){
			$data["doneDelete"] = true;
			// 登録完了フラグを削除する
			unset($_SESSION["doneDelete"]);
		}

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

		$headData["pageName"] = "マイページ | マイページ";
		$this->load->view("head", $headData);

		$user_id = $_SESSION["user"];
		$data["user"] = $this->User_model->fetchUsersWithId( $user_id );
		$data["masterUserId"] = $this->User_model->fetchMasterUserId();
		$this->load->view("user_mypage", $data);
	}

	/**
	 * マネージ画面（編集ページ）
	 */
	public function edit()
	{
		if( !isLoginUser() ){
			redirect("/user-manager");
		}

		// ユーザIDパラメータが無い場合は"ホーム"に戻る
		if( empty($_GET) || !array_key_exists("user_id",$_GET)) {
			redirect(
				"/user-manager/user/manage"
			);
		}

		$headData["pageName"] = "マイページ | 編集";
		$this->load->view("head", $headData);

		$user_id = $_GET["user_id"];
		$user = $this->User_model->fetchUsersWithId( $user_id );

		// 存在しないユーザを編集できないので "ホームに戻る"
		if( empty($user) ){
			redirect(
				"/user-manager/user/manage"
			);
		}

		$masterUserId = $this->User_model->fetchMasterUserId();

		// マスタユーザでは無い場合、他ユーザの編集はできなので "ホーム" に戻る
		if( $masterUserId !== $_SESSION["user"] && $user["id"] !== $_SESSION["user"]){
			redirect(
				"/user-manager/user/manage"
			);
		}

		$isEdited = false;
		if(!empty($_POST)){
			$this->User_model->update($user_id, $_POST);
			$isEdited = true;
		}

		// COMMENT:エラーが無いことを明示的に確認する
		if(!empty($_FILES) && $_FILES["iconImage"]["error"] === 0){
			$file = $_FILES['iconImage'];
			$uploadFile = $file['tmp_name'];
			$uploadPath = $this->uploadDir . $file['name'];
			$isUploadImageSuccess = move_uploaded_file($uploadFile, $uploadPath);
			if($isUploadImageSuccess){
				$data = [
					"icon_url" => $this->uploadUri.$file['name']
				];
				$this->User_model->update($user_id, $data);
				$isEdited = true;
			}
		}

		// ユーザ編集後は "ホーム" に戻る
		if($isEdited){
			redirect(
				"/user-manager/user/manage"
			);
		}

		$data["user"] = $user;
		$this->load->view("user_edit", $data);
	}

	/**
	 * マネージ画面（ユーザ削除）
	 */
	public function delete()
	{
		if( !isLoginUser() ){
			redirect("/user-manager");
		}

		// ユーザIDパラメータが無い場合は"ホーム"に戻る
		if( empty($_GET) || !array_key_exists("user_id",$_GET)) {
			redirect(
				"/user-manager/user/manage"
			);
		}

		$user_id = $_GET["user_id"];
		$user = $this->User_model->fetchUsersWithId( $user_id );

		// 存在しないユーザを編集できないので "ホームに戻る"
		if( empty($user) ){
			redirect(
				"/user-manager/user/manage"
			);
		}

		$masterUserId = $this->User_model->fetchMasterUserId();

		// マスタユーザでは無い場合、他ユーザの編集はできなので "ホーム" に戻る
		if( $masterUserId !== $_SESSION["user"] && $user["id"] !== $_SESSION["user"]){
			redirect(
				"/user-manager/user/manage"
			);
		}

		// ユーザを削除する
		$this->User_model->delete($user_id);

		// 削除完了フラグをセットする
		$_SESSION["doneDelete"] = true;

		// ホームに戻る
		redirect(
			"/user-manager/user/manage"
		);
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
