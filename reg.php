<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<meta charset="utf-8"/>
<title>Регистрация</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<link rel="stylesheet" href="style.css">
<script type="text/javascript">	
	const EMAIL_REGEXP = /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu;

	function isEmailValid(value) {
		return EMAIL_REGEXP.test(value);
	}
	
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
	
	function Reg(){
		const isNumeric = n => !isNaN(n);
		
		document.getElementById('status').innerHTML = '';
		let login = document.getElementById('login').value.trim().toLowerCase();
		let pass = document.getElementById('password').value;
		let pass2 = document.getElementById('password2').value;
		let email = document.getElementById('email').value.trim();
		let nick = document.getElementById('name').value.trim();
		let json = '{"login" : "' + login + '", "pass" : "' + pass + '", "email" : "' + email + '", "nick" : "' + nick + '"}';
		//alert(json);
		if (login.trim() == '' || pass.trim() == '' || pass2.trim() == '' || email.trim() == '' || nick.trim() == '') {
			document.getElementById('status').innerHTML = 'Заполните все поля.';
			return;
		}
		
		let stopReg = false;
		document.getElementById('status').innerHTML = '';
		document.getElementById('descr1').innerHTML = '';
		document.getElementById('descr2').innerHTML = '';
		document.getElementById('descr3').innerHTML = '';
		document.getElementById('descr4').innerHTML = '';
		document.getElementById('descr5').innerHTML = '';
		
		if (login.length < 6) {
			document.getElementById('descr1').innerHTML = 'Логин должен быть минимум 6 символов';
			stopReg = true;
		}
		
		if (pass.length < 6) {
			document.getElementById('descr2').innerHTML = 'Пароль должен быть минимум 6 символов';
			
			stopReg = true;
		} else {
			let foundNumber = false;
			let foundStr = false;
			for (let i = 0; i < pass.length; i++) {
				if (isNumeric(pass[i]))
					foundNumber = true;
				else
					foundStr = true;
			}
			
			if (foundNumber == false) {
				document.getElementById('descr2').innerHTML += '<br>Пароль должен содержать числа';
				stopReg = true;
			}
			
			if (foundStr == false) {
				document.getElementById('descr2').innerHTML += '<br>Пароль должен содержать буквы';
				stopReg = true;
			}
		}
		
		

		if (pass != pass2) {
			document.getElementById('descr3').innerHTML = 'Пароли должны совпадать.';
			stopReg = true;
		}
		

		//if (email.indexOf('@') == -1 || email.length < 3) {
		if (isEmailValid(email) == false) {
			document.getElementById('descr4').innerHTML = 'Введите корректный email';
			stopReg = true;
		}
		
		if (nick.length < 2) {
			document.getElementById('descr5').innerHTML = 'Имя должен быть минимум 2 символа';
			stopReg = true;
		} else {
			let foundNumber = false;
			for (let i = 0; i < nick.length; i++) {
				if (isNumeric(nick[i]))
					foundNumber = true;
			}
			
			if (foundNumber) {
				document.getElementById('descr5').innerHTML += '<br>Имя не может содержать символы';
				stopReg = true;
			}

		}
		
		if (stopReg) return;

		SendData('/api.php?a=reg', json).then(function(responseText) {
			//alert(responseText);
			const JsonObj = JSON.parse(responseText);
			if (JsonObj['status'] == 1)
				document.getElementById('status').innerHTML = 'Пользователь с таким ником уже зарегистирован';
			else if (JsonObj['status'] == 2)
				document.getElementById('status').innerHTML = 'Пользователь с таким email уже зарегистирован';
			else if (JsonObj['status'] == 0) {
				alert('Регистрация прошла успешно');
				document.location = '/';
			}
			//location.reload();
		}).catch(function() {
			console.log('Сервер недоступен');
		});
	}

document.addEventListener("DOMContentLoaded", function(){
	//Reg();
});
	
</script>
</head>

<body>
	<div id="window-box">
		<h2>Регистрация</h2>
		Логин:<br>
		<input type="text" id="login" placeholder="Логин"><br>
		<span id="descr1"></span><br>
		Введите пароль:<br>
		<input type="text" id="password" placeholder="Пароль"><br>
		<span id="descr2"></span><br>
		Повторите пароль:<br>
		<input type="text" id="password2" placeholder="Подтвердите пароль"><br>
		<span id="descr3"></span><br>
		Почта:<br>
		<input type="text" id="email" placeholder="Почта"><br>
		<span id="descr4"></span><br>
		Имя:<br>
		<input type="text" id="name" placeholder="Имя"><br>
		<span id="descr5"></span><br>
		<input type="button" onclick="Reg()" value="Регистрация">
		<input type="button" onclick="document.location='/'" value="Выход">
		<br><span id="status"></span>
	</div>
</body>
</html>