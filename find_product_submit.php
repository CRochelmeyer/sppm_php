<?php
	
	session_start();							//start the session
	
	$errMsg = "";
	$pid = htmlspecialchars (trim($_POST["find_product_id"]));
	$sku = htmlspecialchars (trim($_POST["find_product_sku"]));
	$name = htmlspecialchars (trim ($_POST["find_product_name"]));
	$type = htmlspecialchars (trim ($_POST["find_product_type"]));

	require_once( "php/settings.php" );		//connection info
	$conn = @mysqli_connect( $host, $user, $pwd, $sql_db );

	if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
	}

	if ($_POST["find_product_id"] != "") {
		$query = mysqli_query($conn, "SELECT * FROM product WHERE product_id LIKE '%$pid%';");
	}
	if ($_POST["find_product_sku"] != "") {
		$query = mysqli_query($conn, "SELECT * FROM product WHERE sku LIKE '%$sku%';");
	}
	elseif ($_POST["find_product_name"] != "") {
		$query = mysqli_query($conn, "SELECT * FROM product WHERE name LIKE '%$name%';");
	}
	elseif ($_POST["find_product_type"] != "") {
		$query = mysqli_query($conn, "SELECT * FROM product WHERE type LIKE '%$type%';");
	}
	else {
		$query = mysqli_query($conn, "SELECT * FROM product;");
	}
	
	if(mysqli_num_rows($query) > 0) {
		$success = "<form name=\"bulk_action_form\" id=\"jsform\" action=\"delete_product_submit.php\" method=\"post\" onsubmit=\"if(document.getElementById('checkbox').checked) { return deleteConfirm();} else {return x();}\"><fieldset><legend>Showing all items with SKU: $sku, Name: $name, Type: $type</legend><table class=\"table\" border=\"1\">
					<tr>
					<th>Select</th>
					<th scope=\"row\">Product ID</th>
					<th scope=\"row\">SKU</th>
					<th scope=\"row\">Name</th>
					<th scope=\"row\">Type</th>
					<th scope=\"row\">Price</th>
					<th scope=\"row\">Quantity</th>
					</tr>";

    	while($row = mysqli_fetch_assoc($query)) {
    		$i = $row["product_id"];
    		$s = $row["sku"];
    		$n = $row["name"];
    		$t = $row["type"];
    		$p = $row["price_per_unit"];
    		$q = $row["quantity"];

    		$success .= "<tr>
    					<td align=\"center\"><input type=\"checkbox\" id=\"checkbox\" name=\"checkbox[]\" class=\"checkbox\" value=\"$s\"/></td>
						<td>$i</td>
						<td>$s</td>
						<td>$n</td>
						<td>$t</td>
						<td>$$p</td>
						<td>$q</td>
						</tr>";
    	}
    	$success .= "</table></fieldset><input type=\"submit\" name=\"bulk_delete_submit\" value=\"Delete\" />
		</form>";
	} else {
    	$errMsg = "No results found!";
	}
	mysqli_free_result ($result);
	if ($errMsg != "") 
	{
		$_SESSION["find_product_result"] = "<div id=errmsg><p>$errMsg</p></div>";
		header ("location:product_management.php");
	}else
	{
		$_SESSION["find_product_result"] = $success;
		header ("location:product_management.php");
	}
	$conn->close();
?>