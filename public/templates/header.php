<?php
require '../customize.php';
?><!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?php echo $bar_title ." - ". $app_title; ?></title>

	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Mono">
</head>

<body>
	<nav style="background-color: <?php echo $bar_color; ?>; color: <?php echo $bar_text; ?>;"><?php $request_uri = explode('?', $_SERVER['REQUEST_URI'], 2); if ($request_uri[0] != '/') { ?><a href="/" title="Main Menu">&larr;</a> <?php } echo $bar_title; ?></nav>
	
	<main>