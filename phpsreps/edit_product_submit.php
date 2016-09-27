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

	if (empty($_POST["edit_product_sku"]) && empty($_POST["edit_product_pid"])) {
		$err = "Required field!";
      	$_SESSION["edit_product_v_sku"] = "<div id=errmsg><p>$err</p></div>";
      	$_SESSION["edit_product_v_pid"] = "<div id=errmsg><p>$err</p></div>";
	}
	else {
      $sku = test_input($_POST["edit_product_sku"]);
      if (!preg_match("/^[a-zA-Z0-9]{0,255}$/",$sku)) {
      	$err = "Only numbers and letters are allowed!";
      	$_SESSION["edit_product_v_sku"] = "<div id=errmsg><p>$err</p></div>";
      }
      $pid = test_input($_POST["edit_product_pid"]);
      if (!preg_match("/^[a-zA-Z0-9]{0,255}$/",$pid)) {
      	$err = "Only numbers and letters are allowed!";
      	$_SESSION["edit_product_v_pid"] = "<div id=errmsg><p>$err</p></div>";
      }
    }

    if (isset($_POST["edit_product_name"]) && !empty($_POST["edit_product_name"])) {
    	$name = test_input($_POST["edit_product_name"]);
		if (!preg_match("/^[a-zA-Z0-9 -]{0,255}$/",$name)) {
			$err = "Only numbers, letters, white space and '-' allowed!";
			$_SESSION["edit_product_v_name"] = "<div id=errmsg><p>$err</p></div>";
		}
    }
	if (isset($_POST["edit_product_type"]) && !empty($_POST["edit_product_type"])) {
		$type = test_input($_POST["edit_product_type"]);
		if (!preg_match("/^[a-zA-Z0-9 -]{0,255}$/",$type)) {
			$err = "Only numbers, letters, white space and '-' allowed!";
			$_SESSION["edit_product_v_type"] = "<div id=errmsg><p>$err</p></div>";
		}
	}
	if (isset($_POST["edit_product_price"]) && !empty($_POST["edit_product_price"])) {
		$price = test_input($_POST["edit_product_price"]);
		echo "$price";
		if (!preg_match("/^\d{1,8}(?:\.\d{1,2})?$/",$price)) {
			$err = "Only valid price allowed!";
			$_SESSION["edit_product_v_price"] = "<div id=errmsg><p>$err</p></div>";
		}
	}
	if (isset($_POST["edit_product_quantity"]) && !empty($_POST["edit_product_quantity"])) {
		$quantity = test_input($_POST["edit_product_quantity"]);
		if (!preg_match("/^\d{1,5}$/",$quantity)) {
			$err = "Only numbers allowed!";
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
			
			$query = "UPDATE product SET";
			
			if (isset($_POST["edit_product_name"]) && !empty($_POST["edit_product_name"])) {
				$query .= " name='$name',";
			}
			if (isset($_POST["edit_product_type"]) && !empty($_POST["edit_product_type"])) {
				$query .= " type='$type',";
			}
			if (isset($_POST["edit_product_quantity"]) && !empty($_POST["edit_product_quantity"])) {
				if ($quantity <= 5){
					$limited = 1;
				}
				if ($quantity <= 0){
					$active = 0;
				}
				$query .= " quantity='$quantity',";
			}
			if (isset($_POST["edit_product_price"]) && !empty($_POST["edit_product_price"])) {
				$query .= " price_per_unit='$price',";
			}
			$query .= " limited='$limited', active_for_sale='$active'";
			if (isset($_POST["edit_product_sku"]) && !empty($_POST["edit_product_sku"])) {
				$query .= " WHERE sku='$sku';";
			}
			elseif (isset($_POST["edit_product_pid"]) && !empty($_POST["edit_product_pid"])) {
				$query .= " WHERE product_id='$pid';";
			}

			$result = mysqli_query ($conn, $query);
			
			if ( !$result )
			{
				$errMsg = "Something is wrong with $query<br \>";
			}
			else {
					$success = "<p>Product successfully edited!</p>";
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