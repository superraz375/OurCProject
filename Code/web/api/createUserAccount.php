<?
// a function to create a new Account in the Database

	$host = "localhost";
	$username = "cProject";
	$password = "snap2ask";

	$dbConnection = mysql_connect($host, $username, $password);
	if (!$dbConnection)
	{
		die('Connection failure: ' . mysql_error());
	}


	mysql_select_db("snap2ask", $connection) or die("It could select snap2ask database. Error: " . mysql_error());

	$fname = $_POST['first_name'];
	$lname = $_POST['last_name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$date = time();
	$date =date("Y-m-d H:i:s");


	$insertUser = "INSERT INTO users(first_name, last_name, email, password, data_created) VALUES ({$fname}, {$lname}, '{$email}', '{$password}', '{$date}');";

	
	if (!mysql_query($insertUser))
	{
		die("Cannot Insert User" . mysql_error());
	}

	mysql_close($dbConnection);
	

	




?>