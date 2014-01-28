<?php
foreach($phoneExtData->getData() as $thisPhoneExt){
	if($thisPhoneExt['ext']==$_GET['extension'] || $_GET['extension']==""){
		$attendantInt=0;
		$thisPhoneExt['attendants']="";
		foreach($attendantsData->getData() as $thisAttendant){
			if($thisAttendant['access']!=""){
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
		$command="cat reg.cfg.template | sed 's/EXTENSION/".$thisPhoneExt['ext']."/g' | sed 's/PASSWORD/".$thisPhoneExt['pass']."/g' | sed 's/ATTENDANT/".$thisPhoneExt['attendants']."/g' | sed 's/SEPARATOR/,/g' > $path".$thisPhoneExt['mac']."-reg.cfg";
		shell_exec($command);
		if($_GET['extension']!="")break;
	}
}
?>
