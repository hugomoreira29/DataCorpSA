<?php
	// Verbindung zur Datenbank und Anmeldung mit phpmyadmin
	include "include/db_init.php";
	$mysqli = new mysqli($server, $username, $password, $database);

	// Fehlermeldung anzeigen, wenn die Verbindung fehlschlägt:
	if ($mysqli->connect_errno)
	{
		echo "Failed to connect to database!";
		exit();
	}
?>
