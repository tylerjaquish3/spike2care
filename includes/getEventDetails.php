<?php

include '../admin/includes/datalogin.php';

$success = 1;
$result = null;

if(isset($_GET)) {

	$eventId = $_GET['EID'];

	$query = mysqli_query($conn,"SELECT * FROM events WHERE id = ".$eventId);
	while($event = mysqli_fetch_array($query)) 
	{
		$query2 = mysqli_query($conn,"SELECT * FROM teams WHERE event_id = ".$eventId);
		if (mysqli_num_rows($query2) > 0) {
			while($teams = mysqli_fetch_array($query2)) 
			{
				array_push($event, $teams);
			}
		}

		print json_encode($event);
	}
}

?>