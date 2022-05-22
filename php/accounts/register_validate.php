<?php

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
require_once 'global/validate.php';

if(!post_exists(['email', 'username', 'password', 'gender']))
  failure('Nie dostarczono wszystkich danych.');

$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$gender = sanitize_gender($_POST['gender']);

if(!valid_email($email))
  failure('Walidacja adresu e-mail nie powiodła się.');
if(!valid_username($username))
  failure('Walidacja loginu nie powiodła się.');
if(!valid_password($password))
  failure('Walidacja hasła nie powiodła się. (min. 5, max. 256 znaków)');

$ip = 'null';
if(
  isset($_SERVER['REMOTE_ADDR'])
  && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)
)
$ip = $_SERVER['REMOTE_ADDR'];


require_once 'accounts/reusable.php';
$success = insert_new_user($ip, $email, $username, $password, $gender, 'user');

if(!$success[0])
  failure($success[1]);

echo '1';