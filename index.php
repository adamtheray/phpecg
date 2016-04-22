<html>
<head>
<title>Simple PHP Polycom Generator</title>
</head>
<body>
<a href="?action=edit&file=phoneExt">Edit Phones</a>&nbsp;&nbsp;<a href="?action=edit&file=attendants">Edit Attendants</a>&nbsp;&nbsp;<a href="?action=edit&file=linekeys">Edit LineKeys</a>&nbsp;&nbsp;<a href="?action=edit&file=dialplan">Edit Dialplan</a>&nbsp;&nbsp;<a href="?action=writePhone">Write Phone Configs</a>&nbsp;&nbsp;<a href="?action=writeSIP">Write SIP Config</a>&nbsp;&nbsp;<a href="?action=edit&file=settings">Edit Settings</a>
<?php
include_once("class.CSVHandler.php");
include_once("functions.php");
include_once("settings.php");
switch($_GET['action']){
	case "edit":
		switch ($_GET['file']){
			case "settings":
				$file=".settings.csv";
				$index="settingsIndex";
				break;
			default:
				if($_GET['file']==""){
					$errorText="<br><br><b>No File to edit</b>";
					exit($errorText);
				}
				else {
					$file=$configPath.$dataFiles[$_GET['file']];
					$index=$_GET['file']."Index";
				}
				break;
		}
		$data=new CSVHandler($file,",",$index);
		$data->Edit();
		break;
	case "writePhone":
		if(!isset($_GET['mode'])){
			$extensions=$phoneExtData->GetValues("ext");
			$options="";
			foreach($extensions as $extension){
				$options.="<option value=\"$extension\">$extension</option>";
			}
			?><br><br><form method="GET" action=""><input type="hidden" name="action" value="writePhone" />Choose Extension: <select name="extension"/><option value="">All</option><?php print $options;?></select><input type="submit" name="mode" value="Go!"></form>
			<?php
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
