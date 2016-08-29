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
		require_once( "php/header.php" );
		require_once( "php/navigation.php" );
	?>
	
	<article>
		<h2>Products Page</h2>
	</article>
	
	<?php
		require_once( "php/footer.php" );
	?>
</body>

</html>

