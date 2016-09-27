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
	
	$errMsg = "";
	$rdatefrom = htmlspecialchars(trim($_POST["rdatefrom"]));
	$rdateto = htmlspecialchars (trim ($_POST["rdateto"]));
	$_SESSION['rdatefrom'] = $rdatefrom;
	$_SESSION['rdateto'] = $rdateto;

	require_once( "php/settings.php" );
	$conn = @mysqli_connect( $host, $user, $pwd, $sql_db );

	if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
	}
	$query = mysqli_query($conn,"SELECT ps.product_id, ps.quantity_sold, s.sale_id, s.time_created, s.sale_amount, p.quantity, p.sku, p.name, p.type 
		FROM product_sale_item ps
		INNER JOIN sale s ON ps.sale_id=s.sale_id 
		INNER JOIN product p ON p.product_id=ps.product_id 
		WHERE s.time_created BETWEEN '$rdatefrom' AND'$rdateto';");

	if(mysqli_num_rows($query) > 0) {
		$success = "<fieldset><legend>Displaying sales report between $rdatefrom and $rdateto</legend><table border=\"1\">
					<tr>
					<th scope=\"row\">Product ID</th>
					<th scope=\"row\">Sale ID</th>
					<th scope=\"row\">SKU</th>
					<th scope=\"row\">Product Name</th>
					<th scope=\"row\">Type</th>
					<th scope=\"row\">Quantity Sold</th>
					<th scope=\"row\">Quantity Remaining</th>
					<th scope=\"row\">Time of sale</th>
					<th scope=\"row\">Total sale amount</th>
					</tr>";
    	while($row = mysqli_fetch_assoc($query)) {
				$pid = $row["product_id"];
				$sid = $row["sale_id"];
				$sku = $row["sku"];
				$name = $row["name"];
				$type = $row["type"];
				$quan = $row["quantity_sold"];
				$quanr = $row["quantity"];
				$time = $row["time_created"];
				$amount = $row["sale_amount"];

    			$success .= "<tr>
						<td>$pid</td>
						<td>$sid</td>
						<td>$sku</td>
						<td>$name</td>
						<td>$type</td>
						<td>$quan</td>
						<td>$quanr</td>
						<td>$time</td>
						<td>$amount</td>
						</tr>";
  
    	}
	} 
	else {
    	$errMsg = "No results found!";
	}
	$success .= "<a class=\"btn\" href=\"export.php\"><input type=\"button\" value=\"Download as CSV\" /></a></table></fieldset>";

	mysqli_free_result ($query);

	if ($errMsg != "") //check for errors
	{
		$_SESSION["find_report_result"] = "<p>$errMsg</p>";
		header ("location:reports.php");
	}else
	{
		$_SESSION["find_report_result"] = $success;
		header ("location:reports.php");
	}
	$conn->close();
	/*if (isset ($_POST["view_report"]))
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
	header ("location:reports.php");*/
?>