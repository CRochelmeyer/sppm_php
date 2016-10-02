<?php
	session_start();							//start the session
	
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
		$refine = "<form method=\"post\" />
				<fieldset>
				<legend>Refine by Product</legend>
				
				<p><label for=\"report_product_id\">Product ID</label>
					<input type=\"number\" name=\"report_product_id\" min=\"0\" max=\"99999999999\" /> &nbsp
					
					<label for=\"report_product_sku\">Product SKU</label>
					<input type=\"text\" name=\"report_product_sku\" maxlength=\"40\" size=\"25\" /> &nbsp
					
					<label for=\"report_product_name\">Product Name</label>
					<input type=\"text\" name=\"report_product_name\" maxlength=\"100\" size=\"25\" />
				</p>
			</fieldset><input type=\"submit\" name=\"refine_product\" formaction=\"refine_reportp.php\" value=\"Refine Report\" />
			</form>";
		
		$_SESSION["report_refine"] = $refine;
		header ("location:reports.php");
	}
	
	if (isset ($_POST["refine_type"]))
	{	

		$succes = "<form method=\"post\" />
				<fieldset>
				<legend>Refine by Type</legend>
				<p><label for=\"report_product_type\">Product Type</label>
					<input type=\"text\" name=\"report_product_type\" maxlength=\"100\" size=\"50\" />
				</p>
			</fieldset>
			<input type=\"submit\" name=\"refine_type\" formaction=\"refine_report.php\" value=\"Refine by Type\" />
			</form>";

		$_SESSION["report_refine"] = $succes;
		header ("location:reports.php");
		
	}
	
		
		//SORT QUERY
	/*$query = mysqli_query($conn,"SELECT ps.product_id, ps.quantity_sold, s.sale_id, s.time_created, s.sale_amount, p.quantity, p.sku, p.name, p.type 
		FROM product_sale_item ps
		INNER JOIN sale s ON ps.sale_id=s.sale_id 
		INNER JOIN product p ON p.product_id=ps.product_id
		WHERE s.time_created BETWEEN '$rdatefrom' AND'$rdateto'
		ORDER BY p.type DESC;");

	*/
	
	if (isset ($_POST["view_report"])){
	
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

	mysqli_free_result($query);

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
}
?>