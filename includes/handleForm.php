<?php
session_start();

include('../admin/includes/datalogin.php');
include('../admin/includes/functions.php');
include('../admin/includes/password.php');

require_once('../stripe/init.php');

// var_dump($_POST);die;

	// when a new recap comment is added
	if (isset($_GET['comment'])) {
		$recapId = $_GET['recap_id'];
		$comment = escape($_GET['comment']);
		$commenter = 'Anonymous';
		$date = date('Y-m-d H:i:s');

		if (isset($_GET['commenter']) && $_GET['commenter'] != '') {
			$commenter = escape($_GET['commenter']);
		}

		$sql = $conn->prepare("INSERT INTO recap_comments (recap_id, comment_text, commenter_name, created_at) VALUES (?,?,?,?)");
		$sql->bind_param('isss', $recapId, $comment, $commenter, $date);

		$succeeded = $sql->execute();
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
			$paid = 0;
			$sql = $conn->prepare("INSERT INTO people (full_name, phone, email, paid, created_at) VALUES (?,?,?,?,?)");
			$sql->bind_param('sssis', $name, $phone, $email, $paid, $created_at);
			$sql->execute();

			$newPersonId = $conn->insert_id;
			// Store in session for saving later
			$_SESSION['newPersonId'] = $newPersonId;

			$sql = $conn->prepare("INSERT INTO free_agents (people_id, event_id, division_id) VALUES (?,?,?)");
			$sql->bind_param('iii', $newPersonId, $eventId, $division);
			
			$sql->execute();
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
				$paid = 0;
				$sql = $conn->prepare("INSERT INTO people (full_name, phone, email, paid, created_at) VALUES (?,?,?,?,?)");
				$sql->bind_param('sssis', $name, $phone, $email, $paid, $created_at);
				$sql->execute();

				$newPersonId = $conn->insert_id;
				// Save new person's id in session
				$_SESSION['newPersonId'] = $newPersonId;
				// Only insert if name match is less than 85%
				$captain = 0;
				$active = 1;
				$sql = $conn->prepare("INSERT INTO team_players (team_id, people_id, is_captain, is_active) VALUES (?,?,?,?)");
				$sql->bind_param('iiii', $teamId, $newPersonId, $captain, $active);
				$sql->execute();

			} else {
				// else update user information with form data
				$sql = $conn->prepare("UPDATE people SET full_name = ?, phone = ?, email = ? WHERE id = ?");
				$sql->bind_param('sssi', $name, $phone, $email, $playerFoundId);
				$sql->execute();
				$_SESSION['newPersonId'] = $playerFoundId;
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
							mysqli_query($conn, $sql);
						}
					}
				}
			}

		} elseif ($type == 'new') {
			$division = $_POST['division'];
			$teamName = mysqli_real_escape_string($conn, trim($_POST['team_name']));
			$passcode = mysqli_real_escape_string($conn, trim($_POST['passcode']));
			$paid = 0;

			$sql = $conn->prepare("INSERT INTO people (full_name, phone, email, paid, created_at) VALUES (?,?,?,?,?)");
			$sql->bind_param('sssis', $name, $phone, $email, $paid, $created_at);
			$sql->execute();

			$newCaptainId = $conn->insert_id;
			// Save new person's id in session
			$_SESSION['newPersonId'] = $newCaptainId;
			$isActive = 0;

			$sql = $conn->prepare("INSERT INTO teams (team_name, passcode, event_id, division_id, captain_id, is_active, created_at) VALUES (?,?,?,?,?,?,?)");
			$sql->bind_param('ssiiiis', $teamName, $passcode, $eventId, $division, $newCaptainId, $isActive, $created_at);
			$sql->execute();

			$newTeamId = $conn->insert_id;

			$isCaptain = $isActive = true;
			$sql = $conn->prepare("INSERT INTO team_players (team_id, people_id, is_captain, is_active) VALUES (?,?,?,?)");
			$sql->bind_param('iiii', $newTeamId, $newCaptainId, $isCaptain, $isActive);
			$sql->execute();
			
			foreach ($_POST['players'] as $player) {
				if ($player != '') {

					$paid = 0;
					$sql = $conn->prepare("INSERT INTO people (full_name, paid, created_at) VALUES (?,?,?)");
					$sql->bind_param('sis', $player, $paid, $created_at);
					$sql->execute();
					
					$newPlayerId = $conn->insert_id;

					$isCaptain = false;
					$isActive = true;
					$sql = $conn->prepare("INSERT INTO team_players (team_id, people_id, is_captain, is_active) VALUES (?,?,?,?)");
					$sql->bind_param('iiii', $newTeamId, $newPlayerId, $isCaptain, $isActive);
					$sql->execute();
				}
			}
		}

		header("Location: ../checkout.php?eventId=".$eventId."&teamId=".$newTeamId);
		die;
	}

	if (isset($_GET['passcode'])) {
		// Validate passcode
		$passcode = trim($_GET['passcode']);
		$now = date('Y-m-d H:i:s');

		if ($passcode != '') {

			if (isset($_GET['eventId'])) {
				$eventId = $_GET['eventId'];
				$sql = "SELECT t.*, team_players, full_name FROM teams as t JOIN spike2care.`events` as e ON t.event_id = e.id JOIN people on people.id = t.captain_id WHERE t.is_active = 1 AND e.is_active = 1 AND BINARY t.passcode = '".$passcode."' AND event_id = ".$eventId;
			} else {
				$eventId = null;
				// If eventId isn't set, we're checking for duplicate passcodes
				$sql = "SELECT * FROM teams WHERE passcode = '".$passcode."'";
			}

			$result = mysqli_query($conn, $sql);
	        if (mysqli_num_rows($result) > 0 && $eventId) {
		        while($team = mysqli_fetch_array($result)) 
		        {
		        	$message = '<h4>Team found!</h4> Joining team: '.$team['team_name'].', Captain: '.$team['full_name']
		        	.'<input type="hidden" name="teamId" value="'.$team['id'].'">';
		        	$return = ['type' => 'success', 'message' => $message];
		        }
	        } elseif (mysqli_num_rows($result)) {
	        	$return = ['type' => 'failure', 'message' => 'Passcode is duplicated.'];
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
		$name = trim($_POST['full_name']);
		$phone = $_POST['phone'];
		$email = trim($_POST['email']);
		$paid = 1;

		// user needs to get added into people
		$sql = $conn->prepare("INSERT INTO people (full_name, phone, email, paid, created_at) VALUES (?,?,?,?,?)");
		$sql->bind_param('sssis', $name, $phone, $email, $paid, $created_at);
		$sql->execute();

		$newPersonId = $conn->insert_id;
		// Store in session to use later
		$_SESSION['newPersonId'] = $newPersonId;
		
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
			  "description" => "Spike2Care.org donation (Tax ID: 47-4545145)",
			  "source" => $token,
			));

			$chargeToken = $charge->id;

			// If the user donated to a specific cause, add the event_id to the payment
			if ($cause != 0) {
				$sql = $conn->prepare("INSERT INTO payments (paid_by, donation_amount, cause_id, token, created_at) VALUES (?,?,?,?,?)");
				$sql->bind_param('iiiss', $newPersonId, $_POST['totalDonation'], $cause, $chargeToken, $created_at);
				$sql->execute();
			} else {
				$sql = $conn->prepare("INSERT INTO payments (paid_by, donation_amount, token, created_at) VALUES (?,?,?,?)");
				$sql->bind_param('iiss', $newPersonId, $_POST['totalDonation'], $chargeToken, $created_at);
				$sql->execute();
			}

		} catch (Exception $e) {
			// TODO: error handling
		}

		header("Location: ../index.php?message=thankyou");
		die();
	}

	// Checkout for special event
	if (isset($_POST['quantity']) && $_POST['quantity'] != '') {
		$chargeToken = '';
		$eventId = $paidBy = $eventPrice = $donation = null;
		$cause = (int)$_POST['cause'][0];

		$eventId = $_POST['event_id'];
		$eventPrice = $_POST['eventPrice'] * 100;
		$quantity = $_POST['quantity'];

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
			  "description" => "Spike2Care.org event entry and/or donation",
			  "receipt_email" => $email,
			  "source" => $token,
			));

			$chargeToken = $charge->id;

		} catch (Exception $e) {
			// TODO: error handling
		}

		$createdAt = date('Y-m-d H:i:s');

		$sql = "UPDATE people SET paid = 1, token = '$chargeToken' WHERE id = ".$paidBy;
		mysqli_query($conn, $sql);

		if ($_POST['totalDonation'] != '' && $_POST['totalDonation'] != 0) {
			$donation = $_POST['totalDonation'];
			// If the user donated to a specific cause, add the event_id to the payment
			if ($cause != 0) {
				$sql = $conn->prepare("INSERT INTO payments (paid_by, donation_amount, cause_id, token, created_at) VALUES (?,?,?,?,?)");
				$sql->bind_param('iiiss', $paidBy, $_POST['totalDonation'], $cause, $chargeToken, $createdAt);
				$sql->execute();
			} else {
				$sql = $conn->prepare("INSERT INTO payments (paid_by, donation_amount, token, created_at) VALUES (?,?,?,?)");
				$sql->bind_param('iiss', $paidBy, $_POST['totalDonation'], $chargeToken, $createdAt);
				$sql->execute();
			}
		}

		// update team to paid
		$sql = "UPDATE teams SET is_active = 1, players_paid = ".$quantity." WHERE captain_id = ".$paidBy;
		mysqli_query($conn, $sql);

		$eventEntry = $eventPrice * $quantity;
		// Add to payments for event entry
		$sql = $conn->prepare("INSERT INTO payments (paid_by, paid_for, entry_amount, quantity, event_id, token, created_at) VALUES (?,?,?,?,?,?,?)");
		$sql->bind_param('iiiiiss', $paidBy, $paidBy, $eventEntry, $quantity, $eventId, $chargeToken, $createdAt);
		$sql->execute();

		header("Location: ../showSpecialEvent.php?eventId=".$eventId."&message=success");
		die();
	}

	// New registration with players paid
	if (isset($_POST['action']) && $_POST['action'] == 'teamCheckout') {

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
			echo $e->getMessage();
			// Fake token
			$chargeToken = 'invalid';
			// Redirect user with error message
			header("Location: ../showEvent.php?eventId=".$eventId."&message=invalid");
			die();
		}

		$createdAt = date('Y-m-d H:i:s');

		if ($_POST['totalDonation'] != '' && $_POST['totalDonation'] != 0) {
			$donation = $_POST['totalDonation'];

			// If the user donated to a specific cause, add the event_id to the payment
			if ($cause != 0) {
				$sql = $conn->prepare("INSERT INTO payments (paid_by, donation_amount, cause_id, token, created_at) VALUES (?,?,?,?,?)");
				$sql->bind_param('iiiss', $paidBy, $_POST['totalDonation'], $cause, $chargeToken, $createdAt);
				$sql->execute();
			} else {
				$sql = $conn->prepare("INSERT INTO payments (paid_by, donation_amount, token, created_at) VALUES (?,?,?,?)");
				$sql->bind_param('iiss', $paidBy, $_POST['totalDonation'], $chargeToken, $createdAt);
				$sql->execute();
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
		
		$sql = "UPDATE reserved_teams SET is_active = 0 WHERE event_id = $eventId AND captain_email = '".$email."'";
		$test = mysqli_query($conn, $sql);

    	$newPlayersPaid = $existingPlayersPaid + count($playersPaid);

		$sql = "UPDATE teams SET is_active = 1, players_paid = ".$newPlayersPaid." WHERE id = ".$teamId;
		mysqli_query($conn, $sql);

		// Update each player as having paid
		foreach ($playersPaid as $player) {
			$sql = "UPDATE people SET paid = 1, token = '$chargeToken' WHERE id = ".$player;
			mysqli_query($conn, $sql);

			$sql = $conn->prepare("INSERT INTO payments (paid_by, paid_for, entry_amount, event_id, token, created_at) VALUES (?,?,?,?,?,?)");
			$sql->bind_param('iiiiss', $paidBy, $player, $eventPrice, $eventId, $chargeToken, $createdAt);
			$sql->execute();
		}

		header("Location: ../showEvent.php?eventId=".$eventId."&message=success");
		die();
	}

	// Get players for specific team that are paid/unpaid
	if (isset($_GET['teamId']) && $_GET['teamId'] != '') {
		$paid = $_GET['paid'];
		$teamId = $_GET['teamId'];

		$sql = $conn->prepare("SELECT * FROM teams AS t 
			JOIN team_players AS tp ON tp.team_id = t.id
			JOIN people AS p ON tp.people_id = p.id 
			WHERE tp.is_active = 1 AND t.id = ? AND paid = ?");
		$sql->bind_param('ii', $teamId, $paid);
		$sql->execute();

		$playerArray = $data = [];
		$count = 1;
		$result = $sql->get_result();

        if ($result->num_rows > 0) {
	        while($row = $result->fetch_assoc()) 
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

	// if(isset($_POST['refund-precheck'])) {

	// 	$email = $_POST['email'];
	// 	$passcode = $_POST['passcode'];
	// 	$eventId = (int)$_POST['eventId'];
	// 	$captain = false;

	// 	$sql = "SELECT p.id as userId, t.id as teamId, token, p.full_name, captain_id FROM teams AS t 
	// 	JOIN team_players AS tp ON tp.team_id = t.id
	// 	JOIN people AS p ON tp.people_id = p.id 
	// 		JOIN events e ON t.event_id = e.id
	// 		WHERE email = '$email'
	// 		AND event_id = $eventId
	// 		AND passcode = '$passcode'";
	// 		// probly need to check is_active on tp

	// 	$result = mysqli_query($conn, $sql);
 //        if (mysqli_num_rows($result) > 0) {
	//         while($row = mysqli_fetch_array($result)) 
	//         {
	//         	$token = $row['token'];
	//         	$userId = $row['userId'];
	//         	$teamId = $row['teamId'];
	//         	$playerName = $row['full_name'];
	//         	if ($row['captain_id'] == $userId) {
	//         		$captain = true;
	//         	}
	//         }

	//         $payments = "SELECT * FROM payments WHERE event_id = $eventId AND paid_by = $userId";
	//         $result = mysqli_query($conn, $payments);
	//         if (mysqli_num_rows($result) > 0) {
	// 	        while($row = mysqli_fetch_array($result)) 
	// 	        {
	// 	        	$paidFor[] = $row['paid_for'];
	// 	        }
	// 	    }
	//         $result = ['type' => 'success', 'token' => $token, 'userId' => $userId, 'teamId' => $teamId, 'playerName' => $playerName, 'captain' => $captain, 'paidFor' => $paidFor, 'eventId' => $eventId];
 //        } else {
 //        	$result = ['type' => 'error', 'message' => 'There was an error matching the email and passcode. <br /><a href="contact.php">Click here to message Spike2Care for assistance.</a>'];
 //        }

 //        echo json_encode($result);
	//     die;
	// }

	// if(isset($_POST['refund-form'])) {
		
	// 	$token = $_POST['token'];
	// 	$userId = $_POST['userId'];
	// 	$eventId = $_POST['eventId'];
	// 	$teamId = $_POST['teamId'];
	// 	$captainChoice = $_POST['captainChoice'];

	// 	// Loop through each team member and issue refunds for each payment
	// 	if ($captainChoice == 'refund-entire-team') {
	// 		$sql = "SELECT * FROM teams WHERE id = ".$teamId;
	// 		$result = mysqli_query($conn, $sql);
	//         if (mysqli_num_rows($result) > 0) {
	// 	        while($row = mysqli_fetch_array($result)) 
	// 	        {
	// 	        	for ($x=2; $x<9; $x++) {
	// 	        		if ($row['player'.$x.'_id']) {
	// 	        			$response = refundPlayer($conn, $eventId, $teamId, $row['player'.$x.'_id']);
	// 	        		} else if ($row['captain_id']) {
	// 	        			$response = refundPlayer($conn, $eventId, $teamId, $row['captain_id']);
	// 	        		}
	// 	        	}
	// 	        }
	// 	    }

	// 	    $sql = "UPDATE teams SET is_active = 0, players_paid = 0 WHERE id = ".$teamId;
	// 		mysqli_query($conn, $sql);

	// 	} else if ($captainChoice == 'refund-specific-players') {
	// 		//Refund specific players
	// 		if (isset($_POST['refund-players'])) {
	// 			$refundPlayers = $_POST['refund-players'];
	// 			foreach ($refundPlayers as $index => $playerId) {
	// 				$response = refundPlayer($conn, $eventId, $teamId, $playerId);
	// 			}
	// 		}

	// 		// Save the new captain
	// 		if (isset($_POST['new-captain'])) {
	// 			$newCaptain = $_POST['new-captain'];
	// 			for ($x=2; $x<9; $x++) {
	// 				if ($team['player'.$x.'_id'] == $newCaptain) {
	// 					$resetPlayerId = 'player'.$x.'_id';
	// 				}
	// 			}
				
	// 			$sql = "UPDATE teams SET captain_id = $newCaptain, $resetPlayerId = null WHERE id = ".$teamId;
	// 			mysqli_query($conn, $sql);
	// 		}
	// 	}

	// 	echo json_encode($response);
	//     die;
 //    }

	// function refundPlayer($conn, $eventId, $teamId, $playerId)
	// {
	// 	$response = '';
	// 	// Look up the token from payments table
	// 	$sql = "SELECT * FROM payments WHERE event_id = ".$eventId." AND paid_for =".$playerId;
	// 	$result = mysqli_query($conn, $sql);
 //        if (mysqli_num_rows($result) > 0) {
	//         while($payment = mysqli_fetch_array($result)) 
	//         {
	//         	if ($payment['entry_amount'] && $payment['entry_amount'] > 0) {
	//         		$token = $payment['token'];

	//         		// If a token is found, create the refund
	// 		        if (isset($token) && $token != '') {

	// 		        	// Issue the refund
	// 		        	$response = issueRefund($token);

	// 					// Set player as unpaid
	// 					$sql = "UPDATE people SET paid = 0 WHERE id = ".$playerId;
	// 					mysqli_query($conn, $sql);

	// 					// Remove player from team and decrement number of players paid
	// 					$sql = "SELECT * FROM teams WHERE id = ".$teamId;
	// 					$result = mysqli_query($conn, $sql);
	// 			        if (mysqli_num_rows($result) > 0) {
	// 				        while($team = mysqli_fetch_array($result)) 
	// 				        {
	// 				        	$playersPaid = $team['players_paid'];
	// 				        	// If player is on team, get the player column to nullify
	// 				        	for ($x=2; $x<9; $x++) {
	// 	        					if ($team['player'.$x.'_id'] == $playerId) {
	// 	        						$resetPlayerId = 'player'.$x.'_id';
	// 	        					}
	//         					}
	//         					if ($team['captain_id'] == $playerId) {
	//         						$resetPlayerId = 'captain_id';
	//         					}
	// 				        }
	// 				    }
	// 				    $newPlayersPaid = $playersPaid-1;

	// 				    if (isset($resetPlayerId)) {
	// 						$sql = "UPDATE teams SET ".$resetPlayerId." = null, players_paid = ".$newPlayersPaid." WHERE id = ".$teamId;
	// 						mysqli_query($conn, $sql);
	// 					}

	// 					// Set payment to refunded
	// 					$sql = "UPDATE payments SET is_refunded = 1 WHERE id = ".$payment['id'];
	// 					mysqli_query($conn, $sql);

						
	// 		        } else {
	// 		        	$response = ['type' => 'error', 'message' => 'Error matching email and passcode. <a class="white-link" href="contact.php">Click here to message Spike2Care for assistance.</a>'];
	// 		        }
	//         	}
 //        	}
 //    	}

 //        return $response;
 //    }

	// function issueRefund($token) 
	// {
	// 	$response = ['type' => 'success', 'message' => 'Refund has been issued and may take 5-10 business days to credit your original payment method.'];

	// 	try {
	// 		if (IS_DEV) {
	// 	        \Stripe\Stripe::setApiKey("sk_test_xjdaWuWDrUpmVfeuEhmovSk4");
	// 	    } else {
	// 			\Stripe\Stripe::setApiKey(LIVE_KEY);
	// 		}

	// 		$re = \Stripe\Refund::create(array(
	// 		  "charge" => $token
	// 		));
	// 	} catch (Exception $e) {
	// 		//var_dump($e);
	// 		if (strpos($e->httpBody, 'invalid_request_error') !== false) {
	// 			$result = ['type' => 'error', 'message' => 'Charge has already been refunded.'];
	// 		} else {
	// 			var_dump($e);
	// 		}
	// 	}

	// 	return $response;
	// }

	// Look up a team by passcode
	if (isset($_POST['eventId']) && isset($_POST['email']) && isset($_POST['passcode'])) {
		$email = $_POST['email'];
		$passcode = $_POST['passcode'];
		$eventId = $_POST['eventId'];
		$now = date('Y-m-d H:i:s');

		if ($passcode != '') {

			$sql = $conn->prepare("SELECT t.*, team_players, full_name 
				FROM teams as t 
				JOIN events as e ON t.event_id = e.id 
				JOIN people on people.id = t.captain_id 
				WHERE t.is_active = 1 AND e.is_active = 1 AND e.id = ? AND people.email = ? AND BINARY t.passcode = ?");
			$sql->bind_param('iss', $eventId, $email, $passcode);
			$sql->execute();

			$result = $sql->get_result();

	        if ($result->num_rows > 0) {
		        while($row = $result->fetch_assoc()) {
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
		$sql = "SELECT * FROM causes WHERE active = 1";
		$result = mysqli_query($conn, $sql);

		$data = [];
		$count = 1;

        if (mysqli_num_rows($result) > 0) {
	        while($row = mysqli_fetch_array($result)) 
	        {
	        	array_push($data, $playerArray[$count] = [
	        		'id' => $row['id'],
	        		'text' => $row['name']
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

	// If new registration for special event
	if (isset($_POST) && isset($_POST['paidBySpecial'])) {

		$createdAt = date('Y-m-d H:i:s');
		$eventId = $_POST['eventId'];
		$name = mysqli_real_escape_string($conn, trim($_POST['paidBySpecial']));
		$phone = mysqli_real_escape_string($conn, $_POST['phone']);
		$email = mysqli_real_escape_string($conn, trim($_POST['email']));
		$paid = 0;

		$sql = $conn->prepare("INSERT INTO people (full_name, phone, email, paid, created_at) VALUES (?,?,?,?,?)");
		$sql->bind_param('sssis', $name, $phone, $email, $paid, $createdAt);
		$sql->execute();

		$newCaptainId = $conn->insert_id;
		// Save new person's id in session
		$_SESSION['newPersonId'] = $newCaptainId;
		$passcode = '';
		$division = 1;
		$isActive = 0;

		$sql = $conn->prepare("INSERT INTO teams (team_name, passcode, event_id, division_id, captain_id, is_active, created_at) VALUES (?,?,?,?,?,?,?)");
		$sql->bind_param('ssiiiis', $name, $passcode, $eventId, $division, $newCaptainId, $isActive, $createdAt);
		$sql->execute();

		$newTeamId = $conn->insert_id;
		$isCaptain = $isActive = 1;

		$sql = $conn->prepare("INSERT INTO team_players (team_id, people_id, is_captain, is_active) VALUES (?,?,?,?)");
		$sql->bind_param('iiii', $newTeamId, $newCaptainId, $isCaptain, $isActive);
		$sql->execute();

		echo json_encode("../checkout.php?specialEventId=".$eventId);
		die;
	}

	// User is adding an item to the cart, so add it to the session
	if (isset($_POST) && isset($_POST['addToCart'])) {
		
		if (!isset($_SESSION['items'])) {
			$_SESSION['items'] = [];	
		}
		
		$newItem = [];
		try {
			foreach ($_POST['formData'] as $index => $stuff) {
				foreach ($stuff as $key => $value) {
					if ($key == 'name') {
						$saveKey = $value;
					} else {
						$saveValue = $value;
					}
				}
				$newItem[$saveKey] = $saveValue;
			}

			array_push($_SESSION['items'], $newItem);
		} catch (\Exception $ex) {
			echo json_encode('failed');
			die;
		}

		echo json_encode(['success', $newItem]);
		die;
	}

	// User is removing an item from their cart, so unset it from the session
	if (isset($_POST) && isset($_POST['removeFromCart'])) {
		// session_unset();
		if (!isset($_SESSION['items'])) {
			$_SESSION['items'] = [];	
		}

		$sessionKey = $_POST['sessionItem'];
		
		try {
			// Need to remove the cost from the cart total
			$result = mysqli_query($conn,"SELECT * FROM catalog WHERE id = ".$_SESSION['items'][$sessionKey]['itemId']);
	        while($item = mysqli_fetch_array($result)) 
	        {
	            $itemPrice = $item['price'];
	        } 
	        $_SESSION['total'] -= $itemPrice * $_SESSION['items'][$sessionKey]['quantity'];

			unset($_SESSION['items'][$sessionKey]);
		} catch (\Exception $ex) {
			echo json_encode('failed');
			die;
		}

		echo json_encode('success');
		die;
	}

	// Checkout for new merchandise purchase
	if (isset($_POST['merchandise']) && isset($_SESSION['items'])) {

		$createdAt = date('Y-m-d H:i:s');
		$cause = (int)$_POST['cause'][0];
		$donation = $_POST['totalDonation'];
		$merchandiseAmount = $_POST['totalAmount'] - $donation;

		$name = trim($_POST['full_name']);
		$phone = $_POST['phone'];
		$email = trim($_POST['email']);
		$address = trim($_POST['address']);
		$city = trim($_POST['city']);
		$state = trim($_POST['state']);
		$zip = trim($_POST['zip']);
		$paid = 0;

		// user needs to get added into people
		$sql = $conn->prepare("INSERT INTO people (full_name, phone, email, address, city, state, zip, paid, created_at) VALUES (?,?,?,?,?,?,?,?,?)");
		$sql->bind_param('sssssssis', $name, $phone, $email, $address, $city, $state, $zip, $paid, $createdAt);
		$sql->execute();
		
		$newPersonId = $conn->insert_id;
		// Store in session to use later
		$_SESSION['newPersonId'] = $newPersonId;
	
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
			  "description" => "Spike2Care.org merchandise purchase",
			  "source" => $token,
			));

			$chargeToken = $charge->id;
		} catch (Exception $e) {
			// TODO: error handling
			$chargeToken = 'charge failed';
			//var_dump($e);
		}

		try {
			if ($donation > 0) {
				// If the user donated to a specific cause, add the event_id to the payment
				if ($cause != 0) {
					$sql = $conn->prepare("INSERT INTO payments (paid_by, donation_amount, cause_id, token, created_at) VALUES (?,?,?,?,?)");
					$sql->bind_param('iiiss', $newPersonId, $donation, $cause, $chargeToken, $createdAt);
					$sql->execute();
				} else {
					$sql = $conn->prepare("INSERT INTO payments (paid_by, donation_amount, token, created_at) VALUES (?,?,?,?)");
					$sql->bind_param('iiss', $newPersonId, $donation, $chargeToken, $createdAt);
					$sql->execute();
				}
			}
				
			// Add sales payment
			$sql = $conn->prepare("INSERT INTO payments (paid_by, merchandise_amount, token, created_at) VALUES (?,?,?,?)");
			$sql->bind_param('iiss', $newPersonId, $merchandiseAmount, $chargeToken, $createdAt);
			$sql->execute();

			$newPaymentId = $conn->insert_id;

			// Add items to sales
			foreach ($_SESSION['items'] as $item) {
				$status = 'Created';

				$sql = $conn->prepare("INSERT INTO sales (person_id, catalog_id, quantity, size_id, color_id, status, created_at) VALUES (?,?,?,?,?,?,?)");
				$sql->bind_param('iiiiiss', $newPersonId, $item['itemId'], $item['quantity'], $item['size'], $item['color'], $status, $createdAt);
				$sql->execute();

				$newSalesId = $conn->insert_id;

				// Add to sales_payments pivot table
				$sql = $conn->prepare("INSERT INTO sales_payments (payment_id, sales_id) VALUES (?,?)");
				$sql->bind_param('ii', $newPaymentId, $newSalesId);
				$sql->execute();
			}
			
			// Send email to admin team
			if (IS_DEV) {
	            $to = 'tylerjaquish@gmail.com';
	            $result = array('type' => 'success', 'message'=>"Unable to send email in dev.");
	        } else {
	            $to = 'info@spike2care.org';
	        
	            $subject = 'New merchandise order from spike2care.org';
	            $itemsTable = 'To view all details of the order/payment, login to <a href="spike2care.org/admin">spike2care admin</a>.<br /><br />';
	            $itemsTable .= '<table><tr><th>Item</th><th>Quantity</th><th>Color</th><th>Size</th></tr>';
	           
	            // Add order items into table
	            $sql = $conn->prepare("SELECT title, price, quantity, color, size FROM sales s 
					JOIN catalog c on s.catalog_id = c.id
					JOIN colors on s.color_id = colors.id
					JOIN sizes on s.size_id = sizes.id
					WHERE person_id = ?");
				$sql->bind_param('i', $newPersonId);
				$sql->execute();

				$result = $sql->get_result();

		        if ($result->num_rows > 0) {
			        while($row = $result->fetch_assoc()) {
                        $itemsTable .= '<tr>
                            <td>'.$row['title'].'</td>
                            <td>'.$row['quantity'].'</td>
                            <td>'.$row['color'].'</td>
                            <td>'.$row['size'].'</td>
                        </tr>';
                  	}
                } 
                $itemsTable .= '</table>';

	            $headers  = "From: spike2care.org" . "\r\n";
	            // $headers .= "Reply-To: ". $_POST['nominator_email'] . "\r\n";
	            $headers .= "MIME-Version: 1.0\r\n";
	            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

	            if (isset($_POST['address']) && $_POST['address'] != "") {
	            	$address = $_POST['address'].', '.$_POST['city'].', '.$_POST['state'].' '.$_POST['zip'];
	            } else {
	            	$address = 'Customer chose to pick up their order at a future event.';
	            }

	            $templateTags =  [
	                '{{subject}}' => $subject,
	                '{{name}}'=>$_POST['full_name'],
	                '{{email}}'=>$_POST['email'],
	                '{{phone}}'=>$_POST['phone'],
	                '{{address}}'=>$address,
	                '{{items}}'=>$itemsTable
	            ];

	            $emailTemplate = 'orderEmailTemplate.html';
	            $templateContents = file_get_contents( dirname(__FILE__) . '/'.$emailTemplate);
	            $contents =  strtr($templateContents, $templateTags);

	            try {
	                if (mail( $to, $subject, $contents, $headers)) {
	                    $result = array('type' => 'success', 'message'=>'Your email has been delivered.');
	                } else {
	                    $result = array('type' => 'error', 'message'=>"Unable to send email.");
	                }
	            } catch (Exception $e) {
	                // TODO: handle exception
	            }
	        }

	        // Remove items from session
			unset($_SESSION['items']);
			unset($_SESSION['total']);

		} catch (Exception $e) {
			// TODO: error handling
			//var_dump($e);
			header("Location: ../cart.php?message=error");
			die();
		}

		header("Location: ../index.php?message=shopthankyou");
		die();
	}
?>
			