<?php
$xml_str = $_POST["xml_"];

$myFile = "saved_file.xml";
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh, $xml_str);
fclose($fh);

echo "success";

?>