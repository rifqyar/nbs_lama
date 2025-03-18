<?php

	$js=$_GET['jobslip'];
	$block=$_GET['block_id'];
	$slot=$_GET['slot_id'];
	$row=$_GET['row_id'];
	$tier=$_GET['tier_id'];
	$alat=$_GET['id_alat'];
	$user=$_GET['id_user'];
	$db=getdb();
	$q1="SELECT b.NO_CONT,b.SIZE_, b.TYPE_, b.STATUS_, b.ID_BLOCK, b.NAMA_BLOCK, b.SLOT_, 
			b.ROW_, b.TIER_,b.HZ
			FROM TB_CONT_JOBSLIP b WHERE b.ID_JOB_SLIP=$js";
	//print_r($q1);die;
	$rs1=$db->query($q1);
	$rd1=$rs1->fetchRow();
			
	
	
?>
<script>
var block_id;
var slot_id;
var yard_id='41';
function cek_allo_cont()
{
	block_id=$('#block_rl').val();
	slot_id=$('#slotx').val();
	$('#load_bls').load("<?=HOME?>operational.placement.export.correction/load_row_slot?block_id="+block_id+"&slot_id="+slot_id+"&yard_id="+yard_id+"");
}

function relocate_cont(yard,blok,slot,row,tier)
{
	var id_user	= '<?=$user?>';
	var id_alat	= '<?=$alat?>';
	var id_js	= '<?=$js?>';
	var url		= "<?=HOME?>operational.placement.export.correction/save";
	$.post(url,{ID_YARD:yard,BLOK_ID:blok, SLOT_ID:slot, ROW_ID: row, TIER_ID:tier, ALAT:id_alat,USER:id_user, ID_JS:id_js},function(data) 
	{
		alert(data);
		console.log(data);
		$('#load_bls').load("<?=HOME?>operational.placement.export.correction/load_row_slot2?block_id="+block_id+"&slot_id="+slot_id+"&yard_id="+yard_id+"");
	});	
}
</script>


<div>
	Relokasi Container Export Before Placement
	<br>
	<br>
	<table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="100%" id="list_cont_dev">
	<tr>
		<th class="grid-header" rowspan=2>No.</th>	
		<th class="grid-header" rowspan=2>No Container</th>
		<th class="grid-header" rowspan=2>Size</th>
		<th class="grid-header" rowspan=2>Type</th>
		<th class="grid-header" rowspan=2>Status</th>
		<th class="grid-header" rowspan=2>Hz</th>
		<th class="grid-header" colspan=2>B S</th>
		<th class="grid-header" rowspan=2>Status</th>
		</tr>
		<th class="grid-header">Blok</th>
		<th class="grid-header">Slot</th>
		
	</tr>
	<tr>
		<td>1</td>
		<td><?=$rd1['NO_CONT']?></td>
		<td><?=$rd1['SIZE_']?></td>
		<td><?=$rd1['TYPE_']?></td>
		<td><?=$rd1['STATUS_']?></td>
		<td><?=$rd1['HZ']?></td>
		<td><select id='block_rl' name="block_rl">
			<?
				
				$q2="SELECT ID,NAME
					FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA=41 and NAME<>'NULL'";
	
				$rs2=$db->query($q2);
				$rd2=$rs2->getall();
				
				foreach($rd2 as $row_a)
				{
					echo "<option value=".$row_a['ID'].">".$row_a['NAME']."</option>";
				}
			?>
			</select>
		</td>
		<td><input type="text" id="slotx" name="slotx" size="5" value="" onblur="cek_allo_cont()"/></td>
		<td></td>
	</tr>
	</table>
	
	<fieldset style="border: 1px solid #94969b;-moz-border-radius:5px;width:430px;margin:20 auto;" id="bwh_layout">
			<legend align="center"><font color='#0482d5'>Preview Blok to Relocate</font></legend>
			<br>
			<div id="load_bls" align="center"></div>
			<BR>
			&nbsp;
			<BR>
			&nbsp;			
	</fieldset>
	
</div>