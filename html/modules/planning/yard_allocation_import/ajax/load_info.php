<?php
	$db=getDb();
	
	$yard_id  = $_GET['id'];
    $no_ukk   = $_GET['id_vs'];
	$id_blok   = $_GET['id_block'];
	$kate	   = $_GET['kategori'];
	
	
	//first
	$rr1=$db->query("SELECT NAME FROM YD_BLOCKING_AREA WHERE ID = '$id_blok'");
	$rt1=$rr1->fetchrow();
	$name =$rt1['NAME'];
	
	
	$rr=$db->query("SELECT MAX(SLOT_) SLOT, MAX(ROW_) ROW_, (COUNT(1)*5) KAPASITAS FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_blok'");
	$rt=$rr->fetchrow();

	$slot = $rt['SLOT'];
	$row_  = $rt['ROW_'];
    $kapasitas  = $rt['KAPASITAS'];
	
	$rr5=$db->query("SELECT count(id_placement) exist FROM yd_placement_yard WHERE ID_BLOCKING_AREA = '$id_blok'");
	$rt5=$rr5->fetchrow();

	$exist = $rt5['EXIST'];
	
	$nukk = $_SESSION['NO_UKK'];
	//$id_book = $_SESSION['ID_BOOK'];
	$idb  = $_GET['idb'];

	$rr6=$db->query("  SELECT a.KATEGORI, a.BOX,
                             a.TEUS,
                             a.ALLOCATED,
                             a.ALLOCATED_LEFT
                          FROM TB_BOOKING_CONT_AREA_GR a
                         WHERE a.ID_VS = TRIM('$nukk') AND a.E_I = 'I'
                         and a.ID_KATEGORI='$kate'
                         ");
	$rt6=$rr6->fetchrow();

	$kateg = $rt6['KATEGORI'];
	$teus = $rt6['TEUS'];
	$allo = $rt6['ALLOCATED'];
	$left = $rt6['ALLOCATED_LEFT'];
	
	
	$rr7=$db->query("  SELECT ALOKASI, TOTAL_ALOKASI,SISA_ALOKASI_KATEGORI,SISA_KATEGORI_BLOK
                          FROM YD_TEMP_ALOKASI 
                         WHERE ID_BLOCK = TRIM('$id_blok')
						 AND ID_KATEGORI = '$kate'
                         ");
	$rt7=$rr7->fetchrow();

	$a = $rt7['TOTAL_ALOKASI'];
	$b = $rt7['SISA_ALOKASI_KATEGORI'];
	$c = $rt7['SISA_KATEGORI_BLOK'];
	$d = $rt7['ALOKASI'];
	
	
	
	//second
	
?>
 <fieldset style="margin: 0px 0px 0px 0px; height:200px; background-color=green;vertical-align:middle; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">

<? if ($_GET['type'] == 'first'){?>
<table>
<tr>
<td colspan='3'><b> <font color="blue"><font size='3'>Block Allocation Information</font></font> <b></td></tr>
<tr><td colspan='7'></td></tr>
<tr><td>Block Name</td><td>:</td><td><b><?=$name?></b></td><td width="30"></td><td rowspan="7">
<table>
<tr><td><font size="3"><b><font color="blue">Kategori</font></b></font></td><td >:</td><td ><b><?=$kateg?></b></td></tr>
<tr><td><font size="3"><blink><b><font color="red">Total this category</font></b></blink></font></td><td >:</td><td ><b><?=$teus?> TEUS</b></td></tr>
<tr><td><b><font color="red">Allocated</font></b></td><td >:</td><td ><b><?=$allo?> TEUS </b> </td></tr>
<tr><td><b><font color="red">Allocated Left</font></b></td><td >:</td><td><b><?=$left?> TEUS</b></td></tr>
</table>
</td>
</tr>

<tr><td>Assumption maximum tier  </td><td>:</td><td><b>5 tier</b></td><td width="100"></td></tr>
<tr><td>Total Capacity </td><td>:</td><td><b><?=$kapasitas?> TEUS</b></td><td width="100"></td></tr>
<tr><td>Total Container Stacking in Block <?=$name?></td><td>:</td><td><b><?=$exist;?> TEUS</b></td></tr>
<tr><td colspan='3'></td></tr>

<?$free = $kapasitas - $exist;?>
<tr><td>Total Container that can be allocated </b></td><td></td><td></td></tr>
<tr><td>20 Feet </td><td>:</td><td><b><?=$free?> BOX</b></td></tr>
<tr><td>40 Feet </td><td>:</td><td><b><?=floor($free/2);?> BOX</b></td></tr>
</table>

<?} else {?>


<table>
<tr>
<td colspan='3'><b> <font color="blue"><font size='3'>Block Allocation Information</font></font> <b></td></tr>
<tr><td colspan='7'></td></tr>
<tr><td>Block Name</td><td>:</td><td><b><?=$name?></b></td><td width="30"></td><td rowspan="7">
<table>
<tr><td><font size="3"><b><font color="blue">Kategori</font></b></font></td><td ><font color="blue"><b>:</b></font></td><td ><font color="blue"><b><?=$kateg?></font></b></td></tr>
<tr><td><font size="3"><blink><b><font color="red">Total this category</font></b></blink></font></td><td >:</td><td ><b><?=$teus?> TEUS</b></td></tr>
<tr><td><b><font color="red">Allocated</font></b></td><td >:</td><td ><b><?=$allo?> TEUS </b> </td></tr>
<tr><td><b><font color="red">Allocated Left</font></b></td><td >:</td><td><b><?=$left?> TEUS</b></td></tr>
<tr height='10'><td colspan='3'></td></tr>
<tr><td><b><font color="blue">Allocation plan</font></b></td><td >:</td><td ><b><?=$d?> TEUS </b> </td></tr>
<tr><td><b><font color="blue">Total allocation plan</font></b></td><td >:</td><td ><b><?=$a?> TEUS </b> </td></tr>
<tr><td><b><font color="blue">Allocated left in this category</font></b></td><td >:</td><td><b><?=$b?> TEUS</b></td></tr>
<tr><td><b><font color="blue">Allocated left in this block</font></b></td><td >:</td><td><b><?=$c?> TEUS</b></td></tr>
</table>
</td>
</tr>

<tr><td>Assumption maximum tier  </td><td>:</td><td><b>5 tier</b></td><td width="100"></td></tr>
<tr><td>Total Capacity </td><td>:</td><td><b><?=$kapasitas?> TEUS</b></td><td width="100"></td></tr>
<tr><td>Total Container Stacking in Block <?=$name?></td><td>:</td><td><b><?=$exist;?> TEUS</b></td></tr>
<tr><td colspan='3'></td></tr>

<?$free = $kapasitas - $exist;?>
<tr><td>Total Container that can be allocated </b></td><td></td><td></td></tr>
<tr><td>20 Feet </td><td>:</td><td><b><?=$free?> BOX</b></td></tr>
<tr><td>40 Feet </td><td>:</td><td><b><?=floor($free/2);?> BOX</b></td></tr>
</table>

<?}?>
</fieldset>