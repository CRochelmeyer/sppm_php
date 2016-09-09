<?php
	session_start();							//start the session
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="description" 	content="Reports Page of PHP-SRePS" />
	<meta name="keywords" 		content="HTML5, tags" />
	<meta name="author" 		content="Chris Hendrickson, Leon Vaisman, Claire Rochelmeyer"  />
	<title>PHP-SRePS - Reports</title>
	<link href= "styles/style.css" rel="stylesheet"/>
</head>

<body>
	<?php
		$_SESSION[ "current" ] = "reports";
		require_once( "php/header.php" );
		require_once( "php/navigation.php" );
	?>
	
	<article>
		<h2>Sales Management</h2>
		
		<hr>
		
		<form method="post" id="view_report" action="reports_submit.php" novalidate="novalidate" >
			<h3>View Report</h3>
			<fieldset>
				<legend>Select Time Range</legend>
				
				<p><label for="report_date_from">Date From</label>
					<input type="date" name="report_date_from" value="<?php echo date('Y-m-d'); ?>" /> &nbsp
					
					<label for="report_date_to">Date To</label>
					<input type="date" name="report_date_to" value="<?php echo date('Y-m-d'); ?>" />
				</p>
			</fieldset>
			
			<?php
				if (isset ($_SESSION["report_refine"]) && $_SESSION["report_refine"] != "")
				{
					$message = $_SESSION["report_refine"];
					echo "<div id=report_refine>", $message, "</div>";
				}
			?>
			
			<?php
				if (isset ($_SESSION["report_input_error"]) && $_SESSION["report_input_error"] != "")
				{
					$message = $_SESSION["report_input_error"];
					echo "<div id=errmsg>", $message, "</div>";
					$_SESSION["report_input_error"] = "";
				}
			?>
			
			<input type="submit" name="refine_product" value="Refine by Product" />
			<input type="submit" name="refine_type" value="Refine by Type" />
			
			<input type="submit" name="reset" value="Reset" />
			<input type="submit" name="view_report" value="View Report" />
		
			<?php
				if (isset ($_SESSION["report_view_err"]) && $_SESSION["report_view_err"] != "")
				{
					$message = $_SESSION["report_view_err"];
					echo "<div id=errmsg>", $message, "</div>";
					$_SESSION["report_view_err"] = "";
				}
				else if (isset ($_SESSION["report_view_success"]) && $_SESSION["report_view_success"] != "")
				{
					$message = $_SESSION["report_view_success"];
					echo "<div id=report_success>", $message, "</div>";
					$_SESSION["report_view_success"] = "";
					unset ($_SESSION["sale_table"]);
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

