#!/usr/bin/php
<?php
include_once('class.CSVHandler.php');
include_once('functions.php');
include_once('settings.php');
$ext=$argv[2];
$tempExt=$argv[3];
switch ($argv[1]){
	case "logon":
		if (!$argv[2]||!$argv[3]) die (usage("You need to provide values for both the tempext and the ext variables to use the login function."));
		$tempExtFound=false;
		foreach($phoneExtData->getData() as $thisPhoneExt){
			if($thisPhoneExt['ext']==$ext)die("[ERROR] Found a different phone (MAC: ".$thisPhoneExt['mac'].") configured with extension $ext. Could not log new phone in.\r\n\r\n");
		}
		foreach($phoneExtData->getData() as $thisPhoneExt){
			if($thisPhoneExt['tempExt']==$tempExt){
				$thisPhoneExt['ext']=$ext;
				$phoneExtData->Update($thisPhoneExt['phoneExtIndex'],$thisPhoneExt);
				genPhoneConfig($thisPhoneExt);
				$tempExtFound=true;
				break;
			}
		}
		if($tempExtFound==false)die("\r\n[ERROR] Could not find the temp extension in the extensions file. Please add it with the GUI and try again.\r\n\r\n");
		break;
	case "logout":
		if (!$argv[2]) die (usage("You need to provide a value for the ext variable to use the logout function."));
		$extFound=false;
		foreach($phoneExtData->getData() as $thisPhoneExt){
			if($thisPhoneExt['ext']==$ext){
				$thisPhoneExt['ext']="";
				$phoneExtData->Update($thisPhoneExt['phoneExtIndex'],$thisPhoneExt);
				genPhoneConfig($thisPhoneExt);
				$extFound=true;
			}
		}
		if($extFound==false)die("\r\n[ERROR] Could not find the extension $ext in the configuration file.\r\n");
		break;
	default:
		die(usage("You need to provide an operation value."));
}
function usage($message=""){
	$um="\r\nUsage:\r\nphoneCLI.php [operation] [ext] [tempExt]\r\n\r\n";
	if($message!="")$um.="[ERROR] $message\r\n\r\n";
	return $um;
}
?>

