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
        $newFileName = round(microtime(true)).rand(1,100).'.'.end($temp);
        $targetFile = $targetDir.$newFileName;

        $return = uploadAttachment($targetFile, $_FILES['attachment_path'], 'document');
            
        $fileName = $targetDir.$newFileName;

        $fields .= 'attachment_path,';
        $insertItems = '"'.$newFileName.'",';
    } 

    if ($return == 'success') {
    
        // Loop through the posted form fields
        foreach ($_POST as $field => $value)
        {
            // Skip over the save button field
            if ($field != 'assistance-application' && $field != 'img') {

                // If field starts with nominator, insert into people table
                if (strpos($field, 'nominator') !== false) {
                    $field = substr($field, 10);
                    $nominatorField .= $field.',';
                    $insertNominator .= '"'.escape($value).'",';
                } elseif (strpos($field, 'nominee') !== false) {
                    $field = substr($field, 8);
                    $nomineeField .= $field.',';
                    $insertNominee .= '"'.escape($value).'",';
                } else {
                    $fields .= $field.',';
                    $insertItems .= '"'.escape($value).'",';
                }
            } 
        }

        // Add created at timestamp
        $nominatorField .= 'created_at';
        $insertNominator .= '"'.date('Y-m-d H:i:s').'"';

        $sql = "INSERT INTO people (".$nominatorField.") VALUES (".$insertNominator.")";

        //var_dump($sql);
        mysqli_query($conn, $sql);
        $nominator_id = mysqli_insert_id($conn);

        // Add created at timestamp
        $nomineeField .= 'created_at';
        $insertNominee .= '"'.date('Y-m-d H:i:s').'"';

        $sql = "INSERT INTO people (".$nomineeField.") VALUES (".$insertNominee.")";

        //var_dump($sql);
        mysqli_query($conn, $sql);
        $nominee_id = mysqli_insert_id($conn);

        $fields .= 'status,nominator_id,nominee_id,';
        $insertItems .= '"Submitted",'.$nominator_id.','.$nominee_id.',';

        // Add created at timestamp
        $fields .= 'submitted_at,';
        $insertItems .= '"'.date('Y-m-d H:i:s').'",';

         // Add signature path
        $fields .= 'signature_path';
        $insertItems .= '"'.$signatureImage.'"';

        $sql = "INSERT INTO applications (".$fields.") VALUES (".$insertItems.")";

        //var_dump($sql);
        mysqli_query($conn, $sql);

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

            $templateTags =  array(
                '{{subject}}' => $subject,
                '{{email}}'=>$_POST['nominator_email'],
                '{{message}}'=>$message,
                '{{name}}'=>$_POST['nominator_full_name']
                );

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
            }
        }

    } else {
        $result = ['type' => 'error', 'message' => $return];
    }

    echo json_encode($result);
    die;
?>