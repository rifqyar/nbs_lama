<?php
	$db=getDb();
	$js=$_POST['ID_JS'];
	$rq=$db->query("SELECT NO_CONT, VESSEL, VOYAGE,SIZE_,TYPE_,STATUS_,NAMA_BLOCK, SLOT_, BERAT, ID_PEL_TUJ FROM tb_cont_jobslip WHERE ID_JOB_SLIP='$js'");
	$req=$rq->fetchRow();
	
?>


<html><div align="left">
<H2>CONTAINER JOBSLIP</H2>
<BR>
<table border="0">
<tr>
<tD COLSPAN=3 align="left"><font size=4px><B>No. Container : <?=$req['NO_CONT'];?></B> </FONT></tD>
</tr>
<tr>
<td width="400"><font size=4px><?=$req['SIZE_'];?> /<?=$req['TYPE_'];?> / <?=$req['STATUS_'];?></B></font></tD> </font></td>
</tr>
<tr>
<td width="400"><font size=4px>Weight : <?=$req['BERAT'];?> Kgs</B></font></tD> </font></td>
</tr>
<tr>
<tD colspan=5><font size=3px><?=$req['VESSEL'];?> <?=$req['VOYAGE'];?><?=$req['ID_PEL_TUJ']?></font></td>
</tr>
<tr>
<tD colspan=5><font size=3px><b><?=$req['ID_PEL_TUJ']?></b></font></td>
</tr>
<tr>
<tD COLSPAN=5 align="left"><font size=4px><B>Blok <?=$req['NAMA_BLOCK'];?> - Slot <?=$req['SLOT_'];?>
</tr>
<tr height="20">
<tD COLSPAN=3></tD>
</tr>
<tr height="20">
<tD COLSPAN=5>REPRINT :: <b><?=$_SESSION['NAMA_LENGKAP']?> :: <?=date('d M Y h:i:s')?></b></tD>
</tr>
</table>
</div>
</html>