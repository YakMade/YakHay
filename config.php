<?php
/* timezone settings - change to local timezone per http://php.net/manual/en/timezones.php */
date_default_timezone_set('America/Los_Angeles');

/* basic server settings */
$web_root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

/* your local database settings */

$host       = "localhost";
$username   = "yakhay";
$password   = "";
$dbname     = "yakhay";

/* do not change settings below unless you know what's up */
$dsn        = "mysql:host=$host;dbname=$dbname";
$options    = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
              );