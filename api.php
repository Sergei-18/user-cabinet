<?php
function isJson($string) {
   json_decode($string);
   return json_last_error() === JSON_ERROR_NONE;
}

// Json DB
require 'db.php';
$db = new _DataBase;
$db->loadUsers();
//echo json_encode($db->users);

// Post read
$postData = file_get_contents('php://input');
if (isJson($postData) == false) die;
$data = parse_str($postData);
$data = json_decode($postData, true);
//Debug post - file_put_contents('json.test', json_encode($data));

$action = '';
if (isset($_GET['a']))
	$action = trim(htmlspecialchars($_GET['a']));
	
if ($action == 'auth') {
	$allowAuth = true;
	if (array_key_exists('login', $data) == false) $allowAuth = false;
	if (array_key_exists('pass', $data) == false) $allowAuth = false;
	if ($allowAuth) {
		$loginStatus = $db->checkUserPassword($data['login'], $data['pass']);
		if ($loginStatus == 0) {
			session_start();
			$_SESSION['login'] = $data['login'];
			setcookie('name', $db->getName($data['login']));
		}
		echo '{"status": ' . $loginStatus . '}';
	}
}

if ($action == 'reg') {
	$allowReg = true;
	if (array_key_exists('login', $data) == false) $allowReg = false;
	if (array_key_exists('pass', $data) == false) $allowReg = false;
	if (array_key_exists('email', $data) == false) $allowReg = false;
	if (array_key_exists('nick', $data) == false) $allowReg = false;
	
	if ($allowReg) { // Если все нужные поля есть
		if (strlen($data['login']) < 6) $allowReg = false;
		if (strlen($data['pass']) < 6) $allowReg = false;
		if (strlen($data['email']) < 3) $allowReg = false;
		if (strlen($data['nick']) < 2) $allowReg = false;
	}
	
	if ($allowReg)
		echo '{"status": ' . $db->addUser($data['login'], $data['pass'], $data['email'], $data['nick']) . '}';
}	

if ($action == 'test') {
	//echo $db->checkEmailExist('1@1');
}	

if ($action == 'exit') {
	session_start();
	session_unset();
	header('Location: /');
}

?>