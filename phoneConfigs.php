<?php
$phoneExt=csvArray("phoneExt.csv");
$attendants=csvArray("attendants.csv");
foreach($phoneExt as $thisPhoneExt){
	if($thisPhoneExt['ext']==$_GET['extension'] || $_GET['extension']==""){
		$attendantInt=0;
		$thisPhoneExt['attendants']="";
		foreach($attendants as $attendant){
			if($attendant['access']!=""){
				if($thisPhoneExt[$attendant['access']]==""){
					$access=0;
				}
				else $access=1;
			}
			else $access=1;
			if($access==1){
				$attendantInt++;
				if($attendantInt==1)$thisPhoneExt['attendants']="<attendant ";
				$thisPhoneExt['attendants'].='attendant.resourceList.'.$attendantInt.'.address="'.$attendant['code'].'" attendant.resourceList.'.$attendantInt.'.label="'.$attendant['label'].'" ';
			}
		}
		if($attendantInt>0){
			$thisPhoneExt['attendants'].=" \/>";
			$thisPhoneExt['attendants']=str_replace("EXTENSION",$thisPhoneExt['ext'],$thisPhoneExt['attendants']);
		}
		$command="cat reg.cfg.template | sed 's/EXTENSION/".$thisPhoneExt['ext']."/g' | sed 's/PASSWORD/".$thisPhoneExt['pass']."/g' | sed 's/ATTENDANT/".$thisPhoneExt['attendants']."/g' | sed 's/SEPARATOR/,/g' > $path".$thisPhoneExt['mac']."-reg.cfg";
		#echo ($command."\r\n");
		echo (shell_exec($command));
		if($_GET['extension']!="")break;
	}
}
?>
