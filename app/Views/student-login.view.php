<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>My first website</title>
	<link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
	<?php
		if (App\Session::exists('error')) {
			echo App\Session::flash('error');
		}
	 ?>
	<div class="left">
		<h2>Login</h2>
		<form class="form-login" method="POST" action="/student/login">
			<input type="email" name="email" placeholder="E-mail" required>
			<input type="password" name="password" placeholder="Password" required>
			<input type="submit" value="Login">
		</form>
	</div>
	<div class="right">
		<h2>Register</h2>
		<form class="form-register" method="POST" action="/student/register">
			<input type="text" name="name" placeholder="Name">
			<input type="email" name="email" placeholder="E-mail">
			<input type="password" name="password" placeholder="password">
			<input type="submit" value="Register">
		</form>
	</div>
</body>
</html>
