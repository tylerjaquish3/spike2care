<?php

include 'admin/includes/datalogin.php';

$success = 1;
$result = null;

if(isset($_GET)) {

	$startDate = date("Y-m-d 00:00:00", round($_GET["from"]/1000));
	$endDate = date("Y-m-d 23:59:59", round($_GET["to"]/1000));

	$i = 0;

	$query = mysqli_query($conn,"SELECT * FROM events WHERE is_active = 1 AND event_date >= '".$startDate."' AND event_date < '".$endDate."'");
	while($event = mysqli_fetch_array($query)) 
	{
		$result[$i]['id'] = $event['id'];
		$result[$i]['title'] = $event['title'];
		$result[$i]['url'] = $event['id'];
		$result[$i]['class'] = 'event-info';
		$result[$i]['start'] = strtotime("+1 day", strtotime($event['event_date']))*1000;
		$result[$i]['end'] = strtotime("+1 day", strtotime($event['event_date']))*1000;
		$result[$i]['page'] = 'showEvent.php';
		if ($event['special_event']) {
			$result[$i]['page'] = 'showSpecialEvent.php';
		}

		$i++;
	}

}

$response['success'] = $success;
$response['result'] = $result;

print json_encode($response);

?>