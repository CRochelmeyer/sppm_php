<?php
	
	session_start();
	
	$errMsg = "";
	$datefrom = htmlspecialchars(trim($_POST["datefrom"]));
	$dateto = htmlspecialchars (trim ($_POST["dateto"]));
	

	require_once( "php/settings.php" );
	$conn = @mysqli_connect( $host, $user, $pwd, $sql_db );

	if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
	}
	$query = mysqli_query($conn,"SELECT product_sale_item.product_id, product_sale_item.quantity_sold, sale.sale_id, sale.time_created, sale.sale_amount FROM product_sale_item INNER JOIN sale ON product_sale_item.sale_id=sale.sale_id WHERE sale.time_created BETWEEN '$datefrom' AND'$dateto';");
	if(mysqli_num_rows($query) > 0) {
		$success = "<fieldset><legend>Showing all sales between $datefrom and $dateto</legend><table border=\"1\">
					<tr>
					<th scope=\"row\">Product ID</th>
					<th scope=\"row\">Sale ID</th>
					<th scope=\"row\">Quantity</th>
					<th scope=\"row\">Time</th>
					<th scope=\"row\">Total</th>
					</tr>";
    	while($row = mysqli_fetch_assoc($query)) {
				$pid = $row["product_id"];
				$sid = $row["sale_id"];
				$quan = $row["quantity_sold"];
				$time = $row["time_created"];
				$amount = $row["sale_amount"];

    			$success .= "<tr>
						<td>$pid</td>
						<td>$sid</td>
						<td>$quan</td>
						<td>$time</td>
						<td>$amount</td>
						</tr>";
  
    	}
    	$success .= "</table></fieldset>";
	} else {
    	$errMsg = "No results found!";
	}
	mysqli_free_result ($query);
	if ($errMsg != "") //check for errors
	{
		$_SESSION["find_sale_result"] = "<p>$errMsg</p>";
		header ("location:sales_management.php");
	}else
	{
		$_SESSION["find_sale_result"] = $success;
		header ("location:sales_management.php");
	}
	$conn->close();
?>