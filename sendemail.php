<?php

    include 'admin/includes/datalogin.php';
    include 'admin/includes/functions.php';

	header('Content-type: application/json');
	$status = array(
		'type'=>'success',
		'message'=>'Email sent!'
	);

    $name = @trim(stripslashes($_POST['username'])); 
    $email = @trim(stripslashes($_POST['email'])); 
    $subject = 'New message from spike2care.org';
    $message = @trim(stripslashes($_POST['message'])); 

    $createdAt = date('Y-m-d H:i:s');
    
    $sql = $conn->prepare("INSERT INTO people (full_name, email, created_at) VALUES (?,?,?)");
    $sql->bind_param('sss', $name, $email, $createdAt);
    $sql->execute();

    $newPersonId = $conn->insert_id;
    $status = 'New';

    $sql = $conn->prepare("INSERT INTO messages (people_id, message_text, status, created_at) VALUES (?,?,?,?)");
    $sql->bind_param('isss', $newPersonId, $message, $status, $createdAt);
    $sql->execute();

    $email_template = 'includes/emailTemplate.html';
    
    if (IS_DEV) {
        $to = 'tylerjaquish@gmail.com';
        $result = array( 'type' => 'success', 'message'=>'Unable to send email in dev.'  );
    } else {
        $to = 'info@spike2care.org';
    
        $headers  = "From: " . $name . ' <' . $email . '>' . "\r\n";
        $headers .= "Reply-To: ". $email . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $templateTags =  array(
            '{{subject}}' => $subject,
            '{{email}}'=>$email,
            '{{message}}'=>$message,
            '{{name}}'=>$name
            );

        $templateContents = file_get_contents( dirname(__FILE__) . '/'.$email_template);
        $contents =  strtr($templateContents, $templateTags);

        if (mail( $to, $subject, $contents, $headers)) {
            $result = array( 'type' => 'success', 'message'=>'<strong>Thank You!</strong>&nbsp; Your email has been delivered.' );
        } else {
            $result = array( 'type' => 'error', 'message'=>'<strong>Error!</strong>&nbsp; Cann\'t Send Mail.'  );
        }
}

    echo json_encode($result);

    die;
?>