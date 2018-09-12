<?php
require "../config.php";
require "../common.php";
include "templates/header.php";
?>

<?php
session_start();

if (!empty($_POST)) {
	if (isset($_POST['username']) && isset($_POST['password'])) {
		try {
			$connection = new PDO($dsn, $username, $password, $options);
			$sql = "SELECT * FROM `users` WHERE `username` = '" . escape($_POST['username']) . "' LIMIT 1;";
			$statement = $connection->prepare($sql);
			$statement->bindParam('s', $_POST['username'], PDO::PARAM_STR);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_OBJ);
			//$the_user = json_decode(json_encode($result, JSON_FORCE_OBJECT));

			if ($_POST['password'] == $result->password) {
				$_SESSION['user_id'] = $result->id;
				$_SESSION['is_admin'] = $result->is_admin;
			} else {
				echo "<p>Invalid password <i>". $_POST['password'] . "</i>.</p>";
			}

		} catch(PDOException $error) {
			echo $sql . "<br>" . $error->getMessage();
		}
	}
}
?>

<?php if (!isset($_SESSION['user_id'])) { ?>
<h1>Log in</h1>

<form action="" method="post">
	<input type="text" name="username" placeholder="Username" required>
	<input type="password" name="password" placeholder="Password" required>
	<input type="submit" value="Login">
</form>

<p>Forgot your password, or need to get set up? Contact your campaign manager.</p>
<?php } else {
	header("Location: " . $web_root);
}

include "templates/footer.php";
?>