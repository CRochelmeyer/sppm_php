<?php

session_start();							//start the session
	
	$errMsg = "";
	$rdatefrom = htmlspecialchars(trim($_POST["rdatefrom"]));
	$rdateto = htmlspecialchars (trim ($_POST["rdateto"]));
	$pid = htmlspecialchars (trim ($_POST["report_product_id"]));
	$sku = htmlspecialchars (trim ($_POST["report_product_sku"]));
	$nm = htmlspecialchars (trim ($_POST["report_product_name"]));
	$_SESSION['rdatefrom'] = $rdatefrom;
	$_SESSION['rdateto'] = $rdateto;
	$_SESSION['report_refine'] = $refine;
	$_SESSION['report_product_id'] = $pid;
	$_SESSION['report_product_sku'] = $sku;
	$_SESSION['report_product_name'] = $nm;

	require_once( "php/settings.php" );
	$conn = @mysqli_connect( $host, $user, $pwd, $sql_db );

	if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
	}

	$query = mysqli_query($conn,"SELECT ps.product_id, ps.quantity_sold, s.sale_id, s.time_created, s.sale_amount, p.quantity, p.sku, p.name, p.type 
					FROM product_sale_item ps
					INNER JOIN sale s ON ps.sale_id=s.sale_id 
					INNER JOIN product p ON p.product_id=ps.product_id 
					WHERE s.time_created BETWEEN '$rdatefrom' AND'$rdateto' AND (ps.product_id LIKE '$pid' OR p.sku LIKE '$sku' OR p.name LIKE '$nm');");

					if(mysqli_num_rows($query) > 0) {
					$refine = "<fieldset><legend>Displaying sales report between $rdatefrom and $rdateto, refined by product.</legend><div class=\"container-fluid\"><table class=\"table\" border=\"1\">
					<thead class=\"thead-inverse\">
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
					</tr>
					</thead>";
    				
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

    				$refine .= "<tr>
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
					$refine .= "<a class=\"btn\" href=\"export2.php\"><input type=\"button\" value=\"Download as CSV\" /></a></table></div></fieldset>";
				}
				else {
    				$errMsg = "No results found!";
				}
				mysqli_free_result ($query);

	if ($errMsg != "") //check for errors
	{
		$_SESSION["report_refine"] = "<p>$errMsg</p>";
		header ("location:reports.php");
	}else
	{
		$_SESSION["report_refine"] = $refine;
		header ("location:reports.php");
	}
	$conn->close();

			

?>