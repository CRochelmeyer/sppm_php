<?php
	session_start();							//start the session
	
	// If Reset button was clicked
	if (isset ($_POST["reset"]))
	{
		$_SESSION = array();
		session_destroy();
		header ("location:reports.php");
	}
	
	// If Refine by Product button was clicked
	if (isset ($_POST["refine_product"]))
	{
		$refine = "<fieldset>
				<legend>Refine by Product</legend>
				
				<p><label for=\"report_product_id\">Product ID</label>
					<input type=\"number\" name=\"report_product_id\" min=\"0\" max=\"99999999999\" /> &nbsp
					
					<label for=\"report_product_sku\">Product SKU</label>
					<input type=\"text\" name=\"report_product_sku\" maxlength=\"40\" size=\"25\" /> &nbsp
					
					<label for=\"report_product_name\">Product Name</label>
					<input type=\"text\" name=\"report_product_name\" maxlength=\"100\" size=\"25\" />
				</p>
			</fieldset>";
		
		$_SESSION["report_refine"] = "<p>$refine</p>";
		header ("location:report.php");
	}
	
	// If Refine by Type button was clicked
	if (isset ($_POST["refine_type"]))
	{
		$refine = "<fieldset>
				<legend>Refine by Type</legend>
				
				<p><label for=\"report_product_type\">Product Type</label>
					<input type=\"text\" name=\"report_product_type\" maxlength=\"100\" size=\"50\" />
				</p>
			</fieldset>";
		
		$_SESSION["report_refine"] = "<p>$refine</p>";
		header ("location:report.php");
	}
	
	// If View Report button was clicked
	if (isset ($_POST["view_report"]))
	{
		$errMsg = "";
		
		// VALIDATION...
		//
		// $fromTimestamp -> $fromTimestamp = strtotime($dateFrom);
		$fromTimestamp = strtotime($_POST["report_date_from"]);
		// $toTimestamp - Neither of these can be null
		$toTimestamp = strtotime($_POST["report_date_to"]);
		// Check that fromTimestamp <= toTimestamp (= will yield all results for the one day)
		//
		// $productID - If not "", validate
		$productID = "";
		// $productSku - If not "", validate
		$productSku = "";
		// $productName - If not "", validate
		$productName = "";
		// $productType - If not "", validate
		$productType = "";
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
				// SELECT sale_id 
				// FROM sale 
				// WHERE 'time_created' >= '$fromTimestamp' AND 'time_created' <= '1362959999'
				//
				// If $productID OR $productSku OR $productName are not "",
				// use a query that checks for them also (see add product section of add_sale_submit
				// for a similar query)
				//
				// If $productType is not "", use a query that checks for it
				//
				// For each sale_id found, select all product_sale_items with that sale_id,
				// and increment quanity sold and total price for each product of a similar product_id
				//
				
				$fromDate = date("d-m-Y", strtotime($fromTimestamp));
				$toDate = date("d-m-Y", strtotime($toTimestamp));
				
				$itemID = "";
				$itemSKU = "";
				$itemName = "";
				$itemProduct = "";
				$itemQty = "";
				$itemPrice = "";
				$itemTot = "";
				
				// Output format
				$success = "<h4>Sales Report from $fromDate to $toDate</h4>
				<table border=\"1\">
				<tr>
				<th scope=\"row\">ID</th>
				<th scope=\"row\">SKU</th>
				<th scope=\"row\">Name</th>
				<th scope=\"row\">Type</th>
				<th scope=\"row\">Qty Sold</th>
				<th scope=\"row\">Price</th>
				<th scope=\"row\">Total</th>
				</tr>";
				
				// Loop through this depending on the number of products in report
				$success .= "<tr>
				<td>$itemID</td>
				<td>$itemSKU</td>
				<td>$itemName</td>
				<td>$itemProduct</td>
				<td>$itemQty</td>
				<td>$$itemPrice</td>
				<td>$$itemTot</td>
				</tr>";
				
				// End with 
				$success .= "</table>";
			
				if ($errMsg != "")
				{
					$_SESSION["report_view_err"] = "<p>$errMsg</p>";
					header ("location:report.php");
				}else
				{
					$_SESSION["report_view_success"] = "<p>$success</p>";
					header ("location:report.php");
				}
			}
		}
	}
	
	// In exceptions, return to sales management
	header ("location:reports.php");
?>