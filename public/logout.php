<?php
/* simply destroy any session */
session_start();
session_destroy();
?>
<?php require "templates/header.php"; ?>

<h1>You have been logged out</h1>

<p><a href="login">Return to login page</a>

<?php require "templates/footer.php"; ?>