<?php
/* import contacts page */
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['is_admin'] != null) {
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
	
	<h1>Import Contacts</h1>
	<?php if ($result && $statement->rowCount() > 0) { ?>
	<form id="" action="contacts.php" method="get">
	
		<p>Upload a .csv file of contacts to a specific calling campaign. A template file is located in the root directory.</p>
		<p><input type="file" required></p>
		<p>Import into calling campaign: <select name="campaign">
			<?php foreach ($result as $row) { ?>
			<option value="<?php echo escape($row["id"]); ?>"><?php echo escape($row["name"]); ?></option>
			<?php } ?>
		</select>
		</p>
		<button type="submit" class="small">Import</button>
		</form>
	
		<?php } else { ?>
		<em>No calling campaigns found!</em>
		<?php } ?>
	
	<? include "templates/footer.php"; ?>

<?php } else {
	header("Location: ". $web_root . "/login");
}
?>