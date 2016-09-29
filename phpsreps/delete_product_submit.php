<?php
	
	session_start();					

	include_once("php/settings.php");
	$conn = @mysqli_connect( $host, $user, $pwd, $sql_db );

	if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
	}

	if(isset($_POST['bulk_delete_submit'])){
        $idArr = $_POST['checked_id'];
        foreach($idArr as $sku){
            mysqli_query($conn,"DELETE FROM product WHERE sku =".$sku);
        }
        $_SESSION['success_msg'] = 'Item(s) have been deleted successfully.';
        header("Location:product_management.php");
    }
	
?>