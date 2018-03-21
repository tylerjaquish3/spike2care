<?php

include('includes/datalogin.php');

if (isset($_GET) && !empty($_GET)) {
    $eventId = $_GET['eventId']; 
} 

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Entrants.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('Name', 'Email', 'Phone #', 'Quantity', 'Paid Online', 'Order Date'));

$player = [];

$sql = "SELECT full_name, email, phone, quantity, paid, pmt.created_at FROM payments pmt JOIN people p on p.id = pmt.paid_by WHERE event_id = $eventId AND is_refunded = 0";
if ($result = mysqli_query($conn, $sql)) {

	// loop over the rows, outputting them
	while ($row = mysqli_fetch_array($result)) {
		  
    	$entrant['name'] = $row['full_name'];
    	$entrant['email'] = $row['email'];
    	$entrant['phone'] = $row['phone'];
        $entrant['quantity'] = $row['quantity'];
    	$entrant['paid'] = ($row['paid'] ? 'yes' : 'no');
        $entrant['created_at'] = date('m.d.Y', strtotime($row['created_at']));;
        
        fputcsv($output, $entrant);
                                   
	}
}

?>