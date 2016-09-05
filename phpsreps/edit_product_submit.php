<?php
	session_start();							//start the session
	
	$success = "";
	$errMsg = "";
	$err = "";
	
	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	if (empty($_POST["edit_product_sku"])) {
		$err = "Required field!";
      	$_SESSION["edit_product_v_sku"] = "<div id=errmsg><p>$err</p></div>";
	}
	else {
      $sku = test_input($_POST["edit_product_sku"]);
      if (!preg_match("/^[a-zA-Z0-9]{0,255}$/",$sku)) {
      	$err = "Only numbers and letters are allowed!";
      	$_SESSION["edit_product_v_sku"] = "<div id=errmsg><p>$err</p></div>";
      }
    }

    if (empty($_POST["edit_product_name"])) {
    	$err = "Required field!";
      	$_SESSION["edit_product_v_name"] = "<div id=errmsg><p>$err</p></div>";
    }
	else {
      $name = test_input($_POST["edit_product_name"]);
      if (!preg_match("/^[a-zA-Z0-9 -]{0,255}$/",$name)) {
      	$err = "Only numbers, letters, white space and '-' allowed!";
      	$_SESSION["edit_product_v_name"] = "<div id=errmsg><p>$err</p></div>";
      }
    }

	if (empty($_POST["edit_product_type"])) {
    	$err = "Required field!";
      	$_SESSION["edit_product_v_type"] = "<div id=errmsg><p>$err</p></div>";
    }
	else {
      $type = test_input($_POST["edit_product_type"]);
      if (!preg_match("/^[a-zA-Z0-9 -]{0,255}$/",$type)) {
      	$err = "Only numbers, letters, white space and '-' allowed!";
      	$_SESSION["edit_product_v_type"] = "<div id=errmsg><p>$err</p></div>";
      }
    }

    if (empty($_POST["edit_product_price"])) {
    	$err = "Required field!";
      	$_SESSION["edit_product_v_price"] = "<div id=errmsg><p>$err</p></div>";
    }
	else {
      $price = test_input($_POST["edit_product_price"]);
      if (!preg_match("/^\d{1,8}(?:\.\d{1,2})?$/",$price)) {
      	$err = "Only numbers, letters, white space and '-' allowed!";
      	$_SESSION["edit_product_v_price"] = "<div id=errmsg><p>$err</p></div>";
      }
    }

    if (empty($_POST["edit_product_quantity"])) {
    	$err = "Required field!";
      	$_SESSION["edit_product_v_quantity"] = "<div id=errmsg><p>$err</p></div>";
    }
	else {
      $quantity = test_input($_POST["edit_product_quantity"]);
      if (!preg_match("/^\d{1,5}$/",$quantity)) {
      	$err = "Only numbers, letters, white space and '-' allowed!";
      	$_SESSION["edit_product_v_quantity"] = "<div id=errmsg><p>$err</p></div>";
      }
    }

	if ($err != "") //check for errors
	{
		header ("location:product_management.php");
	}
	else{

	require_once( "php/settings.php" );		//connection info
	$conn = @mysqli_connect( $host, $user, $pwd, $sql_db );
	
	if( !$conn )
	{
		$errMsg = "Database connection failure!<br \>";
	}else
	{
			$limited = 0;
			$active = 1;
			if ($quantity <= 5)
				$limited = 1;
			if ($quantity <= 0)
				$active = 0;
			
			$query = "UPDATE product SET name='$name', type='$type', quantity='$quantity', price_per_unit='$price', limited='$limited', active_for_sale='$active'
			WHERE sku='$sku';";
			
			$result = mysqli_query ($conn, $query);
			if ( !$result )
			{
				$errMsg = "Something is wrong with $query<br \>";
			}
			else {
					$success = "<p>Product successfully edited!</p>
					<table border=\"1\">
					<tr>
					<th scope=\"row\">SKU</th>
					<th scope=\"row\">Name</th>
					<th scope=\"row\">Type</th>
					<th scope=\"row\">Price</th>
					<th scope=\"row\">Quantity</th>
					</tr>
					<tr>
					<td>$sku</td>
					<td>$name</td>
					<td>$type</td>
					<td>$$price</td>
					<td>$quantity</td>
					</tr>
					</table>";
			}
		
		mysqli_free_result ($result);
	}
	
	mysqli_close ($conn);
		
	if ($errMsg != "") //check for errors
	{
		$_SESSION["edit_product_result"] = "<div id=errmsg><p>$errMsg</p></div>";
		header ("location:product_management.php");
	}elseif ($success != "")
	{
		$_SESSION["edit_product_result"] = "<div id=success><p>$success</p></div>";
		header ("location:product_management.php");
	}
}
?>