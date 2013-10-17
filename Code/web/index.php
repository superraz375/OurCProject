<?php

// Allow the included files to be executed
define('inc_file', TRUE);

// Start the named session
session_name('loginSession');

// Making the cookie live for 2 weeks
session_set_cookie_params(2*7*24*60*60);

// Start the session
session_start();


// Require the functions file
require_once('functions.php');


if (isset($_POST['submit'])) {

	if($_POST['submit'] == 'Log in')
	{
		// Checking whether the Login form has been submitted

		$err = array();
		// Will hold our errors

		$request = array(
			'account_identifier' => $_POST['email'],
			'password' => $_POST['password'],
			'authentication_mode' => 'custom'
		);

		//cURL used to collect login information
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/login');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		$response = curl_exec($ch);
		curl_close($ch);

		//sent to the be decoded
		$responseObj = json_decode($response,true);

		//depending on the response we either ask for different credentials or log the user in
		if($responseObj['success'])
		{
			$_SESSION['user_id'] = $responseObj['user_id'];

			// Stay logged in
			setcookie('rememberCookie',true);
		}
		else
		{
			$err[]='Invalid email/password.';
		}

		if($err)
		{
			// Save the error messages in the session
			$_SESSION['msg']['login-err'] = implode('<br />',$err);
		}

		header("Location: index.php");
		exit;

	} else if($_POST['submit'] == 'Sign Up') {
	
		// Will hold our errors
		$err = array();
		

		// preferred_category_id is TEMPORARY
		$request = array(
			'email' => $_POST['email'],
			'password' => $_POST['password'],
			'first_name' => $_POST['first_name'],
			'last_name' => $_POST['last_name'],
			'is_tutor' => true,
			'authentication_mode' => 'custom',
			'register_or_login' => false,
			'preferred_category_id' => 1
		);

		//cURL used to collect login information
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/users');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		$response = curl_exec($ch);
		curl_close($ch);

		//sent to the be decoded
		$responseObj = json_decode($response,true);

		//depending on the response we either ask for different credentials or log the user in
		if($responseObj['success'])
		{
			$_SESSION['user_id'] = $responseObj['user_id'];			
			setcookie('rememberCookie',true);
		}
		else
		{
			$err[]='Could not create account.';
		}

		if($err)
		{
			// Save the error messages in the session
			$_SESSION['msg']['register-err'] = implode('<br />',$err);
		}
		
		header("Location: index.php");
		exit;
	}
}

if (isset($_SESSION['user_id']))
{

	// Refresh the session information (name, balanace)
	getUserInfo(true);
	
	// User is already logged in.
	// Redirect to the browse questions page
	header('Location: browse.php');
	exit;
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/validateInput.js" type="text/javascript"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js"></script>

	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<header>

		<h1><img src="res/temp_logo.png" alt="Snap-2-Ask Logo" id="logo"/></h1>

		<!-- content -->
		<div id="slogan">
				<h2>Snap. Ask. Done.</h2>
		</div>

	</header>

	<div id="mainContainer">

		<div class="divider"></div>

		<div id="loginContainer" >

			<h2>Login</h2>

<?php

if(isset($_SESSION['msg']['login-err']))
{
	// Display the login error message
	echo '<div class="error">'.$_SESSION['msg']['login-err'].'</div>';
	unset($_SESSION['msg']['login-err']);
}

?>
				<!-- Login Form in html that sends email and pass to corresponding php script -->
			<form id="loginForm" method="POST" action="index.php">
				<input type="email" name="email" placeholder="Email" title="Please enter a valid email" required autocomplete="on" />
				<input type="password" name="password" placeholder="Password" title="Please enter a password" required autocomplete="on" />
				<input  class="button" type="submit" name="submit" value="Log in" />
			</form>
		</div>



		<div class="divider"></div>

		<div id="registerContainer">

		<h2>Create an Account</h2>

<?php

if(isset($_SESSION['msg']['register-err']))
{
	// Display the login error message
	echo '<div class="error">'.$_SESSION['msg']['register-err'].'</div>';
	unset($_SESSION['msg']['register-err']);
}

?>
			<form id="registerForm" method="POST" action="index.php">

				<input type="text" name="first_name" placeholder="First Name" title="Please enter your first name" required autocomplete="on" />
			    <input type="text" name="last_name" placeholder="Last Name" title="Please enter your last name" required autocomplete="on" />

				<input type="email" name="email" placeholder="Email" required autocomplete="on" title="Please enter a valid email address" />
				<input type="password" name="password" id="password" placeholder="Password" title="Password must be at least 8 characters" required autocomplete="on" />
				<input type="password" name="confirm_password" placeholder="Confirm Password" title="Password does not match" required autocomplete="on" />


				<input class="button" type="submit" name="submit" value="Sign Up" />

			</form>
		</div>
	</div>
</body>
</html>
