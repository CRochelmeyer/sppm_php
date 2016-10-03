<?php session_start(); //start the session ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP-SRePS - Predictions</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/prod.css" rel="stylesheet" />

    <meta name="description" content="Products Page of PHP-SRePS" />
    <meta name="keywords" content="HTML5, tags" />
    <meta name="author" content="Chris Hendrickson, Leon Vaisman, Claire Rochelmeyer" />


    <script src="http://code.jquery.com/jquery-1.9.1.js" type="text/javascript"></script>
</head>

<body>
    <div class="container">
        <?php $_SESSION[ "current"]="reports" ; //require_once( "php/header.php" ); //require_once( "php/navigation.php" ); ?>
        <div class="masthead">
            <nav>
                <ul class="nav nav-justified">
                    <li><a href="index.php">Home</a>
                    </li>
                    <li><a href="product_management.php">Products</a>
                    </li>
                    <li><a href="sales_management.php">Sales</a>
                    </li>
                    <li><a href="reports.php">Reports</a>
                    </li>
                    <li class="active"><a href="sales_prediction.php">Predictions</a>
                    </li>
                </ul>
            </nav>
        </div>

        <article>
            <h2>Sales Predictions</h2>

            <hr>

            <form method="post" id="view_report" class="form-inline" action="predictions_submit.php" novalidate="novalidate">
                <h3>View Predictions</h3>
                <fieldset>
                    <legend>Display By</legend>

                    <p>
                        <label for="weekly">Weekly</label>
                        <input type="radio" name="predict_time_frame" value="weekly" checked="checked" />
                        <label for="monthly">Monthly</label>
                        <input type="radio" name="predict_time_frame" value="monthly" />
                    </p>

                    <p>
                        <label for="predict_id">ID</label>
                        <input type="number" name="predict_id" min="0" max="99999" class="form-control" required="required" autofocus="autofocus" title="Must only be numbers" /> &nbsp
                        <label for="predict_sku">SKU</label>
                        <input type="text" name="predict_sku" maxlength="40" size="25" class="form-control" pattern="^[a-zA-Z0-9]+$" required="required" title="Must only be letters or numbers" /> &nbsp
                        <label for="predict_name">Name</label>
                        <input type="text" name="predict_name" maxlength="100" size="50" class="form-control" pattern="^[a-zA-Z0-9 -]+$" required="required" title="Can only contain A-Z, a-z, 0-9, and -" />
                    </p>


                    <p>Or</p>


                    <p>
                        <label for="predict_type">Type</label>
                        <input type="text" name="predict_type" maxlength="100" size="50" class="form-control" pattern="^[a-zA-Z0-9 -]+$" required="required" title="Can only contain A-Z, a-z, 0-9, and -" />
                    </p>
                </fieldset>

                <?php if (isset($_SESSION[ "predict_input_error"]) && $_SESSION[ "predict_input_error"] !="" ) { $message=$_SESSION[ "predict_input_error"]; echo "<div id=errmsg>", $message, "</div>"; $_SESSION[ "predict_input_error"]="" ; } ?>

                <input type="submit" class="btn btn-primary" name="display_prediction" value="Display Prediction" />
                <input type="submit" class="btn btn-secondary" name="reset" value="Reset" />


                <?php if (isset($_SESSION[ "predict_view_err"]) && $_SESSION[ "predict_view_err"] !="" ) { $message=$_SESSION[ "predict_view_err"]; echo "<div id=errmsg>", $message, "</div>"; $_SESSION[ "predict_view_err"]="" ; } else if (isset($_SESSION[ "predict_success"]) && $_SESSION[ "predict_success"] !="" ) { $message=$_SESSION[ "predict_success"]; echo "<div id=predict_success>", $message, "</div>"; $_SESSION[ "predict_success"]="" ; } ?>
            </form>
            <br>
            <br>
            <br>
            <br>
        </article>

        <?php require_once( "php/footer.php"); ?>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>