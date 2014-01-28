<?php
include("class.CSVHandler.php");
include("class.Viewvar.php");
$data=new CSVHandler("test.csv",";","record");
$result=$data->Select("9081","Phone");
new viewvar("Select",$result);
?>