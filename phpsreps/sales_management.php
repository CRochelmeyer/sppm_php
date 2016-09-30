<?php
	session_start();							//start the session
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" 	content="Sales Page of PHP-SRePS" />
	<meta name="keywords" 		content="HTML5, tags" />
	<meta name="author" 		content="Chris Hendrickson, Leon Vaisman, Claire Rochelmeyer"  />
	<title>PHP-SRePS - Sales</title>
	<link href= "styles/style.css" rel="stylesheet"/>
	<script src="http://code.jquery.com/jquery-1.9.1.js" type="text/javascript"></script>
	<script type="text/javascript">
		function deleteConfirm(){
		    var result = confirm("Are you sure to delete this item(s)?");
		    if(result){
		        return true;
		    }else{
		        return false;
		    }
		}

		function x(){
			var result = confirm("You haven't selected any items, do you wish to continue?");
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
		<br><hr>
		<form action="find_sale_submit.php" method="post">
            <h3>Edit Sale</h3>
            <fieldset>
            	<legend>Find Sale</legend>
	            <p><label>Date From:</label>
	            <input type="date" name="datefrom" value="<?php echo date('Y-m-d', strtotime("-1 month")); ?>" /> &nbsp
	            <label>Date To:</label>
	            <input type="date" name="dateto" value="<?php echo date('Y-m-d'); ?>" />
	            </p>
            </fieldset>
            <input type="submit" name="find_sale" value="Search" />
		</form>
        <?php
		if (isset ($_SESSION["find_sale_result"]) && $_SESSION["find_sale_result"] != "")
		{
			$message = $_SESSION["find_sale_result"];
			echo "<div>", $message, "</div>";
			$_SESSION["find_sale_result"] = "";
		}
		?>
	</article>
	<?php
		require_once( "php/footer.php" );
	?>
</body>

</html>

