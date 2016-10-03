<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP-SRePS - Home</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/prod.css" rel="stylesheet" />

    <meta name="description" content="Products Page of PHP-SRePS" />
    <meta name="keywords" content="HTML5, tags" />
    <meta name="author" content="Chris Hendrickson, Leon Vaisman, Claire Rochelmeyer" />
</head>

<body>
    <?php $_SESSION[ "current" ]="index" ; ?>
    <div class="container">
        <div class="masthead">
            <nav>
                <ul class="nav nav-justified">
                    <li class="active"><a href="index.php">Home</a>
                    </li>
                    <li><a href="product_management.php">Products</a>
                    </li>
                    <li><a href="sales_management.php">Sales</a>
                    </li>
                    <li><a href="reports.php">Reports</a>
                    </li>
                    <li><a href="sales_prediction.php">Predictions</a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="jumbotron">
            <article>
                <h1>Welcome to PHP-SRePS</h1>
                <br>
                <p class="lead"><b>From here you can:</b>
                </p>
                <br>
                <p class="lead">
                    Add and edit products with the inventory management system
                    <br> Create, modify and search for sales records
                    <br> View sales reports and export them to CSV
                    <br> And view basic predictions of sales
                    <br>
                </p>
                <br>
            </article>
        </div>
        <?php require_once( "php/footer.php" ); ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>