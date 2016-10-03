<?php
	session_start();							//start the session
	
	// If Reset button was clicked
	if (isset ($_POST["reset"]))
	{
		$_SESSION = array();
		session_destroy();
		header ("location:sales_management.php");
	}
	
	$errMsg = "";
	
	$lowstock = $_POST[ "lowstock" ];
	
	if ($_POST["find_limited_less_than"] != "")
	{
		$less_than = htmlspecialchars (trim (stripslashes ($_POST["find_limited_less_than"])));
		if (!is_numeric ($less_than) || $less_than <= 0 || $less_than > 99999)
			$errMsg .= "Less Than must be > 0 and less than 99999.<br />";
	}else
	{
		$less_than = "";
	}
	
	// If validation fails return to sales_management
	if ($errMsg != "")
	{
		$_SESSION["find_limited_result"] = "<p>$errMsg</p>";
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
			if ($less_than != "")
			{
				$query = "SELECT * FROM product 
				WHERE (quantity < '$less_than');";
			}
			else if ($lowstock == "limited")
			{
				$query = "SELECT * FROM product 
				WHERE (limited = 1 AND active_for_sale = 1 );";
			}
			else if ($lowstock == "out_of_stock")
			{
				$query = "SELECT * FROM product 
				WHERE (active_for_sale = 0 );";
			}
			else
			{
				$query = "";
			}
			
			$result = mysqli_query ($conn, $query);
			
			if (!$result)
			{
				$errMsg = "Something is wrong with $query<br \>";
			}else
			{
				if (mysqli_num_rows ($result) <= 0)
				{
					$errMsg = "There are no products that are limited or out of stock, or have a quantity less than specified.<br \>";
				}else
				{
					$success = "<table class=\"table\" border=\"1\">
					<tr>
					<th scope=\"row\">ID</th>
					<th scope=\"row\">SKU</th>
					<th scope=\"row\">Name</th>
					<th scope=\"row\">Type</th>
					<th scope=\"row\">Price</th>
					<th scope=\"row\">Quantity</th>
					</tr>";
					
					while ($row = mysqli_fetch_assoc ($result))
					{
						$id = $row["product_id"];
						$sku = $row["sku"];
						$name = $row["name"];
						$type = $row["type"];
						$price = $row["price_per_unit"];
						$quantity = $row["quantity"];
						
						$success .= "<tr>
						<td>$id</td>
						<td>$sku</td>
						<td>$name</td>
						<td>$type</td>
						<td>$$price</td>
						<td>$quantity</td>
						</tr>";
					}
					
					$success .= "</table>";
				}
			}
			
			mysqli_free_result ($result);
		}
		
		mysqli_close ($conn);
		
		if ($errMsg != "")
		{
			$_SESSION["find_limited_result"] = "<div id=errmsg><p>$errMsg</p></div>";
			header ("location:product_management.php");
		}else
		{
			$_SESSION["find_limited_result"] = "<div id=success><p>$success</p></div>";
			header ("location:product_management.php");
		}
	}
	
	// In exceptions, return to product management
	header ("location:product_management.php");
?>