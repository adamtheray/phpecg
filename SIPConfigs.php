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
$command="cat sip-basic.cfg.template | sed 's/DIALPLAN/$dialplanText/g' | sed 's/SEPARATOR/,/g' | sed 's/SIPSERVER/$SIPServer/g' > ".$path."sip-basic.cfg";
shell_exec($command);
?>
