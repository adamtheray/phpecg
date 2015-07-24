<?php
foreach($phoneExtData->getData() as $thisPhoneExt){
	if($thisPhoneExt['ext']==$_GET['extension'] || $_GET['extension']==""){
		$attendantInt=0;
		$thisPhoneExt['attendants']="";
		foreach($attendantsData->getData() as $thisAttendant){
			if ($thisPhoneExt['ext']==$thisAttendant['code']) {
				$access=0;
			}
			elseif($thisAttendant['access']!=""){
				if($thisPhoneExt[$thisAttendant['access']]==""){
					$access=0;
				}
				else {
					$access=1;
				}
			}
			else $access=1;
			if($access==1){
				$attendantInt++;
				if($attendantInt==1)$thisPhoneExt['attendants']="<attendant ";
				$thisPhoneExt['attendants'].='attendant.resourceList.'.$attendantInt.'.address="'.$thisAttendant['code'].'" attendant.resourceList.'.$attendantInt.'.label="'.$thisAttendant['label'].'" ';
			}
		}
		if($attendantInt>0){
			$thisPhoneExt['attendants'].=" \/>";
			$thisPhoneExt['attendants']=str_replace("EXTENSION",$thisPhoneExt['ext'],$thisPhoneExt['attendants']);
		}
		if(!$thisPhoneExt['AltSIP']=="") $thisSIP=$thisPhoneExt['AltSIP'];
		else $thisSIP=$SIPServer;
		if(!$thisPhoneExt['AltNTP']=="") $thisNTP=$thisPhoneExt['AltNTP'];
		else $thisNTP=$NTPServer;
		$command="cat extension.cfg.template | sed 's/SIPSERVER/$thisSIP/g' | sed 's/EXTENSION/".$thisPhoneExt['ext']."/g' | sed 's/PASSWORD/".$thisPhoneExt['pass']."/g' | sed 's/ATTENDANT/".$thisPhoneExt['attendants']."/g' | sed 's/SEPARATOR/,/g' | sed 's/NTPSERVER/$thisNTP/g' > $path".$thisPhoneExt['ext'].".cfg";
		$command2="cat mac.cfg.template | sed 's/EXTENSION/".$thisPhoneExt['ext']."/g' > $path".$thisPhoneExt['mac'].".cfg";
		shell_exec($command);
		shell_exec($command2);
		if($_GET['extension']!="")break;
	}
}
?>
