<?php
foreach($phoneExtData->getData() as $thisPhoneExt){
	if($thisPhoneExt['ext']==$_GET['extension'] || $_GET['extension']==""||$cli==true){
		genPhoneConfig($thisPhoneExt);
		if($_GET['extension']!="")break;
	}
}
?>
