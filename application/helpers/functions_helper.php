<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function isLoginUser(): bool
{
  return !empty($_SESSION) && array_key_exists("user", $_SESSION);
}

function redirect(
  string $to
){
  $toLocation = sprintf("location: %s", $to);
  header($toLocation);
  exit();
}

/**
 * リクエストメソッドに応じてサニタイズする
 *
 * @return void
 */
function _hMethod()
{
  $requestMethod = $_SERVER["REQUEST_METHOD"];
  if($requestMethod === "GET"){
    foreach($_GET as $key => $value){
      $_GET[$key] = $value;
    }
    return;
  }
  foreach($_POST as $key => $value){
    $_POST[$key] = $value;
  }
  // COMMENT:スーパーグローバル変数なので返り値不要
}