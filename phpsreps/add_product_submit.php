<?php
	session_start();							//start the session
	
	if (isset ($_POST[ "reset" ]))
	{
		$_SESSION = array();
		session_destroy();
		header ("location:product_management.php");
	}
	
	$errMsg = "";
	
	if ($_POST["add_product_sku"] != "")
	{
		$sku = htmlspecialchars (trim ($_POST["add_product_sku"]));
		if (!preg_match ("/^[a-zA-Z0-9]+$/", $sku) || strlen ($sku) > 40)
			$errMsg .= "SKU must only be letters or numbers and <= 40 characters long.<br />";
	}
	else
		$errMsg .= "Fill in SKU.<br />";
	
	if ($_POST["add_product_name"] != "")
	{
		$name = htmlspecialchars (trim ($_POST["add_product_name"]));
		if (!preg_match ("/^[a-zA-Z0-9 -]+$/", $name) || strlen ($name) > 100)
			$errMsg .= "Name can only contain A-Z, a-z, 0-9, and - and be <= 100 characters long.<br />";
	}
	else
		$errMsg .= "Fill in Name.<br />";
	
	if ($_POST["add_product_type"] != "")
	{
		$type = htmlspecialchars (trim ($_POST["add_product_type"]));
		if (!preg_match( "/^[a-zA-Z0-9 -]+$/", $type) || strlen ($type) > 100)
			$errMsg .= "Type can only contain A-Z, a-z, 0-9, and - and be <= 100 characters long.<br />";
	}
	else
		$errMsg .= "Fill in Type.<br />";
	
	if ($_POST["add_product_price"] != "")
	{
		$price = htmlspecialchars (trim ($_POST["add_product_price"]));
		if (!preg_match ("/^[0-9.]+$/", $price) || $price < 0 || $price > 99999999.99)
			$errMsg .= "Price must be >= 0 and less than 99999999.99.<br />";
	}
	else
		$errMsg .= "Fill in Price.<br />";
	
	if ($_POST["add_product_quantity"] != "")
	{
		$quantity = htmlspecialchars (trim ($_POST["add_product_quantity"]));
		if (!preg_match ("/^[0-9.]+$/", $quantity) || $quantity < 0 || $quantity > 99999)
			$errMsg .= "Quantity must be > 0 and less than 99999.<br />";
	}
	else
		$errMsg .= "Fill in Quantity.<br />";
	
	 // If validation fails return to product_management
	if ($errMsg != "")
	{
		$_SESSION["add_product_result"] = "<p>$errMsg</p>";
		header ("location:product_management.php");
	}else
	{
		require_once ("php/settings.php");		//connection info
		$conn = @mysqli_connect ($host, $user, $pwd, $sql_db);
		
		if (!$conn)
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
				
				$query = "INSERT INTO product (product_id, sku, name, type, quantity, price_per_unit, limited, active_for_sale)
				VALUES (NULL, '$sku', '$name', '$type', '$quantity', '$price', '$limited', '$active');";
				
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
					<th scope=\"row\">Quantity</th>
					<th scope=\"row\">Price</th>
					</tr>
					<tr>
					<td>$sku</td>
					<td>$name</td>
					<td>$type</td>
					<td>$quantity</td>
					<td>$$price</td>
					</tr>
					</table>";
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
	}
?>