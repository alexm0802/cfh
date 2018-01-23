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
        <h1>Confirmation</h1> 
        
        <?php 
	        /* ini_set('display_errors',1);
	        ini_set('display_startup_errors',1);
	        error_reporting(-1); */   

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
        
<!--         include the file with all relevant variables  -->
        <?php include 'orderforminclude.php'; ?>
        
        <?php 
        //current database table
        $dbtable = $dbtableGlobal;
        //current date
        $currDate = $currDateGlobal;
        
        //column labels in the orders database for the special flavors
        $spec1StrDB="spec1";
        $spec2StrDB="spec2";
        $spec3StrDB="spec3";
        $spec4StrDB="spec4";
                
        //how many volunteer hours is equivalent to $1
        $hrPerDollar = 5/5.5;
        
		//unique identifier for each order 
        $identifier = test_input($_GET["id"]);
        //make sure id is of the correct format 
        settype($identifier, "float"); 
        
        //**************************************************************************
        
//connect to database to get the special flavors
        // Create connection
        $con=mysqli_connect($server,$user,$pass,$dbase);
        
        // Check connection
        if (mysqli_connect_errno()) {
        	echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        else
        {
        	//get all flavors for the week
        	$sql="SELECT * FROM flavors WHERE date='$currDate'";
        	$result = mysqli_query($con,$sql);
        		
        	if (!mysqli_query($con,$sql)) {
        		die('Error: ' . mysqli_error($con));
        	}
        
        	$result = mysqli_fetch_array($result);
        	//if order info is blank
        	if ($result == null)
        	{
        		echo "ERROR: Could not get special flavors from database.";
        	}
        	else
        	{
        		//get the names of special flavors from the database
        		$spec1Str = $result['spec1'];
        		$spec2Str = $result['spec2'];
        		$spec3Str = $result['spec3'];
        		$spec4Str = $result['spec4'];
        	}
        
        	//close database connection
        	mysqli_close($con);
        }
        
        	//get confirmation info from paypal 
	        $pp_hostname = "www.paypal.com"; // Change to www.sandbox.paypal.com to test against sandbox
	        // read the post from PayPal system and add 'cmd'
	        $req = 'cmd=_notify-synch';
	        if (array_key_exists('tx', $_GET))
	        {
	        	$tx_token = $_GET['tx'];
	        }
	        else 
	        {
	        	$tx_token=0; 
	        }
	        
        	//echo "tx_token: "; 
        	//echo $tx_token; 
        	
	        //for paypal PDT
	        $auth_token = "Vufum_A50XWKERlFUEbQ1u69dRg2PMXMwwFRbGdGRDtqeCCeCj-aFGY59HO"; 
	        
	        //------------------for sandbox: 
	        //$auth_token = "fQj-D_tdkpuC40w-3f-DcOdd42x4MOSeQ0PvYS_Ynh9SuiVkXAi5CvtzckK"; 
	        
	        $req .= "&tx=$tx_token&at=$auth_token";
	        
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, "https://$pp_hostname/cgi-bin/webscr");
	        curl_setopt($ch, CURLOPT_POST, 1);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	        //set cacert.pem verisign certificate path in curl using 'CURLOPT_CAINFO' field here,
	        //if your server does not bundled with default verisign certificates.
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $pp_hostname"));
	        $res = curl_exec($ch);
	        curl_close($ch); 
	        
	        if (!$res)
	        {
	        	
	        	echo "There was an error getting the data of your transaction. "; 
	        }
	        else 
	        {
        
        		//echo "res = "; 
        		//echo $res; 
        		//echo "<br><br>"; 
        		
        		// parse the data
        		$lines = explode("\n", $res);
        		$keyarray = array();
        		if (strcmp ($lines[0], "SUCCESS") == 0) 
        		{
        			for ($i=1; $i<count($lines);$i++)
        			{
        				if (!strpos($lines[$i], "=")===false)
        				{
        					list($key,$val) = explode("=", $lines[$i]);
        					$keyarray[urldecode($key)] = urldecode($val);
        				}
        			}
        			
        			$paymentStatus = $keyarray['payment_status']; 
        			echo "<h6>Payment Status: " . $paymentStatus . "</h6>"; 
        			
        			//enter payment status into database 
        			// Create connection
        			$con=mysqli_connect($server, $user, $pass, $dbase);
        			// Check connection
        			if (mysqli_connect_errno()) {
        				echo "Failed to connect to MySQL: " . mysqli_connect_error();
        			}
        			else
        			{
        				//enter payment status into database
        				$paymentStatus = mysqli_real_escape_string($con, $paymentStatus); 
        				$sql="UPDATE " . $dbtable . " SET Paid='$paymentStatus' WHERE identifier='$identifier'"; 
        				if (!mysqli_query($con,$sql)) {
        					die('Error: ' . mysqli_error($con));
        				}
        			}

        			//if payment succeeded 
        			if ($paymentStatus == "Completed")
        			{
        				echo "<p>Thank you for your payment. PayPal will email you a receipt. You may log into your Paypal
        				account, if you have one, at <a href='https://www.paypal.com/us/'>www.paypal.com/us</a> to view
        				details of this transaction.</p>"; 
        				
        				//display order info 
        				//get relevant into from database
        				// Check connection
        				if (mysqli_connect_errno()) {
        					echo "Failed to connect to MySQL: " . mysqli_connect_error();
        				}
        				else
        				{
        					$sql = "SELECT * FROM " . $dbtable . " WHERE identifier='$identifier'";
        					//echo $sql; 
        					$result = mysqli_query($con,$sql);
        					if($result == false)
        					{
        						echo "ERROR: Could not get order information from database.";
        						die(mysql_error());
        					}
        					else
        					{
        						$result = mysqli_fetch_array($result);
        						//if order info is blank 
        						if ($result == null)
        						{
        							echo "ERROR: Could not get order information from database.";
        						}
        						else 
        						{
	        						//display order info
	        						echo "<h4>Order Summary </h4>";
	        						 
	        						//display what they ordered
	        						echo "<p><h6>Name: </h6>" . $result['name'] . "<br>";
	        						echo "<h6>Email: </h6>" . $result['email'] . "</p>";
	        				
	        						//if their order is a gift
	        						if (!empty($result['gift']))
	        						{
	        							echo "<p><h6>This order is a gift. </h6><br>";
	        							echo "<h6>Recipient's name: </h6>" . $result['rName'] . "<br>";
	        							echo "<h6>Recipient's email: </h6>" . $result['rEmail'] . "</p>";
	        						}
	        				
	        						//display flavors they ordered
	        						$flavorStr = "";
	        						echo "<p><h6>You ordered: </h6><br>";
	        						if ($result['plain'] > 0)
	        						{
	        							$flavorStr = $flavorStr . "<h6>Plain: </h6>" . $result['plain'] . "<br>";
	        						}
	        						if ($result['plainVeg'] > 0)
	        						{
	        							$flavorStr = $flavorStr . "<h6>Plain (Vegan): </h6>" . $result['plainVeg'] . "<br>";
	        						}
	        						if ($result['nutella'] > 0)
	        						{
	        							$flavorStr = $flavorStr . "<h6>Nutella: </h6>" . $result['nutella'] . "<br>";
	        						}
	        						if ($result['cs'] > 0)
	        						{
	        							$flavorStr = $flavorStr . "<h6>Cinnamon Sugar: </h6>" . $result['cs'] . "<br>";
	        						}
	        						if ($result['csvegan'] > 0)
	        						{
	        							$flavorStr = $flavorStr . "<h6>Cinnamon Sugar (Vegan): </h6>" . $result['csvegan'] . "<br>";
	        						}
	        						//-----------------------------------------------------------------------------------------------------------
	        						//modify for special flavors
	        						if ($result[$spec1StrDB] > 0)
	        						{
	        							$flavorStr = $flavorStr . "<h6>" . $spec1Str . ": </h6>" . $result[$spec1StrDB] . "<br>";
	        						}
	        						if ($result[$spec2StrDB] > 0)
	        						{
	        							$flavorStr = $flavorStr . "<h6>" . $spec2Str . ": </h6>" . $result[$spec2StrDB] . "<br>";
	        						}
	        						if ($result[$spec3StrDB] > 0)
	        						{
	        							$flavorStr = $flavorStr . "<h6>" . $spec3Str . ": </h6>" . $result[$spec3StrDB] . "<br>";
	        						}
	        						if ($result[$spec4StrDB] > 0)
	        						{
	        							$flavorStr = $flavorStr . "<h6>" . $spec4Str . ": </h6>" . $result[$spec4StrDB] . "<br>";
	        						}
	        						//------------------------------------------------------------------------------------------------------------
	        						echo $flavorStr . "</p>";
	        						 
	        						//display totals
	        						$totalCost = $result['totalCost'];
	        						echo "<p><h6>Total challah ordered: </h6>" . $result['totalChallah'] . "<br>";
	        						echo "<h6>Total cost: </h6>$" . $totalCost;
	        						//if paying in hours, tell how many hours they need to pay for challah
	        						if ($result['paymentMethod']=="Volunteer Hours")
	        						{
	        							$h = $totalCost*$hrPerDollar;
	        							echo " = " . $h . " hours";
	        						}
	        						echo "</p>";
	        				
	        						//payment method
	        						echo "<p><h6>Payment Method: </h6>" . $result['paymentMethod'] . "</p>";
	        				
	        						//display delivery location
	        						echo "<p><h6>Delivery Location: </h6>" . $result['location'] . "</p>";
	        						 
	        						//info about volunteering
	        						echo "<p><h6>Will you be volunteering with us this week? </h6>" . $result['volunteer'] . "</p>";
	        				
	        						//flavor requests and comments
	        						echo "<p><h6>Flavor requests: </h6>" . $result['flavorRequests'] . "</p>";
	        						echo "<p><h6>Comments: </h6>" . $result['comments'] . "</p>";
	        					}
        					}
        				}
        			}
        		}
        		else 
        		{
        			//getting data did not succeed 
        			echo $lines[0]; 
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
