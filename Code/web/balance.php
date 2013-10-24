<?php

// Start the named session
session_name('loginSession');
session_start();

// Allow the included files to be executed
define('inc_file', TRUE);

if (!isset($_SESSION['user_id'])) {
	// The user is not logged in
	header('Location: index.php');
	exit;
}

// Require the functions file
require_once('functions.php');

$responseObj = getUserInfo(true);


if (isset($_POST['submit']) && $_POST['submit'] == 'Withdraw SnapCash') {

	// WITHDRAW FUNDS
	// Simulate withdrawing money by settings the user's balance to 0
	
	// UPDATE THE USER INFO VIA REST API 
	$request = array(
			'last_name' => $responseObj['last_name'],
			'first_name' => $responseObj['first_name'],
			'balance' => 0,
			'is_tutor' => $responseObj['is_tutor'],
			'rating' => $responseObj['rating'],
			'preferred_category_id' => 1
		);



	//cURL used to collect login information
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/users/' . $responseObj['id']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
	$updateResponse = curl_exec($ch);
	curl_close($ch);

	
	//sent to the be decoded
	$updateResponseObj = json_decode($updateResponse,true);

	//depending on the response we either ask for different credentials or log the user in
	if($updateResponseObj['success'])
	{
		header('Location: balance.php');
	} else {
		die($updateResponseObj['reason']);
	}
	
}



// FOR TESTING ONLY
// ADD FUNCTIONALITY TO ADD 10 SNAPCASH
if (isset($_POST['submit']) && $_POST['submit'] == 'Add 10 SnapCash (FOR TESTING ONLY)') {

	$request = array(
			'last_name' => $responseObj['last_name'],
			'first_name' => $responseObj['first_name'],
			'balance' => $responseObj['balance'] + 10,
			'is_tutor' => $responseObj['is_tutor'],
			'rating' => $responseObj['rating'],
			'preferred_category_id' => 1
		);



	//cURL used to collect login information
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/users/' . $responseObj['id']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
	$updateResponse = curl_exec($ch);
	curl_close($ch);

	
	//sent to the be decoded
	$updateResponseObj = json_decode($updateResponse,true);

	//depending on the response we either ask for different credentials or log the user in
	if($updateResponseObj['success'])
	{
		header('Location: balance.php');
	} else {
		die($updateResponseObj['reason']);
	}
	
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Balance</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<?php include('header.php') ?>
	
	<div id="container">

		<div id="linksNav">
			<ul>
				<li><a href="browse.php" >Browse</a></li>
				<li class="selected"><a href="balance.php" >Balance</a></li>
				<li><a href="profile.php" >Profile</a></li>
			</ul>
		</div>
	
		
	
		<div id="mainContent">
			<!--POPULATE BALANCE INFO HERE-->
			<!--Validate input values-->
<?php

// Echo the information using sprintf
// Escape special html characters to enhance XSS security
echo sprintf("<h3>Available SnapCash: %s</h3>", htmlspecialchars($responseObj['balance']));

?>

		
		<form id="withdrawSnapCashForm" action="#" method="post">
			<input type="submit" name="submit" value="Withdraw SnapCash">
		</form>
		
		<form action="#" method="post">
			<input type="submit" name="submit" value="Add 10 SnapCash (FOR TESTING ONLY)">
		</form>
    
		</div>
	</div>
	


	<?php include('footer.php') ?>

</body>
</html>
