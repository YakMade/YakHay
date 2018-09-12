<?php
session_start();
if (isset($_SESSION['user_id'])) {
	require "../config.php";
	require "../common.php";
	
	/* db connection */ 
	try {
	    $connection = new PDO($dsn, $username, $password, $options);
	    $sql = "SELECT * FROM campaigns";
	    $statement = $connection->prepare($sql);
	    $statement->bindParam(':id', $id, PDO::PARAM_STR);
	    $statement->execute();
	    $result = $statement->fetchAll();
	} catch(PDOException $error) {
	    echo $sql . "<br>" . $error->getMessage();
	}
	
	?>
	<?php
	include "templates/header.php";
	?>
	
	<?php
		/* admin menu */
		if($_SESSION['is_admin'] != null) {
	?>
	<h1>Administration Menu</h1>
	<ul>
		<li><a href="adduser"><strong>Add a user</strong></a></li>
		<li><a href="users"><strong>Manage users</strong></a></li>
		<li><a href="addcampaign"><strong>Add a campaign</strong></a></li>
		<li><a href="campaigns"><strong>Manage campaigns</strong></a></li>
		<li><a href="contacts"><strong>Import contacts</strong></a></li>
	</ul>
	
	<hr>
	
	<?php } ?>
		
	<h1>Main Menu</h1>
	<ul>
	<?php
	if (isset($_SESSION['user_id'])) {
		if ($result && $statement->rowCount() > 0) { ?>
		<li>Calling campaign: <form id="campaign" action="call" method="get"><select name="campaign">
			<?php foreach ($result as $row) { ?>
			<option value="<?php echo escape($row["id"]); ?>"><?php echo escape($row["name"]); ?></option>
			<?php } ?>
		</select>
		<button type="submit" class="small">Start Calling</button>
		</form>
		</li>
		<?php } else { ?>
		<li>Calling campaign: <em>No calling campaigns found!</em></li>
		<?php } ?>
		<li><a href="logout"><strong>Logout</strong></a>
		<?php } else { ?>
		<li><a href="login"><strong>Login</strong></a>
		<?php } ?>
	</ul>
	
	<? include "templates/footer.php"; ?>
	
<?php } else {
	header("Location: ". $web_root . "/login");
}
?>