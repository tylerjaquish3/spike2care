<?php

// output headers so that the file is downloaded rather than displayed
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=Teams.csv");
header("Pragma: no-cache");
header("Expires: 0");

include('includes/datalogin.php');

if (isset($_GET) && !empty($_GET)) {
    $eventId = $_GET['eventId']; 
}

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('Here', 'Player', 'Team', 'Needs to Pay', 'ERVA - Cash/Card/Check'));

$player = [];

    
if ($result = mysqli_query($conn, 'SELECT 
    full_name, (LOCATE(" ", full_name) = 0) AS hasMultipleWords
    ,REVERSE(SUBSTRING_INDEX(REVERSE(full_name), " ", 1)) AS lastName
    ,TRIM(SUBSTRING(full_name, 1, CHAR_LENGTH(full_name) - CHAR_LENGTH(SUBSTRING_INDEX(REVERSE(full_name), " ", 1)))) AS firstNames,
    team_name, entry_amount, e.price
    FROM teams AS t 
    JOIN team_players AS tp ON tp.team_id = t.id
    JOIN people AS p ON tp.people_id = p.id 
    JOIN events as e ON t.event_id = e.id
    LEFT JOIN payments AS pay on pay.paid_for = p.id
    WHERE tp.is_active = 1 AND t.event_id = '.$eventId.' AND t.is_active = 1 AND donation_amount IS NULL
    ORDER BY lastName ASC')) {

	// loop over the rows, outputting them to csv
	while ($team = mysqli_fetch_array($result)) {
        $player['here'] = '';
    	$player['name'] = $team['lastName'].", ".$team['firstNames'];
    	$player['team'] = $team['team_name'];
    	$player['entry_amount'] = ($team['entry_amount'] ? '' : '$ '.number_format(($team['price'] + 5),2));
        $player['erva'] = '';

        fputcsv($output, $player);                                     
	}
}

fclose($output);
die;

?>