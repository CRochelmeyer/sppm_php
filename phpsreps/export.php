<?php
session_start();

$from = $_SESSION['rdatefrom'];
$to = $_SESSION['rdateto'];
$host = "127.0.0.1";
$user = "sdpm_user";
$pwd = "dBw-Fdu-c6q-tXF";
$sql_db = "sdpm_db";

$conn = @mysqli_connect( $host, $user, $pwd, $sql_db );

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$filename = "sr_".$from."to".$to.".csv";
$filename = str_replace("-","_",$filename);

$sql="SELECT ps.product_id, ps.quantity_sold, s.sale_id, s.time_created, s.sale_amount, p.quantity, p.sku, p.name, p.type 
    FROM product_sale_item ps
    INNER JOIN sale s ON ps.sale_id=s.sale_id 
    INNER JOIN product p ON p.product_id=ps.product_id
    WHERE s.time_created BETWEEN '$from' AND'$to';";

header('Content-type: text/csv');
header('Content-Disposition: attachment; filename='.$filename);

$fp = fopen('php://output', 'w');
fputcsv($fp, array('Product ID', 'Quantity Sold', 'Sale ID', 'Sale Date', 'Sale Amount', 'Quantity', 'SKU', 'Name', 'Type')); 
$result = $conn->query($sql);
while($row = mysqli_fetch_row($result)) {
  fputcsv($fp, $row);
}
fclose($fp);
$conn->close();
?>