<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CFH: Order Confirmation</title>
<link href="blueLayout.css" rel="stylesheet" type="text/css" />
<style>
	h4{
		font-size: 1.4em; 
		font-weight: bold; 	
		display: block; 
		padding: 0; 
		margin: 5px 0 10px 0;
	}
	h5{
		font-size: 1.0em; 
		font-weight: bold; 	
		display: block; 
	}
	h6{
		font-size: 1.0em; 
		font-weight: bold; 	
		display: inline;  
	}
</style>
</head>

<body>

<div id="container">
	<div id="header">
	</div>
        
	<div id="leftColumn">
    	<?php include 'leftcolumn.php'; ?>        
  </div>

	<div id="mainContentContainer">
    <div class="maincontent">    	
        <h1>Cancellation</h1> 
        
        <?php 
	        ini_set('display_errors',1);
	        ini_set('display_startup_errors',1);
	        error_reporting(-1);   

	        function test_input($data) {
	        	$data = trim($data);
	        	$data = stripslashes($data);
	        	$data = htmlspecialchars($data);
	        	return $data;
	        }
        
        //************************************************************************
        //global variables
        //mySQL server
        $server = "sql.mit.edu";
        $user = "cfh";
        $pass = "lom52day";
        $dbase = "cfh+home";
        ?>
        	        
        <!--     include the file with all relevant variables  -->
            <?php include 'orderforminclude.php'; ?>
                
            <?php
        //current database table
        $dbtable = $dbtableGlobal;
                
		//unique identifier for each order 
        $identifier = test_input($_GET["id"]);
        //make sure id is of the correct format 
        settype($identifier, "float"); 
        
        //**************************************************************************
        

        //enter into database that order was cancelled  
        // Create connection
        $con=mysqli_connect($server, $user, $pass, $dbase);
        // Check connection
        if (mysqli_connect_errno()) 
		{
        	echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        else
        {
        	//enter as payment status into database
        	$paymentStatus = "Cancelled"; 
        	$sql="UPDATE " . $dbtable . " SET Paid='$paymentStatus' WHERE identifier='$identifier'"; 
        	if (!mysqli_query($con,$sql)) 
			{
        		die('Error: ' . mysqli_error($con));
        	}
        	if (mysqli_affected_rows($con) > 0) 
        	{
        		echo "<h5>Your order has been cancelled. </h5>";
        	} 
        	else 
        	{
        		echo "ERROR: No matching order in database. "; 
        	}
        }

        
        ?>               
        
    </div>
	</div>

  <div id="links">
  	<?php include 'links.php'; ?>
	
    </div>
    
  <div id="footer">
    	<?php include 'footer.php'; ?>   
    </div>
</div>

</body>
</html>
