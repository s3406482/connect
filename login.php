<?php session_start();
	
	$userName = $email = $password = "";
	$emailErr = $usernameErr = $passwordErr ="";
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	if (empty($_POST["username"])) {
        	$usernameErr = "missing";
    	}
    	else {
        	$userName = $_POST["username"];
    	}
    	if (empty($_POST["password"])) {
    		$passwordErr = "missing";
    	}
    	else {
    		$password = $_POST["password"];
    	}
	include_once('php/db.php');
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
	$query = "SELECT * FROM  `User`";
	$result = $conn->query($query);
	// check variable to check if username or email exists in database
	$check = 0;
	// this will check if email or username already exists
	foreach($result as $row)
	{
		if($row['userName'] == "$userName" && $row['password'] == "$password" )
		{
			$_SESSION["username"] = $row['userName'];
			$check++;
		}
		if($row['email'] == "$username" && $row['password'] == "$password" )
		{
			$_SESSION["username"] = $row['userName'];
			$check++;
		}
	}
	if($check == 0)
	{
		$_SESSION["message"] = "Incorrect Login Details!!";

	}
	else	{
		$_SESSION["message"] = "Logged in as " . $_SESSION["username"];
	}

	header("Location:index.php");
}
?>
