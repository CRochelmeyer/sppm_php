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
	<script src="jquery.min.js"></script>
	<script type="text/javascript">
function deleteConfirm(){
    var result = confirm("Are you sure to delete this item(s)?");
    if(result){
        return true;
    }else{
        return false;
    }
}

$(document).ready(function(){
    $('#select_all').on('click',function(){
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
        }
  });
    
    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    });
});
</script>
</head>

<body>
	<?php
		$_SESSION[ "current" ] = "products";
		require_once ("php/header.php");
		require_once ("php/navigation.php");
	?>
	
	<article>
		<h2>Product Management</h2>
		
		<hr>
		
		<form method="post" id="add_product" action="add_product_submit.php" novalidate="novalidate" >
			<h3>Add Product</h3>
			<fieldset>
				<legend>Product Details</legend>
				
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
			<input type="submit" name="reset" value="Reset" />
		</form>
		<br>
		<hr>
		
		<form method="post" id="find_product" action="find_product_submit.php" >
			<h3>Find Product</h3>
			<fieldset>
				<legend>Product Details</legend>
				
				<p><label for="find_product_sku">SKU</label>
					<input type="text" name="find_product_sku" id="find_product_sku" maxlength="40" size="25" pattern="^[a-zA-Z0-9]+$" title="Must only be letters or numbers" />
				</p>
				<p><label for="find_product_name">Name</label>
					<input type="text" name="find_product_name" id="find_product_name" maxlength="100" size="50" pattern="^[a-zA-Z0-9 -]+$" title="Can only contain A-Z, a-z, 0-9, and -" />
				</p>
				<p><label for="find_product_type">Type</label>
					<input type="text" name="find_product_type" id="find_product_type" maxlength="100" size="50" pattern="^[a-zA-Z0-9 -]+$" title="Can only contain A-Z, a-z, 0-9, and -" />
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
		<br>
		<hr>
		
		<form method="post" id="find_limited" action="find_limited_submit.php" >
			<h3>Find Limited Products</h3>
			<fieldset>
				<legend>Find By</legend>
				
				<p><label for="limited">Limited</label>
					<input type="radio" name="lowstock" value="limited" checked="checked" />
					<label for="business">Out of Stock</label>
					<input type="radio" name="lowstock" value="out_of_stock" />
				</p>
				
				<p>Or</p>
				
				<p><label for="find_limited_less_than">Stock with Less Than</label>
					<input type="number" name="find_limited_less_than" min="0" max="99999" />
				</p>
			</fieldset>
			
			<?php
				if (isset ($_SESSION["find_limited_result"]) && $_SESSION["find_limited_result"] != "")
				{
					$message = $_SESSION["find_limited_result"];
					echo "<div id=errmsg>", $message, "</div>";
					$_SESSION["find_limited_result"] = "";
				}
			?>
			
			<input type="submit" value="Find" />
		</form>
		<br>
		<hr>
		
		<form method="post" id="edit_product" action="edit_product_submit.php" >
			<h3>Edit Products</h3>
			<fieldset>
				<legend>Product Details</legend>
				
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
		<br>
		<hr>
		
		<?php
			require_once( "php/settings.php" );
			$conn = @mysqli_connect( $host, $user, $pwd, $sql_db );
    		$query = mysqli_query($conn,"SELECT * FROM product");
		?>
		<form name="bulk_action_form" action="delete_product_submit.php" method="post" onsubmit="return deleteConfirm();"/>
			<h3>Delete Product</h3>
    		<fieldset>
    		<legend>Product Details</legend>
    		<table border="1">
        		<thead>
        		<tr>
            		<th>Select All<input type="checkbox" name="select_all" id="select_all" value=""/></th>        
            		<th>SKU</th>
            		<th>Name</th>
            		<th>Type</th>
            		<th>Price</th>
        		</tr>
        		</thead>
        		<?php
            		if(mysqli_num_rows($query) > 0){
                		while($row = mysqli_fetch_assoc($query)){
        		?>
        		<tr>
            		<td align="center"><input type="checkbox" name="checked_id[]" class="checkbox" value="<?php echo $row['sku']; ?>"/></td>        
            		<td><?php echo $row['sku']; ?></td>
            		<td><?php echo $row['name']; ?></td>
            		<td><?php echo $row['type']; ?></td>
            		<td><?php echo $row['price_per_unit']; ?></td>
        		</tr> 
        		<?php } }else{ ?>
            		<tr><td colspan="5">No records found.</td></tr> 
        		<?php } ?>
    		</table>
    		</fieldset>
    		<input type="submit" class="btn btn-danger" name="bulk_delete_submit" value="Delete"/>
		</form>
		<br><br>
	</article>
	
	<?php
		require_once ("php/footer.php");
	?>
</body>

</html>

