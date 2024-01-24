<?
	$no_ukk = TRIM($_GET['no_ukk']);
	$no_cont = $_GET['no_cont'];
	$alat = $_GET['alat'];
	$soa = $_GET['soa'];
	$db = getdb();
	
	$q1 = "SELECT NO_CONTAINER,
				  SIZE_, 
				  TYPE_, 
				  STATUS,
				  HZ
			FROM ISWS_LIST_CONTAINER
			WHERE NO_UKK = '$no_ukk'
				AND NO_CONTAINER = '$no_cont'";
	//print_r($q1);die;
	$rs1=$db->query($q1);
	$rd1=$rs1->fetchRow();
	$nocont=$rd1['NO_CONTAINER'];
	$sz=$rd1['SIZE_'];
	$ty=$rd1['TYPE_'];
	$st=$rd1['STATUS'];
	$hz=$rd1['HZ'];
	//print_r($no_ukk);die;
	
?>
<script>

var ukk = '<?=$no_ukk?>';
var cont_no = '<?=$no_cont?>';
var sz_cont = '<?=$sz?>';
var alat = '<?=$alat?>';
var soa = '<?=$soa?>';

function ubah()
{
	var id_no = $('#bay_select').val();
	var explode6 = id_no.split(',');
		var bay_id = explode6[0];
		var bay_no = explode6[1];
	$('#load_bls').load("<?=HOME?>operational.loading.plc/load_bay_pss?bay_id="+bay_id+"&bay_no="+bay_no+"&no_ukk="+ukk+"&no_cont="+cont_no+"&sz_cont="+sz_cont+"&alat="+alat);
}

function plc_bay_cont(bay_area,cell_address,no_ukk,no_cont,bay_no,sz_cont,alat)
{
	var url		= "<?=HOME?>operational.loading.plc/save_plc_bay";		
	
	var r = confirm("Are you sure?");
	if (r==true)
	{
		$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
		$.post(url,{BAY_AREA:bay_area, ADDRESS_CELL:cell_address, NO_UKK:no_ukk, NO_CONT:no_cont, BAY_NO:bay_no, SZ_CONT:sz_cont, ALAT:alat, SOA:soa},function(data2) 
		{
			//alert(data2);
			if (data2=='NOT')
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				alert("Failed...");
				return false;
			}
			else if(data2=='CAPACITY')
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				alert("Success with Overload Capacity...");
				window.location = '<?=HOME?>operational.loading/';
			}
			else if(data2=='BAY')
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				alert("Wrong bay confirm...");
				return false;
			}
			else if(data2=='OK')
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				alert("Success...");
				window.location = '<?=HOME?>operational.loading/';
			}
		});	
	}
	else
	{
		return false;
	}	
}
	
</script>


<div>
	<table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="100%" id="list_cont_dev">
	<tr>
		<th class="grid-header">No.</th>	
		<th class="grid-header">No Container</th>
		<th class="grid-header">Size</th>
		<th class="grid-header">Type</th>
		<th class="grid-header">Status</th>
		<th class="grid-header">Hz</th>
		<th class="grid-header">Bay Position</th>
	</tr>
	<tr align="center">
		<td>1</td>
		<td><?=$nocont?></td>
		<td><?=$sz?></td>
		<td><?=$ty?></td>
		<td><?=$st?></td>
		<td><?=$hz?></td>
		<td>
			<select name="bay_select" id="bay_select" onChange="ubah()">
						<option value="">-pilih-</option>				
				<?
					$db = getDB();
					$query_get_bay = "SELECT ID,BAY FROM STW_BAY_AREA WHERE ID_VS = '$no_ukk' AND BAY > 0 ORDER BY ID ASC";
					$result_bay    = $db->query($query_get_bay);
					$row_bay       = $result_bay->getAll();
					
					foreach ($row_bay as $row5) {
						$no_bay = $row5['BAY'];
						$odd_bay = $no_bay+1;
				?>				
						<option value="<?=$row5['ID'];?>,<?=$no_bay;?>"><? if($row5['BAY']<10) { if ($row5['BAY']==1) { echo "00".$no_bay."(00".$odd_bay.")"; } else if(($row5['BAY']-1)%4==0){ if($no_bay==9) { echo "00".$no_bay."(0".$odd_bay.")"; } else { echo "00".$no_bay."(00".$odd_bay.")"; } } else { echo "00".$row5['BAY']; } } else { if(($row5['BAY']-1)%4==0) {echo "0".$no_bay."(0".$odd_bay.")"; } else { echo "0".$row5['BAY']; } } ?></option>
						
				<? } ?>		
			</select>
		</td>
	</tr>
	</table>
	
	<fieldset style="border: 1px solid #94969b;-moz-border-radius:5px;width:650px;margin:20 auto;" id="bwh_layout">
			<legend align="center"><font color='#0482d5'>Preview Ship Position</font></legend>
			<br>
			<div id="load_bls" align="center"></div>
			<br/>
			&nbsp;
			<br/>
			&nbsp;			
	</fieldset>
	
</div>