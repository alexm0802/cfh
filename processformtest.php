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
        <h1>The form is being processed...</h1> 
        <p>
			<?php        	 
				$namefl = $_POST['namefl'];
				$email = $_POST['email'];
				$plain = $_POST['plain']; 
				$plainvegan = $_POST['plainvegan']; 
				$nutella = $_POST['nutella'];  
				$location = $_POST['location'];
				$flavReq = $_POST['flavReq'];
				 print "Name: $namefl \n Email: $email \n"; 
			?>
			<br>
			<?php
				print "Number of plain: $plain"; 
				print "Number of plain (Vegan): $plainvegan"; 
				print "Number of Nutella: $nutella"; 
				print "Location: $location"; 
				print "Flavor Requests: $flavReq"; 
			?>
			
			<?php
				// Create connection
				$con=mysqli_connect("sql.mit.edu","cfh","lom52day","cfh+home");
				
				// Check connection
				if (mysqli_connect_errno()) {
				  echo "Failed to connect to MySQL: " . mysqli_connect_error();
				}
				else 
				{					
					echo "success";		
					//figure out the number of challah they ordered 
					$totalChallah = $plain + $plainvegan + $nutella; 

					//first, make sure there is sufficient inventory (that we won't reach our order limit)
					$sql="SELECT numberChallah FROM inventory WHERE date='2014-10-02'";
					$result = mysqli_query($con,$sql);
					$currInventory = mysqli_fetch_array($result)['numberChallah'];
					
					if ($totalChallah <= $currInventory)
					{
						echo "order ok"; 
						$sql="INSERT INTO orders100214 (name, email, plain, plainVeg, nutella, location) 
						VALUES ('$namefl', '$email', '$plain', '$plainvegan', '$nutella', '$location')";
																	
						if (!mysqli_query($con,$sql)) {
							die('Error: ' . mysqli_error($con));
						}
						echo "1 record added";
						
						//subtract from inventory
						$currInventory = $currInventory - $totalChallah;
						$sql = "UPDATE inventory 
						SET numberChallah='$currInventory' 
						WHERE date='2014-10-02'"; 
						if (!mysqli_query($con,$sql)) {
							die('Error: ' . mysqli_error($con));
						}
						
					}			
					else 
					{						
						echo "not enough challah"; 
					}
					
					mysqli_close($con);
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
