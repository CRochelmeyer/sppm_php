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
	</article>
	
	<?php
		require_once ("php/footer.php");
	?>
</body>

</html>

