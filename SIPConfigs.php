<?php
$dialplans=csvArray("dialplan.csv");
$dialplanDigitMap="";
$dialplanTimeouts="";
foreach($dialplans as $dialplan){
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
$command="cat sip-basic.cfg.template | sed 's/DIALPLAN/$dialplanText/g' | sed 's/SEPARATOR/,/g' > ".$path."sip-basic.cfg";
#echo ($command."\r\n");
echo (shell_exec($command));
?>
