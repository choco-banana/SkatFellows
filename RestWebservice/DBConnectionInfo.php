<?php
	
	
	function GetConnection()
	{
		$host = 'database';
		$user = 'appuser';
		$pass = 'cbb48';
		$name = 'SkatFellows';
		$charset = 'utf8'; // Zeichenkodierung
		$mysqli = new mysqli($host, $user, $pass, $name);		
		return $mysqli;
	}
?>