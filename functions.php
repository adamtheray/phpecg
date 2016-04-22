<?php
function csvArray($filename='', $delimiter=',')
{
    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}
function templateCheck($template,$directory){
	$templateExp=explode(".",$template);
	$templateName="";
	for($i=0;$i<count($templateExp)-1;$i++){
		$templateName.=$templateExp[$i];
	}
	$templateName.=".template.".$templateExp[count($templateExp)-1];
	if(!file_exists($directory.$template))copy($templateName,$directory.$template);
}
function genPhoneConfig($thisPhoneExt){
  global $attendantsData,$SIPServer,$NTPServer,$reg1linekeys,$path;
  //print ("SIP: ".$thisSIP."<br>");
  //print ("This Extension Data: <br />");
  //print_r($thisPhoneExt);
	$attendantInt=0;
	$thisExtension=($thisPhoneExt['ext']==""?$thisPhoneExt['tempExt']:$thisPhoneExt['ext']);
  //print("<br /><br />This Extension: ".$thisExtension."<br /><br />");
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
		$thisPhoneExt['attendants']=str_replace("EXTENSION",$thisExtension,$thisPhoneExt['attendants']);
	}
	if(!$thisPhoneExt['AltSIP']=="") $thisSIP=$thisPhoneExt['AltSIP'];
	else $thisSIP=$SIPServer;
	if(!$thisPhoneExt['AltNTP']=="") $thisNTP=$thisPhoneExt['AltNTP'];
	else $thisNTP=$NTPServer;
	$command="cat reg.cfg.template | sed 's/SIPSERVER/$thisSIP/g' | sed 's/EXTENSION/".$thisExtension."/g' | sed 's/PASSWORD/".$thisPhoneExt['pass']."/g' | sed 's/ATTENDANT/".$thisPhoneExt['attendants']."/g' | sed 's/SEPARATOR/,/g' | sed 's/NTPSERVER/$thisNTP/g' | sed 's/REG1LINEKEYS/".$reg1linekeys."/g' > $path".$thisPhoneExt['mac']."-reg.cfg";
  //print("Command: ".$command);
	shell_exec($command);
}
?>
