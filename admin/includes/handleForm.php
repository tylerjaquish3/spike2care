<?php
//session_start();

include 'datalogin.php';
include 'functions.php';
include 'password.php';

require_once('../../stripe/init.php');

// var_dump($_GET);die;
	if(isset($_POST['save-event'])) {


		$specialEvent = $registrationOpen = false;
		$fields = $insertItems = $setValues = '';
		$isNew = ($_POST['is-new'] == 'true' ? true : false);
		$eventId = $_POST['event_id'];

		// Upload image first
		$targetDir = "../../images/events/";
		
		if($_FILES['image_path']['name'] != '') {
			$temp = explode(".", $_FILES['image_path']["name"]); 
			$newFileName = round(microtime(true)).rand(1,100).'.'.end($temp);
			$targetFile = $targetDir.$newFileName;

			$return = uploadAttachment($targetFile, $_FILES['image_path'], 'image');
				
			$fileName = $targetDir.$newFileName;

			$fields .= 'image_path,';
			$insertItems = '"'.$newFileName.'",';
			$setValues .= 'image_path="'.$newFileName.'", ';
		}
		
		// Loop through the posted form fields
		foreach ($_POST as $field => $value)
		{
			if ($field == 'special_event') {
				$specialEvent = true;
			} 
			if ($field == 'registration_open') {
				$registrationOpen = true;
			} 
			if ($field == 'is_active') {
				$active = true;
			} 
			
			$ignore = ['save-event', 'is-new', 'event_id', 'special_event', 'registration_open', 'division1', 'div1MaxTeams', 'division2', 'div2MaxTeams', 'division3', 'div3MaxTeams', 'is_active'];

			if (!in_array($field, $ignore)) {
				// If new event, create insert statement
				if ($isNew) {
					$fields .= $field.',';
					// If date, format it properly
					if ($field == 'event_date') {
						$date = date_create_from_format('m/d/Y', $value);
						$insertItems .= '"'.date_format($date, 'Y-m-d').'",';
					} elseif ($field == 'registration_deadline') {
						$date = date_create_from_format('m/d/Y', $value);
						$insertItems .= '"'.date_format($date, 'Y-m-d 23:59:59').'",';
					} else {
						$insertItems .= '"'.escape($value).'",';
					}
				// Create update statement for existing event
				} else {
					if ($field == 'event_date') {
						$date = date_create_from_format('m/d/Y', $value);
						$setValues .= $field."='".date_format($date, 'Y-m-d 00:00:00')."', ";
					} elseif ($field == 'registration_deadline') {
						$date = date_create_from_format('m/d/Y', $value);
						$setValues .= $field."='".date_format($date, 'Y-m-d 23:59:59')."', ";
					} else {
						$setValues .= $field."='".escape($value)."', ";
					}
				}
			} 
		}

		// Add created at timestamp
		$fields .= 'created_at';
		$insertItems .= '"'.date('Y-m-d H:i:s').'"';
		
		if ($isNew && $specialEvent) {
			$fields .= ', special_event';
			$insertItems .= ', 1';
		} elseif ($isNew && !$specialEvent) {
			$fields .= ', special_event';
			$insertItems .= ', 0';
		} elseif (!$isNew && !$specialEvent) {
			$setValues .= "special_event=0";
		} elseif (!$isNew && $specialEvent) {
			$setValues .= "special_event=1";
		}

		if ($isNew && $registrationOpen) {
			$fields .= ', registration_open';
			$insertItems .= ', 1';
		} elseif ($isNew && !$registrationOpen) {
			$fields .= ', registration_open';
			$insertItems .= ', 0';
		} elseif (!$isNew && !$registrationOpen) {
			$setValues .= ", registration_open=0";
		} elseif (!$isNew && $registrationOpen) {
			$setValues .= ", registration_open=1";
		}

		if ($isNew && $active) {
			$fields .= ', is_active';
			$insertItems .= ', 1';
		} elseif ($isNew && !$active) {
			$fields .= ', is_active';
			$insertItems .= ', 0';
		} elseif (!$isNew && !$active) {
			$setValues .= ", is_active=0";
		} elseif (!$isNew && $active) {
			$setValues .= ", is_active=1";
		}
 
		if ($isNew) {
			$sql = "INSERT INTO events (".$fields.") VALUES (".$insertItems.")";
		} else {
			$sql = "UPDATE events SET ".$setValues." WHERE id = '$eventId'";
		}

		// var_dump($sql);
		mysqli_query($conn, $sql);

		if(!$eventId) {
			$eventId = mysqli_insert_id($conn);
		}

		// Delete the existing divisions and save new event divisions in pivot table
		$sql = 'DELETE FROM event_divisions WHERE event_id ='.$eventId;
		mysqli_query($conn, $sql);

		if (isset($_POST['division1'])) {
			$divId = $_POST['division1'];
			$maxTeams = 1000;
			if (isset($_POST['div1MaxTeams']) && $_POST['div1MaxTeams'] != "") {
				$maxTeams = $_POST['div1MaxTeams'];
			}
			$sql = 'INSERT INTO event_divisions (event_id, division_id, max_teams) VALUES ('.$eventId.','.$divId.','.$maxTeams.')';
			mysqli_query($conn, $sql);
		}
		if (isset($_POST['division2'])) {
			$divId = $_POST['division2'];
			$maxTeams = 1000;
			if (isset($_POST['div2MaxTeams']) && $_POST['div2MaxTeams'] != "") {
				$maxTeams = $_POST['div2MaxTeams'];
			}
			$sql = 'INSERT INTO event_divisions (event_id, division_id, max_teams) VALUES ('.$eventId.','.$divId.','.$maxTeams.')';
			mysqli_query($conn, $sql);
		}
		if (isset($_POST['division3'])) {
			$divId = $_POST['division3'];
			$maxTeams = 1000;
			if (isset($_POST['div3MaxTeams']) && $_POST['div3MaxTeams'] != "") {
				$maxTeams = $_POST['div3MaxTeams'];
			}
			$sql = 'INSERT INTO event_divisions (event_id, division_id, max_teams) VALUES ('.$eventId.','.$divId.','.$maxTeams.')';
			mysqli_query($conn, $sql);
		}
		
		header("Location: ".URL."/admin/events.php");
		die();
	}	

	// User saved an event recap
	if(isset($_POST['save-recap'])) {

		$eventId = $_POST['event-id'];
		$active = $_POST['is_active'];
		$target_prefix = "../../";
		$target_dir = "images/recaps/";
		$return = 'success';
		
		for($i = 1; $i < count($_FILES) + 1; $i++) {
			if($_FILES['image'.$i]['name'] != '') {
				$temp = explode(".", $_FILES['image'.$i]["name"]); 
				$newFileName = round(microtime(true)) . rand(1,100) . '.' . end($temp);
				$target_file = $target_prefix . $target_dir . $newFileName;

				$return = uploadAttachment($target_file, $_FILES['image'.$i], 'image');
					
				$fileName = $target_dir . $newFileName;
				
				$arranged_order = $i;
				$caption = '';
				
				$sql = "INSERT INTO photos (event_id, image_path) VALUES ($eventId, '$newFileName')";
				mysqli_query($conn, $sql);
			}
		}

		if ($return == 'success') {
			$isNew = $_POST['is-new'];
			$recapText = escape($_POST['recap-text']);
			$createdAt = date('Y-m-d H:i:s');

			if ($isNew) {
				$sql = "INSERT INTO recaps (event_id, recap_text, is_active, created_at) VALUES ('$eventId', '$recapText', $active, '$createdAt')";
			} else {
				$sql = "UPDATE recaps SET recap_text = '$recapText', is_active = $active, created_at = '$createdAt' WHERE event_id = '$eventId'";
			}

			mysqli_query($conn, $sql);
			// 	$result = ['type' => 'success', 'message' => 'Recap has been saved.'];
			// } else {
			// 	$result = ['type' => 'error', 'message' => 'Error saving recap. Please contact a developer.'];
			// }
			header("Location: ".URL."/admin/events.php?message=success");
			die();
		} else {
			// $result = ['type' => 'error', 'message' => $return];
			header("Location: ".URL."/admin/events.php?message=error");
			die();
		}
	}
	
	// When new admin user is created
	if(isset($_POST['save-user'])){

		$username = '';
		$active = 0;
		if (isset($_POST['userId']))
			$userId = $_POST['userId'];
		if (isset($_POST['user_name']))
			$username = escape($_POST['user_name']);
		if (isset($_POST['email']))
			$email = escape($_POST['email']);
		if (isset($_POST['is_active']))
			$active = $_POST['is_active'];
		if (isset($_POST['role']))
			$role = $_POST['role'];
		if (isset($_POST['isNew']))
			$isNew = $_POST['isNew'];

		$date = date('Y-m-d H:i:s');
		$slug = rand_str();

		if ($isNew) {
			$sql = "INSERT INTO admin (user_name, email, role_id, slug, is_active, updated_at) VALUES ('$username', '$email', $role, '$slug', $active, '$date')";
		} else {
			$sql = "UPDATE admin SET user_name = '$username', email = '$email', role_id = $role, slug = '$slug', is_active = $active, updated_at = '$date' WHERE id = $userId";
		}
		
		// If the user is new, send an email to update password
		if(mysqli_query($conn, $sql)){
			
			if ($isNew) {
				$message = 'User has been created';
			
				$subject = "Spike2Care Admin Account Created";
				$message = "Your admin user account for Spike2Care.org has been created!". "\r\n";
				$message .= "Please click the link below to set your password.". "\r\n". "\r\n";
				$message .= "<a href='http://spike2care.org/passwordReset.php?id=".$slug."'>Password reset</a>";
			
				$to = $email;

				$emailSent = sendEmail($to, $subject, $message, 'genericTemplate.html');

				$result = ['type' => 'success', 'message' => $message];
			} else {
				$result = ['type' => 'success', 'message' => 'User has been saved.'];
			}
		} else {
			$result = ['type' => 'error', 'message' => 'There was an error. Please contact admin.'];
		}

	    echo json_encode($result);
	    die;
	}

	if(isset($_POST['save-content'])){

		$fields = $insertItems = '';
		// Add updated at timestamp
		$date = date('Y-m-d H:i:s');
		
		// Loop through the posted form fields
		foreach ($_POST as $field => $value)
		{
			// Skip over the save button field
			if ($field != 'save-content') {
				$sql = "UPDATE content SET content_text = '".$value."', updated_at = '".$date."' WHERE context = '".$field."'";

				//var_dump($sql);
				mysqli_query($conn, $sql);
			} 
		}
		
		header("Location: ".URL."/admin/content.php");
		die();
	}

	// saving a board member bio
	if(isset($_POST['save-board'])){

		$date = date('Y-m-d H:i:s');
		
		// Loop through POST array
		foreach ($_POST as $postedItem => $value) {

			if ($postedItem == 'bio_text') {
				$tableName = 'board_bios';

				foreach ($value as $k => $v) {
					// Clean up any input
					$v = escape($v);
					// Create sql update statement
					$sql = "UPDATE ".$tableName." SET ";
					$sql .= $postedItem." = '".$v."', updated_at = '".$date."' WHERE people_id = ".$k;
					//var_dump($sql);
					// Insert into db
					if(mysqli_query($conn, $sql)) {
						$mysqlResult = "Saved succesfully!";
					} else {
						$mysqlResult = "Error message: %s\n". mysqli_error($conn);
					}	
				}

			} else if ($postedItem == 'full_name') {

				$tableName = 'people';
				foreach ($value as $k => $v) {
					// Clean up any input
					$v = escape($v);
					// Create sql update statement
					$sql = "UPDATE ".$tableName." SET ";
					$sql .= $postedItem." = '".$v."', updated_at = '".$date."' WHERE id = ".$k;
					//var_dump($sql);
					// Insert into db
					if(mysqli_query($conn, $sql)) {
						$mysqlResult = "Saved succesfully!";
					} else {
						$mysqlResult = "Error message: %s\n". mysqli_error($conn);
					}
				}
			}
		}
		
		header("Location: ".URL."/admin/content.php");
		die();
	}

	// Delete event from database
	if ($_GET && array_key_exists('action', $_GET) && $_GET['action'] == 'remove') {

		$eventId = $_GET['eventId'];
		$sql = "DELETE FROM events where id=".$eventId;

		mysqli_query($conn, $sql);

		header("Location: ".URL."/admin/events.php");
		die();
	}

	// Delete meeting minutes from database
	if ($_GET && array_key_exists('action', $_GET) && $_GET['action'] == 'removeMinutes') {

		$minutesId = $_GET['minutesId'];
		$sql = "DELETE FROM meeting_minutes where id=".$minutesId;

		mysqli_query($conn, $sql);

		header("Location: ".URL."/admin/minutes.php");
		die();
	}

	// Save the updated application status
	if ($_POST && array_key_exists('action', $_POST) && $_POST['action'] == 'updateStatus') {

		$appId = $_POST['appId'];
		$status = $_POST['status'];

		$sql = "UPDATE applications SET status = '".$status."' where id=".$appId;

		//var_dump($sql);
		$result = mysqli_query($conn, $sql);

		if ($result) {
			$response = ['type' => 'success', 'message' => 'Message has been updated.'];	
		} else {
			$response = ['type' => 'error', 'message' => 'Status update failed. Please contact an admin.'];
		}

		echo json_encode($response);
	}

	// Save the updated message status
	if ($_POST && array_key_exists('action', $_POST) && $_POST['action'] == 'updateMessageStatus') {

		$messageId = $_POST['messageId'];
		$status = $_POST['status'];

		$sql = "UPDATE messages SET status = '".$status."' where id=".$messageId;

		//var_dump($sql);
		$result = mysqli_query($conn, $sql);

		if ($result) {
			$response = ['type' => 'success', 'message' => 'Message has been updated.'];
		} else {
			$response = ['type' => 'error', 'message' => 'Status update failed. Please contact an admin.'];
		}

		echo json_encode($response);
	}
	
	if(isset($_POST['save-minutes'])) {

		$target_prefix = "../../";
		$target_dir = "minutes/";
		//$target_file = $target_prefix . $target_dir . basename($_FILES["fileToUpload"]["name"]);

		$meetingDate = date('Y-m-d H:i:s', strtotime($_POST['event_date']));
		$createdAt = date('Y-m-d H:i:s');
		
		if($_FILES['minutes']['name'] != '') {
			$temp = explode(".", $_FILES['minutes']["name"]); 
			$newFileName = round(microtime(true)) . rand(1,100) . '.' . end($temp);
			$target_file = $target_prefix . $target_dir . $newFileName;

			$return = uploadMinutes($target_file, $_FILES['minutes']);
			echo $return;
				
			$fileName = $target_dir . $newFileName;
			
			$sql = "INSERT INTO meeting_minutes (meeting_date, file_path, is_active, created_at) VALUES ('$meetingDate', '$newFileName', 1, '$createdAt')";

			if(mysqli_query($conn, $sql)){
				echo '<p>Your minutes file has been added.';
			}
			else{
				echo '<p>Error: ' . $sql . '<br .>' . mysqli_error($conn);
			}
		}

		header("Location: ".URL."/admin/minutes.php");
		die();
	}

	if (isset($_POST['save-testimonial'])) {

		$fullName = $_POST['testimonial_name'];
		$createdAt = date('Y-m-d H:i:s');
		
		$isNew = $_POST['is-new'];
		$testimonialText = escape($_POST['testimonial_text']);
		$active = $_POST['is_active'];
		$testimonialId = $_POST['save-testimonial'];

		if ($isNew) {
			$sql = "INSERT INTO people (full_name, created_at) VALUES ('".$fullName."', '".$createdAt."')";
			mysqli_query($conn, $sql);
			$userId = mysqli_insert_id($conn);

			$sql = "INSERT INTO testimonials (user_id, testimonial_text, is_active, created_at) VALUES ($userId, '$testimonialText', $active, '$createdAt')";
		} else {
			$sql = "SELECT * FROM testimonials WHERE id = $testimonialId";
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) 
		        {
		        	$userId = $row['user_id'];
		        }
	        }
			$sql = "UPDATE people SET full_name = '".$fullName."' WHERE id = $userId";
			mysqli_query($conn, $sql);
			$sql = "UPDATE testimonials SET testimonial_text = '$testimonialText', is_active = $active, created_at = '$createdAt' WHERE id = $testimonialId";
		}

		if(mysqli_query($conn, $sql)){
			$result = ['type' => 'success', 'message' => 'Testimonial has been saved.'];
		} else {
			$result = ['type' => 'error', 'message' => 'Error saving recap. Please contact a developer.'];
		}
		
	    echo json_encode($result);
	    die;
	}

	if (isset($_POST['form-email'])) {

		$userEmail = $_POST['user_email'];

		$sql = "SELECT * FROM admin WHERE email = '$userEmail'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_array($result)) 
	        {
	        	$userHash = $row['slug'];
	        }
			$subject = "Reset Admin Password";
			$message = "You have requested to change your admin password for Spike2Care.org". "\r\n";
			$message .= "Please click the link below to change your password.". "\r\n". "\r\n";
			$message .= "<a href='http://spike2care.org/passwordReset.php?id=".$userHash."'>Password reset</a>";
		
			$to      = $userEmail;
			$headers = 'From: Spike2Care.org' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

			$emailSent = sendEmail($to, $subject, $message, 'genericTemplate.html');

			$return = ['type' => 'success', 'message' => 'Email sent!'];
		} else {
			$return = ['type' => 'error', 'message' => 'Email not found.'];
		}

		echo json_encode($return);
	}


	function sendEmail($to, $subject, $message, $template)
	{
		if (IS_DEV) {
	        $to = 'tylerjaquish@gmail.com';
	    }

		$headers  = "From: Spike2Care <info@spike2care.org>" . "\r\n";
		$headers .= "Return-Path: Spike2Care <info@spike2care.com>\r\n";
	    $headers .= "Reply-To: S2C <info@spike2care.org>" . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "X-Priority: 3\r\n";
	    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

	    $templateTags =  [
	        '{{subject}}' => $subject,
	        '{{message}}' => $message
		];

	    $templateContents = file_get_contents( dirname(__FILE__) . '/'.$template);
	    $contents =  strtr($templateContents, $templateTags);

	    if (mail($to, $subject, $contents, $headers)) {
			return true;
    	}

    	return false;
	}

	// Occurs when admin removes a player
	if(isset($_GET['playerId'])){
		$playerId = $_GET['playerId'];
		$teamId = $_GET['teamId'];

		try {

			$sql = "SELECT * FROM teams t
				JOIN events e on e.id = t.event_id
				WHERE t.id = ".$teamId;
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) {
		        	$perPersonAmount = $row['price']*100;
	        	}
        	}

			$sql = "SELECT * FROM people WHERE id = ".$playerId;
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) {
					// If player has paid
					if ($row['paid']) {

						// Issue refund through Stripe API
						if ($row['token']) {
							$response = issueRefund($row['token'], $perPersonAmount);
						}

						if ($response['type'] == 'success') {
							// Update payment to refunded
							$sql = "UPDATE payments SET is_refunded = 1 WHERE paid_for = ".$playerId;
							//var_dump($sql);
							mysqli_query($conn, $sql);

							// Update player to unpaid
							$sql = "UPDATE people SET paid = 0 WHERE id = ".$playerId;
							//var_dump($sql);
							mysqli_query($conn, $sql);

							// Update team players to inactive
							$sql = "UPDATE team_players SET is_active = 0 WHERE people_id = ".$playerId;
							//var_dump($sql);
							mysqli_query($conn, $sql);

							// Update team's number of paid players
							$sql = "UPDATE teams SET players_paid = players_paid - 1 WHERE id = ".$teamId;
							//var_dump($sql);
							mysqli_query($conn, $sql);
						}

					} else {
						// Update team players to inactive
						$sql = "UPDATE team_players SET is_active = 0 WHERE people_id = ".$playerId;
						//var_dump($sql);
						mysqli_query($conn, $sql);
					}

					// If team is now empty, remove team
					$sql = "SELECT * FROM team_players WHERE is_active = 1 AND team_id = ".$teamId;
					$teamPlayers = mysqli_query($conn, $sql);
					if (mysqli_num_rows($teamPlayers) == 0) {
						// Update team's number of paid players
						$sql = "UPDATE teams SET is_active = 0 WHERE id = ".$teamId;
						//var_dump($sql);
						mysqli_query($conn, $sql);
					}

					$response = ['type' => 'success', 'message' => 'Player has been removed.'];
				}
			}
		} catch (Exception $e) {
			$response = ['type' => 'error', 'message' => 'Remove failed. Please contact an admin.'];
		}

		echo json_encode($response);
	}

	function issueRefund($token, $amount) 
	{
		$response = ['type' => 'success', 'message' => 'Refund has been issued and may take 5-10 business days to credit the original payment method.'];

		try {
			if (IS_DEV) {
		        \Stripe\Stripe::setApiKey("sk_test_xjdaWuWDrUpmVfeuEhmovSk4");
		    } else {
				\Stripe\Stripe::setApiKey(LIVE_KEY);
			}

			$re = \Stripe\Refund::create(array(
			  "charge" => $token,
			  "amount" => $amount
			));
		} catch (Exception $e) {
			//var_dump($e);
			if (strpos($e->httpBody, 'invalid_request_error') !== false) {
				$response = ['type' => 'error', 'message' => 'Charge has already been refunded.'];
			} else {
				var_dump($e);
			}
		}

		return $response;
	}

	// Occurs when admin removes a free agent from an event
	if (isset($_GET['faId'])){
		$faId = $_GET['faId'];

		try {

			$sql = "UPDATE free_agents SET is_active = 0 WHERE id = ".$faId;
			$result = mysqli_query($conn, $sql);
			
			if ($result) {
				$response = ['type' => 'success', 'message' => 'Free agent has been removed from this event.'];
			} else {
				$response = ['type' => 'error', 'message' => 'Remove failed. Please contact an admin.'];
			}
				
		} catch (Exception $e) {
			$response = ['type' => 'error', 'message' => 'Remove failed. Please contact an admin.'];
		}

		echo json_encode($response);
	}

	// Occurs when user clicks export on the admin index page
	if (isset($_GET['export-emails'])) {
		// filename for download
		$filename = "S2C Email List - ".date('Ymd').".csv";

		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type: text/csv");

		$out = fopen("php://output", 'w');

		$flag = false;
		$result = mysqli_query($conn, "SELECT DISTINCT full_name, email FROM people WHERE email IS NOT NULL") or die('Query failed!');
		while($row = mysqli_fetch_assoc($result)) {
			if(!$flag) {
				// display field/column names as first row
				fputcsv($out, array_keys($row), ',', '"');
				$flag = true;
			}
			// var_dump($row);
			array_walk($row, __NAMESPACE__ . '\cleanData');
			fputcsv($out, array_values($row), ',', '"');
		}

		fclose($out);
		exit;

	}

	function cleanData(&$str)
	{
		if($str == 't') $str = 'TRUE';
		if($str == 'f') $str = 'FALSE';
		if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
			$str = "'$str";
		}
		if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	}

	// Team name has been changed
  	if (isset($_POST['teamName'])) {
  		$newTeamName = $_POST['teamName'];
  		$teamId = $_POST['teamId'];

  		$sql = "UPDATE teams SET team_name = '$newTeamName' WHERE id = $teamId";
  		$result = mysqli_query($conn, $sql);
			
		if ($result) {
			$response = ['type' => 'success', 'message' => 'Team name has been updated.'];
		} else {
			$response = ['type' => 'error', 'message' => 'Update failed. Please contact an admin.'];
		}

		echo json_encode($response);
		die;
  	}

  	// Event has been canceled
  	if (isset($_POST['action']) && $_POST['action'] == 'cancelEvent') {
  		$eventId = $_POST['id'];

  		// First disable the tournament
  		$sql = "UPDATE events SET registration_open = 0, is_active = 0 WHERE id = '$eventId'";
		$result = mysqli_query($conn, $sql);

  		// Get all teams for event
  		$sql = "SELECT * FROM teams WHERE event_id = '$eventId'";
		$getTeams = mysqli_query($conn, $sql);
		if (mysqli_num_rows($getTeams) > 0) {
			while($team = mysqli_fetch_array($getTeams)) 
	        {
	        	$teamId = $team['id'];
	        	// Get all players and payments for team to refund
	        	$sql = "SELECT payments.token, sum(entry_amount) FROM team_players 
	        		JOIN people p ON p.id = team_players.people_id 
	        		JOIN payments ON payments.paid_for = p.id
	        		WHERE team_id = '$teamId'
	        		GROUP BY payments.token";
				$getPlayers = mysqli_query($conn, $sql);
				if (mysqli_num_rows($getPlayers) > 0) {
					while($row = mysqli_fetch_array($getPlayers)) 
			        {
			        	// Issue refund
			        	// Set payment to is_refunded
			        	// Send email to user
			        }
		        }
	        }
        }
  	}
  	// Add/update an item in the catalog
  	if(isset($_POST['save-item'])) {

		$fields = $insertItems = $setValues = '';
		$isNew = ($_POST['is_new'] == 'true' ? true : false);
		$itemId = $_POST['item_id'];
		$title = $_POST['title'];
		$price = $_POST['price'];
		$description = $_POST['description'];
		$colors = $_POST['colors'];
		$sizes = $_POST['sizes'];
		if (isset($_POST['is_active'])) {
			$active = 1;
		} else {
			$active = 0;
		}
		$createdAt = date('Y-m-d H:i:s');

		// Upload image first
		$targetDir = "../../images/catalog/";

		$imagePaths = [];

		if (isset($_FILES)) {
			foreach($_FILES as $field => $file) {
		
				$temp = explode(".", $file["name"]); 
				$newFileName = round(microtime(true)).rand(1,100).'.'.end($temp);
				$targetFile = $targetDir.$newFileName;

				$return = uploadAttachment($targetFile, $file, 'image');

				if ($return == 'success') {
					$imagePaths[$field] = $newFileName;
				} else {
					// there was an error
				}
			}

			$fields = $values = $updateQuery = '';

			foreach ($imagePaths as $field => $path) {
				$fields .= $field.',';
				$values .= '"'.$path.'",';
				$updateQuery .= $field.' = "'.$path.'",';
			}
		}

		if ($isNew) {
			$sql = "INSERT INTO catalog (title, description, price, ".$fields." active, created_at) 
				VALUES ('{$title}', '{$description}', {$price}, ".$values." {$active}, '{$createdAt}')";
			mysqli_query($conn, $sql);

			$itemId = mysqli_insert_id($conn);
		} else {
			$sql = "UPDATE catalog SET title = '{$title}', description = '{$description}', price = {$price}, ".$updateQuery." active = {$active}
				WHERE id = {$itemId}";
			mysqli_query($conn, $sql);
		}

		// If updating an item, first remove existing colors and sizes
		if (!$isNew) {
			$sql = "DELETE FROM catalog_colors WHERE catalog_id = ".$itemId;
			mysqli_query($conn, $sql);
			$sql = "DELETE FROM catalog_sizes WHERE catalog_id = ".$itemId;
			mysqli_query($conn, $sql);
		}

		if (isset($colors)) {
			foreach ($colors as $color) {
				$sql = "INSERT INTO catalog_colors (catalog_id, color_id) VALUES ({$itemId}, {$color})";
				mysqli_query($conn, $sql);
			}
		}

		if (isset($sizes)) {
			foreach ($sizes as $size) {
				$sql = "INSERT INTO catalog_sizes (catalog_id, size_id) VALUES ({$itemId}, {$size})";
				mysqli_query($conn, $sql);
			}
		}

		header("Location: ".URL."/admin/merchandise.php");
		die();
	}

	// Delete meeting minutes from database
	if ($_GET && array_key_exists('action', $_GET) && $_GET['action'] == 'removeItem') {

		$itemId = $_GET['itemId'];
		$sql = "DELETE FROM catalog where id=".$itemId;
		mysqli_query($conn, $sql);

		$sql = "DELETE FROM catalog_colors where catalog_id=".$itemId;
		mysqli_query($conn, $sql);

		$sql = "DELETE FROM catalog_sizes where catalog_id=".$itemId;
		mysqli_query($conn, $sql);

		header("Location: ".URL."/admin/merchandise.php");
		die();
	}

	// Save the updated order status
	if ($_POST && array_key_exists('action', $_POST) && $_POST['action'] == 'updateOrderStatus') {

		$personId = $_POST['personId'];
		$status = $_POST['status'];

		$sql = "UPDATE sales SET status = '".$status."' where person_id=".$personId;

		//var_dump($sql);
		$result = mysqli_query($conn, $sql);

		if ($result) {
			$response = ['type' => 'success', 'message' => 'Order has been updated.'];	
		} else {
			$response = ['type' => 'error', 'message' => 'Status update failed. Please contact an admin.'];
		}

		echo json_encode($response);
	}

	// Save a new color
	if ($_POST && array_key_exists('action', $_POST) && $_POST['action'] == 'addColor') {

		$color = $_POST['color'];
		$sql = "INSERT INTO colors (color) VALUES ('{$color}')";
		$result = mysqli_query($conn, $sql);

		if ($result) {
			$response = ['type' => 'success', 'message' => 'Color has been added.'];	
		} else {
			$response = ['type' => 'error', 'message' => 'Add color failed. Please contact an admin.'];
		}

		echo json_encode($response);
	}

	// Save a new cause
	if ($_POST && array_key_exists('action', $_POST) && $_POST['action'] == 'addCause') {
		$name = $_POST['name'];
		$active = $_POST['active'] == "true" ? 1 : 0;
		$sql = "INSERT INTO causes (name, active) VALUES ('{$name}', '{$active}')";
		$result = mysqli_query($conn, $sql);

		if ($result) {
			$response = ['type' => 'success', 'message' => 'Cause has been added.'];	
		} else {
			$response = ['type' => 'error', 'message' => 'Add cause failed. Please contact an admin.'];
		}

		echo json_encode($response);
	}

	// Update the cause active field
	if ($_POST && array_key_exists('action', $_POST) && $_POST['action'] == 'toggleCauseActive') {
		$id = $_POST['id'];
		$sql = "UPDATE causes SET active = !active WHERE id = {$id}";
		$result = mysqli_query($conn, $sql);

		if ($result) {
			$response = ['type' => 'success', 'message' => 'Cause has been updated.'];	
		} else {
			$response = ['type' => 'error', 'message' => 'Update cause failed. Please contact an admin.'];
		}

		echo json_encode($response);
	}

	// Get emails for a user based on their name
	if ($_GET && array_key_exists('action', $_GET) && $_GET['action'] == 'findEmail') {

		$result = [];
		$i=0;
		$captainName = $_GET['name'];
		$sql = "SELECT DISTINCT email FROM people where full_name LIKE '".$captainName."' AND email_valid = 1";

		$getPeople = mysqli_query($conn, $sql);
		if (mysqli_num_rows($getPeople) > 0) {
			while($person = mysqli_fetch_array($getPeople)) 
	        {
				$result[] = [
					'id' => $i,
					'text' => $person['email']
				];
				$i++;
			}
		}

		echo json_encode($result);
		die;
	}
	
	// Add a captain to the reserved teams and send the captain an email
	if ($_POST && array_key_exists('action', $_POST) && $_POST['action'] == 'add-team') {
		
		$success = "false";

		try {
			$name = escape($_POST['name']);
			$eventName = escape($_POST['event-name']);
			$eventId = $_POST['event-id'];
			$division = $_POST['selected-division'];

			if (isset($_POST['selected-email']) && $_POST['selected-email'] != '') {
				$email = $_POST['selected-email'];
			} else {
				$email = escape($_POST['new-email']);
			}
			$active = 1;
			$uid = uniqid();
			$createdAt = date('Y-m-d H:i:s');

			$sql = "INSERT INTO reserved_teams (event_id, captain_name, captain_email, division_id, uid, is_active, created_at) 
				VALUES ({$eventId}, '{$name}', '{$email}', {$division}, '{$uid}', {$active}, '{$createdAt}')";
			$result = mysqli_query($conn, $sql);

			// Send email to captain
			$subject = "Spike2Care Tournament Registration";
			$message = "A team reservation has been made for you for the event: <br />".$eventName."<br /><br />";
			$message .= "Please click the link below to register.<br /><br />";
			$message .= "<a class='btn-primary' href='https://spike2care.org/register.php?uid=".$uid."'>Register</a>";
			$to = $email;

			$emailSent = sendEmail($to, $subject, $message, "teamRegistration.html");

			if ($result) {
				$success = "true";
			} 
		} catch (Exception $ex) {
			$success = "false";
			// var_dump($ex->getMessage());die;
		}

		header("Location: ".URL."/admin/addTeam.php?success=".$success."&eventId=".$eventId);
		die();
	}

	// Add a captain to the reserved teams and send the captain an email
	if ($_POST && array_key_exists('action', $_POST) && $_POST['action'] == 'remove-reservation') {

		$success = "false";
		$reservationId = $_POST['teamId'];

		$sql = "UPDATE reserved_teams SET is_active = 0 WHERE id = $reservationId";
		$result = mysqli_query($conn, $sql);

		if ($result) {
			$success = "true";
		} 

		echo $success;
		die;
	}

	// Update the user's email_valid to false
	if ($_POST && array_key_exists('action', $_POST) && $_POST['action'] == 'invalidate-email') {
		
		$success = "false";
		$email = $_POST['email'];

		$sql = "UPDATE people SET email_valid = 0 WHERE email = '$email'";
		$result = mysqli_query($conn, $sql);

		if ($result) {
			$success = "true";
		} 

		echo $success;
		die;
	}
		
?>