<?php
header('Content-Type: text/html');
date_default_timezone_set('America/Halifax');

$currentDate = date("l, F jS");
$currentTime = date("g:ia");

echo "It's $currentDate.<br>Our time is $currentTime.";
?>
