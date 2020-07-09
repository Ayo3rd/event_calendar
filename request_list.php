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

	//Query events from sql 
	$sql = "SELECT events.id, events.name, datetime, flyer, status.status AS status, org.name AS org FROM events
		JOIN status
			ON events.status_id = status.id
		JOIN org
			ON events.org_id = org.id   
		WHERE  status = 0
		ORDER BY datetime ASC;";

	$results = $mysqli->query($sql);

	//Check that there are pending requests
	if($mysqli->affected_rows > 0) 
	{
		$noReturn = false;
	}
	else
	{
		$noReturn = true;
	}

	//Close DB
	$mysqli->close();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Request List Page</title>
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
	<?php $page = "admin"; include 'nav.php'; ?>

	<!-- Main Body -->
    <div class="container main-header">
		<div class="row">
			<h1 class="col-12 mt-4 mb-4">Request List</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->

	<div class="container">
		<?php if($noReturn):?>
			<div class = "row">
				There are no pending event requests at this time :)
			</div>
		<?php endif;?>
		<div class="row mt-4">
			<div class="col-12">

				<table class="table table-responsive">
					<?php while($row = $results->fetch_assoc()): ?>
						<tr>
							<td>
								<a data-id = "<?php echo $row['id'];?>" href="#" class="btn btn-outline-success approve-btn">Approve
								</a>									
							</td>
							<td>
								<a data-id = "<?php echo $row['id'];?>" href="#" class="btn btn-outline-danger delete-btn">
									Delete
								</a>
							</td>

							<td>
								<img src= "<?php echo $row['flyer']; ?>" class="img-thumbnail">				
							</td>
						</tr>
					<?php endwhile; ?>
				</table>

			</div> <!-- .col -->
		</div> <!-- .r-->

		<!-- Logout Button -->
		<!-- <div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="#" role="button" class="btn btn-primary">Logout</a>
			</div> .col -->
		</div> <!-- .row --> 
		<? include 'footer.php'; ?>
	</div> <!-- .container -->



		<!-- Add Bootstrap Javascript -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<!-- Add JQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

	<script>
	//Delete functionality on click
		$(".delete-btn").on("click", function(event) 
		{
			//Stop reloading onclick
			event.preventDefault();
			// Show a popup here to confirm
			let con = confirm("Are you sure you want to delete this event request?");
			if(con)
			{				
				console.log ("Delete confirmed");
				//Remove tr from html
				$(this).fadeOutAndRemove(800);
				//Remove on Database
				// Grab current event id
				let currentId = $(this).data("id");
				//Send to backend to approve
				ajaxGet("delete.php?id=" + currentId, function(results) 
				{	
					// This function gets run when backend.php returns something
					console.log(results);
				});	
			}
			else
			{
				console.log ("Delete aborted");

			}
		});

	//Approve functionality on click
		$(".approve-btn").on("click", function(event)
		{
			//Stop reloading onclick
			event.preventDefault();
			//Remove tr from html
			console.log ("clicked");
			$(this).fadeOutAndRemove(800);

			//Change status on Database
			// Grab current event id
			let currentId = $(this).data("id");
			//Send to backend to approve
			ajaxGet("approve.php?id=" + currentId, function(results) 
			{	
				// This function gets run when backend.php returns something
				console.log(results);
			});	
		});

	//Used Functions
		//GET AJAX function
		function ajaxGet(endpointUrl, returnFunction){
			var xhr = new XMLHttpRequest();
			xhr.open('GET', endpointUrl, true);
			xhr.onreadystatechange = function(){
				if (xhr.readyState == XMLHttpRequest.DONE) {
					if (xhr.status == 200) {
						returnFunction( xhr.responseText );
						//console.log("It sent");
					} else {
						alert('AJAX Error.');
						console.log(xhr.status);
					}
				}
			}
			xhr.send();
		};
		//Fade plug in 
		jQuery.fn.fadeOutAndRemove = function(speed)
		{
		    $(this).fadeOut(speed,function(){
		        //$(this).remove();
		        $(this).parents('tr').remove();
		    })
		}
	</script>
</body>
</html>