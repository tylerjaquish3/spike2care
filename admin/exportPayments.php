<?php

include('includes/datalogin.php');

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=S2C Payments.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('Paid By', 'Email', 'Paid For', 'Amount', 'Type', 'Cause', 'Refunded?', 'Received'));

$player = [];

$sql = "SELECT PB.full_name as paid_by, PB.email as email, PF.full_name as paid_for, 
        donation_amount as donation, entry_amount as entry, merchandise_amount as merch, c.name as cause, 
        is_refunded as refund, p.created_at as created_at
        FROM payments p 
        JOIN people as PB on PB.id = p.paid_by 
        LEFT JOIN people as PF on PF.id = p.paid_for 
        LEFT JOIN causes c ON c.id = p.cause_id";
if ($result = mysqli_query($conn, $sql)) {

	// loop over the rows, outputting them
	while ($row = mysqli_fetch_array($result)) {
		  
        if ($row['donation']) {
            $type = 'Donation';
            $amount = number_format($row['donation']/100, 2);
        } elseif ($row['entry']) {
            $type = 'Event';
            $amount = number_format($row['entry']/100, 2);
        } else {
            $type = 'Merchandise';
            $amount = number_format($row['merch']/100, 2);
        }
        $payment['paid_by'] = $row['paid_by'];
        $payment['email'] = $row['email'];
        $payment['paid_for'] = $row['paid_for'];
        $payment['amount'] = '$ '.$amount;
        $payment['type'] = $type;
        $payment['cause'] = $row['cause'];
        $payment['refunded'] = ($row['refund'] == 1) ? 'Yes' : '';
        $payment['received'] = date('Y.m.d H:i:s', strtotime($row['created_at']));
        
        fputcsv($output, $payment);                           
	}
}

?>