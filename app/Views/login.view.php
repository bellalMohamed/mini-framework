<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
</head>
<body>
	<?php 
		foreach ($users as $user) {
			echo "<p>{$user->name}</p>";
		}
	?>
</body>
</html>