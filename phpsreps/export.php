<?php
session_start();

$from = $_SESSION['rdatefrom'];
$to = $_SESSION['rdateto'];

require_once("php/settings.php");
$conn = @mysqli_connect( $host, $user, $pwd, $sql_db );
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$filename = "sr.".$from."to".$to.".csv";
$fp = fopen('php://output', 'w');

$sql="SELECT ps.product_id, ps.quantity_sold, s.sale_id, s.time_created, s.sale_amount, p.quantity, p.sku, p.name, p.type 
    FROM product_sale_item ps
    INNER JOIN sale s ON ps.sale_id=s.sale_id 
    INNER JOIN product p ON p.product_id=ps.product_id
    WHERE s.time_created BETWEEN '$from' AND'$to';";

$result = $conn->query($sql);
  while ($row = mysqli_fetch_row($result)) {
  $header[] = $row[0];
}

header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);
fputcsv($fp, $header);

$num_column = count($header);   
$result = $conn->query($sql);
while($row = mysqli_fetch_row($result)) {
  fputcsv($fp, $row);
}

?>