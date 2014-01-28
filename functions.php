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
?>
