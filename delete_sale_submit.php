<?php
	
	session_start();

	if(isset($_POST['bulk_delete_submit']))
	{
		require_once ("php/settings.php");		//connection info
		$conn = @mysqli_connect ($host, $user, $pwd, $sql_db);
		
		if (!$conn)
		{
			$errMsg = "Database connection failure!<br \>";
		}else
		{
			$idArr = $_POST['checked_id'];
			foreach($idArr as $sid){
				mysqli_query($conn,"DELETE product_sale_item, sale FROM product_sale_item INNER JOIN sale ON product_sale_item.sale_id=sale.sale_id WHERE sale.sale_id =".$sid);
			}
			$_SESSION['find_sale_result'] = 'Sale has been deleted successfully.';
			header("Location:sales_management.php");
		}
    }
	else
	{
		if (isset ($_SESSION["view_sale_sid"]))
		{
			$errMsg = "";
			$idArray = $_SESSION["view_sale_sid"];
			
			for ($i = 0; $i < count ($idArray); $i++)
			{
				$saleID = $idArray[$i];
				if (isset ($_POST["$saleID"]))
				{
					require_once ("php/settings.php");		//connection info
					$conn = @mysqli_connect ($host, $user, $pwd, $sql_db);
					
					if (!$conn)
					{
						$errMsg = "Database connection failure!<br \>";
					}else
					{
						$query = "SELECT ps.product_id, p.sku, p.name, p.type, p.price_per_unit, ps.quantity_sold, ps.price, s.sale_id, s.time_created, s.sale_amount
						FROM product_sale_item ps
						INNER JOIN sale s ON ps.sale_id=s.sale_id 
						INNER JOIN product p ON p.product_id=ps.product_id 
						WHERE s.sale_id = '$saleID';";
						
						$result = mysqli_query ($conn, $query);
						
						if(mysqli_num_rows($result) > 0)
						{
							$row = mysqli_fetch_assoc ($result);
							$saleTime = $row["time_created"];
							$saleAmount = $row["sale_amount"];
							
							$success = "<h4>Viewing Sale $saleID, Created on $saleTime</h4>
							<table class=\"table\" border=\"1\" style=\"text-align: center;\">
							<tr>
							<th scope=\"row\">ID</th>
							<th scope=\"row\">SKU</th>
							<th scope=\"row\">Name</th>
							<th scope=\"row\">Type</th>
							<th scope=\"row\">Price</th>
							<th scope=\"row\">Qty Sold</th>
							<th scope=\"row\">Total</th>
							</tr>";
							
							// Taking the first result above, removes it
							$query = "SELECT ps.product_id, p.sku, p.name, p.type, p.price_per_unit, ps.quantity_sold, ps.price, s.sale_id
							FROM product_sale_item ps
							INNER JOIN sale s ON ps.sale_id=s.sale_id 
							INNER JOIN product p ON p.product_id=ps.product_id 
							WHERE s.sale_id = '$saleID';";
							
							$result = mysqli_query ($conn, $query);
							
							while ($row = mysqli_fetch_assoc ($result))
							{
								$itemID = $row["product_id"];
								$itemSKU = $row["sku"];
								$itemName = $row["name"];
								$itemType = $row["type"];
								$itemPrice = $row["price_per_unit"];
								$itemQty = $row["quantity_sold"];
								$itemTot = $row["price"];
								
								$success .= "<tr>
								<td>$itemID</td>
								<td>$itemSKU</td>
								<td>$itemName</td>
								<td>$itemType</td>
								<td>$$itemPrice</td>
								<td>$itemQty</td>
								<td>$$itemTot</td>
								</tr>";
							}
							
							$success .= "<tr>
							<th scope=\"row\"></th>
							<th scope=\"row\"></th>
							<th scope=\"row\"></th>
							<th scope=\"row\"></th>
							<th scope=\"row\"></th>
							<th scope=\"row\"></th>
							<th scope=\"row\">$$saleAmount</th>
							</tr>
							</table>";
						}
						else
						{
							$errMsg = "No products found in sale $saleID.<br \>";
						}
						mysqli_free_result ($result);
					}
					mysqli_close ($conn);
					
					break;
				}
			}
		}
		
		if ($errMsg != "") //check for errors
		{
			$_SESSION["find_sale_result"] = "<p>$errMsg</p>";
			header ("location:sales_management.php");
		}else
		{
			$_SESSION["find_sale_result"] = $success;
			header ("location:sales_management.php");
		}
	}	
?>