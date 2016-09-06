<?php
	session_start();							//start the session
	
	function create_success_table ($idArray, $qtyArray, $totArray, $conn)
	{
		$success = "<table border=\"1\">
		<tr>
		<th scope=\"row\">Remove</th>
		<th scope=\"row\">ID</th>
		<th scope=\"row\">SKU</th>
		<th scope=\"row\">Name</th>
		<th scope=\"row\">Qty Sold</th>
		<th scope=\"row\">Price</th>
		<th scope=\"row\">Total</th>
		</tr>";
		
		$arrCount = count ($idArray);
		$totCost = 0;
		for ($x = 0; $x < $arrCount; $x++)
		{
			$itemID = $idArray [$x];
			$itemQty = $qtyArray [$x];
			$itemTot = $totArray [$x];
			$totCost = $totCost + $itemTot;
			
			$query = "SELECT * FROM product 
			WHERE product_id = '$itemID'";
			
			$result = mysqli_query ($conn, $query);
			if( !$result )
			{
				$errMsg .= "Something is wrong with $query<br \>";
			}else
			{
				$row = mysqli_fetch_assoc ($result);
				$itemSKU = $row["sku"];
				$itemName = $row["name"];
				$itemPrice = $row["price_per_unit"];
				$value = "$itemID" . "$itemQty" . "$itemTot";
				
				$success .= "<tr>
				<td><input type=\"checkbox\" name=\"remove[]\" value=$value /></td>
				<td>$itemID</td>
				<td>$itemSKU</td>
				<td>$itemName</td>
				<td>$itemQty</td>
				<td>$$itemPrice</td>
				<td>$$itemTot</td>
				</tr>";
			}
		}
		
		$success .= "<tr>
		<th scope=\"row\"></th>
		<th scope=\"row\"></th>
		<th scope=\"row\"></th>
		<th scope=\"row\"></th>
		<th scope=\"row\"></th>
		<th scope=\"row\"></th>
		<th scope=\"row\">$$totCost</th>
		</tr>
		</table>";
		
		$_SESSION["tot_sale_cost"] = $totCost;
		mysqli_free_result ($result);
		return $success;
	}
	
	// If Reset button was clicked
	if (isset ($_POST["reset"]))
	{
		$_SESSION = array();
		session_destroy();
		header ("location:sales_management.php");
	}
	
	// If Add Product button was clicked
	if (isset ($_POST["add"]))
	{
		$errMsg = "";
		
		if ($_POST["add_sale_id"] != "")
		{
			$id = htmlspecialchars (trim (stripslashes ($_POST["add_sale_id"])));
			if (!is_numeric ($id) || $id < 0 || $id > 99999999999)
				$errMsg .= "Product ID must be > 0 and less than 99999999999.<br />";
		}
		else if ($_POST["add_sale_sku"] != "")
		{
			$sku = htmlspecialchars (trim (stripslashes ($_POST["add_sale_sku"])));
			if (!preg_match ("/^[a-zA-Z0-9]+$/", $sku) || strlen ($sku) > 40)
				$errMsg .= "SKU must only be letters or numbers and <= 40 characters long.<br />";
		}
		else if ($_POST["add_sale_name"] != "")
		{
			$name = htmlspecialchars (trim (stripslashes ($_POST["add_sale_name"])));
			if (!preg_match ("/^[a-zA-Z0-9 -]+$/", $name) || strlen ($name) > 100)
				$errMsg .= "Name can only contain A-Z, a-z, 0-9, and - and be <= 100 characters long.<br />";
		}
		else
			$errMsg .= "Fill in at least one search term.<br />";
		
		if ($_POST["add_sale_quantity"] != "")
		{
			$quantity = htmlspecialchars (trim (stripslashes ($_POST["add_sale_quantity"])));
			if (!is_numeric ($quantity) || $quantity <= 0 || $quantity > 99999)
				$errMsg .= "Quantity must be > 0 and less than 99999.<br />";
		}
		else
			$errMsg .= "Fill in quantity sold.<br />";
		
		// If validation fails return to sales_management
		if ($errMsg != "")
		{
			$_SESSION["add_product_error"] = "<p>$errMsg</p>";
			header ("location:sales_management.php");
		}else
		{
			require_once ("php/settings.php");		//connection info
			$conn = @mysqli_connect ($host, $user, $pwd, $sql_db);
			
			if (!$conn)
			{
				$errMsg = "Database connection failure!<br \>";
			}else
			{
				if ($name != "")
				{
					$query = "SELECT * FROM product 
					WHERE (product_id = '$id' OR sku = '$sku' OR LOWER(name) LIKE LOWER('%$name%'));";
				}
				else
				{
					$query = "SELECT * FROM product 
					WHERE (product_id = '$id' OR sku = '$sku');";
				}
				
				$result = mysqli_query ($conn, $query);
				
				if (!$result)
				{
					$errMsg = "Something is wrong with $query<br \>";
				}else
				{
					// If search returns more than one product
					if (mysqli_num_rows ($result) > 1)
					{
						$errMsg = "There is more than one product that matches your search terms.<br \>";
					}else
					{
						$row = mysqli_fetch_assoc ($result);
						$id = $row["product_id"];
						$sku = $row["sku"];
						$name = $row["name"];
						$inStock = $row["quantity"];
						$ppu = $row["price_per_unit"];
						
						// If not enough stock available for sale
						if ($inStock < $quantity)
						{
							$errMsg = "Stock of \"$name\" is less than the quantity sold: $inStock in stock.<br \>";
						}
						else
						{
							// If there are already products added to the sale
							if (isset($_SESSION["sale_table"]))
							{
								$idArray = $_SESSION["product_id_array"];
								$qtyArray = $_SESSION["product_qty_array"];
								$totArray = $_SESSION["product_tot_array"];
								$arrCount = count ($idArray);
								$idArray[$arrCount] = $id;
								$qtyArray[$arrCount] = $quantity;
								$totArray[$arrCount] = $quantity * $ppu;
							}
							else
							{
								$idArray = array ($id);
								$qtyArray = array ($quantity);
								$totArray = array ($quantity * $ppu);
							}
							
							$_SESSION["product_id_array"] = $idArray;
							$_SESSION["product_qty_array"] = $qtyArray;
							$_SESSION["product_tot_array"] = $totArray;
							
							$success = create_success_table ($idArray, $qtyArray, $totArray, $conn);
						}
					}
				}
				
				mysqli_free_result ($result);
			}
			
			mysqli_close ($conn);
			
			if ($errMsg != "")
			{
				$_SESSION["add_product_error"] = "<p>$errMsg</p>";
				header ("location:sales_management.php");
			}else
			{
				$_SESSION["sale_table"] = "<p>$success</p>";
				header ("location:sales_management.php");
			}
		}
	}
	
	// If Remove Product button was clicked
	if (isset ($_POST["removeBtn"]))
	{
		$errMsg = "";
		
		// If there are products that have been added to the sale
		if (isset ($_SESSION["sale_table"]))
		{
			$idArray = array();
			$qtyArray = array();
			$totArray = array();
			
			$idArray = $_SESSION["product_id_array"];
			$qtyArray = $_SESSION["product_qty_array"];
			$totArray = $_SESSION["product_tot_array"];
			$arrCount = count ($idArray);
			
			if (isset ($_POST["remove"])) //validate extras
			{
				$remove = implode( ", ", $_POST[ "remove" ]);
			
				$removeCount = 0;
				$newIdArray = array();
				$newQtyArray = array();
				$newTotArray = array();
				
				for ($x = 0; $x < $arrCount; $x++)
				{
					$id = $idArray [$x - $removeCount];
					$qty = $qtyArray [$x - $removeCount];
					$tot = $totArray [$x - $removeCount];
					$value = "$id" . "$qty" . "$tot";
					// If box for a product was checked
					if (strpos ($remove, "$value") !== false)
					{
						$index = 0;
						for ($y = 0; $y < $arrCount - $removeCount; $y++)
						{
							if ($idArray [$y] !== $id || $qtyArray [$y] !== $qty || $totArray [$y] !== $tot)
							{
								$newIdArray [$index] = $idArray [$y];
								$newQtyArray [$index] = $qtyArray [$y];
								$newTotArray [$index] = $totArray [$y];
								$index++;
							}
						}
						$removeCount++;
					}
				}
				
				if ($removeCount > 0)
				{
					if (count ($newIdArray) > 0)
					{
						$_SESSION["product_id_array"] = $newIdArray;
						$_SESSION["product_qty_array"] = $newQtyArray;
						$_SESSION["product_tot_array"] = $newTotArray;				
					
						//reprint table
						require_once ("php/settings.php");		//connection info
						$conn = @mysqli_connect ($host, $user, $pwd, $sql_db);
						
						$success = create_success_table ($newIdArray, $newQtyArray, $newTotArray, $conn);
						//$success = "Product removed";
						$errMsg = "";
						
						mysqli_close ($conn);
					}else
					{
						unset ($_SESSION["sale_table"]);
					}
				}
			}else
			{
				$errMsg = "Please select a product to remove.";
			}
		}else
		{
			$errMsg = "No products have been added to the sale.";
		}
		
		if ($errMsg != "")
		{
			$_SESSION["add_product_error"] = "<p>$errMsg</p>";
			header ("location:sales_management.php");
		}else
		{
			$_SESSION["sale_table"] = "<p>$success</p>";
			header ("location:sales_management.php");			
		}
	}
	
	// If Complete Sale button was clicked
	if (isset ($_POST["complete"]))
	{
		// If validation fails return to sales_management
		if (isset ($_SESSION["sale_table"]))
		{
			$idArray = array();
			$qtyArray = array();
			$totArray = array();
			
			$idArray = $_SESSION["product_id_array"];
			$qtyArray = $_SESSION["product_qty_array"];
			$totArray = $_SESSION["product_tot_array"];
			$arrCount = count ($idArray);
			
			require_once ("php/settings.php");		//connection info
			$conn = @mysqli_connect ($host, $user, $pwd, $sql_db);
			
			if (!$conn)
			{
				$errMsg = "Database connection failure!<br \>";
			}else
			{
				$totCost = $_SESSION["tot_sale_cost"];
				
				$query = "INSERT INTO sale (sale_amount)
				VALUES ('$totCost');";
				
				$result = mysqli_query ($conn, $query);
				if( !$result )
				{
					$errMsg = "Something is wrong with $query<br \>";
				}else
				{
					$saleID = mysqli_insert_id($conn);
					
					$idArray = array();
					$qtyArray = array();
					$totArray = array();
					
					$idArray = $_SESSION["product_id_array"];
					$qtyArray = $_SESSION["product_qty_array"];
					$totArray = $_SESSION["product_tot_array"];
					$arrCount = count ($idArray);
					
					for ($x = 0; $x < $arrCount; $x++)
					{
						$itemID = $idArray [$x];
						$itemQty = $qtyArray [$x];
						$itemTot = $totArray [$x];
						
						$query = "SELECT * FROM product 
						WHERE product_id = '$itemID';";
						
						$result = mysqli_query ($conn, $query);
						if( !$result )
						{
							$errMsg .= "Something is wrong with $query<br \>";
						}else
						{
							$row = mysqli_fetch_assoc ($result);
							$quantity = $row["quantity"] - $itemQty;
							$ppu = $row["price_per_unit"];
							
							$limited = 0;
							$active = 1;
							if ($quantity <= 5)
								$limited = 1;
							if ($quantity <= 0)
								$active = 0;
							
							$query = "UPDATE product SET quantity = '$quantity', limited = '$limited', active_for_sale = '$active'
							WHERE product_id = '$itemID';";
							
							$result = mysqli_query ($conn, $query);
							if( !$result )
							{
								$errMsg .= "Something is wrong with $query<br \>";
							}else
							{
								$query = "INSERT INTO product_sale_item (product_sale_id, quantity_sold, price_per_unit, price, sale_id, product_id)
								VALUES (NULL, '$itemQty', '$ppu', '$itemTot', '$saleID', '$itemID');";
								
								$result = mysqli_query ($conn, $query);
								if( !$result )
								{
									$errMsg .= "Something is wrong with $query<br \>";
								}
							}
						}
					}
				}
				
				mysqli_free_result ($result);
			}
			
			mysqli_close ($conn);
		}else
		{
			$errMsg = "No products have been added to the sale.";
		}
		
		if ($errMsg != "")
		{
			$_SESSION["complete_sale_err"] = "<p>$errMsg</p>";
			header ("location:sales_management.php");
		}else
		{
			$_SESSION["complete_sale_success"] = "<p>Sale successfully created!</p>";
			header ("location:sales_management.php");			
		}
	}
	
	// In exceptions, return to sales management
	header ("location:sales_management.php");
?>