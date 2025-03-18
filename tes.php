<?php 
error_reporting(E_ALL);

$pagename = 'my_page1';

$newFileName = './report/pdf/'.$pagename.".php";
$newFileContent = '<?php echo "something..."; ?>';

if(file_put_contents($newFileName,$newFileContent)!=false){
    echo "File created (".basename($newFileName).")";
}else{
    echo "Cannot create file (".basename($newFileName).")";
}

?>