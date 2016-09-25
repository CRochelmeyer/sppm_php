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

	$query = mysqli_query($conn, "SELECT * FROM product WHERE sku = '$sku' or name = '$name' or type = '$type';");
	//$result = $conn->query($query);
	if(mysqli_num_rows($query) > 0) {
		$success = "<fieldset><legend>Showing all items with SKU: $sku Name: $name Type: $type</legend><table border=\"1\">
					<tr>
					<th scope=\"row\">SKU</th>
					<th scope=\"row\">Name</th>
					<th scope=\"row\">Type</th>
					<th scope=\"row\">Price</th>
					<th scope=\"row\">Quantity</th>
					</tr>";

    	while($row = mysqli_fetch_assoc($query)) {
    		$s = $row["sku"];
    		$n = $row["name"];
    		$t = $row["type"];
    		$p = $row["price_per_unit"];
    		$q = $row["quantity"];

    		$success .= "<tr>
						<td>$s</td>
						<td>$n</td>
						<td>$t</td>
						<td>$p</td>
						<td>$q</td>
						</tr>";
    	}
    	$success .= "</table></fieldset>";
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