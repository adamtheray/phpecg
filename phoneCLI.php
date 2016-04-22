#!/usr/bin/php
<?php
include_once('class.CSVHandler.php');
include_once('settings.php');
switch ($argv[1]){
	case "login":
		if (!$argv[2]||!$argv[3]){}
		die (usage());
		break;
	default:
		usage();
}
function usage(){
	$um="Usage:\r\nphoneCLI.php [operation] [tempExt] [ext]\r\n";
	return $um;
}
?>
