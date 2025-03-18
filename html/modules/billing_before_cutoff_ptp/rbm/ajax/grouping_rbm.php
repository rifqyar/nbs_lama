<?php
	$id_vsb=$_GET['id'];
	$ket=$_GET['ket'];
	$db=getDb();
	
		$qrbm="SELECT M.ID_VSB_VOYAGE,M.OPERATOR_ID, M.OPERATOR_NAME,
(select count(1) from bil_stv_list B where B.ID_VSB_VOYAGE=M.ID_VSB_VOYAGE AND SIZE_CONT=20 AND B.OPERATOR_ID=M.OPERATOR_ID AND B.E_I='I') AS QD20,
(select count(1) from bil_stv_list B where B.ID_VSB_VOYAGE=M.ID_VSB_VOYAGE AND SIZE_CONT>20 AND B.OPERATOR_ID=M.OPERATOR_ID AND B.E_I='I') AS QD40,
(select count(1) from bil_stv_list B where B.ID_VSB_VOYAGE=M.ID_VSB_VOYAGE AND SIZE_CONT=20 AND B.OPERATOR_ID=M.OPERATOR_ID AND B.E_I='E') AS QL20,
(select count(1) from bil_stv_list B where B.ID_VSB_VOYAGE=M.ID_VSB_VOYAGE AND SIZE_CONT>20 AND B.OPERATOR_ID=M.OPERATOR_ID AND B.E_I='E') AS QL40
, M.ID_KATEGORI 
 FROM 
(select A.ID_VSB_VOYAGE,A.OPERATOR_ID,A.OPERATOR_NAME, A.ID_KATEGORI 
from bil_stv_list A WHERE A.ID_VSB_VOYAGE='$id_vsb' group by A.ID_VSB_VOYAGE,A.OPERATOR_ID,A.OPERATOR_NAME, A.ID_KATEGORI  order by A.OPERATOR_ID) M";

	$rrbm=$db->query($qrbm);
	
	$grbm=$rrbm->getAll();
	//echo $qrbm;
	
	$qsf="select A.ID_VSB_VOYAGE,A.OPERATOR_ID, A.OPERATOR_NAME,B.SIZE_,A.JENIS_SHIFT,COUNT(1) QTY, A.ID_KATEGORI  
from bil_stv_sf a join master_iso_code b on a.iso_code=b.iso_code where A.ID_VSB_VOYAGE='$id_vsb' 
GROUP BY A.ID_VSB_VOYAGE,A.OPERATOR_ID, A.OPERATOR_NAME,B.SIZE_,A.JENIS_SHIFT, A.ID_KATEGORI order by A.OPERATOR_ID";

$rsf=$db->query($qsf);
	
	$gsf=$rsf->getAll();
	
	$qhm="select SUM(JUMLAH) QTY, ID_KATEGORI from bil_stv_hm where id_vsb_voyage='$id_vsb' AND ACTIVITY='O' GROUP BY ID_KATEGORI";

$rhm=$db->query($qhm);
	
	$ghm=$rhm->fetchRow();
	
	
?>

<script>
	$(document).ready(function() 
	{
		$('#hasil').load("<?=HOME?>billing.rbm.ajax/dhasil?id=<?=$id_vsb;?>");
	});

	function exttools(idvsb,nocont){
		$.post("<?=HOME?>billing.rbm.ajax/set_extratools",{ID_VSB:idvsb,NOCONT:nocont}, function(data){
			
			if (data == 'Y') {
				alert(nocont+' is assigned using extra tools');
			}
			else if (data == 'N') {
				alert(nocont+' is unassigned using extra tools');
			}
			jQuery("#drbm").jqGrid('setGridParam',{datatype: 'json'}).trigger('reloadGrid');
		});
	}

	function imoclass(idvsb,nocont){
		$.post("<?=HOME?>billing.rbm.ajax/set_imoclass",{ID_VSB:idvsb,NOCONT:nocont}, function(data){
			
			if (data == 'Y') {
				alert(nocont+' is assigned as DG no label');
			}
			else if (data == 'N') {
				alert(nocont+' is unassigned as DG no label');
			}
			else if (data == 'Z') {
				alert(nocont+' is not Hazard Container');
			}

			jQuery("#drbm").jqGrid('setGridParam',{datatype: 'json'}).trigger('reloadGrid');
		});
	}
	
	function sbm_rbm()
	{
		var array = [];
		var array2 = [];
		var array3 = [];
		$("[name=grbmx]").each(function(){
			var val = $(this).find(":selected").val();
			
			array.push(val);
		});
				
		$("[name=gsfx]").each(function(){
			var val2 = $(this).find(":selected").val();
			
			array2.push(val2);
		});
		
		$("[name=ghmx]").each(function(){
			var val3 = $(this).find(":selected").val();
			
			array3.push(val3);
		});
		var url="<?=HOME?>billing.rbm.ajax/sv_grbm";
		$.post(url,{KRBM:array, KSF:array2, KHM:array3, ID_VSB:<?=$id_vsb;?>}, function(data)
		{
			console.log(data);
			alert(data);
			$('#hasil').load("<?=HOME?>billing.rbm.ajax/dhasil?id=<?=$id_vsb;?>");
		});
	}
	
	function drbm(a,b)
	{
		$('#detailrbm').load("<?=HOME?>billing.rbm.ajax/drbm?opid="+a+"&idvsb="+b).dialog({modal:true, height:680,width:620,title: 'Detail RBM'});
	}
	function dsf(a,b)
	{
		$('#detailsf').load("<?=HOME?>billing.rbm.ajax/dsf?opid="+a+"&idvsb="+b).dialog({modal:true, height:400,width:400,title: 'Detail Shifting'});
	}
	function dhm(a,b)
	{
		$('#detailhm').load("<?=HOME?>billing.rbm.ajax/dhm?opid="+a+"&idvsb="+b).dialog({modal:true, height:450,width:620,title: 'Detail Hatch Movement'});
	}
</script>
<style>
table
{
border-collapse:collapse;
}
table, td, th
{
border:1px solid #b3b2b1;
}
</style>
<b><?=$ket;?></b>
<br><br>
<p align="left"><b><i>List Container</i></b></p>

<table border="1" align="center">
	<tr>
		<th class="grid-header" rowspan=2 width=20><b>No.</b></th>
		<th class="grid-header" rowspan=2 width=50><b>Action</b></th>
		<th class="grid-header" rowspan=2 width=100><b>OPERATOR</b></th>
		<th class="grid-header" width=90 colspan=3 ><b>DISCH</b></th>
		<th class="grid-header" width=90 colspan=3 ><b>LOAD</b></th>
		<th class="grid-header" rowspan=2 width=40><b>GROUP</b></th>
	</tr>
	<tr>
		<th class="grid-header" width=30><b>20</b></th>
		<th class="grid-header" width=30><b>40</b></th>
		<th class="grid-header" width=30><b>T</b></th>
		<th class="grid-header" width=30><b>20</b></th>
		<th class="grid-header" width=30><b>40</b></th>
		<th class="grid-header" width=30><b>T</b></th>
	</tr>
	<?php 
	$no=1;
	foreach($grbm as $row)
	{?>
	<tr>
		<td align="center"><?=$no;?></td>
		<td align="center"><button onclick="drbm('<?=$row['OPERATOR_ID'];?>','<?=$id_vsb;?>')">detail</button></td>
		<td align="center"><?=$row['OPERATOR_ID'];?> - <?=$row['OPERATOR_NAME'];?></td>
		
		<td align="center"><?=$row['QD20'];?></td>
		<td align="center"><?=$row['QD40'];?></td>
		<td align="center"><?=$row['QD20']+$row['QD40'];?></td>
		<td align="center"><?=$row['QL20'];?></td>
		<td align="center"><?=$row['QL40'];?></td>
		<td align="center"><?=$row['QL20']+$row['QL40'];?></td>
		<td align="center">
			<select id="grbm" name="grbmx">
				<?
					if($row['ID_KATEGORI']!='')
					{
						?>
						<option value='<?=$row['ID_KATEGORI'];?>'> <?=$row['ID_KATEGORI']?> </option>
						<?
					}
				?>
				<option value='1'> 1 </option>
				<option value='2'> 2 </option>
				<option value='3'> 3 </option>
				<option value='4'> 4 </option>
				<option value='5'> 5 </option>
			</select>
		</td>
	</tr>
	<?php $no++;}?>
</table>
<br>

<p align="left"><b><i>Shifting</i></b></p>

<table border="1" align="center">
	<tr>
		<th class="grid-header" width=20><b>No.</b></th>
		<th class="grid-header" width=50><b>Action</b></th>
		<th class="grid-header" width=100><b>OPERATOR</b></th>
		<th class="grid-header" width=40 ><b>SIZE</b></th>
		<th class="grid-header" width=60 ><b>TYPE SHIFT</b></th>
		<th class="grid-header" width=40 ><b>QTY</b></th>
		<th class="grid-header" width=40><b>GROUP</b></th>
	</tr>
		
	<?php 
	$no=1;
	foreach($gsf as $row)
	{?>
	<tr>
		<td align="center"><?=$no;?></td>
		<td align="center"><button onclick="dsf('<?=$row['OPERATOR_ID'];?>','<?=$id_vsb;?>')">detail</button></td>
		<td align="center"><?=$row['OPERATOR_ID'];?> - <?=$row['OPERATOR_NAME'];?></td>
		
		<td align="center"><?=$row['SIZE_'];?></td>
		<td align="center"><?=$row['JENIS_SHIFT'];?></td>
		<td align="center"><?=$row['QTY'];?></td>
		<td align="center">
			<select id="gsf" name="gsfx">
				
					<?
					if($row['ID_KATEGORI']!='')
					{
						?>
						<option value='<?=$row['ID_KATEGORI'];?>'> <?=$row['ID_KATEGORI']?> </option>
						<?
					}
				
				?>
				<option value='1'> 1 </option>
				<option value='2'> 2 </option>
				<option value='3'> 3 </option>
				<option value='4'> 4 </option>
				<option value='5'> 5 </option>
			</select>
		</td>
	</tr>
	<?php $no++;}?>
</table>
<br>

<p align="left"><b><i>Hatch Movement</i></b></p>

<table border="1" align="center">
	<tr>
		<th class="grid-header" width=20><b>No.</b></th>
		<th class="grid-header" width=50><b>Action</b></th>
		<th class="grid-header" width=50><b>QTY</b></th>
		<th class="grid-header" width=40><b>GROUP</b></th>
	</tr>
		
	<?php 
	$no=1;
	IF($ghm['QTY']>0)
	{?>
	<tr>
		<td align="center"><?=$no;?></td>
		<td align="center"><button onclick="dhm('<?=$ghm['OPERATOR_ID'];?>','<?=$id_vsb;?>')">detail</button></td>
		<td align="center"><?=$ghm['QTY'];?></td>
		<td align="center">
			<select id="ghm" name="ghmx">
				<?
					if($ghm['ID_KATEGORI']!='')
					{
						?>
						<option value='<?=$ghm['ID_KATEGORI'];?>'> <?=$ghm['ID_KATEGORI']?> </option>
						<?
					}
				?>
				<option value='1'> 1 </option>
				<option value='2'> 2 </option>
				<option value='3'> 3 </option>
				<option value='4'> 4 </option>
				<option value='5'> 5 </option>
			</select>
		</td>
	</tr>
	<?php $no++;}?>
</table>
<br>
<p align="center"><button onclick="sbm_rbm()">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;G E N E R A T E&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></p>
<br>
<hr width="420" />
<br>
<div id="hasil"></div>

<div>
	<div id="detailrbm"></div>
	<div id="detailsf"></div>
	<div id="detailhm"></div>
</div>