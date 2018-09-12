<?php
/* add a caller / user to the database */

session_start();
if (isset($_SESSION['user_id']) && $_SESSION['is_admin'] != null) {
	
    require "../config.php";
    require "../common.php";
	
	if (isset($_POST['submit'])) {
	    try  {
	        $connection = new PDO($dsn, $username, $password, $options);
	        
	        $new_user = array(
	            "firstname" => $_POST['firstname'],
	            "lastname"  => $_POST['lastname'],
	            "username"  => $_POST['username'],
	            "email"     => $_POST['email'],
	            "password"  => $_POST['password'],
	            "is_admin"     => $_POST['is_admin']
	        );
	        $sql = sprintf(
	                "INSERT INTO %s (%s) values (%s)",
	                "users",
	                implode(", ", array_keys($new_user)),
	                ":" . implode(", :", array_keys($new_user))
	        );
	        
	        $statement = $connection->prepare($sql);
	        $statement->execute($new_user);
	    } catch(PDOException $error) {
	        echo $sql . "<br>" . $error->getMessage();
	    }
	}
	?>
	
	<?php require "templates/header.php"; ?>
	
	<?php if (isset($_POST['submit']) && $statement) { ?>
	    <h3>User '<em><?php echo $_POST['username']; ?></em>' successfully added.</h3>
	<?php } ?>
	
	<h2>Add a user</h2>
	
	<form method="post">
		<input type="text" name="firstname" id="firstname" placeholder="First Name" required>
		<input type="text" name="lastname" id="lastname" placeholder="Last Name" required>
		<input type="text" name="username" id="username" placeholder="Login Name" required>
		<input type="text" name="email" id="email" placeholder="Email Address" required>
		<input type="password" name="password" id="password" placeholder="Password" required>
	    
	    <input type="checkbox" name="is_admin" id="isadmin" value="1" class="inline"><label for="isadmin">Grant administrator rights</label>
	
	    <p><input type="submit" name="submit" value="Add User"></p>
	</form>
	
	
	<?php require "templates/footer.php"; ?>
	
<?php } else {
	header("Location: ". $web_root . "/login");
}
?>