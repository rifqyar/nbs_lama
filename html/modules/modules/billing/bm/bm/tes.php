<?php
$pdf = "report/sample.pdf";
$info = pathinfo($pdf);
$file_name =  basename($pdf,'.'.$info['extension']);
echo $file_name;
$pdf = "sample.pdf[0]";
exec("convert $pdf convert-img/$file_name.jpg");    
?>