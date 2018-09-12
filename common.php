<?php
/* escape html output */
function escape($html) {
	return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

/* format US phone numbers */
function format_phone_number($num) {
	$phone = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', 
	'($1) $2-$3'." \n", $num);
	return $phone;
}

/* figure out age based on DOB */
function get_age($date) {
	return intval(substr(date('Ymd') - date('Ymd', strtotime($date)), 0, -4));
}