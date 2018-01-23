<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CFH: Processing Form</title>
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
        <h1>Payment / Delivery</h1> 
        <p>
			<?php  
			
				/* ini_set('display_errors',1);
				ini_set('display_startup_errors',1);
				error_reporting(-1); */
			
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
			
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
				
				//date of the next baking session
				$currDate = $currDateGlobal; 
//**************************************************************************
				
				//if there is an error in the user's input 
				$userError = false; 
				
				//how much one loaf of challah costs 
				$cost = 5.50; 
				$totalCost = 0; 
				//how many volunteer hours is equivalent to $1
				$hrPerDollar = 5/5.5; 
				
				//timestamp 
				$timestamp = date("m-d-Y H:i:s"); 
				//microtime, used later for order identification 
				$identifier = strval(microtime(true));
				
				//get all variables from form        	 
				$namefl = test_input($_POST['namefl']); 
				$email = test_input($_POST['email']);
				
				//whether the challah is a gift 
				$gift = test_input($_POST['gift']);
				$rName = test_input($_POST['rName']);
				$rEmail = test_input($_POST['rEmail']);
				
				//how many of each flavor was ordered
				$paypalFlavStr = test_input($_POST['paypalFlavStr']);
				$plain = test_input($_POST['plain']); 
				$plainvegan = test_input($_POST['plainvegan']); 
				$nutella = test_input($_POST['nutella']);  
				$cs = test_input($_POST['cs']);
				$csvegan = test_input($_POST['csvegan']);
//------------------------------------------------------------------------------------------
//modify for special flavors 
				$spec1 = test_input($_POST['spec1']); 
				$spec2 = test_input($_POST['spec2']);
				$spec3 = test_input($_POST['spec3']);
				$spec4 = test_input($_POST['spec4']);
				
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
						echo "ERROR: Could not get order information from database.";
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
				
				//total number of challah ordered 
				$totalChallah = $plain + $plainvegan + $nutella + $cs + $csvegan + $spec1
					+ $spec2 + $spec3 + $spec4;
//------------------------------------------------------------------------------------------- 

				//delivery location 
				$location = test_input($_POST['location']);
				//payment method 
				$payment = test_input($_POST['payment']);
				$totalCost = test_input($_POST['totalCost']);
				
				//whether they're volunteering 
				$volunteer = test_input($_POST['volunteer']);
				
				//flavor requests
				$flavReq = test_input($_POST['flavReq']);
				//comments 
				$comments = test_input($_POST['comments']);				
				
				//check if any required values are blank or invalid
				if (empty($namefl))
				{
					echo "<h5>You need to enter your name. </h5>"; 
					$userError = true; 
				}
				if(!filter_var($email, FILTER_VALIDATE_EMAIL) or empty($email))
				{
					echo "<h5>Please enter a valid email address. </h5>";
					$userError = true; 					
				}
				
				//if gift checkbox is selected, make sure recipient info is valid 
				if (!empty($gift))
				{
					if (empty($rName))
					{
						echo "<h5>Please enter the recipient's name. </h5>"; 
						$userError = true; 
					}
					if (!filter_var($rEmail, FILTER_VALIDATE_EMAIL) or empty($rEmail))
					{
						echo "<h5>Please enter the recipient's email address. </h5>"; 
						$userError = true; 
					}
				}
				
				//make sure they ordered something 
				if ($totalChallah <= 0)
				{
					$userError = true; 
					echo "<h5>Please order some challah. </h5>"; 
				}
			
				//if all user input was valid 
				if (!$userError)
				{					
					// Create connection
					$con=mysqli_connect($server, $user, $pass, $dbase);
					
					// Check connection
					if (mysqli_connect_errno()) {
					  echo "Failed to connect to MySQL: " . mysqli_connect_error();
					}
					else 
					{	
						//first, make sure there is sufficient inventory (that we won't reach our order limit)
						$sql="SELECT numberChallah FROM inventory WHERE date= '$currDate'";
						$result = mysqli_query($con,$sql);
						$currInventory = mysqli_fetch_array($result)['numberChallah'];
						
						if ($totalChallah <= $currInventory)
						{
							
							//display relevant information to user-------------
							//payment method 
							echo "<p><h6>Payment Method: </h6>" . $payment . "</p>"; 
							
							//display delivery location and relevant info
							echo "<p><h6>Delivery Location: </h6>" . $location . "<br><br>";   
							if ($location == "Next House")
							{
								echo "You (or the recipient) can pick up the challah in the Country Kitchen 
										after 9:00 PM. After we leave the kitchen 
										(probably around 10:30 PM), 
										the challah will be waiting at the front desk. "; 
							}
							elseif ($location == "Whitehead Institute (Friday Morning)" or $location == "Off-campus")
							{
								echo "We'll contact you with more details about picking up your challah. <br>"; 
							}
							else 
							{
								echo "Your challah will be delivered to the front desk of your 
									(or the recipient's) dorm by midnight. "; 
							}
							echo "</p>"; 
//-------------------------------------------------------------------------------------------------------------
//new database table will need to be created each week 

							//escapes some special characters before adding to database  
							$identifier =  mysqli_real_escape_string($con, $identifier); 
							$timestamp =  mysqli_real_escape_string($con, $timestamp);
							$namefl =  mysqli_real_escape_string($con, $namefl);
							$email =  mysqli_real_escape_string($con, $email);
							$gift =  mysqli_real_escape_string($con, $gift);
							$rName =  mysqli_real_escape_string($con, $rName);
							$rEmail =  mysqli_real_escape_string($con, $rEmail);
							$plain =  mysqli_real_escape_string($con, $plain);
							$plainvegan =  mysqli_real_escape_string($con, $plainvegan);
							$nutella =  mysqli_real_escape_string($con, $nutella);
							$cs =  mysqli_real_escape_string($con, $cs);
							$csvegan =  mysqli_real_escape_string($con, $csvegan);
							$spec1 =  mysqli_real_escape_string($con, $spec1);
							$spec2 =  mysqli_real_escape_string($con, $spec2);
							$spec3 =  mysqli_real_escape_string($con, $spec3);
							$spec4 =  mysqli_real_escape_string($con, $spec4);
							$totalChallah =  mysqli_real_escape_string($con, $totalChallah);
							$location =  mysqli_real_escape_string($con, $location);
							$payment =  mysqli_real_escape_string($con, $payment);
							$volunteer =  mysqli_real_escape_string($con, $volunteer);
							$flavReq =  mysqli_real_escape_string($con, $flavReq);
							$comments =  mysqli_real_escape_string($con, $comments);
							$totalCost =  mysqli_real_escape_string($con, $totalCost);
							
							//if paying with volunteer hours, insert string into "Paid" column 
							//otherwise, leave blank 
							$paidStr = ""; 
							if ($payment == "Volunteer Hours")
							{
								$paidStr = "VH"; 
								$totalCost = 0; 
							}

							//insert into database 
							$sql="INSERT INTO " . $dbtable . "(identifier, timestamp, name, email, gift, rName, rEmail, 
								plain, plainVeg, nutella, cs, csvegan, 
								spec1, spec2, spec3, spec4, 
								totalChallah, location, 
								paymentMethod, volunteer, flavorRequests, comments, totalCost, Paid) 
								VALUES ('$identifier', '$timestamp', '$namefl', '$email', '$gift', '$rName', '$rEmail', 
								'$plain', '$plainvegan', '$nutella', '$cs', '$csvegan', 
								'$spec1', '$spec2', '$spec3', '$spec4',
								'$totalChallah', 
								'$location', '$payment', '$volunteer', '$flavReq', '$comments', '$totalCost', '$paidStr')";
//---------------------------------------------------------------------------------------------------------------
																		
							if (!mysqli_query($con,$sql)) {
								die('Error: ' . mysqli_error($con));
							}
														
							//subtract from inventory
							$currInventory = $currInventory - $totalChallah;
							$sql = "UPDATE inventory 
							SET numberChallah='$currInventory' 
							WHERE date='$currDate'"; 
							if (!mysqli_query($con,$sql)) {
								die('Error: ' . mysqli_error($con));
							}
							
							//if they are paying with paypal
							if ($payment == "PayPal")
							{
								echo "<h4>Click below to continue with payment</h4>"; 
								?>
<p align="center">								
<!-- 
use for testing 
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top"> 
-->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">

<!-- Identify your business so that you can collect the payments. -->
<!-- 
use for testing 
<input type="hidden" name="business" value="cfh-test@mit.edu"> 
-->
<input type="hidden" name="business" value="cfh-paypal@mit.edu"> 

<!-- Specify a Buy Now button. -->
<input type="hidden" name="cmd" value="_xclick">
<!-- Specify details about the item that buyers will purchase. -->
<input type="hidden" name="item_name" value="<?php echo $paypalFlavStr; ?>">
<input type="hidden" name="amount" value="<?php echo $cost; ?>">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="quantity" value="<?php echo $totalChallah; ?>">
<input type="hidden" name="shipping" value="0.00">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="invoice" value="<?php echo $identifier; ?>">
<input type="hidden" name="return" value="http://cfh.scripts.mit.edu/confirmation.php?id=<?php echo $identifier?>">
<input type="hidden" name="cancel_return" value="http://cfh.scripts.mit.edu/cancel.php?id=<?php echo $identifier?>">
<input type="hidden" name="rm" value="2">
<input type="hidden" name="cbt" value="Return and View Order Summary">

<!-- Display the payment button. -->
<input type="image" src="https://www.paypalobjects.com/webstatic/en_US/btn/btn_paynow_cc_144x47.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</p>

							<?php 
							}
							else 
							{
								echo "<h4>Your order is complete!</h4>"; 	
							}							
						}			
						else 
						{						
							echo "<h5>You ordered " . $totalChallah . " challah. We only have " . $currInventory . 
								" challah left. </h5>"; 
						}
						
						mysqli_close($con);
					}
				}
			}
			else 
			{
				//echo "<script>location.href='http://cfh.scripts.mit.edu/orderhere.php'</script>";
				echo "http://cfh.scripts.mit.edu/orderform.php";    
			}
			?> 

        </p>       
        
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
