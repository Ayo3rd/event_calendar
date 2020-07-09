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

	//USC Org Name
	$sql_org = "SELECT * from org;";
	$results_org = $mysqli->query($sql_org);
	if ( $results_org == false ) {
		echo $mysqli->error;
		exit();
	}
	// Close DB Connection
	$mysqli->close();
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
	<?php $page = "request"; include 'nav.php'; ?>

	<!-- Main Body -->
	<div class="container main-header">
		<div class="row">
			<h1 class="col-12 mt-4 mb-4">Add an Event to Calendar</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->
	<div class="container">

		<form action="request_confirmation.php" method="POST" accept="image/gif,image/jpeg"  enctype="multipart/form-data">

			<div class="form-group row">
				<label for="org-id" class="col-sm-3 col-form-label text-sm-right">
					USC Organization: <span class="text-danger">*</span>
				</label>
				<div class="col-sm-9">
					<select name="org_id" id="org-id" class="form-control">
						<option value="" selected disabled>-- Select One --</option>
						<?php while($row = $results_org->fetch_assoc()):?>
							<?php $thisName = $row['name'];?>
							<option value = "<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
						<?php endwhile; ?>
					</select>
				</div>
			</div> <!-- .form-group -->

			<!-- Hidden passing of ORG NAME -->
			<div class="form-group row" hidden>

				<label for="org-name" class="col-sm-3 col-form-label text-sm-right"></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="hidden-id" name="hidden_id" value = "<?php echo $thisName;?>">
				</div>
			</div>


			<div class="form-group row">
				<label for="event-id" class="col-sm-3 col-form-label text-sm-right">
					Event Name: <span class="text-danger">*</span>
				</label>
				<div class="col-sm-9">
					<input type="text" name="ename" id="event-id" class="form-control">
				</div>
			</div> <!-- .form-group -->

			<div class="form-group row">
				<label for="email-id" class="col-sm-3 col-form-label text-sm-right">
					Email Address: <span class="text-danger">*</span>
				</label>
				<div class="col-sm-9">
					<input type="text" name="email" id="email-id" class="form-control">
				</div>
			</div> <!-- .form-group -->


			<div class="form-group row">
				<label for="date-id" class="col-sm-3 col-form-label text-sm-right">
					Date: <span class="text-danger">*</span>
				</label>
				<div class="col-sm-9">
					<input type="date" name="date" id="date-id" class="form-control">
				</div>
			</div> <!-- .form-group -->


			<div class="form-group row">
				<label for="time-id" class="col-sm-3 col-form-label text-sm-right">
					Start Time: <span class="text-danger">*</span>
				</label>
				<div class="col-sm-9">
					<input type="time" name="time" id="time-id" class="form-control">
				</div>
			</div> <!-- .form-group -->

			<div class="form-group row">
				<label for="flyer-id" class="col-sm-3 col-form-label text-sm-right">
					Upload Flyer: <span class="text-danger">*</span>
				</label>
				<div class="col-sm-9">
					<input type="file" name="flyer" id="flyer-id" class="form-control">
				</div>

			</div> <!-- .form-group -->

			<div class="form-group row">
				<div class="ml-auto col-sm-9">
					<span class="text-danger font-italic">* Required</span>
				</div>
			</div> <!-- .form-group -->

			<div class="form-group row">
				<div class="col-sm-3"></div>
				<div class="col-sm-9 mt-2">
					<button type="submit" class="btn btn-primary" value="Submit">Submit</button>
					<button type="reset" class="btn btn-light">Reset</button>
				</div>
			</div> <!-- .form-group -->
		</form>
		<? include 'footer.php'; ?>
	</div> <!-- .container -->



<!-- Add Bootstrap Javascript -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<!-- Add JQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

	<script>
		//Input validation
		 document.querySelector('form').oninput = function(e)
		 {
		 	e.srcElement.classList.remove('is-invalid');
		 }
		 document.querySelector('form').onsubmit = function(e)
		 {
		 	for (var i = 0; i < e.target.length - 1; i++) 
		 	{
		 		if (e.target[i].value.length == 0) 
		 		{
		 			e.target[i].classList.add('is-invalid');
		 		} else {
		 			e.target[i].classList.remove('is-invalid');
		 		}
		 	}
		 	return ( !(document.querySelectorAll('.is-invalid').length > 0) );
		 }
	</script>
</body>
</html>


					
							