<?php
if(!file_exists(".settings.csv"))copy("settings.template.csv",".settings.csv");
$settingsData=new CSVHandler(".settings.csv",",", "settingsIndex");
foreach($settingsData->getData() as $setting){
	$$setting['Name']=$setting['Value'];
}
?>