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
		
	</article>
	
	<?php
		require_once( "php/footer.php" );
	?>
</body>

</html>

