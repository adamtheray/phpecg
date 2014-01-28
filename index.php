<html>
<head>
<title>Simple PHP Polycom Generator</title>
</head>
<body>
<a href="?action=edit&file=phone">Edit Phones</a>&nbsp;&nbsp;<a href="?action=edit&file=attendant">Edit Attendants</a>&nbsp;&nbsp;<a href="?action=edit&file=dialplan">Edit Dialplan</a>&nbsp;&nbsp;<a href="?action=writePhone">Write Phone Configs</a>&nbsp;&nbsp;<a href="?action=writeSIP">Write SIP Config</a>
<?php
include_once("csvhandler/class.CSVHandler.php");
include_once("functions.php");
$path="/tftpboot/test/";
$attendantFile="attendants.csv";
$phoneFile="phoneExt.csv";
$attData=new CSVHandler($attendantFile,",","phoneIndex");
$phoneData=new CSVHandler($phoneFile,",","attendantIndex");
$accessData=$attData->GetValues("access");
$phoneHeaders=$phoneData->getHeaders();
foreach($accessData as $access){
	if($access!=""){
		if(!in_array($access,$phoneHeaders))$phoneData->insertColumn($access);
	}
}
$goodColumns=array("mac","ext","pass","phoneIndex","attendantIndex");
foreach($phoneHeaders as $header){
	if(!in_array($header,$accessData) && !in_array($header,$goodColumns)){
		$phoneData->deleteColumn($header);
	}
}
switch($_GET['action']){
	case "edit":
		switch($_GET['file']){
			case "phone":
				$file=$phoneFile;
				$index="phoneIndex";
				break;
			case "attendant":
				$file=$attendantFile;
				$index="attendantIndex";
				break;
			case "dialplan":
				$file="dialplan.csv";
				$index="dpIndex";
				break;
			default:
				$errorText="<br><br><b>No File to edit</b>";
				exit($errorText);
				break;
		}
		$data=new CSVHandler($file,",",$index);
		$data->Edit();
		break;
	case "writePhone":
		if(!isset($_GET['mode'])){
			$extensions=$phoneData->GetValues("ext");
			$options="";
			foreach($extensions as $extension){
				$options.="<option value=\"$extension\">$extension</option>";
			}
			?><br><br><form method="GET" action=""><input type="hidden" name="action" value="writePhone" />Choose Extension: <select name="extension"/><option value="">All</option><?=$options;?></select><input type="submit" name="mode" value="Go!"></form>
			<?
		}
		elseif($_GET['confirm']!="yes" && $_GET['extension']=="")echo "<br><br><b>This will overwrite all existing configs. Do you wish to do this? <a href=\"?action=writePhone&confirm=yes&mode=multiple\">Yes</a>&nbsp;&nbsp;<a href=\".\">No</a></b>";
		else {
			include_once ("phoneConfigs.php");
		}
		break;
	case"writeSIP":
		if($_GET['confirm']!="yes" && $_GET['extension']=="")echo "<br><br><b>Writing this file will affect all phones. Do you wish to do this? <a href=\"?action=writeSIP&confirm=yes&mode=multiple\">Yes</a>&nbsp;&nbsp;<a href=\".\">No</a></b>";
		else {
			include_once("SIPConfigs.php");
		}
		break;
	default:
		break;
}
?>
</body>
</html>
