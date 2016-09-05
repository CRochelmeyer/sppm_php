<?php
	session_start();							//start the session
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="description" 	content="Products Page of PHP-SRePS" />
	<meta name="keywords" 		content="HTML5, tags" />
	<meta name="author" 		content="Chris Hendrickson, Leon Vaisman, Claire Rochelmeyer"  />
	<title>PHP-SRePS - Products</title>
	<link href= "styles/style.css" rel="stylesheet"/>
</head>

<body>
	<?php
		$_SESSION[ "current" ] = "products";
		require_once ("php/header.php");
		require_once ("php/navigation.php");
	?>
	
	<article>
		<h2>Product Management</h2>
		
		<form method="post" id="add_product" action="add_product_submit.php" >
			<fieldset>
				<legend>Add Product</legend>
				
				<p><label for="add_product_sku">SKU</label>
					<input type="text" name="add_product_sku" id="add_product_sku" maxlength="40" size="25" pattern="^[a-zA-Z0-9]+$" required="required" autofocus="autofocus" title="Must only be letters or numbers" />
				</p>
				<p><label for="add_product_name">Name</label>
					<input type="text" name="add_product_name" id="add_product_name" maxlength="100" size="50" pattern="^[a-zA-Z0-9 -]+$" required="required" title="Can only contain A-Z, a-z, 0-9, and -" />
				</p>
				<p><label for="add_product_type">Type</label>
					<input type="text" name="add_product_type" id="add_product_type" maxlength="100" size="50" pattern="^[a-zA-Z0-9 -]+$" required="required" title="Can only contain A-Z, a-z, 0-9, and -" />
				</p>
				<p><label for="add_product_price">Price Per Unit $</label>
					<input type="number" name="add_product_price" id="add_product_price" step="0.01" min="0" max="99999999.99" required="required" />
				</p>
				<p><label for="add_product_quantity">Initial Quantity</label>
					<input type="number" name="add_product_quantity" id="add_product_quantity" min="0" max="99999" required="required" />
				</p>
			</fieldset>
		
			<?php
				if (isset ($_SESSION["add_product_result"]) && $_SESSION["add_product_result"] != "")
				{
					$message = $_SESSION["add_product_result"];
					echo "<div id=errmsg>", $message, "</div>";
					$_SESSION["add_product_result"] = "";
				}
			?>
			
			<input type="submit" value="Add Product" />
		</form>
		<br><br>
		<form method="post" id="find_product" action="find_product_submit.php" >
			<fieldset>
				<legend>Find Product</legend>
				
				<p><label for="find_product_sku">SKU</label>
					<input type="text" name="find_product_sku" id="find_product_sku" maxlength="40" size="25" pattern="^[a-zA-Z0-9]+$"  autofocus="autofocus" title="Must only be letters or numbers" />
				</p>
				<p><label for="find_product_name">Name</label>
					<input type="text" name="find_product_name" id="find_product_name" maxlength="100" size="50" pattern="^[a-zA-Z0-9 -]+$"  title="Can only contain A-Z, a-z, 0-9, and -" />
				</p>
				<p><label for="find_product_type">Type</label>
					<input type="text" name="find_product_type" id="find_product_type" maxlength="100" size="50" pattern="^[a-zA-Z0-9 -]+$"  title="Can only contain A-Z, a-z, 0-9, and -" />
				</p>
			</fieldset>
			<?php
				if (isset ($_SESSION["find_product_result"]) && $_SESSION["find_product_result"] != "")
				{
					$message = $_SESSION["find_product_result"];
					echo "<div id=errmsg>", $message, "</div>";
					$_SESSION["find_product_result"] = "";
				}
			?>
			
			<input type="submit" value="Find Product" />
		</form>
		<br><br>
		<form method="post" id="edit_product" action="edit_product_submit.php" >
			<fieldset>
				<legend>Edit Product</legend>
				
				<p><br><label for="edit_product_sku">Edit by: SKU</label>
					<input type="text" name="edit_product_sku" id="edit_product_sku" maxlength="255" size="25" />
				<?php
					if (isset ($_SESSION["edit_product_v_sku"]) && $_SESSION["edit_product_v_sku"] != "")
					{
						echo $_SESSION["edit_product_v_sku"];
						unset($_SESSION["edit_product_v_sku"]);
					}
				?>
				<hr>
				</p>
				<p><label for="edit_product_name">Name</label>
					<input type="text" name="edit_product_name" id="edit_product_name" maxlength="255" size="50" />
				<?php
					if (isset ($_SESSION["edit_product_v_name"]) && $_SESSION["edit_product_v_name"] != "")
					{
						echo $_SESSION["edit_product_v_name"];
						unset($_SESSION["edit_product_v_name"]);
					}
				?>
				</p>
				<p><label for="edit_product_type">Type</label>
					<input type="text" name="edit_product_type" id="edit_product_type" maxlength="255" size="50" />
				<?php
					if (isset ($_SESSION["edit_product_v_type"]) && $_SESSION["edit_product_v_type"] != "")
					{
						echo $_SESSION["edit_product_v_type"];
						unset($_SESSION["edit_product_v_type"]);
					}
				?>
				</p>
				<p><label for="edit_product_price">Price Per Unit $</label>
					<input type="number" name="edit_product_price" id="edit_product_price" step="0.01" min="0" />
				<?php
					if (isset ($_SESSION["edit_product_v_price"]) && $_SESSION["edit_product_v_price"] != "")
					{
						echo $_SESSION["edit_product_v_price"];
						unset($_SESSION["edit_product_v_price"]);
					}
				?>
				</p>
				<p><label for="edit_product_quantity">Quantity</label>
					<input type="number" name="edit_product_quantity" id="edit_product_quantity" min="0" max="99999" />
					<?php
					if (isset ($_SESSION["edit_product_v_quantity"]) && $_SESSION["edit_product_v_quantity"] != "")
					{
						echo $_SESSION["edit_product_v_quantity"];
						unset($_SESSION["edit_product_v_quantity"]);
					}
				?>
				</p>
			</fieldset>
		
			<?php
				if (isset ($_SESSION["edit_product_result"]) && $_SESSION["edit_product_result"] != "")
				{
					echo $_SESSION["edit_product_result"];
					unset($_SESSION["edit_product_result"]);
				}
			?>
			
			<input type="submit" value="Edit Product" />
		</form>
	</article>
	
	<?php
		require_once ("php/footer.php");
	?>
</body>

</html>

