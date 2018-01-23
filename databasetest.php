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
        <h1>Trying to connect to a database</h1> 
        <p>
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
					/* $sql = "CREATE TABLE orders100214
					(
						PID INT NOT NULL AUTO_INCREMENT,
						PRIMARY KEY(PID),
						name TEXT,
						email TEXT,
						plain INT, 
						plainVeg INT,
						nutella INT 
					)";

					if (mysqli_query($con,$sql)) {
						echo "Table created successfully";
					} 
					else {
						echo "Error creating table: " . mysqli_error($con);
					} */
					
					$sql="INSERT INTO orders100214 (name, email, plain, plainVeg, nutella) 
					VALUES ('name value2', 'email value2',4, 5, 6)";
					
					if (!mysqli_query($con,$sql)) {
						die('Error: ' . mysqli_error($con));
					}
					echo "1 record added";
					
					
					
					$result = mysqli_query($con,"SELECT * FROM orders100214");
					
					echo "<table border='1'>
					<tr>
					<th>Name</th>
					<th>Email</th>
					<th>Plain</th>
					<th>Plain (Vegan)</th>
					<th>Nutella</th>
					</tr>";
					
					while($row = mysqli_fetch_array($result)) {
						echo "<tr>";
						echo "<td>" . $row['name'] . "</td>";
						echo "<td>" . $row['email'] . "</td>";
						echo "<td>" . $row['plain'] . "</td>";
						echo "<td>" . $row['plainVeg'] . "</td>";
						echo "<td>" . $row['nutella'] . "</td>";
						echo "</tr>";
					}
					
					echo "</table>"; 
					
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
