<?php session_start(); //start the session ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP-SRePS - Reports</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/prod.css" rel="stylesheet" />

    <meta name="description" content="Products Page of PHP-SRePS" />
    <meta name="keywords" content="HTML5, tags" />
    <meta name="author" content="Chris Hendrickson, Leon Vaisman, Claire Rochelmeyer" />


    <script src="http://code.jquery.com/jquery-1.9.1.js" type="text/javascript"></script>
</head>

<body>
    <div class="container">
        <?php $_SESSION[ "current" ]="reports" ; //require_once( "php/header.php" ); //require_once( "php/navigation.php" ); ?>
        <div class="masthead">
            <nav>
                <ul class="nav nav-justified">
                    <li><a href="index.php">Home</a>
                    </li>
                    <li><a href="product_management.php">Products</a>
                    </li>
                    <li><a href="sales_management.php">Sales</a>
                    </li>
                    <li class="active"><a href="reports.php">Reports</a>
                    </li>
                    <li><a href="sales_prediction.php">Predictions</a>
                    </li>
                </ul>
            </nav>
        </div>

        <article>
            <h2>Sales Reports</h2>

            <hr>

            <form method="post" id="view_report" class="form-inline" action="reports_submit.php" novalidate="novalidate">
                <h3>View Report</h3>
                <fieldset>
                    <legend>Select a range</legend>
                    <div class="form-group">
                        <p>
                            <label for="rdate_from">Date From</label>
                            <input type="date" name="rdatefrom" class="form-control" value="<?php echo date('Y-m-d', strtotime(" -1 month ")); ?>" /> &nbsp

                            <label for="rdate_to">Date To</label>
                            <input type="date" name="rdateto" class="form-control" value="<?php echo date('Y-m-d'); ?>" />
                        </p>
                    </div>
                </fieldset>

                <?php if (isset ($_SESSION[ "report_refine"]) && $_SESSION[ "report_refine"] !="" ) { $message=$_SESSION[ "report_refine"]; echo "<div id=report_refine>", $message, "</div>"; $_SESSION[ "report_refine"]="" ; } ?>

                <?php if (isset ($_SESSION[ "report_input_error"]) && $_SESSION[ "report_input_error"] !="" ) { $message=$_SESSION[ "report_input_error"]; echo "<div id=errmsg>", $message, "</div>"; $_SESSION[ "report_input_error"]="" ; } ?>

                <input type="submit" class="btn btn-primary" name="refine_product" value="Refine by Product" />
                <input type="submit" class="btn btn-primary" name="refine_type" value="Refine by Type" />

                <input type="submit" class="btn btn-secondary" name="reset" value="Reset" />
                <input type="submit" class="btn btn-primary" name="view_report" value="View Report" />


                <?php if (isset ($_SESSION[ "report_view_err"]) && $_SESSION[ "report_view_err"] !="" ) { $message=$_SESSION[ "report_view_err"]; echo "<div id=errmsg>", $message, "</div>"; $_SESSION[ "report_view_err"]="" ; } else if (isset ($_SESSION[ "find_report_result"]) && $_SESSION[ "find_report_result"] !="" ) { $message=$_SESSION[ "find_report_result"]; echo "<div id=report_success>", $message, "</div>"; $_SESSION[ "find_report_result"]="" ; } ?>
            </form>
            <br>
            <br>
            <br>
            <br>
        </article>

        <?php require_once( "php/footer.php" ); ?>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>