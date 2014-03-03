<?php
if(!file_exists(".settings.csv"))copy("settings.template.csv",".settings.csv");
$settingsData=new CSVHandler(".settings.csv",",", "settingsIndex");
$templateSettings=new CSVHandler("settings.template.csv",",","settingsIndex");
$changeme=0;
foreach($templateSettings->getData() as $templateSetting){
	$settingFound=0;
	foreach($settingsData->getData() as $setting){
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
?>