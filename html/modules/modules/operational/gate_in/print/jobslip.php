<?
	$block=$_POST['BLOK'];
	$slot=$_POST['SLOT'];
	$no_req=$_POST['NO_REQ'];
	$no_cont=$_POST['NO_CONT'];
	$db=getDb();
	$rq=$db->query("SELECT NO_UKK, VESSEL,VOYAGE FROM REQ_RECEIVING_H WHERE TRIM(ID_REQ)=TRIM('$no_req')");
	$req=$rq->fetchRow();
	$ukk=$req['NO_UKK'];
	$rq2=$db->query("SELECT SIZE_, TYPE_, STATUS_, NAMA_BLOCK, SLOT_, BERAT, ID_PEL_TUJ FROM TB_CONT_JOBSLIP WHERE TRIM(ID_VS)=TRIM('$ukk') AND NO_CONT='$no_cont'");
	$req2=$rq2->fetchRow();
	
?>


<html><div align="left">
<H2>CONTAINER JOBSLIP</H2>
<BR>
<table border="0">
<tr>
<tD COLSPAN=3 align="left"><font size=4px><B>No. Container : <?=$no_cont;?></B> </FONT></tD>
</tr>
<tr>
<td width="400"><font size=4px><?=$req2['SIZE_'];?> /<?=$req2['TYPE_'];?> / <?=$req2['STATUS_'];?></B></font></tD> </font></td>
</tr>
<tr>
<td width="400"><font size=4px>Weight : <?=$req2['BERAT'];?> Kgs</B></font></tD> </font></td>
</tr>
<tr>
<tD colspan=5><font size=3px><?=$req['VESSEL'];?> <?=$req['VOYAGE'];?></font></td>
</tr>
<tr>
<tD colspan=5><font size=3px><b> <?=$req2['ID_PEL_TUJ']?></b></font></td>
</tr>
<tr>
<tD COLSPAN=5 align="left"><font size=4px><B>Blok <?=$block;?> - Slot <?=$slot;?>
</tr>
<tr height="20">
<tD COLSPAN=3></tD>
</tr>
<tr height="20">
<tD COLSPAN=5>PRINT :: <b><?=$_SESSION['NAMA_LENGKAP']?> :: <?=date('d M Y h:i:s')?></b></tD>
</tr>
</table>
</div>
</html>