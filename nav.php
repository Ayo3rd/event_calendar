<!--NavBar  -->
	<nav class="navbar fixed-top navbar-expand-md navbar-dark">
		<div class = "container">
	      <a class="navbar-brand" href="home.php">
	      	<img src="Flyers/logo.png">
	      </a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="navbar-toggler-icon"></span>
	      </button>

	      <div class="collapse navbar-collapse" id="navbarsExample04">
	        <ul class="navbar-nav nav justify-content-end">
	          <li class="nav-item <?php if($page == "home"){echo "active"; } ?>">	          	
	            <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
	            
	          </li>
	          <li class="nav-item <?php if($page == "request"){echo "active"; } ?>">
	            <a class="nav-link" href="request_form.php">Event Request</a>
	          </li>
	          <li class="nav-item <?php if($page == "admin"){echo "active"; } ?>">
	            <a class="nav-link" href="request_list.php">Request List</a>
	          </li>
	        </ul>        
	      </div>
	    </div>
    </nav>