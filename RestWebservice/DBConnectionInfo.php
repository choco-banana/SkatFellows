<?php
	
	
	function GetConnection()
	{
		$host = 'database';
		$user = 'appuser';
		$pass = 'cbb48';
		$name = 'SkatFellows';
		$charset = 'utf8'; // Zeichenkodierung
		$mysqli = mysqli_connect($host, $user, $pass, $name);		
		return $mysqli;
	}
?>