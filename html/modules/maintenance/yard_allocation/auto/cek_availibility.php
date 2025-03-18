<?php
$id_vs	= $_POST['ID_VS'];
$size	= $_POST['SIZE'];
$type	= $_POST['TYPE'];
$tuj	= $_POST['TUJ'];
$status	= $_POST['STATUS'];

$db 			= getDB();
	
$query 			= "SELECT SIZE_CONT,BOX, TEUS,ID_BOOK, PELABUHAN_TUJUAN FROM TB_BOOKING_CONT_AREA 
					WHERE ID_VS='$id_vs' AND SIZE_CONT='$size' AND TYPE_CONT='$type' AND STATUS_CONT='$status' AND ID_PEL_TUJ=REGEXP_REPLACE('$tuj', '[[:space:]]', '' ) ";
//print_r($query);die;
$result			= $db->query($query);
$row			= $result->fetchrow();	
$a= $row['PELABUHAN_TUJUAN'];
$b= $row['BOX'];
$c= $row['TEUS'];
$d=	$row['SIZE_CONT'];
$e=	$row['ID_BOOK'];

if (count($b)<>'')
{
	$xx="<TABLE><tr><td align='center'><i>Available Booking Container <BR><B>$size/$type/$status</B><br> with destination to<BR> <B>$a</B> is <br><font color='red'></i><B>$b box $c teus</B></font></td></tr></TABLE>";
	
}
else
{
	$xx= "<font color='red'>There's no booking <br>for those<BR>container specification</font>";
}	
	echo $xx.",".$d.",".$b.",".$e;

?>