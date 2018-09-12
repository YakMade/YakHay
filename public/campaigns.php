<?php
/* manage campaigns */

session_start();
if (isset($_SESSION['user_id']) && $_SESSION['is_admin'] != null) {
	
    require "../config.php";
    require "../common.php";
		 
	try {
	    $connection = new PDO($dsn, $username, $password, $options);
	    $sql = "SELECT * FROM campaigns";
	    $statement = $connection->prepare($sql);
	    $statement->execute();
	    $result = $statement->fetchAll();
	} catch(PDOException $error) {
	    echo $sql . "<br>" . $error->getMessage();
	}
	
	?>
	<?php require "templates/header.php"; ?>
	        
	<?php
	    if ($result && $statement->rowCount() > 0) { ?>
	        <h2>Manage Campaigns</h2>
	
	        <table>
	            <thead>
	                <tr>
	                    <th>Campaign Name</th>
		                <th>&nbsp;</th>
	                </tr>
	            </thead>
	            <tbody>
	        <?php foreach ($result as $row) { ?>
	            <tr>
	                <td><?php echo escape($row["name"]); ?></td>
	                <td><a class="btn small" href="campaign?id=<?php echo escape($row["id"]); ?>">Edit</a></td>
	            </tr>
	        <?php } ?>
	        </tbody>
	    </table>
	    <?php } 
	?>
	
	<?php require "templates/footer.php"; ?>
	
<?php } else {
	header("Location: ". $web_root . "/login");
}
?>