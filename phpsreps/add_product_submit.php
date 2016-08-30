<?php
	session_start();							//start the session
	
	$errMsg = "";
	
	$sku = htmlspecialchars (trim ($_POST["add_product_sku"]));
	$name = htmlspecialchars (trim ($_POST["add_product_name"]));
	$type = htmlspecialchars (trim ($_POST["add_product_type"]));
	$price = htmlspecialchars (trim ($_POST["add_product_price"]));
	$quantity = htmlspecialchars (trim ($_POST["add_product_quantity"]));
	
	require_once( "php/settings.php" );		//connection info
	$conn = @mysqli_connect( $host, $user, $pwd, $sql_db );
	
	if( !$conn )
	{
		$errMsg = "Database connection failure!<br \>";
	}else
	{
		$query = "SELECT * FROM product
		WHERE sku = '$sku';";
		$result = mysqli_query ($conn, $query);
		
		if (mysqli_num_rows ($result) > 0)
		{
			$errMsg = "The entered SKU is already a product in the system.<br \>";
		}else
		{
			$limited = 0;
			$active = 1;
			if ($quantity <= 5)
				$limited = 1;
			if ($quantity <= 0)
				$active = 0;
			
			$query = "INSERT INTO product (sku, name, type, price_per_unit, limited, active_for_sale)
			VALUES ('$sku', '$name', '$type', '$price', '$limited', '$active');";
			
			$result = mysqli_query ($conn, $query);
			if( !$result )
			{
				$errMsg = "Something is wrong with $query<br \>";
			}else
			{
				$query = "INSERT INTO stock (product_sku, in_stock)
				VALUES ('$sku', '$quantity');";
				
				$result = mysqli_query ($conn, $query);
				if( !$result )
				{
					$errMsg = "Something is wrong with $query<br \>";
				}else
				{
					$success = "<p>Product successfully added!</p>
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
		}
		
		mysqli_free_result ($result);
	}
	
	mysqli_close ($conn);
		
	if ($errMsg != "") //check for errors
	{
		$_SESSION["add_product_result"] = "<p>$errMsg</p>";
		header ("location:product_management.php");
	}else
	{
		$_SESSION["add_product_result"] = "<p>$success</p>";
		header ("location:product_management.php");
	}
?>