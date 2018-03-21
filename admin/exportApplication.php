<?php

include('includes/datalogin.php');

if (isset($_GET) && !empty($_GET)) {
    $appId = $_GET['id']; 
} 

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Application.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('Nominator', 'Phone #', 'Email'));

$nominator = $nominee = $nomineeDetails = [];

$applications = mysqli_query($conn,"SELECT * FROM applications JOIN people on applications.nominator_id = people.id WHERE applications.id = $appId");
while($app = mysqli_fetch_array($applications)) 
{ 
    $nominator['name'] = $app['full_name'];
    $nominator['phone'] = $app['phone'];
    $nominator['email'] = $app['email'];

    fputcsv($output, $nominator);
}

$sql = "SELECT * FROM applications JOIN people on applications.nominee_id = people.id WHERE applications.id = $appId";
if ($result = mysqli_query($conn, $sql)) {

	// loop over the rows, outputting them
	while ($app = mysqli_fetch_array($result)) {
		 
        // output the column headings
        fputcsv($output, array('Nominee', 'Phone #', 'Email', 'Address', 'City', 'State', 'Zip')); 
    	
        $nominee['name'] = $app['full_name'];
        $nominee['phone'] = $app['phone'];
        $nominee['email'] = $app['email'];
        $nominee['address'] = $app['address'];
        $nominee['city'] = $app['city'];
        $nominee['state'] = $app['state'];
        $nominee['zip'] = $app['zip'];

        fputcsv($output, $nominee);

        // output the column headings
        fputcsv($output, array('Volleyball Story', 'Circumstances', 'Amt. Requested', 'Requested Date', 'Submit Date', 'Status'));

        $nomineeDetails['volleyball_association'] = $app['volleyball_association'];
        $nomineeDetails['circumstances'] = $app['circumstances'];
        $nomineeDetails['amount_requested'] = '$'.$app['amount_requested'];
        $nomineeDetails['requested_date'] = $app['requested_date'];
        $nomineeDetails['submitted_at'] = $app['submitted_at'];
        $nomineeDetails['status'] = $app['status'];
        
        fputcsv($output, $nomineeDetails);                         
	}
}

?>