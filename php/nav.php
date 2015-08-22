<div id = 'nav'>
	<p id = 'navbar'>
		<a href="genre.php">Genres</a>
		<br />
		<?php include_once('db.php');
		$conn;
		try {
                	$conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PW);
                // set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        	}
        	// Catch exception if connection fails
        	catch(PDOException $e)
        	{
                	echo "Connection failed: " . $e->getMessage();
        	}
		$query = "SELECT * FROM Genre";
		$result = $conn->query($query);
		foreach($result as $row)
		{
			echo "<a href='genre.php'>  - $row[1]</a>";		
			echo "<br />";
		}
		?>
		<br />
		<?php if(isset($_SESSION["username"])) :  ?>
			<a href="createtopic.php">Create Topic</a>
			<br />
			<a href="logout.php">Logout</a>
			<br />
		<?php else:?>
			<a href="register.php">Sign Up!</a>
			<br />
		<?php endif; ?>
		<br />
	</p>
	
	<?php if(isset($_SESSION["username"])) :  ?> 
	<br />
	<?php else: ?>
	<form method ="POST" action ="login.php">
			<fieldset>
			<label for = "username">Username:</label><br />
			<input type="text" name="username" id="username" /><br />
			<label for = "password">Password:</label><br />
			<input type="password" name="password" id="password" /><br />
			<br />
			<input type="submit" value="Login" />
			</fieldset>
	</form>
	<?php endif; ?>
	</div>

