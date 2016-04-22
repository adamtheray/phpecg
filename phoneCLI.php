#!/usr/bin/php
<?php
include_once('class.CSVHandler.php');
include_once('settings.php');
switch ($argv[1]){
	case "update":
		echo "Updating extension";
		break;
	default:
		echo "Usage:\r\nphoneCLI.php [operation] [tempExt] [ext]\r\n";
}
?>
