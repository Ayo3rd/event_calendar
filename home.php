<?php
	require "config/config.php";
	//Create Event Class
	class event
	{
		public $name;
		public $date;
		public $flyer;
		public $num;

		public function __construct($name, $date, $flyer, $num)
		{
			$this->name = $name;
			$this->date = $date;
			$this->flyer = $flyer;
			$this->num = $num;
		}
	}
	//Weekday Array
	$weektoNum["Sun"] = 1;
	$weektoNum["Mon"] = 2;
	$weektoNum["Tue"] = 3;
	$weektoNum["Wed"] = 4;
	$weektoNum["Thu"] = 5;
	$weektoNum["Fri"] = 6;
	$weektoNum["Sat"] = 7;


	// DB Connection.
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); 
	if($mysqli->connect_errno)
	{
		echo $mysqli->connect_error;
		exit();
	}
	//Set Character set
	$mysqli->set_charset("utf8");

	//Change timezone to Pacific
	date_default_timezone_set('US/Pacific');

	//Get start from current month 
	$firstDate = date('Y-m-01');								//Change to test month. Default m
	echo $firstDate;

	//Get the name of the month
	$thisMonth = date("F", strtotime('m'));							//Change to test month. Default m

	//Get weekday of first day and number
	$firstDay = date('D', strtotime($firstDate));
	$firstDayNum = $weektoNum[$firstDay]; 
	
	//Get end from current month 
	$year = date('Y');
	$month = date('m');											//Change to test month. Default m
	$lastDate = date('Y-m-' . date('t', mktime(0, 0, 0, $month, 1, $year)));  //Change to test month. Default m
	//Get number of days in month
	$monthNum = date('d', strtotime($lastDate));
	$monthNum = (int)$monthNum;

	//Calculate Number of rows
	$totalBlocks = ($firstDayNum - 1) + $monthNum; 
	(int)$numRows = $totalBlocks / 7;
	$numRows = (int)$numRows;
	//Add row if necessary
	if($totalBlocks % 7 != 0)
	{
		$numRows++;
	}	
	//Day of month start
	$dayOfMonth = 1;

	//Query events from sql 
	$sql = "SELECT events.name, datetime, flyer, status.status AS status, org.name AS org FROM events
		JOIN status
			ON events.status_id = status.id
		JOIN org
			ON events.org_id = org.id   
		WHERE (datetime BETWEEN '". $firstDate ." 00:00:00' and '". $lastDate ." 23:59:59') AND status = 1
		ORDER BY datetime ASC;";

	$results = $mysqli->query($sql);

	//Store events in event array
	$i = 0;
	while($event = $results->fetch_assoc())
	{
		//Assign relevant variables
		$getName = $event['name'];
		$getDateTime = $event['datetime'];
		$getFlyer = $event['flyer'];
		$getNum = date('d', strtotime($getDateTime));
		//Create temporary event
		$temp  = new event($getName, $getDateTime, $getFlyer, $getNum);
		//Add temp event to event array
		$eventArray[$i] = $temp;
		$i++;
	}
	//Find most upcoming event
	$currDate = date('d');
	for($n = 0; $n < count($eventArray); $n++)
	{
		if($eventArray[$n]->num >= $currDate)
		{
			$latestFlyer = $eventArray[$n]->flyer;
			$latestName = $eventArray[$n]->name;
			break;
		}
		//Show last event if no upcoming
		$latestFlyer = $eventArray[(count($eventArray)-1)]->flyer;
		$latestName = $eventArray[(count($eventArray)-1)]->name;		
	}

	//Condition for started Cal
	$startCal = 0;

	//----- Step 4: CLose DN connection
	$mysqli->close();


		//	 '<img src="data:image/jpeg;base64,'.base64_encode( $row['flyer'] ).'"/>';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<!-- Add Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Calistoga|Black+Ops+One|Solway|Rock+Salt|Roboto&display=swap" rel="stylesheet">

	<!-- Add General CSS File-->
	<link rel="stylesheet" type="text/css" href="general.css">
	<style>			
		<!-- #flyer -->
		{
			margin-left: auto;
			margin-right: auto;
		}
		#flyer img
		{
			width: 100%;
			height:auto;
		}
		#event-title
		{
			text-align: center;
			font-family: serif;
			font-size: 120%;
		}
		.monName
		{
			font-size: 260%;
			color: black;
			text-align: center;
			font-family: 'Solway', serif;	
		}
		table
		{
			width: 100%;

		}
		.calHead
		{
			background-color: #7E160A;
			width: 14.2857142857%;
			border: 2px solid gold;
			border-radius: 3px;
			font-family: cursive;
			font-weight: bold;
			color: white;
		}
		.calBody
		{			
			border-radius: 17px;
			border: 1px solid white;
			height:120px;
			padding: 0px;
			background-color: #D3D3D3;
			word-wrap: break-word;
			/*overflow: hidden;*/
		}
		ul
		{
			padding-left: 30%;
		}
		.calBody li a
		{
			color: purple;
			font-size: 70%;
		}
		.emptyDay
		{
			background-color: gray;
		}

	</style>
</head>
<body>
	<!-- NavBar -->
	<?php $page = "home"; include 'nav.php'; ?>

	<!-- Main Body -->
    <div class="container-fluid main-header">
		<div class="row">
			<h1 class="col-12 mt-4 mb-4">Event Calendar</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->

	<div class = "container-fluid">
		<div class = "row">
			<div class = "col-sm-12 col-md-5">
				<h2 class ="monName">Event Flyer</h2>
				<div class = "row">
					<div id="flyer" class="col-12">
						<img src="<?php echo $latestFlyer?>" class=" bigFlyer img-fluid img-thumbnail" alt="<?php echo $eventArray[0]->name?>">
					</div>
					<div id="event-title" class="col-12">
						<?php echo $latestName; ?>
						<hr>
					</div>
				</div>
			</div>
			

			<div class = "col-sm-12 col-md-7">
				<h2 class ="monName"><?php echo $thisMonth;?></h2>
				<div class = "row justify-content-between" id="cal">
					<table>
						<thead>
							<td class = "calHead">SUN</td>
							<td class = "calHead">MON</td>
							<td class = "calHead">TUE</td>
							<td class = "calHead">WED</td>
							<td class = "calHead">THU</td>
							<td class = "calHead">FRI</td>
							<td class = "calHead">SAT</td>				
						</thead>
						<tbody>
							<?php for($y = 0; $y < $numRows; $y++): ?>
								<tr>	
									<?php for($x = 1; $x <= 7; $x++): ?>
										<?php if( $firstDayNum != $x && $startCal == 0) :?>
											<!-- Empty blocks Before Calendar -->
											<td class = "emptyDay calBody"></td>
										<?php else: ?>	
											<!--Fill Calendar days  -->
											<?php if($dayOfMonth <= $monthNum):?>
												<td class = "calBody">
													<?php echo $dayOfMonth; ?>
													<ul>
														<?php for($n = 0; $n < count($eventArray); $n++):?>
															<?php if($dayOfMonth == $eventArray[$n]->num):?>
																<li>
																	<a class ="eventLink" data-image="<?php echo $eventArray[$n]->flyer?>" data-name="<?php echo $eventArray[$n]->name?>" href = "#">
																		<?php
																			if(strlen($eventArray[$n]->name) < 11)
																			{
																				echo $eventArray[$n]->name;
																			}
																			else
																			{
																				echo substr($eventArray[$n]->name,0,9). "...";
																			}										
																		?>											
																	</a>
																</li>
																
															<?php endif;?>
														<?php endfor;?>		
													</ul>
												</td>
												<?php $startCal = 1;?>
												<?php $dayOfMonth++; ?>
											<?php else: ?>
												<!-- Empty blocks After Calendar -->
												<td class = "emptyDay calBody"></td>
											<?php endif; ?>
										<?php endif; ?>
									<?php endfor;?>	
								</tr>		
							<?php endfor; ?>

						</tbody>
					</table>

				</div>
			</div>			
		</div>
		<? include 'footer.php'; ?>
	</div>



	<!-- Add Bootstrap Javascript -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<!-- Add JQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<!--Add Page Java  -->
	<script>
		//Function onclicking link
		$(".eventLink").on("click", function(event) 
		{
			//Stop reloading onclick
			event.preventDefault();
			//Change Event Name
			$("#event-title").text($(this).data("name"));
			//Change Flyer image
			$(".bigFlyer").attr("src", $(this).data("image"));
		});
	</script>
</body>
</html>