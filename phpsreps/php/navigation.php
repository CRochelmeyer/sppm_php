<?php
	$current = $_SESSION[ "current" ];

	echo "<nav id=\"navigation\">";
	
	if( $current == "index" )
		echo "<p class=\"menu\"><a href=\"index.php\" id=\"current\">Home</a></p>";
	else
		echo "<p class=\"menu\"><a href=\"index.php\">Home</a></p>";
	
	if( $current == "products" )
		echo "<p class=\"menu\"><a href=\"product_management.php\" id=\"current\">Products</a></p>";
	else
		echo "<p class=\"menu\"><a href=\"product_management.php\">Products</a></p>";
	
	if( $current == "sales" )
		echo "<p class=\"menu\"><a href=\"sales_management.php\" id=\"current\">Sales</a></p>";
	else
		echo "<p class=\"menu\"><a href=\"sales_management.php\">Sales</a></p>";
	
	if( $current == "reports" )
		echo "<p class=\"menu\"><a href=\"reports.php\" id=\"current\">Reports</a></p>";
	else
		echo "<p class=\"menu\"><a href=\"reports.php\">Reports</a></p>";
	
	echo "</nav>";
?>

