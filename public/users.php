<?php
/* manage users */

session_start();
if (isset($_SESSION['user_id']) && $_SESSION['is_admin'] != null) {

    require "../config.php";
    require "../common.php";
    
	try {
	    $connection = new PDO($dsn, $username, $password, $options);
	    $sql = "SELECT * FROM users ORDER BY users.calls_made DESC, users.is_admin";
	    $statement = $connection->prepare($sql);
	    //$statement->bindParam(':location', $location, PDO::PARAM_STR);
	    $statement->execute();
	    $result = $statement->fetchAll();
	} catch(PDOException $error) {
	    echo $sql . "<br>" . $error->getMessage();
	}
	
	?>
	<?php require "templates/header.php"; ?>
	        
	<?php
	    if ($result && $statement->rowCount() > 0) { ?>
	        <h2>Manage Users</h2>
	
	        <table>
	            <thead>
	                <tr>
	                    <th>Name</th>
	                    <th>Login name</th>
	                    <th>Email Address</th>
	                    <th>Admin</th>
	                    <th>Calls Made</th>
		                <th>&nbsp;</th>
	                </tr>
	            </thead>
	            <tbody>
	        <?php foreach ($result as $row) { ?>
	            <tr>
	                <td><?php echo escape($row["firstname"]) . " " . escape($row["lastname"]); ?></td>
	                <td><?php echo escape($row["username"]); ?></td>
	                <td><?php echo escape($row["email"]); ?></td>
	                <td><center><?php if($row["is_admin"] != null) { echo "&check;"; } ?></center></td>
	                <td><?php echo number_format(escape($row["calls_made"])); ?></td>
	                <td><a class="btn small" href="user.php?id=<?php echo escape($row["id"]); ?>">Edit</a></td>
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