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
		$timeframe = $_POST[ "time_frame" ];
		// $productID - If not "", validate
		$productID = "";
		// $productSku - If not "", validate
		$productSku = "";
		// $productName - If not "", validate
		$productName = "";
		// $productType - If not "", validate
		$productType = "";
		
		// Ensure at least ID, SKU or Name, OR Type is entered
		// If ID, SKU or Name AND Type is entered, report error as
		// only allowed to search for a single product OR a type of product
		
		//
		// Use $errMsg .= "Error was ... at ... <br \>";
		//
		
		// If validation fails return to reports
		if ($errMsg != "")
		{
			$_SESSION["report_input_error"] = "<p>$errMsg</p>";
			header ("location:reports.php");
		}else
		{
			require_once ("php/settings.php");		//connection info
			$conn = @mysqli_connect ($host, $user, $pwd, $sql_db);
			
			if (!$conn)
			{
				$errMsg = "Database connection failure!<br \>";
			}else
			{
				// If 
				//
				// If $productID OR $productSku OR $productName are not "",
				// use a query that checks for them also (see add product section of add_sale_submit
				// for a similar query)
				// If result returns more than one product, return error (report_input_error)
				//
				// If $productType is not "", use a query that checks for it
				//
				// Find todays date
				// Find earliest date item was sold at: if greater than 1 year ago, set to 1 year ago
				// For previous day, increment total of item sold (day7sold = x, day7avg = 1)
				// For day before, increment total of item sold (day6sold = x, day6avg = 1)
				// etc.
				// increment total of item sold (day7sold = x+y, day7avg = 2****)
				// 
				// For each day (7 if weekly, 30 if monthly - 30 to keep it simple)
				// use dayXsold / dayXavg = day7predict
				//
				// For each sale_id found, select all product_sale_items with that sale_id,
				// and increment quanity sold and total price for each product of a similar product_id
				//
				
				$itemID = "";
				$itemName = "";
				$day1predict ="";
				$day2predict ="";
				$day3predict ="";
				$day4predict ="";
				$day5predict ="";
				$day6predict ="";
				$day7predict ="";
				
				// Output format (you will need to loop if searching by type - add columns headed by ID)
				$success = "<h4>Sales Prediction of $itemName ($itemID)</h4>
				<table border=\"1\">
				<tr>
				<th scope=\"row\">Day</th>
				<th scope=\"row\">Sales Est.</th>
				</tr>
				<tr>
				<td>Day 1</td>
				<td>$day1predict</td>
				</tr>
				<tr>
				<td>Day 2</td>
				<td>$day2predict</td>
				</tr>
				<tr>
				<td>Day 3</td>
				<td>$day3predict</td>
				</tr>
				<tr>
				<td>Day 4</td>
				<td>$day4predict</td>
				</tr>
				<tr>
				<td>Day 5</td>
				<td>$day5predict</td>
				</tr>
				<tr>
				<td>Day 6</td>
				<td>$day6predict</td>
				</tr>
				<tr>
				<td>Day 7</td>
				<td>$day7predict</td>
				</tr>
				</table>";
			
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
	}
	
	// In exceptions, return to sales management
	header ("location:sales_prediction.php");
?>