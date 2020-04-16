<?php

// Declaring variables to prevent errors
$fname = "";
$lname = "";
$em = "";
$em2 = "";
$password = "";
$password2 = "";
$date = "";
$error_array = array();

if(isset($_POST['register_button'])){
	// Registration form values

	//First Name
	$fname = strip_tags($_POST['reg_fname']); // Remove HTML Tags
	$fname = str_replace(' ', '', $fname); // Remove spaces from the name
	$fname = ucfirst(strtolower($fname)); // Convert all letters to lowercase and capitalizes the first letter
	$_SESSION['reg_fname'] = $fname; //Stores first name in the session variable

	//Last Name
	$lname = strip_tags($_POST['reg_lname']); // Remove HTML Tags
	$lname = str_replace(' ', '', $lname); // Remove spaces from the name
	$lname = ucfirst(strtolower($lname)); // Convert all letters to lowercase and capitalizes the first letter
	$_SESSION['reg_lname'] = $lname;

	//Email
	$em = strip_tags($_POST['reg_email']); // Remove HTML Tags
	$em = str_replace(' ', '', $em); // Remove spaces from the name
	$em = ucfirst(strtolower($em)); // Convert all letters to lowercase and capitalizes the first letter
	$_SESSION['reg_email'] = $em;

	//Email 2
	$em2 = strip_tags($_POST['reg_email2']); // Remove HTML Tags
	$em2 = str_replace(' ', '', $em2); // Remove spaces from the name
	$em2 = ucfirst(strtolower($em2)); // Convert all letters to lowercase and capitalizes the first letter
	$_SESSION['reg_email2'] = $em2;


	//Password
	$password = strip_tags($_POST['reg_password']); // Remove HTML Tags

	//Password 2
	$password2 = strip_tags($_POST['reg_password2']); // Remove HTML Tags
	
	$date = date("Y-m-d"); // Current date

	if($em == $em2){
		//Check if email is in valid format
		if(filter_var($em, FILTER_VALIDATE_EMAIL)){
			$em = filter_var($em, FILTER_VALIDATE_EMAIL);

			//Check if email already exists
			$e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

			//Count number of rows returned
			$num_rows = mysqli_num_rows($e_check);

			if($num_rows>0){
				array_push($error_array, "Email already in use<br>");
			}

		}else{
			array_push($error_array, "Invalid Format<br>");
		}
	}else{
		array_push($error_array, "Emails don't match<br>");
	}

	if(strlen($fname)>25 || strlen($fname) < 2){
		array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
	}

	if(strlen($lname)>25 || strlen($lname) < 2){
		array_push($error_array, "Your last name must be between 2 and 25 characters<br>");
	}

	if($password != $password2){
		array_push($error_array, "Your passwords do not match!<br>");
	}else{
		if(preg_match('/[^A-Za-z0-9]/', $password)){
			array_push($error_array, "Your password can contain only English characters or numbers<br>");
		}
	}

	if(strlen($password) > 30 || strlen($password) < 5){
		array_push($error_array, "Your password must be between 5 and 30 characters<br>");
	}

	if(empty($error_array)){
		$password = md5($password); // Encrypts the password before storing in the database

		// Generate username by concatenating first name and last name
		$username = strtolower($fname . "_" . $lname);
		$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

		$i = 0;
		//if username exists, add number to username
		while(mysqli_num_rows($check_username_query)!=0){
			$i++;
			$username = $username . "_" . $i;
			$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

		}
	}

	// Profile picture assignment
	$rand = rand(1,2); // Random number bw 1 and 2

	if($rand==1)
		$profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
	else if($rand==2)
		$profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";

	$query = mysqli_query($con, "INSERT INTO users VALUES (NULL, '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");

	array_push($error_array, "<span style='color: #14C800'> You're all set! Go ahead and login </span><br>");

	//Clear session variables
	$_SESSION['reg_fname']= "";
	$_SESSION['reg_lname']= "";
	$_SESSION['reg_email']= "";
	$_SESSION['reg_email2']= "";


}

?>