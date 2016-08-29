<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="description" 	content="Home Page of PHP-SRePS" />
	<meta name="keywords" 		content="HTML5, tags" />
	<meta name="author" 		content="Chris Hendrickson, Leon Vaisman, Claire Rochelmeyer"  />
	<title>PHP-SRePS - Home</title>
	<link href= "styles/style.css" rel="stylesheet"/>
</head>

<body>
	<?php
		$_SESSION[ "current" ] = "index";
		require_once( "php/header.php" );
		require_once( "php/navigation.php" );
	?>
	
	<article>
		<h2>Welcome to PHP-SRePS</h2>
		<p>From here you can:</p>
		<ul>
			<li>Add and edit products</li>
			<li>Create and search for sales records</li>
			<li>View sales reports (and print as CSV files)</li>
			<li>And view basic predictions of sales</li>
		</ul>
	</article>
	
	<?php
		require_once( "php/footer.php" );
	?>
</body>

</html>

