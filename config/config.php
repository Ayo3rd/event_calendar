<?php
	// Define constants - constants cannot be changed after they have been declared.
	// define("DB_HOST", "db4free.net");
	// define("DB_USER", "realope");
	// define("DB_PASS", "january3rd");
	// define("DB_NAME", "calendar_db");

	$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
	$cleardb_server = $cleardb_url["host"];
	$cleardb_username = $cleardb_url["user"];
	$cleardb_password = $cleardb_url["pass"];
	$cleardb_db = substr($cleardb_url["path"],1);

	define("DB_HOST", $cleardb_server);
	define("DB_USER", $cleardb_username);
	define("DB_PASS", $cleardb_password);
	define("DB_NAME", $cleardb_db);




?>

