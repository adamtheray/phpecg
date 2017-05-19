<?php
$wwwDir=realpath(dirname(__FILE__))."/";
if(getcwd()!=$wwwDir)chdir($wwwDir);
if(!file_exists(".settings.csv"))copy("settings.template.csv",".settings.csv");
$settingsData=new CSVHandler(".settings.csv",",", "settingsIndex");
$templateSettings=new CSVHandler("settings.template.csv",",","settingsIndex");
$changeme=0;
foreach($templateSettings->getData() as $templateSetting){
	$settingFound=0;
	foreach($settingsData->getData() as $setting){
		if($setting['Name']=="path" && $setting['Value']!= "CHANGEME" && substr($setting['Value'],-1)!="/"){
			$setting['Value']=$setting['Value']."/";
			$settingsData->Update($setting['settingsIndex'],$setting);
		}
		if($templateSetting['Name']==$setting['Name']){
			$settingFound=1;
			break;
		}
	}
	if($settingFound==0){
		$settingsData->Add("settingsIndex", $templateSetting);
		$settingsData->WriteData();
	}
}
foreach($settingsData->getData() as $setting){
	$$setting['Name']=$setting['Value'];
	if($setting['Value']=="CHANGEME")$changeme=1;
}
if($changeme==1 && $_GET['file']!="settings"){
	$_GET['file']="settings";
	$_GET['action']="edit";
	print "<br><br><font color=\"red\">You need to change the default settings.</font><br><br>";
}
$configPath=$path."phpecg/";
if (!file_exists($configPath)) {
	mkdir($path.phpecg, 0775, true);
}
$dataFiles=array(attendants=>"attendants.csv",phoneExt=>"phoneExt.csv",dialplan=>"dialplan.csv",linekeys=>"linekeys.csv");
foreach($dataFiles as $var=>$dataFile){
	templateCheck($dataFile, $configPath);
	$varName=$var."Data";
	$$varName=new CSVHandler($configPath.$dataFile,",", $var."Index");
}
$accessData=$attendantsData->GetValues("access");
$phoneExtHeaders=$phoneExtData->getHeaders();
foreach($accessData as $access){
	if($access!=""){
		if(!in_array($access,$phoneExtHeaders))$phoneExtData->insertColumn($access);
	}
}
$goodColumns=array("mac","ext","tempExt","pass","phoneExtIndex","AltSIP","AltNTP","AltParkExt");
foreach($phoneExtHeaders as $header){
	if(!in_array($header,$accessData) && !in_array($header,$goodColumns)){
		$phoneExtData->deleteColumn($header);
	}
}
foreach($goodColumns as $column){
	if(!in_array($column,$phoneExtHeaders)){
		$phoneExtData->insertColumn($column);
	}
}
?>
