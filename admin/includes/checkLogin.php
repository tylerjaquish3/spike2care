<?php

	//MySQL Database Connect 
	include 'datalogin.php';
	require 'password.php';
	require 'functions.php';
	$tbl_name = "admin";

	// username and password sent from form 
	$usernameTry=$_POST['myusername']; 
	$passwordTry=$_POST['mypassword'];
	
	// To protect MySQL injection
	$usernameTry = htmlspecialchars($usernameTry);
	$passwordTry = htmlspecialchars($passwordTry);
	$usernameTry = mysqli_real_escape_string($conn, $usernameTry);
	$passwordTry = mysqli_real_escape_string($conn, $passwordTry);
	
	$sql = $conn->prepare("SELECT * FROM $tbl_name WHERE email= ? AND is_active = 1");
	$sql->bind_param('s', $usernameTry);
	$sql->execute();
	$result = $sql->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
			$myuserid = $row['id'];
			$myusername = $row['user_name'];
			$mypassword = $row['password'];
		}
	}

	// Mysql_num_row is counting table row
	$count = mysqli_num_rows($result);

	//this is how you create a new password: enter it as first parameter, then try to login. 
	//it will display the hash for the password you entered.
	//var_dump(password_hash('s2c123', PASSWORD_BCRYPT));
	
	// If result matched $usernameTry, there was 1 result
	if($count == 1){
	
		if(password_verify($passwordTry, $mypassword)){
			
			// Register $myusername, $mypassword and redirect to dashboard
			session_start();
			
			$_SESSION["user_name"] = $myusername;
			$_SESSION["user_id"] = $myuserid;

			$now = date("Y-m-d H:i:s");
			// Update datetime in admin table
			$sql = "UPDATE admin SET updated_at = '$now' WHERE id = $myuserid";
			$result = mysqli_query($conn, $sql);
			
			$result = ['type' => 'success', 'message' => 'Logged in.'];
		}
		else{
			$result = ['type' => 'error', 'message' => 'Wrong password. Please try again.'];
		}
	
	}
	else 
	{
		$result = ['type' => 'error', 'message' => 'Your user name does not exist. Please try again.'];
	}

    echo json_encode($result);
    die;
?>