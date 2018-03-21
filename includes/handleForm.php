<?php
session_start();

include('../admin/includes/datalogin.php');
include('../admin/includes/functions.php');
include('../admin/includes/password.php');

require_once('../stripe/init.php');

	// when a new recap comment is added
	if (isset($_GET['comment'])) {
		$recap_id = $_GET['recap_id'];
		$comment = escape($_GET['comment']);
		$commenter = 'Anonymous';

		if (isset($_GET['commenter']) && $_GET['commenter'] != '') {
			$commenter = escape($_GET['commenter']);
		}

		$sql = "INSERT INTO recap_comments (recap_id, comment_text, commenter_name, created_at) VALUES (".$recap_id.",'".$comment."','".$commenter."','".date('Y-m-d H:i:s')."')";
		$succeeded = mysqli_query($conn, $sql);
		$success = ['success' => $succeeded];

		echo json_encode($success);
	}

	// when a user registers for an event
	if (isset($_POST['event-registration'])) {
		
		$teamMember = '';
		$created_at = date('Y-m-d H:i:s');
		$eventId = $_POST['event_id'];
		if (isset($_POST['teamId'])) {
			$newTeamId = $_POST['teamId'];
		}

		$type = $_POST['type'];
		$name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
		$phone = mysqli_real_escape_string($conn, $_POST['phone']);
		$email = mysqli_real_escape_string($conn, trim($_POST['email']));

		if ($type == 'freeAgent') {
			$division = $_POST['division'];
			$sql = "INSERT INTO people (full_name, phone, email, paid, created_at) VALUES ('".$name."', '".$phone."', '".$email."', 0, '".$created_at."')";
			mysqli_query($conn, $sql);
			$newPersonId = mysqli_insert_id($conn);
			// Store in session for saving later
			$_SESSION['newPersonId'] = mysqli_insert_id($conn);

			$sql = "INSERT INTO free_agents (people_id, event_id, division_id) VALUES (".$newPersonId.", ".$eventId.", ".$division.")";
			mysqli_query($conn, $sql);

		} elseif ($type == 'existing') {
			// Player is joining an existing team using the passcode
			$existingPasscode = $_POST['passcode_check'];
			
			$team = mysqli_query($conn,"SELECT * FROM teams WHERE passcode = '".$existingPasscode."'");
			if (mysqli_num_rows($team) > 0) {
				while($row = mysqli_fetch_array($team)) {
					$newTeamId = $teamId = $row['id'];
				}

				$teamPlayers = mysqli_query($conn,"SELECT * FROM team_players tp JOIN people p ON p.id = tp.people_id WHERE tp.is_active = 1 AND team_id = ".$teamId);
				if (mysqli_num_rows($teamPlayers) > 0) {
					$shouldInsertNew = true;
					while($row = mysqli_fetch_array($teamPlayers)) {
						$teamPlayerName = $row['full_name'];
						// Check if name is close to matching a name already on the team
						similar_text(strtolower($teamPlayerName), strtolower($_POST['full_name']), $percent);
						if ($percent > 85) {
							$shouldInsertNew = false;
							$playerFoundId = $row['id'];
						}
					}
				}
			}

			if ($shouldInsertNew) {
				$sql = "INSERT INTO people (full_name, phone, email, paid, created_at) VALUES ('".$name."', '".$phone."', '".$email."', 0, '".$created_at."')";
				mysqli_query($conn, $sql);
				$newPersonId = mysqli_insert_id($conn);
				// Save new person's id in session
				$_SESSION['newPersonId'] = mysqli_insert_id($conn);
				// Only insert if name match is less than 85%
				$sql = "INSERT INTO team_players (team_id, people_id, is_captain, is_active) VALUES (".$teamId.",".$newPersonId.",0,1)";
				mysqli_query($conn, $sql);
			} else {
				// else update user information with form data
				$sql = "UPDATE people SET full_name = '".trim($_POST['full_name'])."', phone = '".$_POST['phone']."', email = '".trim($_POST['email'])."' WHERE id = $playerFoundId";
				mysqli_query($conn, $sql);
			}

			if (isset($_POST['current-free-agent'])) {
				$freeAgents = mysqli_query($conn,"SELECT * FROM free_agents fa JOIN people p ON p.id = fa.people_id WHERE fa.is_active = 1 AND event_id = ".$eventId);
				if (mysqli_num_rows($freeAgents) > 0) {
					while($row = mysqli_fetch_array($freeAgents)) {
						$freeAgentName = $row['full_name'];
						// Check if name is close to matching a name in the free agent list for this event
						similar_text(strtolower($freeAgentName), strtolower($_POST['full_name']), $percent);
						if ($percent > 85) {
							// Update free agent to inactive
							$sql = "UPDATE free_agents SET is_active = 0 WHERE people_id = ".$row['people_id'];
							//var_dump($sql);
							mysqli_query($conn, $sql);
						}
					}
				}
			}

		} elseif ($type == 'new') {
			$division = $_POST['division'];
			$teamName = mysqli_real_escape_string($conn, trim($_POST['team_name']));
			$passcode = mysqli_real_escape_string($conn, trim($_POST['passcode']));

			$sql = "INSERT INTO people (full_name, phone, email, paid, created_at) VALUES ('".$name."', '".$phone."', '".$email."', 0, '".$created_at."')";
			mysqli_query($conn, $sql);
			$newCaptainId = mysqli_insert_id($conn);
			// Save new person's id in session
			$_SESSION['newPersonId'] = mysqli_insert_id($conn);

			$sql = "INSERT INTO teams (team_name, passcode, event_id, division_id, captain_id, is_active, created_at) VALUES ('".$teamName."', '".$passcode."', '".$eventId."', '".$division."', '".$newCaptainId."', 0, '".$created_at."')";
			mysqli_query($conn, $sql);

			$newTeamId = mysqli_insert_id($conn);

			$sql = "INSERT INTO team_players (team_id, people_id, is_captain, is_active) VALUES (".$newTeamId.",".$newCaptainId.",true, true)";
			mysqli_query($conn, $sql);
			
			foreach ($_POST['players'] as $player) {
				if ($player != '') {

					$sql = "INSERT INTO people (full_name, created_at) VALUES ('".$player."', '".$created_at."')";
					mysqli_query($conn, $sql);

					$newPlayerId = mysqli_insert_id($conn);
					
					$sql = "INSERT INTO team_players (team_id, people_id, is_captain, is_active) VALUES (".$newTeamId.",".$newPlayerId.",false, true)";
					mysqli_query($conn, $sql);
				}
			}
		}

		header("Location: ../checkout.php?eventId=".$eventId."&teamId=".$newTeamId);
		die();
	}

	if (isset($_GET['passcode'])) {
		// Validate passcode
		$passcode = trim($_GET['passcode']);
		$now = date('Y-m-d H:i:s');

		if ($passcode != '') {

			$sql = "SELECT t.*, team_players, full_name FROM teams as t JOIN spike2care.`events` as e ON t.event_id = e.id JOIN people on people.id = t.captain_id WHERE t.is_active = 1 AND e.is_active = 1 AND BINARY t.passcode = '".$passcode."'";

			$result = mysqli_query($conn, $sql);

	        if (mysqli_num_rows($result) > 0) {
		        while($team = mysqli_fetch_array($result)) 
		        {
		        	$message = '<h4>Team found!</h4> Joining team: '.$team['team_name'].', Captain: '.$team['full_name']
		        	.'<input type="hidden" name="teamId" value="'.$team['id'].'">';
		        	$return = ['type' => 'success', 'message' => $message];
		        }

	        } else {
	        	$return = ['type' => 'error', 'message' => 'No team found.'];
	        }
	    } else {
	    	$return = ['type' => 'error', 'message' => 'No team found.'];
    	}

    	echo json_encode($return);
    	die;
	}

	// Checkout for new donation
	if (isset($_POST['new-donation-person'])) {

		$created_at = date('Y-m-d H:i:s');
		$cause = (int)$_POST['cause'][0];

		// user needs to get added into people
		$sql = "INSERT INTO people (full_name, phone, email, created_at) VALUES ('".trim($_POST['full_name'])."', '".$_POST['phone']."', '".trim($_POST['email'])."', '".$created_at."')";
		mysqli_query($conn, $sql);
		$newPersonId = mysqli_insert_id($conn);
		// Store in session to use later
		$_SESSION['newPersonId'] = mysqli_insert_id($conn);
		
		try {

			if (IS_DEV) {
		        \Stripe\Stripe::setApiKey("sk_test_xjdaWuWDrUpmVfeuEhmovSk4");
		    } else {
				\Stripe\Stripe::setApiKey(LIVE_KEY);
			}

			// Token is created using Stripe.js or Checkout!
			// Get the payment token ID submitted by the form:
			$token = $_POST['stripeToken'];
			$amount = $_POST['totalAmount'];

			// Charge the user's card:
			$charge = \Stripe\Charge::create(array(
			  "amount" => $amount,
			  "currency" => "usd",
			  "receipt_email" => $_POST['email'],
			  "description" => "Spike2Care.org donation",
			  "source" => $token,
			));

			$chargeToken = $charge->id;

			$sql = "INSERT INTO payments (paid_by, donation_amount, token, created_at) VALUES ('".$newPersonId."', '".$_POST['totalDonation']."', '".$chargeToken."', '".$created_at."')";
			mysqli_query($conn, $sql);

		} catch (Exception $e) {
			// TODO: error handling
		}

		if ($cause != 0) {
			$sql = "UPDATE events SET specified_donations = specified_donations + ".$_POST['donation']." WHERE id = ".$cause;
			mysqli_query($conn, $sql);
		}

		header("Location: ../index.php?message=thankyou");
		die();
	}

	// Checkout for special event
	if (isset($_POST['quantity']) && $_POST['quantity'] != '') {
		$chargeToken = '';
		$eventId = $paidBy = $eventPrice = $donation = null;

		$eventId = $_POST['event_id'];
		$paidBy = $_POST['paidBy'];
		$eventPrice = $_POST['eventPrice'] * 100;
		$quantity = $_POST['quantity'];

		// Find the email for the user to send a receipt to
		$sql = "SELECT * FROM people WHERE id = ".$paidBy;
		$result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
	        while($person = mysqli_fetch_array($result)) 
	        {
	        	$email = $person['email'];
	        }
        } else {
        	$email = 'tylerjaquish@gmail.com';
        }

		try {

			if (IS_DEV) {
		        \Stripe\Stripe::setApiKey("sk_test_xjdaWuWDrUpmVfeuEhmovSk4");
		    } else {
				\Stripe\Stripe::setApiKey(LIVE_KEY);
			}

			// Token is created using Stripe.js or Checkout!
			// Get the payment token ID submitted by the form:
			$token = $_POST['stripeToken'];
			$amount = $_POST['totalAmount'];

			// Charge the user's card:
			$charge = \Stripe\Charge::create(array(
			  "amount" => $amount,
			  "currency" => "usd",
			  "description" => "Spike2Care.org event entry and/or donation",
			  "receipt_email" => $email,
			  "source" => $token,
			));

			$chargeToken = $charge->id;

		} catch (Exception $e) {
			// TODO: error handling
		}

		$createdAt = date('Y-m-d H:i:s');

		if ($_POST['totalDonation'] != '') {
			$donation = $_POST['totalDonation'];
			$sql = "INSERT INTO payments (paid_by, donation_amount, token, created_at) VALUES ($paidBy, $donation, '$chargeToken', '$createdAt')";
			mysqli_query($conn, $sql);
		}

		$sql = "UPDATE people SET paid = 1, token = '$chargeToken' WHERE id = ".$paidBy;
		mysqli_query($conn, $sql);
		$sql = "INSERT INTO payments (paid_by, paid_for, entry_amount, quantity, event_id, token, created_at) VALUES ($paidBy, $paidBy, '$eventPrice', $quantity, $eventId, '$chargeToken', '$createdAt')";
		mysqli_query($conn, $sql);

		header("Location: ../showSpecialEvent.php?eventId=".$eventId."&message=success");
		die();
	}

	// New registration with players paid
	if ((isset($_POST['players_paid']) && $_POST['players_paid'] != '') || isset($_POST['paidBy']) && isset($_POST['donation']) && $_POST['donation'] > 0)  {

		$chargeToken = '';
		$eventId = $teamId = $playersPaid = $paidBy = $eventPrice = $donation = null;

		$eventId = $_POST['event_id'];
		$teamId = $_POST['team_id'];
		$playersPaid = $_POST['players_paid'];
		
		$eventPrice = $_POST['eventPrice'] * 100;
		$cause = (int)$_POST['cause'][0];

		if (isset($_SESSION['newPersonId'])) {
			$paidBy = $_SESSION['newPersonId'];
		} else {
			$paidBy = $_POST['paidBy'];
		}

		// Find the email for the user to send a receipt to
		$sql = "SELECT * FROM people WHERE id = ".$paidBy;
		$result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
	        while($person = mysqli_fetch_array($result)) 
	        {
	        	$email = $person['email'];
	        }
        } else {
        	$email = 'tylerjaquish@gmail.com';
        }

		try {

			if (IS_DEV) {
		        \Stripe\Stripe::setApiKey("sk_test_xjdaWuWDrUpmVfeuEhmovSk4");
		    } else {
				\Stripe\Stripe::setApiKey(LIVE_KEY);
			}

			// Token is created using Stripe.js or Checkout!
			// Get the payment token ID submitted by the form:
			$token = $_POST['stripeToken'];
			$amount = $_POST['totalAmount'];

			// Charge the user's card:
			$charge = \Stripe\Charge::create(array(
			  "amount" => $amount,
			  "currency" => "usd",
			  "receipt_email" => $email,
			  "description" => "Spike2Care.org event entry and/or donation",
			  "source" => $token,
			));

			$chargeToken = $charge->id;

		} catch (Exception $e) {
			// TODO: error handling
		}

		$createdAt = date('Y-m-d H:i:s');

		if ($_POST['totalDonation'] != '' && $_POST['totalDonation'] != 0) {
			$donation = $_POST['totalDonation'];
			$sql = "INSERT INTO payments (paid_by, donation_amount, token, created_at) VALUES ($paidBy, $donation, '$chargeToken', '$createdAt')";
			mysqli_query($conn, $sql);

			$cause = (int)$_POST['cause'][0];

			if ($cause != 0) {
				$sql = "UPDATE events SET specified_donations = specified_donations + ".$_POST['donation']." WHERE id = ".$cause;
				mysqli_query($conn, $sql);
			}
		}

		// Get any existing players paid and update it
		$sql = "SELECT * FROM teams WHERE id = ".$teamId;
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
	        while($row = mysqli_fetch_array($result)) 
	        {
	        	$existingPlayersPaid = $row['players_paid'];
        	}
    	}

    	$newPlayersPaid = $existingPlayersPaid + count($playersPaid);

		$sql = "UPDATE teams SET is_active = 1, players_paid = ".$newPlayersPaid." WHERE id = ".$teamId;
		mysqli_query($conn, $sql);

		// Update each player as having paid
		foreach ($playersPaid as $player) {
			$sql = "UPDATE people SET paid = 1, token = '$chargeToken' WHERE id = ".$player;
			mysqli_query($conn, $sql);
			$sql = "INSERT INTO payments (paid_by, paid_for, entry_amount, event_id, token, created_at) VALUES ($paidBy, $player, '$eventPrice', $eventId, '$chargeToken', '$createdAt')";
			mysqli_query($conn, $sql);
		}

		header("Location: ../showEvent.php?eventId=".$eventId);
		die();
	}

	// Get players for specific team that are paid/unpaid
	if (isset($_GET['teamId']) && $_GET['teamId'] != '') {
		$paid = $_GET['paid'];
		$teamId = $_GET['teamId'];

		$sql = "SELECT * FROM teams AS t 
		JOIN team_players AS tp ON tp.team_id = t.id
		JOIN people AS p ON tp.people_id = p.id 
		WHERE tp.is_active = 1 AND t.id = ".$teamId." AND paid = $paid";

		$result = mysqli_query($conn, $sql);

		$playerArray = $data = [];
		$count = 1;

        if (mysqli_num_rows($result) > 0) {
	        while($row = mysqli_fetch_array($result)) 
	        {
	        	array_push($data, $playerArray[$count] = [
	        		'id' => $row['id'],
	        		'text' => $row['full_name']
	        	]);
	        	$count++;
	        }
        } else {
        	echo 'error';
        }

        $items = ['results' => $data];
        echo json_encode($data);
        die;
	}

	if(isset($_POST['reset-password'])) {

		$adminId = $_POST['userId'];
		$newPassword = $_POST['password1'];
		$passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);
		
		$sql = "UPDATE admin SET password = '$passwordHash' WHERE id = ".$adminId;

		if(mysqli_query($conn, $sql)){
			$result = ['type' => 'success', 'message' => 'Password has been saved. You may now login using your new password.'];
		} else {
			$result = ['type' => 'error', 'message' => 'Error saving password. Please contact a developer.'];
		}
		
	    echo json_encode($result);
	    die;
	}

	if(isset($_POST['refund-precheck'])) {

		$email = $_POST['email'];
		$passcode = $_POST['passcode'];
		$eventId = (int)$_POST['eventId'];
		$captain = false;

		$sql = "SELECT p.id as userId, t.id as teamId, token, p.full_name, captain_id FROM teams AS t 
		JOIN team_players AS tp ON tp.team_id = t.id
		JOIN people AS p ON tp.people_id = p.id 
			JOIN events e ON t.event_id = e.id
			WHERE email = '$email'
			AND event_id = $eventId
			AND passcode = '$passcode'";
			// probly need to check is_active on tp

		$result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
	        while($row = mysqli_fetch_array($result)) 
	        {
	        	$token = $row['token'];
	        	$userId = $row['userId'];
	        	$teamId = $row['teamId'];
	        	$playerName = $row['full_name'];
	        	if ($row['captain_id'] == $userId) {
	        		$captain = true;
	        	}
	        }

	        $payments = "SELECT * FROM payments WHERE event_id = $eventId AND paid_by = $userId";
	        $result = mysqli_query($conn, $payments);
	        if (mysqli_num_rows($result) > 0) {
		        while($row = mysqli_fetch_array($result)) 
		        {
		        	$paidFor[] = $row['paid_for'];
		        }
		    }
	        $result = ['type' => 'success', 'token' => $token, 'userId' => $userId, 'teamId' => $teamId, 'playerName' => $playerName, 'captain' => $captain, 'paidFor' => $paidFor, 'eventId' => $eventId];
        } else {
        	$result = ['type' => 'error', 'message' => 'There was an error matching the email and passcode. <br /><a href="contact.php">Click here to message Spike2Care for assistance.</a>'];
        }

        echo json_encode($result);
	    die;
	}

	if(isset($_POST['refund-form'])) {
		
		$token = $_POST['token'];
		$userId = $_POST['userId'];
		$eventId = $_POST['eventId'];
		$teamId = $_POST['teamId'];
		$captainChoice = $_POST['captainChoice'];

		// Loop through each team member and issue refunds for each payment
		if ($captainChoice == 'refund-entire-team') {
			$sql = "SELECT * FROM teams WHERE id = ".$teamId;
			$result = mysqli_query($conn, $sql);
	        if (mysqli_num_rows($result) > 0) {
		        while($row = mysqli_fetch_array($result)) 
		        {
		        	for ($x=2; $x<9; $x++) {
		        		if ($row['player'.$x.'_id']) {
		        			$response = refundPlayer($conn, $eventId, $teamId, $row['player'.$x.'_id']);
		        		} else if ($row['captain_id']) {
		        			$response = refundPlayer($conn, $eventId, $teamId, $row['captain_id']);
		        		}
		        	}
		        }
		    }

		    $sql = "UPDATE teams SET is_active = 0, players_paid = 0 WHERE id = ".$teamId;
			mysqli_query($conn, $sql);

		} else if ($captainChoice == 'refund-specific-players') {
			//Refund specific players
			if (isset($_POST['refund-players'])) {
				$refundPlayers = $_POST['refund-players'];
				foreach ($refundPlayers as $index => $playerId) {
					$response = refundPlayer($conn, $eventId, $teamId, $playerId);
				}
			}

			// Save the new captain
			if (isset($_POST['new-captain'])) {
				$newCaptain = $_POST['new-captain'];
				for ($x=2; $x<9; $x++) {
					if ($team['player'.$x.'_id'] == $newCaptain) {
						$resetPlayerId = 'player'.$x.'_id';
					}
				}
				
				$sql = "UPDATE teams SET captain_id = $newCaptain, $resetPlayerId = null WHERE id = ".$teamId;
				mysqli_query($conn, $sql);
			}
		}

		echo json_encode($response);
	    die;
    }

	function refundPlayer($conn, $eventId, $teamId, $playerId)
	{
		$response = '';
		// Look up the token from payments table
		$sql = "SELECT * FROM payments WHERE event_id = ".$eventId." AND paid_for =".$playerId;
		$result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
	        while($payment = mysqli_fetch_array($result)) 
	        {
	        	if ($payment['entry_amount'] && $payment['entry_amount'] > 0) {
	        		$token = $payment['token'];

	        		// If a token is found, create the refund
			        if (isset($token) && $token != '') {

			        	// Issue the refund
			        	$response = issueRefund($token);

						// Set player as unpaid
						$sql = "UPDATE people SET paid = 0 WHERE id = ".$playerId;
						mysqli_query($conn, $sql);

						// Remove player from team and decrement number of players paid
						$sql = "SELECT * FROM teams WHERE id = ".$teamId;
						$result = mysqli_query($conn, $sql);
				        if (mysqli_num_rows($result) > 0) {
					        while($team = mysqli_fetch_array($result)) 
					        {
					        	$playersPaid = $team['players_paid'];
					        	// If player is on team, get the player column to nullify
					        	for ($x=2; $x<9; $x++) {
		        					if ($team['player'.$x.'_id'] == $playerId) {
		        						$resetPlayerId = 'player'.$x.'_id';
		        					}
	        					}
	        					if ($team['captain_id'] == $playerId) {
	        						$resetPlayerId = 'captain_id';
	        					}
					        }
					    }
					    $newPlayersPaid = $playersPaid-1;

					    if (isset($resetPlayerId)) {
							$sql = "UPDATE teams SET ".$resetPlayerId." = null, players_paid = ".$newPlayersPaid." WHERE id = ".$teamId;
							mysqli_query($conn, $sql);
						}

						// Set payment to refunded
						$sql = "UPDATE payments SET is_refunded = 1 WHERE id = ".$payment['id'];
						mysqli_query($conn, $sql);

						
			        } else {
			        	$response = ['type' => 'error', 'message' => 'Error matching email and passcode. <a class="white-link" href="contact.php">Click here to message Spike2Care for assistance.</a>'];
			        }
	        	}
        	}
    	}

        return $response;
    }

	function issueRefund($token) 
	{
		$response = ['type' => 'success', 'message' => 'Refund has been issued and may take 5-10 business days to credit your original payment method.'];

		try {
			if (IS_DEV) {
		        \Stripe\Stripe::setApiKey("sk_test_xjdaWuWDrUpmVfeuEhmovSk4");
		    } else {
				\Stripe\Stripe::setApiKey(LIVE_KEY);
			}

			$re = \Stripe\Refund::create(array(
			  "charge" => $token
			));
		} catch (Exception $e) {
			//var_dump($e);
			if (strpos($e->httpBody, 'invalid_request_error') !== false) {
				$result = ['type' => 'error', 'message' => 'Charge has already been refunded.'];
			} else {
				var_dump($e);
			}
		}

		return $response;
	}

	// Look up a team by passcode
	if (isset($_POST['eventId']) && isset($_POST['email']) && isset($_POST['passcode'])) {
		$email = $_POST['email'];
		$passcode = $_POST['passcode'];
		$eventId = $_POST['eventId'];
		$now = date('Y-m-d H:i:s');

		if ($passcode != '') {

			$sql = "SELECT t.*, team_players, full_name FROM teams as t JOIN spike2care.`events` as e ON t.event_id = e.id JOIN people on people.id = t.captain_id WHERE t.is_active = 1 AND e.is_active = 1 AND e.id = ".$eventId." AND people.email = '".$email."' AND BINARY t.passcode = '".$passcode."'";
			$result = mysqli_query($conn, $sql);

	        if (mysqli_num_rows($result) > 0) {
		        while($team = mysqli_fetch_array($result)) 
		        {
		        	$message = '<h4>Team found!</h4> Joining team: '.$team['team_name'].', Captain: '.$team['full_name']
		        	.'<input type="hidden" name="teamId" value="'.$team['id'].'">';
		        	$return = ['type' => 'success', 'message' => $message];
		        }

	        } else {
	        	$return = ['type' => 'error', 'message' => 'No team found.'];
	        }
	    } else {
	    	$return = ['type' => 'error', 'message' => 'No team found.'];
    	}

    	echo json_encode($return);
    	die;
	}

	// Return causes for users to choose to donate to
	if (isset($_GET) && isset($_GET['getCauses'])) {
		// Get future events and any event less than 30 days ago
		$thirtyDaysAgo = date("Y-m-d", strtotime('-30 days'));
		$sql = "SELECT * FROM events WHERE is_active = 1 AND event_date >= '".$thirtyDaysAgo."'";
		$result = mysqli_query($conn, $sql);

		$data = [];
		$count = 1;

        if (mysqli_num_rows($result) > 0) {
	        while($row = mysqli_fetch_array($result)) 
	        {
	        	array_push($data, $playerArray[$count] = [
	        		'id' => $row['id'],
	        		'text' => $row['title']
	        	]);
	        	$count++;
	        }
        } else {
        	echo 'error';
        }

        $items = ['results' => $data];
        echo json_encode($data);
        die;
	}

?>
			