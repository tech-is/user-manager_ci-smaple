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