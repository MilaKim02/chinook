<?php
require "dbconnection.php";
$dbcon = createDbConnection();

$invoice_id = 1;

$sql = "DELETE FROM invoice_items WHERE InvoiceLineId=$invoice_id"; 

$dbcon->exec($sql);  
