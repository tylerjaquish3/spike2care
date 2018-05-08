<?php
date_default_timezone_set('America/Los_Angeles');
//define('URL', 'https://spike2care.org');
define('URL', 'http://spike2care.local');

function getUser($id)
{
	include 'datalogin.php';

	$sql = "SELECT * FROM admin WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_array($result)) 
	{
		return $row['user_name'];
	}
}

function image_fix_orientation($filename) {
    $exif = exif_read_data($filename);
    if (!empty($exif['Orientation'])) {
        $image = imagecreatefromjpeg($filename);
        switch ($exif['Orientation']) {
            case 3:
                $image = imagerotate($image, 180, 0);
                break;

            case 6:
                $image = imagerotate($image, -90, 0);
                break;

            case 8:
                $image = imagerotate($image, 90, 0);
                break;
        }

        imagejpeg($image, $filename, 90);
    }
}

function convertMoney($amount)
{
	$price = "<span class='money'>$" . number_format($amount, 2) . "</span>";
	
	return $price;
}

function loadStates()
{
	echo '<option value="" disabled selected>Select state</option>
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>';
}

function checkIfSet($variable)
{
	if(isset($variable)){
		return escape($variable);
	}
	else{
		return null;
	}
}

//escapes all foreign characters from user's input
function escape($str)
{
	$search=array("\\","\0","\n","\r","\x1a","'",'"');
	$replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
	return str_replace($search,$replace,$str);
}

function uploadAttachment($target_file, $fileToUpload, $filetype)
{
	$uploadOk = 1;
	$return = '';
	$uploadedFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = getimagesize($fileToUpload["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			$return .= "File is not an image. ";
			$uploadOk = 0;
		}
	}
	// Check if file already exists
	if (file_exists($target_file)) {
		$return .= "File name already exists. ";
		$uploadOk = 0;
	} 
	
	// Check file size
	if ($fileToUpload["size"] > 50000000) {
		$return .= "Your file is too large. ";
		$uploadOk = 0;
	}

	$uploadedFileType = strtolower($uploadedFileType);
	if ($filetype == 'document') {
		// Allow document file formats
		if($uploadedFileType != "doc" && $uploadedFileType != "docx" && $uploadedFileType != "pdf" && $uploadedFileType != "txt") {
			$return .= "Only DOC, DOCX, PDF & TXT files are allowed. ";
			$uploadOk = 0;
		}
	} else if ($filetype == 'image') {
		// Allow image file formats
		if($uploadedFileType != "jpg" && $uploadedFileType != "png" && $uploadedFileType != "jpeg" && $uploadedFileType != "gif") {
			$return .= "Only JPG, JPEG, PNG & GIF files are allowed. ";
			$uploadOk = 0;
		}
	}
	
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$return .= "Sorry, your file was not uploaded. ";
	// if everything is ok, try to upload file
	} else {
		if ($filetype == 'image') {
			image_fix_orientation($fileToUpload["tmp_name"]);
		}
		if (!move_uploaded_file($fileToUpload["tmp_name"], $target_file)) {
			$return .= "Sorry, there was an error uploading your file. ";
		} else {
			$return = 'success';
		}
	}
	
	return $return;
}

function uploadMinutes($target_file, $fileToUpload)
{
	$uploadOk = 1;
	$return = '';
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

	// Check if file already exists
	if (file_exists($target_file)) {
		$return .= "Sorry, file name already exists.";
		$uploadOk = 0;
	} 
	
	// Check file size
	if ($fileToUpload["size"] > 50000000) {
		$return .= "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	// Allow certain file formats
	$imageFileType = strtolower($imageFileType);
	if($imageFileType != "pdf" && $imageFileType != "PDF") {
		$return .= "Sorry, only PDF files are allowed.";
		$uploadOk = 0;
	}
	
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$return .= "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($fileToUpload["tmp_name"], $target_file)) {
			$return .= "<br>The file ". basename( $fileToUpload["name"]). " has been uploaded.";
		} else {
			$return .= "Sorry, there was an error uploading your file.";
		}
	}
	
	return $return;
}

function rand_str($length = 8, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890') 
{
	$chars_length = (strlen($chars) - 1); // Length of character list
	$string = $chars{rand(0, $chars_length)}; // Start our string
	for ($i = 1; $i < $length; $i = strlen($string)) { // Generate random string
		$r = $chars{rand(0, $chars_length)}; // Grab a random character from our list
		$string .=  $r; // Make sure the same two characters don’t appear next to each other
	}

	return $string;
}

function getRegisteredPlayers($conn, $teamId) 
{
	$registeredPlayers = 0;

	$sql = "SELECT count(*) as numPlayers FROM team_players WHERE team_id = $teamId AND is_active = 1";
	$players = mysqli_query($conn,$sql);
	if (mysqli_num_rows($players) > 0) {
        while($row = mysqli_fetch_array($players)) 
        { 
            $registeredPlayers = $row['numPlayers']; 
        }
    }

	return $registeredPlayers;
}

function getDashboardActivity($table)
{
	include 'datalogin.php';

	$now = new DateTime();
	$last14days = date_format(date_sub($now, date_interval_create_from_date_string('14 days')), 'Y-m-d');
	$today = date("Y-m-d H:i:s");

	$sql = "SELECT count(*) as activity_count FROM $table";
	if ($table == 'events') {
		$sql .= " LEFT JOIN recaps ON recaps.event_id = events.id WHERE event_date > '$last14days' AND event_date < '$today' AND recaps.id IS NULL";
	} else if ($table == 'applications') {
		$sql .= " WHERE submitted_at > '$last14days'";
	} else {
		$sql .= " WHERE created_at > '$last14days'";
	}

	$result = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_array($result)) 
	{
		return $row['activity_count'];
	}
}


?>