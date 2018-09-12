<?php
/* lock voter record page */
session_start();
if (isset($_SESSION['user_id'])) {
	/* lock voter record for 30 seconds - iframe hack loaded from call.php - reloads every 25 seconds */
	$address = $_SERVER['REQUEST_URI'];
	header("Refresh: 25; URL=" . $address . "");
	
	if(isset($_GET['c'])) {
		
		/* use requested contact ID */
		$c = $_GET['c'];
		
		try {
			require "../config.php";
			require "../common.php";
			$connection = new PDO($dsn, $username, $password, $options);
			$sql = "UPDATE contacts SET last_accessed = ". strtotime("+ 30 seconds") ." WHERE contacts.id = ". $c .";";
			$statement = $connection->prepare($sql)->execute();
		} catch(PDOException $error) {
			echo $sql . "<br>" . $error->getMessage();
		}
		
		echo "Locking #" . $c . " to " . strtotime("+ 30 seconds");
	}
}
?>