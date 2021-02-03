<?php



    $host = "localhost";		         // host = localhost because database hosted on the same server where PHP files are hosted
    $dbname = "id15900605_esp8266";              // Database name
    $username = "id15900605_santiyfer";		// Database username
    $password = "_<F}(%^\Mn+L3}za";	        // Database password


// Establish connection to MySQL database
$conn = new mysqli($host, $username, $password, $dbname);


// Check if connection established successfully
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

else { echo "Connected to mysql database. "; }

   
// Get date and time variables
  $fecha = date_create();
  $d= date_timestamp_get($fecha);
    
    
// If values send by NodeMCU are not empty then insert into MySQL database table

  if(!empty($_POST['temp']) && !empty($_POST['hum']) )
    {
		$temperatura = strip_tags($_POST['temp']);
        $humedad = strip_tags($_POST['hum']);


        // Update your tablename here
	    $sql = "INSERT INTO `datos`(`serial`, `fecha`, `temperatura`, `humedad`) VALUES (2,$d,$temperatura,$humedad)"; 
 


		if ($conn->query($sql) === TRUE) {
		    echo "Values inserted in MySQL database table.";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}


// Close MySQL connection
$conn->close();



?>