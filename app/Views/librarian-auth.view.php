<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>My first website</title>
	<link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
	<?php
		if (App\Session::exists('message')) {
			echo App\Session::flash('message');
		}
	 ?>
	<div class="left">
		<h2>Login</h2>
		<form class="form-login" method="POST" action="/librarian/login">
			<input type="email" name="email" placeholder="E-mail" required>
			<input type="password" name="password" placeholder="Password" required>
			<input type="submit" value="Login">
		</form>
	</div>
</body>
</html>
