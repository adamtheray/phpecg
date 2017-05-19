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
  global $attendantsData,$SIPServer,$NTPServer,$reg1linekeys,$path,$ParkExtension;
	$attendantInt=0;
	$thisExtension=($thisPhoneExt['ext']==""?$thisPhoneExt['tempExt']:$thisPhoneExt['ext']);
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
  if(!$thisPhoneExt['AltParkExt']=="") $thisParkExt = $thisPhoneExt['AltParkExt'];
  else $thisParkExt=$ParkExtension;
  $thisParkExtExp = str_split($thisParkExt,1);
  $thisParkExt="";
  foreach ($thisParkExtExp as $var){
    $thisParkExt = $thisParkExt."\$FDialpad$var\$";
  }
  print($thisParkExt);
  $link=mysql_connect("localhost","root","");
  $db=mysql_select_db("asterisk",$link) or die("\r\nDB Conn Error: ".mysql_error()."\r\n");
  $query="SELECT data AS secret FROM sip WHERE id='$thisExtension' AND keyword='secret'";
  if(!$result=mysql_query($query,$link))die("\r\nMysqlError: ".mysql_error()."\r\nQuery:$query\r\n");
  $row=mysql_fetch_array($result);
  $password=mysql_num_rows($result)>0?$row['secret']:$thisPhoneExt['pass'];
	$command="cat reg.cfg.template | sed 's/SIPSERVER/$thisSIP/g' | sed 's/EXTENSION/".$thisExtension."/g' | sed 's/PASSWORD/".$password."/g' | sed 's/ATTENDANT/".$thisPhoneExt['attendants']."/g' | sed 's/SEPARATOR/,/g' | sed 's/NTPSERVER/$thisNTP/g' | sed 's/REG1LINEKEYS/".$reg1linekeys."/g' | sed 's/PARKEXT/".$thisParkExt."/g' > $path".$thisPhoneExt['mac']."-reg.cfg";
  shell_exec($command);
}
?>
