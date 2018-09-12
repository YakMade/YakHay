<?php
/* add a calling campaign to the database */

session_start();
if (isset($_SESSION['user_id']) && $_SESSION['is_admin'] != null) {
	
	require "../config.php";
	require "../common.php";
	
	if (isset($_POST['submit'])) {
	    try  {
	        $connection = new PDO($dsn, $username, $password, $options);
	        
	        $new_campaign = array(
	            "name" => $_POST['name'],
	            "script"  => $_POST['script']
	        );
	        $sql = sprintf(
	                "INSERT INTO %s (%s) values (%s)",
	                "campaigns",
	                implode(", ", array_keys($new_campaign)),
	                ":" . implode(", :", array_keys($new_campaign))
	        );
	        
	        $statement = $connection->prepare($sql);
	        $statement->execute($new_campaign);
	    } catch(PDOException $error) {
	        echo $sql . "<br>" . $error->getMessage();
	    }
	}
	?>
	
	<?php require "templates/header.php"; ?>
	
	<?php if (isset($_POST['submit']) && $statement) { ?>
	    <h3>Campaign '<em><?php echo $_POST['name']; ?></em>' successfully added.</h3>
	<?php } ?>
	
	<h2>Add a campaign</h2>
	
	<form method="post">
		<input type="text" name="name" id="name" placeholder="Campaign Name" required>
		<textarea name="script" id="script" placeholder="Sample calling script" rows="10" cols="10"></textarea>
	
	    <p><input type="submit" name="submit" value="Add Campaign"></p>
	</form>
	
	
	<?php require "templates/footer.php"; ?>


<?php } else {
	header("Location: ". $web_root . "/login");
}
?>