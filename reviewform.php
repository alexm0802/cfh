<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CFH: Review Order</title>
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
    <h1>Please Review Your Order</h1> 
        <p>
        <?php  
	        ini_set('display_errors',1);
	        ini_set('display_startup_errors',1);
	        error_reporting(-1);
        
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
				//date of the next baking session
				$currDate = $currDateGlobal;				

				//how much one loaf of challah costs
				$cost = 5.50;
				$totalCost = 0;
				//how many volunteer hours is equivalent to $1
				$hrPerDollar = 5/5.5;
				
				//text for "Continue" button
				$btnTxt = "Confirm Order and Continue to Payment"; 
//**************************************************************************
				
				//if there is an error in the user's input 
				$userError = false; 				
								
				//get all variables from form        	 
				$namefl = test_input($_POST['namefl']); 
				$email = test_input($_POST['email']);
				
				//whether the challah is a gift 
				//check if key exists first
				if (array_key_exists('gift', $_POST))
				{
					$gift = $_POST['gift'];
				}
				else 
				{
					$gift = ""; 
				}
				$rName = test_input($_POST['rName']);
				$rEmail = test_input($_POST['rEmail']);
				
				
				//how many of each flavor was ordered 
				$plain = $_POST['plain']; 
				$plainvegan = $_POST['plainvegan']; 
				$nutella = $_POST['nutella'];  
				$cs = $_POST['cs'];
				$csvegan = $_POST['csvegan'];
//------------------------------------------------------------------------------------------
//modify for special flavors 
				$spec1 = $_POST['spec1']; 
				$spec2 = $_POST['spec2'];
				$spec3 = $_POST['spec3'];
				$spec4 = $_POST['spec4'];
				
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
				$location = $_POST['location'];
				//payment method 				
				if (array_key_exists('payment', $_POST))
				{
					$payment = test_input($_POST['payment']);
					//if paying with hours, modify "Continue" button
					if ($payment == "Volunteer Hours")
					{
						$btnTxt = "Confirm Order"; 
					}
				}
				else
				{
					$payment = "";
				}
				
				//whether they're volunteering 
				if (array_key_exists('volunteer', $_POST))
				{
					$volunteer = test_input($_POST['volunteer']);
				}
				else 
				{
					$volunteer=""; 
				}
				
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
				if (empty($volunteer))
				{
					echo "<h5>Please indicate whether you will be volunteering this week. </h5>";
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
				
				//make sure they're paying 
				if (empty($payment))
				{
					$userError = true; 
					echo "<h5>Please choose a payment method. </h5>"; 
				}				
			
				//if all user input was valid 
				if (!$userError)
				{								
					  echo "If all this information is correct, press the <b>" . $btnTxt . "</b> button. ";
					  if ($payment == "PayPal")
					  {  
					  	echo "You will then be directed to the PayPal website to complete your payment. 
							If you do not have a PayPal account, you can pay with most credit or 
							debit cards. <br><br>"; 
					  }
					  echo "If you need to make any corrections, return to the previous page."; 
					
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
							//display what they ordered
							echo "<p><h6>Name: </h6>" . $namefl . "<br>";
							echo "<h6>Email: </h6>" . $email . "</p>";  
							
							//if their order is a gift 
							if (!empty($gift))
							{
								echo "<p><h6>This order is a gift. </h6><br>"; 
								echo "<h6>Recipient's name: </h6>" . $rName . "<br>"; 
								echo "<h6>Recipient's email: </h6>" . $rEmail . "</p>"; 
							}
							
							//display flavors they ordered 
							$flavorStr = ""; 
							//for PayPal
							$paypalFlavStr = "Challah: "; 
							//to determine whether to add commas in the paypal string 
							$r = $totalChallah; 
							echo "<p><h6>You ordered: </h6><br>"; 
							if ($plain > 0)
							{
								$flavorStr = $flavorStr . "<h6>Plain: </h6>" . $plain . "<br>";
								$paypalFlavStr = $paypalFlavStr . "Plain";  
								if ($r > $plain)
								{
									$paypalFlavStr = $paypalFlavStr . ", ";
									$r = $r - $plain; 
								}
							}
							if ($plainvegan > 0)
							{
								$flavorStr = $flavorStr . "<h6>Plain (Vegan): </h6>" . $plainvegan . "<br>";
								$paypalFlavStr = $paypalFlavStr . "Plain (Vegan)";
								if ($r > $plainvegan)
								{
									$paypalFlavStr = $paypalFlavStr . ", ";
									$r = $r - $plainvegan; 
								}
							}
							if ($nutella > 0)
							{
								$flavorStr = $flavorStr . "<h6>Nutella: </h6>" . $nutella . "<br>";
								$paypalFlavStr = $paypalFlavStr . "Nutella";
								if ($r > $nutella)
								{
									$paypalFlavStr = $paypalFlavStr . ", ";
									$r = $r - $nutella; 
								}
							}
							if ($cs > 0)
							{
								$flavorStr = $flavorStr . "<h6>Cinnamon Sugar: </h6>" . $cs . "<br>";
								$paypalFlavStr = $paypalFlavStr . "Cinnamon Sugar";
								if ($r > $cs)
								{
									$paypalFlavStr = $paypalFlavStr . ", ";
									$r = $r - $cs; 
								}
							}
							if ($csvegan > 0)
							{
								$flavorStr = $flavorStr . "<h6>Cinnamon Sugar (Vegan): </h6>" . $csvegan . "<br>";
								$paypalFlavStr = $paypalFlavStr . "Cinnamon Sugar (Vegan)";
								if ($r > $csvegan)
								{
									$paypalFlavStr = $paypalFlavStr . ", ";
									$r = $r - $csvegan; 
								}
							}
//-----------------------------------------------------------------------------------------------------------
//modify for special flavors 
							if ($spec1 > 0)
							{
								$flavorStr = $flavorStr . "<h6>" . $spec1Str . ": </h6>" . $spec1 . "<br>";
								$paypalFlavStr = $paypalFlavStr . $spec1Str;
								if ($r > $spec1)
								{
									$paypalFlavStr = $paypalFlavStr . ", ";
									$r = $r - $spec1; 
								}
							}
							if ($spec2 > 0)
							{
								$flavorStr = $flavorStr . "<h6>" . $spec2Str . ": </h6>" . $spec2 . "<br>";
								$paypalFlavStr = $paypalFlavStr . $spec2Str;
								if ($r > $spec2)
								{
									$paypalFlavStr = $paypalFlavStr . ", ";
									$r = $r - $spec2; 
								}
							}
							if ($spec3 > 0)
							{
								$flavorStr = $flavorStr . "<h6>" . $spec3Str . ": </h6>" . $spec3 . "<br>";
								$paypalFlavStr = $paypalFlavStr . $spec3Str;
								if ($r > $spec3)
								{
									$paypalFlavStr = $paypalFlavStr . ", ";
									$r = $r - $spec3; 
								}
							}
							if ($spec4 > 0)
							{
								$flavorStr = $flavorStr . "<h6>" . $spec4Str . ": </h6>" . $spec4 . "<br>";
								$paypalFlavStr = $paypalFlavStr . $spec4Str;
								if ($r > $spec4)
								{
									$paypalFlavStr = $paypalFlavStr . ", ";
									$r = $r - $spec4; 
								}
							}
//------------------------------------------------------------------------------------------------------------
							echo $flavorStr . "</p>"; 
								
							//display totals
							$totalCost = number_format($cost*$totalChallah, 2);
							echo "<p><h6>Total challah ordered: </h6>" . $totalChallah . "<br>"; 
							echo "<h6>Total cost: </h6>$" . $totalCost;
							//if paying in hours, tell how many hours they need to pay for challah  
							if ($payment=="Volunteer Hours")
							{ 								
								$h = $totalCost*$hrPerDollar; 
								echo " = " . $h . " hours";  
							}
							echo "</p>";
							
							//payment method 
							echo "<p><h6>Payment Method: </h6>" . $payment . "</p>"; 
							
							//display delivery location 
							echo "<p><h6>Delivery Location: </h6>" . $location . "</p>";   
														
							//info about volunteering 
							echo "<p><h6>Will you be volunteering with us this week? </h6>" . $volunteer . "</p>"; 
							
							//flavor requests and comments 
							echo "<p><h6>Flavor requests: </h6>" . $flavReq . "</p>"; 
							echo "<p><h6>Comments: </h6>" . $comments . "</p>";
							?>
							
							<form method="POST" action="processform.php">
								<input type="submit" value="<?php echo $btnTxt; ?>">
								
								<!-- send all variables to the next page -->
								<input type="hidden" name="namefl" value="<?php echo $namefl; ?>">
								<input type="hidden" name="email" value="<?php echo $email; ?>">
								<input type="hidden" name="gift" value="<?php echo $gift; ?>">
								<input type="hidden" name="rName" value="<?php echo $rName; ?>">
								<input type="hidden" name="rEmail" value="<?php echo $rEmail; ?>">
								<input type="hidden" name="plain" value="<?php echo $plain; ?>">
								<input type="hidden" name="plainvegan" value="<?php echo $plainvegan; ?>">
								<input type="hidden" name="nutella" value="<?php echo $nutella; ?>">
								<input type="hidden" name="cs" value="<?php echo $cs; ?>">
								<input type="hidden" name="csvegan" value="<?php echo $csvegan; ?>">
								
<!-- modify for special flavors ------------------------------------------------------------------ -->
								<input type="hidden" name="spec1" value="<?php echo $spec1; ?>">
								<input type="hidden" name="spec2" value="<?php echo $spec2; ?>">
								<input type="hidden" name="spec3" value="<?php echo $spec3; ?>">
								<input type="hidden" name="spec4" value="<?php echo $spec4; ?>">
<!-- end modify for special flavors -------------------------------------------------------------- -->
								
								<input type="hidden" name="location" value="<?php echo $location; ?>">
								<input type="hidden" name="payment" value="<?php echo $payment; ?>">
								<input type="hidden" name="totalCost" value="<?php echo $totalCost; ?>">
								<input type="hidden" name="volunteer" value="<?php echo $volunteer; ?>">
								<input type="hidden" name="flavReq" value="<?php echo $flavReq; ?>">
								<input type="hidden" name="comments" value="<?php echo $comments; ?>">
								<input type="hidden" name="paypalFlavStr" value="<?php echo $paypalFlavStr; ?>">
								
							</form>
							
							<?php
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
