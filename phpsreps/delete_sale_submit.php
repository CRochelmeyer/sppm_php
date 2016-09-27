<?php
	
	session_start();					

	include_once("php/settings.php");
	$conn = @mysqli_connect( $host, $user, $pwd, $sql_db );

	if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
	}

	if(isset($_POST['bulk_delete_submit'])){
        $idArr = $_POST['checked_id'];
        foreach($idArr as $sid){
            mysqli_query($conn,"DELETE product_sale_item, sale FROM product_sale_item INNER JOIN sale ON product_sale_item.sale_id=sale.sale_id WHERE sale.sale_id =".$sid);
        }
        $_SESSION['success_msg'] = 'Sale has been deleted successfully.';
        header("Location:sales_management.php");
    }
	
?>