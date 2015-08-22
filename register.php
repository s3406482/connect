<?php
	session_start();


?>

<head>
	<?php include_once ('php/header.php');?>
	<title>Sign Up!!</title>

</head>

<body>
	
<div id = 'content'>
		<form method ="POST" action ="validate.php">
			<fieldset>
			<label for = "username">Username: </label><br />
			<input type="text" name="username" id="username" required /><br />
			<label for = "email">Email Address:</label><br />
			<input type="text" name="email" id = "email" required></input><br />
			<label for = "password">Password:(required)</label><br />
			<input type="password" name="password" id="password" required/><br />
			<br />
			<input type="submit" value="Register" />
			</fieldset>
</div>
</body>

