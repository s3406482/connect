<?php
  session_start();
//initialise needed variables

$userName = $email = $password = "";
$emailErr = $usernameErr = $passwordErr ="";

if($_SERVER["REQUEST_METHOD"] == "POST")
{

	if (empty($_POST["email"]))  {
        	$emailErr = "Missing";
    	}
    	else {
    		$email = $_POST["email"];
    		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  			$emailErr = "Error - Invalid email format"; 
		}
     
    	}

	if (empty($_POST["username"])) {
        	$usernameErr = "missing";
    	}
    	else {
        	$userName = $_POST["username"];
        	if (ctype_alnum($userName) == FALSE)
        	{
        		$usernameErr = "Error - Username Must only be alphanumeric!!";
        	}
    	}
    	if (empty($_POST["password"])) {
    		$passwordErr = "missing";
    	}
    	else {
    		$password = $_POST["password"];
    	
    		if (strlen($password) < 8 )
    		{
    			$passwordErr = "Error - Password Must be more than 8 characters!!";
    		}

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

	// id variable to get the new UserID
	$id = 0;
	// this will check if email or username already exists
	foreach($result as $row)
	{
		if($row['userName'] == "$userName")
		{
			$usernameErr = " Error Username exists!!";

		}
		if($row['email'] == "$email")
		{
			$emailErr = " Error Email Already Exists!!";
		}
		if($row['userID'] > $id)
		{
			$id = $row['userID'];
		}
	} 

	$id = $id + 1;
	// this will check to ensure there is no errors, if not a message will be stored saying 
	// the registration is successful and the user session will be saved, the user data will 
	// also be stored in the database
	if($usernameErr == "" && $passwordErr == "" && $emailErr == "" )
	{
		$_SESSION["message"] = "Registration Successful!!!";
		$_SESSION["username"] = "$userName";
		$stmt = $conn->prepare("INSERT INTO  `data`.`User` (
					`userID` ,`userName` ,`email` ,`password` ,`posts` ,`rankID`)
					VALUES ('$id',  '$userName',  '$email',  '$password',  '0',  '1')");
		$stmt->execute();

	}
	// if an error is found it will store the error message and display it on the next screen
	else
	{
		$_SESSION["message"] = " $usernameErr $passwordErr $emailErr";
	}
	// close connection
	$conn = NULL;
	// return to homepage
	header("Location:index.php");
}
