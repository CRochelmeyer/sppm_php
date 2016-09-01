<?php
	
	session_start();							//start the session
	
	$errMsg = "";
	$sku = htmlspecialchars(trim($_POST["find_product_sku"]));
	$name = htmlspecialchars (trim ($_POST["find_product_name"]));
	$type = htmlspecialchars (trim ($_POST["find_product_type"]));
	

	require_once( "php/settings.php" );		//connection info
	$conn = @mysqli_connect( $host, $user, $pwd, $sql_db );

	if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
	}

	$query = "SELECT * FROM product 
	WHERE sku = '$sku' or name = '$name' or type = '$type';";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
    	while ($row = $result->fetch_assoc()) {
        $success = "SKU: " . $row["sku"]. " <br>Name: " . $row["name"]. "<br> Type: " . $row["type"]. "<br> Price: $" . $row["price_per_unit"]. "<br> Quantity: " . $row["active_for_sale"]. "<br>";
    	}
	} else {
    	$errMsg = "No results found!";
	}
	mysqli_free_result ($result);
	if ($errMsg != "") //check for errors
	{
		$_SESSION["find_product_result"] = "<p>$errMsg</p>";
		header ("location:product_management.php");
	}else
	{
		$_SESSION["find_product_result"] = "<p>$success</p>";
		header ("location:product_management.php");
	}
	$conn->close();
?>