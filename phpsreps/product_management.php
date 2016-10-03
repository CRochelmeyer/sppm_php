<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP-SRePS - Products</title>
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
        <?php $_SESSION[ "current" ]="products" ; //require_once ( "php/header.php"); //require_once ( "php/navigation.php"); ?>
        <div class="masthead">
            <nav>
                <ul class="nav nav-justified">
                    <li><a href="index.php">Home</a>
                    </li>
                    <li class="active"><a href="product_management.php">Products</a>
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
        <article>
            <h2>Product Management</h2>

            <hr>

            <form method="post" class="form-inline" id="add_product" action="add_product_submit.php" novalidate="novalidate">
                <h3>Add Product</h3>
                <fieldset>
                    <legend>Enter Product Details</legend>
                    <div class="form-group">
                        <p>
                            <label for="add_product_sku">SKU</label>
                            <input type="text" name="add_product_sku" id="add_product_sku" maxlength="40" size="25" pattern="^[a-zA-Z0-9]+$" required="required" autofocus="autofocus" class="form-control" title="Must only be letters or numbers" />
                        </p>
                    </div>
                    <div class="form-group">
                        <p>
                            <label for="add_product_name">Name</label>
                            <input type="text" name="add_product_name" id="add_product_name" maxlength="100" size="50" pattern="^[a-zA-Z0-9 -]+$" required="required" class="form-control" title="Can only contain A-Z, a-z, 0-9, and -" />
                        </p>
                    </div>
                    <div class="form-group">
                        <p>
                            <label for="add_product_type">Type</label>
                            <input type="text" name="add_product_type" id="add_product_type" maxlength="100" size="50" pattern="^[a-zA-Z0-9 -]+$" required="required" class="form-control" title="Can only contain A-Z, a-z, 0-9, and -" />
                        </p>
                    </div>
                    <div class="form-group">
                        <p>
                            <label for="add_product_price">Price Per Unit $</label>
                            <input type="number" class="form-control" name="add_product_price" id="add_product_price" step="0.01" min="0" max="99999999.99" required="required" />
                        </p>
                    </div>
                    <div class="form-group">
                        <p>
                            <label for="add_product_quantity">Initial Quantity</label>
                            <input type="number" name="add_product_quantity" id="add_product_quantity" min="0" max="99999" class="form-control" required="required" />
                        </p>
                    </div>
                </fieldset>

                <?php if (isset ($_SESSION[ "add_product_result"]) && $_SESSION[ "add_product_result"] !="" ) { $message=$_SESSION[ "add_product_result"]; echo "$message"; $_SESSION[ "add_product_result"]="" ; } ?>

                <input class="btn btn-primary" type="submit" value="Add Product" />
                <input class="btn btn-secondary" type="submit" name="reset" value="Reset" />
            </form>
            <br>
            <hr>


            <form method="post" class="form-inline" id="edit_product" action="edit_product_submit.php">
                <h3>Edit Products</h3>
                <fieldset>
                    <legend>Product Details</legend>

                    <div class="form-group">
                        <p>
                            <label for="edit_product_sku">Edit by: SKU</label>
                            <input type="text" name="edit_product_sku" id="edit_product_sku" class="form-control" maxlength="255" size="25" />
                            <?php if (isset ($_SESSION[ "edit_product_v_sku"]) && $_SESSION[ "edit_product_v_sku"] !="" ) { echo $_SESSION[ "edit_product_v_sku"]; unset($_SESSION[ "edit_product_v_sku"]); } ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <p>
                            <label for="edit_product_pid">Or: Product ID</label>

                            <input type="text" name="edit_product_pid" id="edit_product_pid" class="form-control" maxlength="255" size="25" />
                            <?php if (isset ($_SESSION[ "edit_product_v_pid"]) && $_SESSION[ "edit_product_v_pid"] !="" ) { echo $_SESSION[ "edit_product_v_pid"]; unset($_SESSION[ "edit_product_v_pid"]); } ?>
                        </p>
                    </div>
                    <hr>
                    <div class="form-group">
                        <p>
                            <label for="edit_product_name">Name</label>
                            <input type="text" name="edit_product_name" id="edit_product_name" class="form-control" maxlength="255" size="50" />
                            <?php if (isset ($_SESSION[ "edit_product_v_name"]) && $_SESSION[ "edit_product_v_name"] !="" ) { echo $_SESSION[ "edit_product_v_name"]; unset($_SESSION[ "edit_product_v_name"]); } ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <p>
                            <label for="edit_product_type">Type</label>
                            <input type="text" name="edit_product_type" id="edit_product_type" class="form-control" maxlength="255" size="50" />
                            <?php if (isset ($_SESSION[ "edit_product_v_type"]) && $_SESSION[ "edit_product_v_type"] !="" ) { echo $_SESSION[ "edit_product_v_type"]; unset($_SESSION[ "edit_product_v_type"]); } ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <p>
                            <label for="edit_product_price">Price Per Unit $</label>
                            <input type="number" name="edit_product_price" id="edit_product_price" class="form-control" step="0.01" />
                            <?php if (isset ($_SESSION[ "edit_product_v_price"]) && $_SESSION[ "edit_product_v_price"] !="" ) { echo $_SESSION[ "edit_product_v_price"]; unset($_SESSION[ "edit_product_v_price"]); } ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <p>
                            <label for="edit_product_quantity">Quantity</label>
                            <input type="number" name="edit_product_quantity" class="form-control" id="edit_product_quantity" />
                            <?php if (isset ($_SESSION[ "edit_product_v_quantity"]) && $_SESSION[ "edit_product_v_quantity"] !="" ) { echo $_SESSION[ "edit_product_v_quantity"]; unset($_SESSION[ "edit_product_v_quantity"]); } ?>
                        </p>
                    </div>
                </fieldset>

                <?php if (isset ($_SESSION[ "edit_product_result"]) && $_SESSION[ "edit_product_result"] !="" ) { echo $_SESSION[ "edit_product_result"]; unset($_SESSION[ "edit_product_result"]); } ?>

                <input type="submit" class="btn btn-primary" value="Edit Product" />
            </form>

            <br>
            <hr>

            <form class="form-inline" method="post" action="find_product_submit.php">
                <h3>Find Product</h3>
                <fieldset>
                    <legend>Enter at Least 1 Product Characteristic</legend>
                    <div class="form-group">
                        <p>
                            <label for="find_product_id">Product ID</label>
                            <input type="text" name="find_product_id" id="find_product_id" class="form-control" maxlength="40" size="25" pattern="^[a-zA-Z0-9]+$" title="Must only be letters or numbers" />
                        </p>
                    </div>
                    <div class="form-group">
                        <p>
                            <label for="find_product_sku">SKU</label>
                            <input type="text" name="find_product_sku" id="find_product_sku" class="form-control" maxlength="40" size="25" pattern="^[a-zA-Z0-9]+$" title="Must only be letters or numbers" />
                        </p>
                    </div>
                    <div class="form-group">
                        <p>
                            <label for="find_product_name">Name</label>
                            <input type="text" name="find_product_name" id="find_product_name" class="form-control" maxlength="100" size="50" pattern="^[a-zA-Z0-9 -]+$" title="Can only contain A-Z, a-z, 0-9, and -" />
                        </p>
                    </div>
                    <div class="form-group">
                        <p>
                            <label for="find_product_type">Type</label>
                            <input type="text" name="find_product_type" id="find_product_type" class="form-control" maxlength="100" size="50" pattern="^[a-zA-Z0-9 -]+$" title="Can only contain A-Z, a-z, 0-9, and -" />
                        </p>
                    </div>
                </fieldset>
                <input type="submit" class="btn btn-primary" value="Find Product" />
            </form>
            <?php if (isset ($_SESSION[ "find_product_result"]) && $_SESSION[ "find_product_result"] !="" ) { $message=$_SESSION[ "find_product_result"]; echo "<div>", $message, "</div>"; $_SESSION[ "find_product_result"]="" ; } ?>
            <br>
            <hr>

            <form method="post" class="form-inline" id="find_limited" action="find_limited_submit.php">
                <h3>Find Limited Products</h3>
                <fieldset>
                    <legend>Find By</legend>
                    <div class="form-group">
                        <p>
                            <label for="limited">Limited</label>
                            <input type="radio" name="lowstock" class="form-check-input" value="limited" checked="checked" />
                            <label for="out_of_stock">Out of Stock</label>
                            <input type="radio" name="lowstock" class="form-check-input" value="out_of_stock" />
                        </p>
                    </div>

                    <p>Or</p>
                    <div class="form-group">
                        <p>
                            <label for="find_limited_less_than">Stock with Less Than</label>
                            <input type="number" name="find_limited_less_than" class="form-control" min="0" max="99999" />
                        </p>
                    </div>
                </fieldset>

                <?php if (isset ($_SESSION[ "find_limited_result"]) && $_SESSION[ "find_limited_result"] !="" ) { $message=$_SESSION[ "find_limited_result"]; echo "$message"; $_SESSION[ "find_limited_result"]="" ; } ?>

                <input type="submit" class="btn btn-primary" value="Find Limited Item(s)" />
            </form>
            <br>
            <hr>
        </article>
        <?php require_once ( "php/footer.php"); ?>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>