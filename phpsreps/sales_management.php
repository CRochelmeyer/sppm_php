<?php
	session_start();							//start the session
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="description" 	content="Sales Page of PHP-SRePS" />
	<meta name="keywords" 		content="HTML5, tags" />
	<meta name="author" 		content="Chris Hendrickson, Leon Vaisman, Claire Rochelmeyer"  />
	<title>PHP-SRePS - Sales</title>
	<link href= "styles/style.css" rel="stylesheet"/>
</head>

<body>
	<?php
		$_SESSION[ "current" ] = "sales";
		require_once( "php/header.php" );
		require_once( "php/navigation.php" );
	?>
	
	<article>
		<h2>Sales Management</h2>
		
		<hr>
		
		<form method="post" id="add_sale" action="add_sale_submit.php" novalidate="novalidate" >
			<h3>Create Sale</h3>
			<fieldset>
				<legend>Add Product to Sale</legend>
				
				<p><label for="add_sale_id">Product ID</label>
					<input type="number" name="add_sale_id" min="0" max="99999999999" autofocus="autofocus" /> &nbsp
					
					<label for="add_sale_sku">Product SKU</label>
					<input type="text" name="add_sale_sku" maxlength="40" size="25" pattern="^[a-zA-Z0-9]+$"  title="Must only be letters or numbers" /> &nbsp
					
					<label for="add_sale_name">Product Name</label>
					<input type="text" name="add_sale_name" maxlength="100" size="25" pattern="^[a-zA-Z0-9 -]+$" title="Can only contain A-Z, a-z, 0-9, and -" /> &nbsp
					
					<label for="add_sale_quantity">Quantity Sold</label>
					<input type="number" name="add_sale_quantity" min="0" max="99999" />
				</p>
			</fieldset>
			
			<?php
				if (isset ($_SESSION["add_product_error"]) && $_SESSION["add_product_error"] != "")
				{
					$message = $_SESSION["add_product_error"];
					echo "<div id=errmsg>", $message, "</div>";
					$_SESSION["add_product_error"] = "";
				}
			?>
			
			<input type="submit" name="add" value="Add Product" />
		
			<?php			
				if (isset ($_SESSION["sale_table"]) && $_SESSION["sale_table"] != "")
				{
					$message = $_SESSION["sale_table"];
					echo "<div id=sale_table>", $message, "</div>";
				}
			?>
			
			<input type="submit" name="removeBtn" value="Remove Products" />
			<input type="submit" name="reset" value="Reset" />
			<input type="submit" name="complete" value="Complete Sale" />
		
			<?php
				if (isset ($_SESSION["complete_sale_err"]) && $_SESSION["complete_sale_err"] != "")
				{
					$message = $_SESSION["complete_sale_err"];
					echo "<div id=errmsg>", $message, "</div>";
					$_SESSION["complete_sale_err"] = "";
				}
				else if (isset ($_SESSION["complete_sale_success"]) && $_SESSION["complete_sale_success"] != "")
				{
					$message = $_SESSION["complete_sale_success"];
					echo "<div id=errmsg>", $message, "</div>";
					$_SESSION["complete_sale_success"] = "";
					unset ($_SESSION["sale_table"]);
				}
			?>
		</form>
		<br><br>
		<br><br>
		<?php
			require_once( "php/settings.php" );
			$conn = @mysqli_connect( $host, $user, $pwd, $sql_db );
    		$query = mysqli_query($conn,"SELECT product_sale_item.product_id, product_sale_item.quantity_sold, sale.sale_id, sale.time_created, sale.sale_amount FROM product_sale_item INNER JOIN sale ON product_sale_item.sale_id=sale.sale_id");
		?>
			<fieldset>
				<legend>All Sales</legend>
				<table border="1">
				<thead>
        		<tr>
        			<th>Product ID</th>
            		<th>Sale ID</th>
            		<th>Quantity</th>
            		<th>Time of Sale</th>
            		<th>Total</th>
        		</tr>
        		</thead>
				<?php
            		if(mysqli_num_rows($query) > 0){
                		while($row = mysqli_fetch_assoc($query)){
        		?>
        		<tr>
        			<td><?php echo $row['product_id']; ?></td>        
            		<td><?php echo $row['sale_id']; ?></td>
            		<td><?php echo $row['quantity_sold']; ?></td>
            		<td><?php echo $row['time_created']; ?></td>
            		<td><?php echo $row['sale_amount']; ?></td>
        		</tr> 
        		<?php } }else{ ?>
            		<tr><td colspan="5">No records found.</td></tr> 
        		<?php } ?>
        		</table>
			</fieldset>
		<hr>
	</article>
	
	<?php
		require_once( "php/footer.php" );
	?>
</body>

</html>

