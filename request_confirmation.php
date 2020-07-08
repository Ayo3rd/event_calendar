<?php
// Check that user has filled out required fields
if (!isset($_POST['org_id'])||empty($_POST['org_id'])||!isset($_POST['ename'])||empty($_POST['ename'])||!isset($_POST['email'])||empty($_POST['email'])||!isset($_POST['date'])||empty($_POST['date'])||!isset($_POST['time'])||empty($_POST['time'])||!isset($_FILES["flyer"]["name"])||empty($_FILES["flyer"]["name"]))
{
	$error = "Please fill out all required fields.";
}
else
{
	//Condition for complete input
	$isComplete = true;
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

	//Assign Org name
	$org_id = $_POST['org_id'];

	//Assign event name
	$ename = $_POST['ename'];

	//Assign email
	$email = $_POST['email'];

	//Assign date
	$date = $_POST['date'];

	//Assign time
	$time = $_POST['time'];

	//Create datetime
	$datetime = $date. " " . $time . ":00";

	//Assign flyer
	$target_dir = "Flyers/";
	$target_file = $target_dir . basename($_FILES["flyer"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));	
	$flyer = $target_file;	
	if (move_uploaded_file($_FILES["flyer"]["tmp_name"], $target_file)) {
       // echo "The file ". basename( $_FILES["flyer"]["name"]). " has been uploaded.";
    } else 
    {
        //echo "Sorry, there was an error uploading your file.";
    }
	//Assign Status to default 0
	$status_id = 1;

	//Write SQL to insert event
	//Write prepared statement
	$sql_prepared = "INSERT INTO events(name, email, datetime, flyer, status_id, org_id)
		VALUES(?,?,?,?,?,?)";
	$statement = $mysqli->prepare($sql_prepared);
	// Match the ? placeholders with variables
	// First paramter is the data type
	$statement->bind_param("ssssii", $ename, $email, $datetime, $flyer, $status_id, $org_id);
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
	}
	$statement->close();

	//Close DB Connection
	$mysqli->close();
}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add Event Request</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<!-- Add Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Calistoga|Roboto&display=swap" rel="stylesheet">
	<!-- Add General CSS File-->
	<link rel="stylesheet" type="text/css" href="general.css">
	<style>
				
	</style>

</head>
<body>
	<!-- NavBar -->
	<?php $page = "requestCon"; include 'nav.php'; ?>

	<!-- Main Body -->
    <div class="container main-header">
		<div class="row">
			<h1 class="col-12 mt-4 mb-4">Event Request Completed</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->

	<div class="container">
	<!-- 		<div class = "row">
				Event Request was successful. Event will be added pending approval from Admin.
			</div> -->
			<div class="row mt-4">
				<div class="col-12">
					<!-- Error Message -->
					<?php if(isset($error)) :?>
						<div class="text-danger font-italic"><?php echo $error;?></div>
					<?php endif;?>

					<!-- Success Message -->
					<?php if( isset($isUpdated) && $isUpdated):?>
						<div class="text-success font-italic">Thank you. Confirmation email will be sent upon Admin approval.</div>
					<?php endif;?>

					<?php if(isset($isComplete) && $isComplete): ?>

						<table class="table table-responsive">

							<tr>
								<th class="text-right">USC Organization:</th>
								<td><?php echo $_POST['hidden_id'];?></td>
							</tr>

							<tr>
								<th class="text-right">Event Name</th>
								<td><?php echo $ename;?></td>
							</tr>

							<tr>
								<th class="text-right">Email Address:</th>
								<td><?php echo $email;?></td>
							</tr>

							<tr>
								<th class="text-right">Date:</th>
								<td><?php echo $date;?></td>
							</tr>

							<tr>
								<th class="text-right">Start Time:</th>
								<td><?php echo $time;?></td>
							</tr>

							<tr>
								<th class="text-right">Flyer:</th>
								<td><img src= "<?php echo $flyer; ?>" class="img-thumbnail"></td>
							</tr>
						</table>
					<?php endif;?>


				</div> <!-- .col -->
			</div> <!-- .r-->
			<div class="row mt-4 mb-4">
				<div class="col-12">
					<a href="request_form.php" role="button" class="btn btn-primary">New Request</a>
				</div> <!-- .col -->
			</div> <!-- .row -->
			<? include 'footer.php'; ?>
	</div>


<!-- Add Bootstrap Javascript -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
