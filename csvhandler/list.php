<?php
include("class.CSVHandler.php");
$data=new CSVHandler("test.csv",";","record");
$data->ListAll();
?>