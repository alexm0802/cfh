//appears to be unused. Try orderform.php instead. - Sarah 11/05/17
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CFH: Order Form</title>
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
		display: inline; 
	}
	input[type=text]{
		margin-bottom: 10px; 
	}
</style>
</head>

<body>

<?php 

/* ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1); */

//************************************************************************
	//global variables 
	//mySQL server
	$server = "sql.mit.edu";
	$user = "cfh";
	$pass = "lom52day";
	$dbase = "cfh+home";

	//date of the next baking session 
	$currDate = '2015-02-12'; 
	$currDateTxt = "Feb. 12, 2015"; 
	
	//whether the order form has been released yet 
	$released = true;  

	//the names of special flavors 
	$spec1 = "Strawberry Jam"; 
	$spec2 = "Strawberry Jam (Vegan)"; 
	$spec3 = "Nutella + Strawberry Jam"; 
	$spec4 = "Garlic-Herb";  
	
//*************************************************************************
?>

<div id="container">
	<div id="header">
	</div>
        
	<div id="leftColumn">
    	<?php include 'leftcolumn.php'; ?>        
  </div>

	<div id="mainContentContainer">
    <div class="maincontent">    	
        <h1>Challah for Hunger Order Form</h1>
        <?php 
        	//if the order form has been released for the week (whether or not it's closed) 
        	if ($released == true)
        	{
        ?>
        <h4>You are ordering for <?php print $currDateTxt ?>. </h4> 
        
        <h5>Number of challah remaining: 
        <?php 
        	//connect to database to get current inventory
	        // Create connection
	        $con=mysqli_connect($server,$user,$pass,$dbase);
	        
	        // Check connection
	        if (mysqli_connect_errno()) {
	        	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	        }
	        else
	        {	        		
	        	//get current inventory 
	        	$sql="SELECT numberChallah FROM inventory WHERE date='$currDate'";
	        	$result = mysqli_query($con,$sql); 
	        		
	        	if (!mysqli_query($con,$sql)) {
	        	die('Error: ' . mysqli_error($con));
	        	}
	        	
	        	$currInventory = mysqli_fetch_array($result)['numberChallah'];
	        	//display inventory 
	        	echo $currInventory; 
	        	
	        	//close database connection 	
	        	mysqli_close($con);
	        }
        
        
        ?></h5>
        
        <?php
        	//only show the form if there are challah left to order 
        	if ($currInventory > 0){
		?>
		
		<!-- General info about the form -->
		<p>
			Challah is a delicious bread that we bake on Thursday nights.  
			Each challah is $5.50 for a full 1 lb loaf. 
		</p>
		<p>
			Challah is delivered late Thursday night to the front desk of your dorm, arriving around 10:30 pm - 12:00 am. 
			You can also pick up your challah in the Next House Country Kitchen after 9:00 PM. 
			Pickup is at Next House from 9 PM - 10 PM in the Country Kitchen. After we leave the kitchen 
			(probably around 10:30 PM), we'll leave all Next House challah at the front desk.
		</p> 
		<p>
			If none of these delivery/pick-up locations work for you (for example, if you live off campus), 
			email us at cfh-exec@mit.edu or leave a note in the comments and we can work something out. 
		</p>
		
		<p>*Required fields</p>
	        <form method="POST" action="reviewform.php">
	        	<h3>Enter your information:</h3>
	        	<p>
	      			<h5>Name (first and last)*: </h5><input type="text" name="namefl"> <br>
	      			<h5>Email*: </h5><input type="text" name="email">     		
	        	</p>
	        	       
	        	<h3>If you are ordering a gift for someone else: </h3>
	        	<p>
	        		For gift orders, we will charge you, but the challah will be delivered to the recipient. 
	        		The recipient will be emailed when the challah is delivered to their dorm. 
	        	</p>
	        	<p>	        		
					<input type="checkbox" name="gift" value="cGift">This challah is a gift. <br>
	        		<h5>Recipient's Name (first and last): </h5><input type="text" name="rName"> <br>
	      			<h5>Recipient's Email: </h5><input type="text" name="rEmail">
	        	</p>
				
				<h3>Flavors and Quantities</h3>
				<p>
					Select all the flavors you will be ordering this week. 
				
				<table>
					<tr>
						<td><h5>Plain:</h5></td>
						<td> 
						<select name = "plain">
							<option value="0" selected> 0 </option>
							<option value="1"> 1 </option> 
							<option value="2"> 2 </option> 
							<option value="3"> 3 </option> 
						</select>
						</td>
					</tr>
					<tr>
						<td><h5>Plain (Vegan):</h5></td> 
						<td>
						<select name = "plainvegan">
							<option value="0" selected> 0 </option>
							<option value="1"> 1 </option> 
							<option value="2"> 2 </option> 
							<option value="3"> 3 </option> 
						</select>
						</td>
					</tr>
					<tr>
						<td><h5>Nutella:</h5></td>
						<td> 
						<select name = "nutella">
							<option value="0" selected> 0 </option>
							<option value="1"> 1 </option> 
							<option value="2"> 2 </option> 
							<option value="3"> 3 </option> 
						</select>
						</td>
					</tr>
					<tr>
						<td><h5>Cinnamon Sugar:</h5></td>
						<td> 
						<select name = "cs">
							<option value="0" selected> 0 </option>
							<option value="1"> 1 </option> 
							<option value="2"> 2 </option> 
							<option value="3"> 3 </option> 
						</select>
						</td>
					</tr>
					<tr>
						<td><h5>Cinnamon Sugar (Vegan):</h5></td>
						<td> 
						<select name = "csvegan">
							<option value="0" selected> 0 </option>
							<option value="1"> 1 </option> 
							<option value="2"> 2 </option> 
							<option value="3"> 3 </option> 
						</select>
						</td>
					</tr>
<?php 
//------------------------------------------------------------------------------
//edit this section for special flavors 
?>
					<tr>
						<td><h5><?php echo $spec1;?>:</h5></td>
						<td>  
						<select name = "spec1">
							<option value="0" selected> 0 </option>
							<option value="1"> 1 </option> 
							<option value="2"> 2 </option> 
							<option value="3"> 3 </option> 
						</select>
						</td>
					</tr>
					<tr>
						<td><h5><?php echo $spec2;?>:</h5></td>
						<td>  
						<select name = "spec2">
							<option value="0" selected> 0 </option>
							<option value="1"> 1 </option> 
							<option value="2"> 2 </option> 
							<option value="3"> 3 </option> 
						</select>
						</td>
					</tr>
					<tr>
						<td><h5><?php echo $spec3;?>:</h5></td>
						<td>  
						<select name = "spec3">
							<option value="0" selected> 0 </option>
							<option value="1"> 1 </option> 
							<option value="2"> 2 </option> 
							<option value="3"> 3 </option> 
						</select>
						</td>
					</tr>
					<tr>
						<td><h5><?php echo $spec4;?>:</h5></td>
						<td>  
						<select name = "spec4">
							<option value="0" selected> 0 </option>
							<option value="1"> 1 </option> 
							<option value="2"> 2 </option> 
							<option value="3"> 3 </option> 
						</select>
						</td>
					</tr>
<?php 
//--------------------------------------------------------------------------------
?>
				</table>
				</p>
	
				<h3>Payment and Delivery</h3>
				<p>
					If you do not pay or contact us about payment by <b>12 pm on Thursday</b>, 
						your order will be cancelled. <br><br> 
						
					5 hours of volunteering earns you a free challah. If you select this payment option and 
					don't have enough hours, we will charge you the difference. <br><br>
					If you choose <b>Paypal</b> as the payment method, after you place your order, 
					you will be directed to the PayPal website. If you do not have a PayPal 
					account, <b>you can pay with most credit or debit cards.</b><br><br> 
					
					<h5>Payment Method*: </h5><br>
					<input type="radio" name="payment" value="PayPal">PayPal<br>
					<input type="radio" name="payment" value="Volunteer Hours">Volunteer Hours 
				</p>
				<p>
					<h5>Delivery/Pick-Up Location* </h5><br>
					If you live in a place where we don't deliver, it's because we don't have any regular volunteers that come from that area on campus.  
					If you're planning to come help out, let us know so we can add your location to the list.  
					We'd love to deliver everywhere, but we need more people to help us out! 
				</p>
				<p>
										
					<select name = "location">
                        <option value="Not Selected" selected>Next House </option>
						<option value="Next House" selected>Next House </option>
						<option value="Baker">Baker </option> 
						<option value="Burton-Conner">Burton-Conner </option> 
						<!-- <option value="Green Hall (Theta)">Green Hall (Theta) </option> -->
						<option value="MacGregor">MacGregor </option> 
						<option value="Maseeh">Maseeh </option> 
						<option value="McCormick">McCormick </option> 
						<option value="New House">New House </option> 
						<!-- <option value="Pi Phi">Pi Phi </option> -->
						<!-- <option value="Random">Random Hall</option>   -->
						<!-- <option value="Senior House">Senior House </option>   -->
                        <option value="SigEp"> SigEp </option>
						<option value="Simmons">Simmons </option> 
						<!-- <option value="Sidney Pacific"> Sidney Pacific </option> 
						<option value="Tang"> Tang </option>  -->
						<! -- <option value="Whitehead Institute (Friday Morning)">Whitehead Institute (Friday Morning) </option> -->
						<!-- <option value="WILG">WILG </option>  --> 
						<option value="Off-campus">I live off campus and would like to work something out. </option>
					</select>
				</p>
				<p>
					<h5>Will you be volunteering with us this week*? </h5><br>
					Dough making runs from 5:00 pm - 7 pm. Baking starts at 7:30 pm. You can arrive whenever and stay as long as you like. <br>
					<input type="radio" name="volunteer" value="Yes - dough making" checked>Yes - dough making <br>
					<input type="radio" name="volunteer" value="Yes - baking">Yes - baking <br>
					<input type="radio" name="volunteer" value="Yes - both">Yes - both <br>
					<input type="radio" name="volunteer" value="Maybe">Maybe <br>
					<input type="radio" name="volunteer" value="No">No 
				</p>
				<p>
					<h5>Do you have any flavor requests for future weeks? </h5><br>
					<textarea rows="2" name="flavReq">
					</textarea>
				</p>
				<p>
					<h5>Notes/Comments </h5><br>
					<textarea rows="2" name="comments">
					</textarea>
				</p>
				<p>	
					You can review your order before final submission. <br>
	      			<input type="submit" value="Submit">
	      			<input type="reset" value="Reset">
				</p> 
			</form> 
	<?php 
		}
		else
		{
			//if there are no challah left
	?>
			<p>We have sold out of challah! Feel free to submit an order to the 
			<a href="https://docs.google.com/forms/d/1dcLoeKE7cayuHZ0CV4a4KTe5MMn-VRJGNuUKdmsyX90/viewform">wait 
			list</a>. If we have extra challah, you might get one! </p>
	<?php 			
		}
        	}
        	else 
        	{
        		//the order form has not been released to the general public yet 
        		echo '<p>Early in the week (usually on Tuesday), we open the order form. 
					On Thursday, we bake your challah and deliver it to your dorm, or you can pick it up at 
					the Next House Country Kitchen.  </p>'; 
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
