<?php 
	//error_reporting(E_ALL | E_STRICT);
	//ini_set('display_errors', 1);
	define('site_url', 'https://'.$_SERVER['HTTP_HOST'].'/');
	$host = 'localhost';
	$username = 'palcura_dbuser'; 
	$password = 'ai^9#MS^q39w';
	$db_name = 'palcura_teaserbd';
	//Connect and select the database
	$db = mysqli_connect($host,$username, $password, $db_name);
	if (!$db) {
		
		echo "There is some problem with Database Connection"; 
		exit;
		
	}
