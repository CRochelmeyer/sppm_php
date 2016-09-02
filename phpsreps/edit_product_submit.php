<?php
	session_start();							//start the session
	
	$errMsg = "";
	
	$sku = htmlspecialchars (trim ($_POST["edit_product_sku"]));
	$name = htmlspecialchars (trim ($_POST["edit_product_name"]));
	$type = htmlspecialchars (trim ($_POST["edit_product_type"]));
	$price = htmlspecialchars (trim ($_POST["edit_product_price"]));
	$quantity = htmlspecialchars (trim ($_POST["edit_product_quantity"]));
	
	require_once( "php/settings.php" );		//connection info
	$conn = @mysqli_connect( $host, $user, $pwd, $sql_db );
	
	if( !$conn )
	{
		$errMsg = "Database connection failure!<br \>";
	}else
	{
			$limited = 0;
			$active = 1;
			if ($quantity <= 5)
				$limited = 1;
			if ($quantity <= 0)
				$active = 0;
			
			$query = "UPDATE product SET name='$name', type='$type', price_per_unit='$price', limited='$limited', active_for_sale='$active'
			WHERE sku='$sku';";
			
			$result = mysqli_query ($conn, $query);
			if ( !$result )
			{
				$errMsg = "Something is wrong with $query<br \>";
			}
			else {
				$query = "UPDATE stock SET in_stock='$quantity'
				WHERE product_sku='$sku';";
				
				$result = mysqli_query ($conn, $query);
				if( !$result )
				{
					$errMsg = "Something is wrong with $query<br \>";
				}
				else {
					$success = "<p>Product successfully edited!</p>
					<table border=\"1\">
					<tr>
					<th scope=\"row\">SKU</th>
					<th scope=\"row\">Name</th>
					<th scope=\"row\">Type</th>
					<th scope=\"row\">Price</th>
					<th scope=\"row\">Quantity</th>
					</tr>
					<tr>
					<td>$sku</td>
					<td>$name</td>
					<td>$type</td>
					<td>$$price</td>
					<td>$quantity</td>
					</tr>
					</table>";
				}
			}
		
		mysqli_free_result ($result);
	}
	
	mysqli_close ($conn);
		
	if ($errMsg != "") //check for errors
	{
		$_SESSION["edit_product_result"] = "<p>$errMsg</p>";
		header ("location:product_management.php");
	}else
	{
		$_SESSION["edit_product_result"] = "<p>$success</p>";
		header ("location:product_management.php");
	}
?>