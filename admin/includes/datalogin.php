<?php
	//remote connection
	/*$servername = "spike2careorg.ipagemysql.com";
	$username = "s2c_admin";
	$password = "s2c_admin";
	$dbname = "spike2care";*/
	
	//local connection
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "spike2care";
		
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	if (!defined('IS_DEV')) {
		define('IS_DEV', true);
	}
?>