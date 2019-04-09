<?php

    include 'admin/includes/datalogin.php';
    include 'admin/includes/functions.php';

    $insertItems = $insertNominator = $insertNominee = '';
    $fields = $nominatorField = $nomineeField = '';
    $return = 'success';

    if (isset($_POST['img'])) {
        $signatureImage = base64_to_jpeg($_POST['img']);   
    }

    function base64_to_jpeg($base64_string) {
        $targetDir = "attachments/";
        $newFileName = round(microtime(true)).rand(1,100).'.png';
        $targetFile = $targetDir.$newFileName;
        $ifp = fopen( $targetFile, 'w' ); 
        $data = explode( ',', $base64_string );
        fwrite($ifp, base64_decode( $data[ 1 ] ) );
        fclose($ifp); 

        return $targetFile; 
    }

    // Upload file first
    $targetDir = "attachments/";
    
    if($_FILES && $_FILES['attachment_path']['name'] != '') {
        $temp = explode(".", $_FILES['attachment_path']["name"]); 
        $attachmentPath = round(microtime(true)).rand(1,100).'.'.end($temp);
        $targetFile = $targetDir.$attachmentPath;

        $return = uploadAttachment($targetFile, $_FILES['attachment_path'], 'document');
            
        $fileName = $targetDir.$attachmentPath;
    } 

    if ($return == 'success') {

        $createdAt = date('Y-m-d H:i:s');

        $nominatorName = mysqli_real_escape_string($conn, trim($_POST['nominator_full_name']));
        $nominatorPhone = mysqli_real_escape_string($conn, trim($_POST['nominator_phone']));
        $nominatorEmail = mysqli_real_escape_string($conn, trim($_POST['nominator_email']));
        $paid = 0;

        // This check should keep it from submitting empty applications
        if ($nominatorName != "") {

            // Save nominator
            $sql = $conn->prepare("INSERT INTO people (full_name, phone, email, paid, created_at) VALUES (?,?,?,?,?)");
            $sql->bind_param('sssis', $nominatorName, $nominatorPhone, $nominatorEmail, $paid, $createdAt);
            $sql->execute();

            $nominatorId = $conn->insert_id;

            // Save nominee
            $nomineeName = mysqli_real_escape_string($conn, trim($_POST['nominee_full_name']));
            $nomineePhone = mysqli_real_escape_string($conn, trim($_POST['nominee_phone']));
            $nomineeEmail = mysqli_real_escape_string($conn, trim($_POST['nominee_email']));
            $nomineeAddress = mysqli_real_escape_string($conn, trim($_POST['nominee_address']));
            $nomineeCity = mysqli_real_escape_string($conn, trim($_POST['nominee_city']));
            $nomineeState = mysqli_real_escape_string($conn, trim($_POST['nominee_state']));
            $nomineeZip = mysqli_real_escape_string($conn, trim($_POST['nominee_zip']));

            // Save nominator
            $sql = $conn->prepare("INSERT INTO people (full_name, phone, email, address, city, state, zip, paid, created_at) VALUES (?,?,?,?,?,?,?,?,?)");
            $sql->bind_param('sssssssis', $nomineeName, $nomineePhone, $nomineeEmail, $nomineeAddress, $nomineeCity, $nomineeState, $nomineeZip, $paid, $createdAt);
            $sql->execute();

            $nomineeId = $conn->insert_id;

            $status = "Submitted";
            $volleyballAssociation = mysqli_real_escape_string($conn, trim($_POST['volleyball_association']));
            $circumstances = mysqli_real_escape_string($conn, trim($_POST['circumstances']));
            $amountRequested = $_POST['amount_requested'];
            $requestedDate = mysqli_real_escape_string($conn, trim($_POST['requested_date']));
            $signedDate = mysqli_real_escape_string($conn, trim($_POST['signed_date']));

            // Save the application
            $sql = $conn->prepare("INSERT INTO applications (status, nominator_id, nominee_id, volleyball_association, circumstances, amount_requested, requested_date, attachment_path, signature_path, signed_date, submitted_at) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $sql->bind_param('siissdsssss', $status, $nominatorId, $nomineeId, $volleyballAssociation, $circumstances, $amountRequested, $requestedDate, $attachmentPath, $signatureImage, $signedDate, $createdAt);
            $sql->execute();

            // Send an email
        	$email_template = 'includes/emailTemplate.html';

            if (IS_DEV) {
                $to = 'tylerjaquish@gmail.com';
                $result = array('type' => 'success', 'message'=>"Unable to send email in dev.");
            } else {
                $to = 'info@spike2care.org';
            
                $subject = 'New assistance application from spike2care.org';
                $message = 'To view the application, login to <a href="spike2care.org/admin">spike2care admin</a>.';

                $headers  = "From: " . $_POST['nominator_full_name'] . ' <' . $_POST['nominator_email'] . '>' . "\r\n";
                $headers .= "Reply-To: ". $_POST['nominator_email'] . "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                $templateTags =  [
                    '{{subject}}' => $subject,
                    '{{email}}'=>$_POST['nominator_email'],
                    '{{message}}'=>$message,
                    '{{name}}'=>$_POST['nominator_full_name']
                ];

                $templateContents = file_get_contents( dirname(__FILE__) . '/'.$email_template);
                $contents =  strtr($templateContents, $templateTags);

                try {
                    if (mail( $to, $subject, $contents, $headers)) {
                        $result = array('type' => 'success', 'message'=>'Your email has been delivered.');
                    } else {
                        $result = array('type' => 'error', 'message'=>"Unable to send email.");
                    }
                } catch (Exception $e) {
                    // TODO: handle exception
                    echo $e->getMessage();
                }
            }
        }
    } else {
        $result = ['type' => 'error', 'message' => $return];
    }

    echo json_encode($result);
    die;
?>