<?php session_start(); //start the session ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP-SRePS - Sales</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/prod.css" rel="stylesheet" />

    <meta name="description" content="Products Page of PHP-SRePS" />
    <meta name="keywords" content="HTML5, tags" />
    <meta name="author" content="Chris Hendrickson, Leon Vaisman, Claire Rochelmeyer" />


    <script src="http://code.jquery.com/jquery-1.9.1.js" type="text/javascript"></script>
    <script type="text/javascript">
    function deleteConfirm() {
        var result = confirm("Are you sure to delete this item(s)?");
        if (result) {
            return true;
        } else {
            return false;
        }
    }

    function x() {
        var result = confirm("You haven't selected any items, do you wish to continue?");
        if (result) {
            return true;
        } else {
            return false;
        }
    }

    $(document).ready(function() {
        $('#select_all').on('click', function() {
            if (this.checked) {
                $('.checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.checkbox').each(function() {
                    this.checked = false;
                });
            }
        });

        $('.checkbox').on('click', function() {
            if ($('.checkbox:checked').length == $('.checkbox').length) {
                $('#select_all').prop('checked', true);
            } else {
                $('#select_all').prop('checked', false);
            }
        });
    });
    </script>
</head>

<body>
    <div class="container">
        <?php $_SESSION[ "current" ]="sales" ; //require_once( "php/header.php" ); //require_once( "php/navigation.php" ); ?>
        <div class="masthead">
            <nav>
                <ul class="nav nav-justified">
                    <li><a href="index.php">Home</a>
                    </li>
                    <li><a href="product_management.php">Products</a>
                    </li>
                    <li class="active"><a href="sales_management.php">Sales</a>
                    </li>
                    <li><a href="reports.php">Reports</a>
                    </li>
                    <li><a href="sales_prediction.php">Predictions</a>
                    </li>
                </ul>
            </nav>
        </div>

        <article>
            <h2>Sales Management</h2>
            <hr>

            <form method="post" class="form-inline" id="add_sale" action="add_sale_submit.php" novalidate="novalidate">
                <h3>Create Sale</h3>
                <fieldset>
                    <legend>Add Product to Sale</legend>
                    <div class="form-group">
                        <p>
                            <label for="add_sale_id">Product ID</label>
                            <input type="number" name="add_sale_id" min="0" max="99999999999" class="form-control" autofocus="autofocus" /> &nbsp

                            <label for="add_sale_sku">Product SKU</label>
                            <input type="text" name="add_sale_sku" maxlength="40" size="25" class="form-control" pattern="^[a-zA-Z0-9]+$" title="Must only be letters or numbers" /> &nbsp

                            <label for="add_sale_name">Product Name</label>
                            <input type="text" name="add_sale_name" maxlength="100" size="25" class="form-control" pattern="^[a-zA-Z0-9 -]+$" title="Can only contain A-Z, a-z, 0-9, and -" /> &nbsp

                            <label for="add_sale_quantity">Quantity Sold</label>
                            <input type="number" name="add_sale_quantity" class="form-control" min="0" max="99999" />
                        </p>
                    </div>
                </fieldset>

                <?php if (isset ($_SESSION[ "add_product_error"]) && $_SESSION[ "add_product_error"] !="" ) { $message=$_SESSION[ "add_product_error"]; echo "<div id=errmsg>", $message, "</div>"; $_SESSION[ "add_product_error"]="" ; } ?>

                <input type="submit" class="btn btn-primary" name="add" value="Add Product" />

                <?php if (isset ($_SESSION[ "sale_table"]) && $_SESSION[ "sale_table"] !="" ) { $message=$_SESSION[ "sale_table"]; echo "<div id=sale_table>", $message, "</div>"; } ?>

                <input type="submit" class="btn btn-primary" name="removeBtn" value="Remove Products" />
                <input type="submit" class="btn btn-secondary" name="reset" value="Reset" />
                <input type="submit" class="btn btn-primary" name="complete" value="Complete Sale" />

                <?php if (isset ($_SESSION[ "complete_sale_err"]) && $_SESSION[ "complete_sale_err"] !="" ) { $message=$_SESSION[ "complete_sale_err"]; echo "<div id=errmsg>", $message, "</div>"; $_SESSION[ "complete_sale_err"]="" ; } else if (isset ($_SESSION[ "complete_sale_success"]) && $_SESSION[ "complete_sale_success"] !="" ) { $message=$_SESSION[ "complete_sale_success"]; echo "<div id=errmsg>", $message, "</div>"; $_SESSION[ "complete_sale_success"]="" ; unset ($_SESSION[ "sale_table"]); } ?>
            </form>
            <br>
            <hr>
            <form action="find_sale_submit.php" class="form-inline" method="post">
                <h3>Edit Sale</h3>
                <fieldset>
                    <legend>Find Sale</legend>
                    <div class="form-group">
                        <p>
                            <label>Date From:</label>
                            <input type="date" name="datefrom" class="form-control" value="<?php echo date('Y-m-d', strtotime(" -1 month ")); ?>" /> &nbsp
                            <label>Date To:</label>
                            <input type="date" name="dateto" class="form-control" value="<?php echo date('Y-m-d'); ?>" />
                        </p>
                    </div>
                </fieldset>
                <input type="submit" class="btn btn-primary" name="find_sale" value="Search" />
            </form>
            <?php if (isset ($_SESSION[ "find_sale_result"]) && $_SESSION[ "find_sale_result"] !="" ) { $message=$_SESSION[ "find_sale_result"]; echo "<div>", $message, "</div>"; $_SESSION[ "find_sale_result"]="" ; } ?>
        </article>
        <?php require_once( "php/footer.php" ); ?>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>