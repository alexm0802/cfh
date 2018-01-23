<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MIT Challah for Hunger</title>
<link href="blueLayout.css" rel="stylesheet" type="text/css" />
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
        <h1>This is a test form</h1> 
        
        <h2>Current challah inventory: 
        <?php 
        	//connect to database to get current inventory
	        // Create connection
	        $con=mysqli_connect("sql.mit.edu","cfh","lom52day","cfh+home");
	        
	        // Check connection
	        if (mysqli_connect_errno()) {
	        	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	        }
	        else
	        {	        		
	        	$sql="SELECT numberChallah FROM inventory WHERE date='2014-10-02'";
	        	$result = mysqli_query($con,$sql); 
	        		
	        	if (!mysqli_query($con,$sql)) {
	        	die('Error: ' . mysqli_error($con));
	        	}
	        	
	        	$currInventory = mysqli_fetch_array($result)['numberChallah']; 
	        	echo $currInventory; 
	        		
	        	mysqli_close($con);
	        }
        
        
        ?></h2>
        
        <?php 
        
        if ($currInventory > 0){



?>
        <form method="POST" action="processformtest.php">
        	<p>
      			Name (first and last): <input type="text" name="namefl"> <br>
      			Email: <input type="text" name="email">     		
        	</p>       
        
			<h2>Flavors and Quantities</h2>
			<p>
				Select all the flavors you will be ordering this week. 
			</p>
			<p>
				Plain: 
				<select name = "plain">
					<option value="0" selected> 0 </option>
					<option value="1"> 1 </option> 
				</select>
			</p>
			<p>
				Plain (Vegan): 
				<select name = "plainvegan">
					<option value="0" selected> 0 </option>
					<option value="1"> 1 </option> 
				</select>
			</p>
			<p>
				Nutella: 
				<select name = "nutella">
					<option value="0" selected> 0 </option>
					<option value="1"> 1 </option> 
				</select>
			</p>

			<h2>Payment and Delivery</h2>
			<p>
				If you live in a place where we don't deliver, it's because we don't have any regular volunteers that come from that area on campus.  
				If you're planning to come help out, let us know so we can add your location to the list.  
				We'd love to deliver everywhere, but we need more people to help us out! 
			</p>
			<p>
				Delivery/Pick-Up Location <br>
				*Note: Pick up with cash is ONLY available at Next House* <br>
				<select name = "location">
					<option value="Next House" selected> Next House </option>
					<option value="Baker"> Baker </option> 
				</select>
			</p>
			<p>
				Payment Method: <br>
				Cash orders MUST be picked up at Next House by 10 PM. <br>
				<input type="radio" name="payment" value="paypal" checked>Paypal<br>
				<input type="radio" name="payment" value="cash">Cash
			</p>
			<p>
				Will you be volunteering with us this week? <br>
				Dough making runs from 5:00 pm - 7 pm. Baking starts at 7:30 pm. You can arrive whenever and stay as long as you like. <br>
				<input type="radio" name="volunteer" value="dough making">Yes - dough making <br>
				<input type="radio" name="volunteer" value="baking">Yes - baking
			</p>
			<p>
				Do you have any flavor requests for future weeks? <br>
				<textarea rows="2" name="flavReq">
				</textarea>
			</p>
			<p>

      			<input type="submit" value="GO">
			</p> 
		</form> 
<?php 
}
else
{
	print "no more challah";
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
