<?php 
	require "config/config.php";
	// DB Connection.
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); 
	if($mysqli->connect_errno)
	{
		echo $mysqli->connect_error;
		exit();
	}
	//Set Character set
	$mysqli->set_charset("utf8");
	// Get the search term using $_GET
	$thisId = $_GET["id"];

	//Write sql statement
	$sql_prepared = "DELETE FROM events WHERE events.id = ?;";
	$statement = $mysqli->prepare($sql_prepared);
	// Match the ? placeholders with variables
	// First paramter is the data type
	$statement->bind_param("i", $thisId);
	// Execute the prepared statement
	$executed = $statement->execute();
	// Returns false if error w/ executing the statement
	if(!$executed) {
		echo $mysqli->error;
	}
	//var_dump($statement->affected_rows);
	// If succesful, $statement->affected_rows will return 1
	if($statement->affected_rows == 1) 
	{
		$isUpdated = true;
		echo "It was Deleted";

	}
	$statement->close();


	//Close DB
	$mysqli->close();
?>