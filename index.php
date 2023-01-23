<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<meta charset="utf-8"/>
<title>Авторизация</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<link rel="stylesheet" href="style.css">
<script type="text/javascript">
	function SendData(URL, Data){
		 return new Promise(function(resolve, reject) {
		 
			let request = new XMLHttpRequest();
			request.open('POST', URL);
			request.setRequestHeader('Content-type', 'text/plain');
			
			request.onload = function() {
				if	(request.status == 200)
					resolve(request.response);
				else
					reject();
			}
			
			request.onerror = function() {
				reject();
			};
			
			request.send(Data);
		});
	}
	
	function Auth(){
		document.getElementById('status').innerHTML = '';
		let login = document.getElementById('login').value.trim();
		let pass = document.getElementById('password').value;
		let json = '{"login" : "' + login + '", "pass" : "' + pass + '"}';
		if (login.trim() == '' || pass.trim() == '')
			return;

		SendData('/api.php?a=auth', json).then(function(responseText) {
			//alert(responseText);
			const JsonObj = JSON.parse(responseText);
			if (JsonObj['status'] == 2)
				document.getElementById('status').innerHTML = 'Пользователь не найден';
			else if (JsonObj['status'] == 1)
				document.getElementById('status').innerHTML = 'Пароль неверный';
			else if (JsonObj['status'] == 0)
				document.location = '/page.php';
			//location.reload();
		}).catch(function() {
			console.log('Сервер недоступен');
		});
	}

document.addEventListener("DOMContentLoaded", function(){
	
});
	
</script>
</head>
<?php 
session_start();
if (isset($_SESSION['login']))
	header('Location: /page.php');
?>
<body>
	<div id="window-box">
		<h2>Авторизация</h2>
		<form>
		<input type="text" id="login" placeholder="Логин"><br>
		<input type="password" id="password" placeholder="Пароль"><br>
		<input type="button" onclick="Auth()" value="Войти">
		<input type="button" onclick="document.location='/reg.php'" value="Регистрация">
		</form>
		<br><span id="status"></span>
	</div>
</body>
</html>