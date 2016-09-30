<?php
	session_start();							//start the session
	
	// If Reset button was clicked
	if (isset ($_POST["reset"]))
	{
		$_SESSION = array();
		session_destroy();
		header ("location:sales_prediction.php");
	}
	
	
	if (isset ($_POST["display_prediction"]))
	{
		$errMsg = "";
		
		// VALIDATION...
		//
		// 
		$timeframe = $_POST[ "predict_time_frame" ];
		// $productID - If not "", validate
		$productID = $_POST[ "predict_id" ];
		// $productSku - If not "", validate
		$productSku = $_POST[ "predict_sku" ];
		// $productName - If not "", validate
		$productName = $_POST[ "predict_name" ];
		// $productType - If not "", validate
		$productType = $_POST[ "predict_type" ];
		
		// Ensure at least ID, SKU or Name, OR Type is entered
		// If ID, SKU or Name AND Type is entered, report error as
		// only allowed to search for a single product OR a type of product
		
		//
		// Use $errMsg .= "Error was ... at ... <br \>";
		//
		
		// If validation fails return to sales_prediction
		if ($errMsg != "")
		{
			$_SESSION["predict_input_error"] = "<p>$errMsg</p>";
			header ("location:sales_prediction.php");
		}else
		{
			require_once ("php/settings.php");		//connection info
			$conn = @mysqli_connect ($host, $user, $pwd, $sql_db);
			
			if (!$conn)
			{
				$errMsg = "Database connection failure!<br \>";
			}else
			{
				$products = array();
				
				if ($productType == "") // Predictions via single product
				{
					$query = "";
					
					if ($productName != "")
					{
						$query = "SELECT product_id FROM product 
						WHERE (product_id = '$productID' OR sku = '$productSku' OR LOWER(name) LIKE LOWER('%$productName%'));";
					}
					else if ($productID != "" || $productSku != "")
					{
						$query = "SELECT product_id FROM product 
						WHERE (product_id = '$productID' OR sku = '$productSku');";
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
						}
						else if (mysqli_num_rows ($result) <= 0)
						{
							$errMsg = "The product you searched for couldn't be found.<br \>";
						}
						else
						{
							$row = mysqli_fetch_assoc ($result);
							$products[0] = $row["product_id"];
						}
					}
				}
				else // Predctions via type
				{
					$query = "SELECT product_id FROM product 
					WHERE (LOWER(type) LIKE LOWER('%$productType%'));";
					
					$result = mysqli_query ($conn, $query);
					
					if (!$result)
					{
						$errMsg = "Something is wrong with $query<br \>";
					}
					else
					{
						if (mysqli_num_rows ($result) <= 0)
						{
							$errMsg = "The type you searched for couldn't be found.<br \>";
						}
						else
						{
							$i = 0;
							while($row = mysqli_fetch_assoc($result))
							{
								$products[$i] = $row["product_id"];
								$i++;
							}
						}
					}
				}
				
				if (count ($products) > 0)
				{
					$currentDate = date('Y-m-d');
					$earliestDate = date('Y-m-d', strtotime("-360 days"));
					
					$productPredicts = array();
					
					// Loop through each product 
					for ($i = 0; $i < count ($products); $i++)
					{
						$query = "SELECT ps.product_id, ps.quantity_sold, s.time_created
						FROM product_sale_item ps
						INNER JOIN sale s ON ps.sale_id=s.sale_id
						WHERE (s.time_created BETWEEN '$earliestDate' AND'$currentDate') AND (ps.product_id = '$products[$i]');";
						
						$result = mysqli_query ($conn, $query);
						$resultRows = array();
						$resultInt = 0;
						while ($rows = mysqli_fetch_assoc($result))
						{
							$resultRows[$resultInt] = $rows;
							$resultInt++;
						}
						
						$tempDate = $currentDate;
						$safetyInt = 0; // Breaks while loop after 365 days
						$weekInt = 6;
						$monthInt = 29;
						$soldOnDay = array(); // Total sales made on the same day of the week
						$dayAvg = array(); // How many days sold on the same day of the week
						
						// Loop through max time frame to collect sales
						while (strtotime($earliestDate) <= strtotime($tempDate))
						{
							// Loop through each individual sale
							for ($j = 0; $j < count ($resultRows); $j++)
							{
								// If sale date is the same as the tempDate that is being looped
								$row = $resultRows[$j];
								$saleDate = date("Y-m-d", strtotime($row["time_created"]));
								if ($saleDate == $tempDate)
								{
									if ($timeframe == "weekly")
									{
										if (isset ($soldOnDay[$weekInt]) == false)
										{
											$soldOnDay[$weekInt] = $row["quantity_sold"];
											$dayAvg[$weekInt] = 1;
										}
										else
										{
											$soldOnDay[$weekInt] += $row["quantity_sold"];
											$dayAvg[$weekInt]++;
										}
									}
									else if ($timeframe = "monthly")
									{
										if (isset ($soldOnDay[$monthInt]) == false)
										{
											$soldOnDay[$monthInt] = $row["quantity_sold"];
											$dayAvg[$monthInt] = 1;
										}
										else
										{
											$soldOnDay[$monthInt] += $row["quantity_sold"];
											$dayAvg[$monthInt]++;
										}
									}
								}
							}
							
							$tempDate = date('Y-m-d', strtotime("-1 day", strtotime($tempDate)));
							$weekInt--;
							if ($weekInt < 0)
							{
								$weekInt = 6;
							}
							$monthInt--;
							if ($monthInt < 0)
							{
								$monthInt = 29;
							}
							
							
							$safetyInt++;
							if ($safetyInt >= 365)
							{
								$errMsg = "Loop expands past $earliestDate";
								break;
							}
						}
						
						// Calculate sales for each day
						$productSales = array();
						$max = 0;
						if ($timeframe == "weekly")
						{
							$max = 7;
						}
						else if ($timeframe = "monthly")
						{
							$max = 30;
						}
							
						for ($j = 0; $j < $max; $j++)
						{
							if (isset ($soldOnDay[$j]))
							{
								$query = "SELECT price_per_unit FROM product 
								WHERE (product_id = '$products[$i]');";
								
								$result = mysqli_query ($conn, $query);
								$row = mysqli_fetch_assoc($result);
								
								$sold = $soldOnDay[$j];
								$avg = $dayAvg[$j];
								$productSales[$j] = (round ($sold / $avg)) * $row["price_per_unit"];
							}
							else
							{
								$productSales[$j] = 0;
							}
						}
						// Set all predictions for one product to array at same index as product's ID
						$productPredicts[$i] = $productSales;
					}
					
					// PRINTING OUTPUT
					
					$tableDayInt = 1;
					$tableRowNo = 0;
					
					
					// Print header
					$query = "SELECT name FROM product 
					WHERE (product_id = '$products[0]');";
					
					$result = mysqli_query ($conn, $query);
					$row = mysqli_fetch_assoc($result);
					$name = $row["name"];
					$id = $products[0];
					
					$success = "<h4>Sales Prediction of $name ($id)";
					if (count ($products) > 1)
					{
						for ($i = 1; $i < count ($products); $i++)
						{
							$query = "SELECT name FROM product 
							WHERE (product_id = '$products[$i]');";
							
							$result = mysqli_query ($conn, $query);
							$row = mysqli_fetch_assoc($result);
							$name = $row["name"];
							$id = $products[$i];
							
							$success .= ", $name ($id)";
						}
					}
					$success .= "</h4>";
					
					// Print table heading
					$success .= "<table border=\"1\">
					<col width=\"50\">";
					for ($i = 0; $i < count ($products); $i++)
					{
						$success .= "<col width=\"80\">";
					}
					$success .= "<tr>
					<th>Day</th>";
					for ($i = 0; $i < count ($products); $i++)
					{
						$id = $products[$i];
						$success .= "<th>$id</th>";
					}
					$success .= "</tr>";
					
					// Print rows looping through data
					if ($timeframe == "weekly")
					{
						$tableLoopNo = 7;
					}
					else if ($timeframe = "monthly")
					{
						$tableLoopNo = 30;
					}
					for ($i = 0; $i < $tableLoopNo; $i++)
					{
						$success .= "<tr>
						<td>Day $tableDayInt</td>";
						
						// Loop for each product being displayed
						for ($j = 0; $j < count ($products); $j++)
						{
							$predictArray = $productPredicts[$j];
							$prediction = $predictArray[$i];
							$success .= "<td>$$prediction</td>";
						}
						
						$success .= "</tr>";
						$tableDayInt++;
					}
					
					$success .= "</table>";					
				}
				
				mysqli_free_result ($result);
			}
			
			mysqli_close ($conn);
		
			if ($errMsg != "")
			{
				$_SESSION["predict_view_err"] = "<p>$errMsg</p>";
				header ("location:sales_prediction.php");
			}else
			{
				$_SESSION["predict_success"] = "<p>$success</p>";
				header ("location:sales_prediction.php");
			}
		}
	}
	
	// In exceptions, return to sales management
	header ("location:sales_prediction.php");
?>