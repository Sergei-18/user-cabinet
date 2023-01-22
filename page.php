<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Описание" />
<title>Кабинет</title>
<link rel="stylesheet" href="style.css">
<style type="text/css"></style>
<script src="javascript.js"></script>
<script type="text/javascript">
//document.addEventListener("DOMContentLoaded", function(){alert('DOM готов');});
</script>
</head>
<body>
	<div id="window-box">
		<a href="/api.php?a=exit">Выход</a><br><br>
<?php
session_start();
if (isset($_SESSION['login']))
	$login = $_SESSION['login'];
else
	header('Location: /');

$name = '';
if (isset($_COOKIE['name']))
	$name = $_COOKIE['name'];

	echo "Добро пожаловать, $name!";
	//echo "<br>Логин пользователя: $login";
?>
</div>
</body>
</html>
