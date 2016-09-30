<?php
	session_start();							//start the session
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="description" 	content="Predictions Page of PHP-SRePS" />
	<meta name="keywords" 		content="HTML5, tags" />
	<meta name="author" 		content="Chris Hendrickson, Leon Vaisman, Claire Rochelmeyer"  />
	<title>PHP-SRePS - Predictions</title>
	<link href= "styles/style.css" rel="stylesheet"/>
</head>

<body>
	<?php
		$_SESSION[ "current" ] = "prediction";
		require_once( "php/header.php" );
		require_once( "php/navigation.php" );
	?>
	
	<article>
		<h2>Sales Predictions</h2>
		
		<hr>
		
		<form method="post" id="view_report" action="predictions_submit.php" novalidate="novalidate" >
			<h3>View Predictions</h3>
			<fieldset>
				<legend>Display By</legend>
				
				<p><label for="weekly">Weekly</label>
					<input type="radio" name="predict_time_frame" value="weekly" checked="checked" />
					<label for="monthly">Monthly</label>
					<input type="radio" name="predict_time_frame" value="monthly" />
				</p>
				
				<p><label for="predict_id">ID</label>
					<input type="number" name="predict_id" min="0" max="99999" required="required" autofocus="autofocus" title="Must only be numbers" />
					&nbsp
					<label for="predict_sku">SKU</label>
					<input type="text" name="predict_sku" maxlength="40" size="25" pattern="^[a-zA-Z0-9]+$" required="required" title="Must only be letters or numbers" />
					&nbsp
					<label for="predict_name">Name</label>
					<input type="text" name="predict_name" maxlength="100" size="50" pattern="^[a-zA-Z0-9 -]+$" required="required" title="Can only contain A-Z, a-z, 0-9, and -" />
				</p>
				
				
				<p>Or</p>
				
				
				<p><label for="predict_type">Type</label>
					<input type="text" name="predict_type" maxlength="100" size="50" pattern="^[a-zA-Z0-9 -]+$" required="required" title="Can only contain A-Z, a-z, 0-9, and -" />
				</p>
			</fieldset>
			
			<?php
				if (isset ($_SESSION["predict_input_error"]) && $_SESSION["predict_input_error"] != "")
				{
					$message = $_SESSION["predict_input_error"];
					echo "<div id=errmsg>", $message, "</div>";
					$_SESSION["predict_input_error"] = "";
				}
			?>
			
			<input type="submit" name="display_prediction" value="Display Prediction" />
			<input type="submit" name="reset" value="Reset" />

		
			<?php
				if (isset ($_SESSION["predict_view_err"]) && $_SESSION["predict_view_err"] != "")
				{
					$message = $_SESSION["predict_view_err"];
					echo "<div id=errmsg>", $message, "</div>";
					$_SESSION["predict_view_err"] = "";
				}
				else if (isset ($_SESSION["predict_success"]) && $_SESSION["predict_success"] != "")
				{
					$message = $_SESSION["predict_success"];
					echo "<div id=predict_success>", $message, "</div>";
					$_SESSION["predict_success"] = "";
				}
			?>
		</form>
		<br><br>
		<br><br>
	</article>
	
	<?php
		require_once( "php/footer.php" );
	?>
</body>

</html>

