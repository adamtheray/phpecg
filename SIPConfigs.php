<?php
$dialplanDigitMap="";
$dialplanTimeouts="";
foreach($dialplanData->getData() as $dialplan){
	$dialplanDigitMap.=$dialplan['dialString']."|";
	if($dialplan['timeout']=="")$dialplan['timeout']=3;
	$dialplanTimeouts.=$dialplan['timeout']."|";
}
$dialplanDigitMap=rtrim($dialplanDigitMap,"|");
$dialplanTimeouts=rtrim($dialplanTimeouts,"|");
if($dialplanDigitMap!=""){
	$dialplanText=" dialplan.digitmap=\"$dialplanDigitMap\" ";
	if($dialplanTimeouts!="")$dialplanText.="dialplan.digitmap.timeout=\"$dialplanTimeouts\" ";
}
else $dialplanText="";
$parkText="";
for($i=0;$i<strlen($ParkExtension);$i++){
	$thisChar=substr($ParkExtension,$i,1);
	$parkText.="\$FDialPad$thisChar\$";
}
if ($linekeysYorN=="Y"){
	$linekeyText="<lineKey ";
	$i=1;
	$sd=1;
	$line=1;
	foreach ($linekeysData->getData() as $linekey){
		switch ($linekey['type']){
			case "Line":
				$lineIndex=$line;
				$line++;
				break;
			case "speedDial":
				$lineIndex=$sd;
				$sd++;
				break;
			default:
				$lineIndex=0;
				break;
		}
		$linekeyText.="lineKey.$i.index=\"$lineIndex\" lineKey.$i.category=\"".$linekey['type']."\" ";
		$i++;
	}
	while ($i<252){
		switch ($linekeysUnassignedType){
			case "Line":
				$lineIndex=$line;
				$line++;
				break;
			case "speedDial":
				$lineIndex=$sd;
				$sd++;
				break;
			default:
				$lineIndex=0;
				break;
		}
		$linekeyText.="lineKey.$i.index=\"$lineIndex\" lineKey.$i.category=\"$linekeysUnassignedType\" ";
		$i++;
	}
	$linekeyText.="><lineKey.reassignment lineKey.reassignment.enabled=\"1\" \/><\/linekey>";
}
else $linekeyText="";
$command="cat sip-basic.cfg.template | sed 's/DIALPLAN/$dialplanText/g' | sed 's/SEPARATOR/,/g' | sed 's/CALLPARK/$parkText/g' | sed 's/LINEKEYS/$linekeyText/g' > ".$path."sip-basic.cfg";
shell_exec($command);
?>
