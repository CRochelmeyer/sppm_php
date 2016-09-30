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
	$query = mysqli_query($conn,"SELECT sale.sale_id, sale.time_created, sale.sale_amount FROM sale WHERE sale.time_created BETWEEN '$datefrom' AND'$dateto';");
	
	if(mysqli_num_rows($query) > 0) {
		$success = "<form action=\"delete_sale_submit.php\" method=\"post\" onsubmit=\"if(document.getElementById('checkbox').checked) { return deleteConfirm();} else {return x();}\"><fieldset><legend>Showing all sales between $datefrom and $dateto</legend><table border=\"1\" style=\"text-align: center;\">
					<tr>
					<th>Select</th>
					<th scope=\"row\">Sale ID</th>
					<th scope=\"row\">Sale Date</th>
					<th scope=\"row\">Total</th>
					</tr>";
    	while($row = mysqli_fetch_assoc($query)) {
				$sid = $row["sale_id"];
				$time = $row["time_created"];
				$amount = $row["sale_amount"];

    			$success .= "<tr>
						<td align=\"center\"><input type=\"checkbox\" id=\"checkbox\" name=\"checked_id[]\" class=\"checkbox\" value=\"$sid\"/></td>
						<td>$sid</td>
						<td>$time</td>
						<td>$$amount</td>
						</tr>";
  
    	}
    	$success .= "</table></fieldset><input type=\"submit\" name=\"bulk_delete_submit\" value=\"Delete\" />
		</form>";
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