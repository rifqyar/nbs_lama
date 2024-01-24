<html lang="en">
<head>
<style>
.content{
	widtd:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
.main_side{
	widtd:100%;
	float:left;
	text-align:left;
}
.tabss{
	margin-top: 20px;
}
#message_box { 
       position: absolute; 
       top: 100px; 
	   left: 0; 
       z-index: 0; 
	   background:#FFFFCC;
	   padding:5px;
       border:1px solid #CCCCCC;
       text-align:center; 
       font-weight:bold; 
       width:150px;
	   height:50px;
      }
</style>

<? 
	$id_block_nama = $_POST['block_id'];
	$data_block = explode(",",$id_block_nama);
	$id_block3 = $data_block[0];
	$block3 = $data_block[1];
	$slot3 = $data_block[2];
	
	$id_vs = $_GET['id_vs'];
	//echo 'coba';die;
	$db=getDb();
	$qr="select JENIS_KAPAL from rbm_h where trim(NO_UKK)=trim('$id_vs')";
	$eqr=$db->query($qr);
	$hqr=$eqr->fetchRow();
	$jkv=$hqr['JENIS_KAPAL'];
	IF($jkv=='T')
	{
		$pct_v='vespro_tg.png';
	}
	else
	{
		$pct_v='vespro_stw.png';
	}
	//echo $pct_v;die;
	$yard = $_GET['yard'];
	$block4 = $_GET['block'];
	$id_block4 = $_GET['id_block'];
	$slot4 = $_GET['slot'];
	$filter = $_GET['filter'];
	
	if(($block4!=NULL)&&($id_block4!=NULL)&&($slot4!=NULL))
	{
		$block = $block4;
		$id_block = $id_block4;
		$slot = $slot4;
	}
	
	if(($block3!=NULL)&&($id_block3!=NULL)&&($slot3!=NULL))
	{
		$block = $block3;
		$id_block = $id_block3;
		$slot = $slot3;
	}
	
	$id_area_occ = $_POST['bay_select'];
	$bay_area_occ = explode(",",$id_area_occ);
	$id_area_p = $bay_area_occ[0];
	$occ_bay_p = $bay_area_occ[1];
	$posisi_stack_p = $_POST['posisi_bay'];	
	
	$id_area_g = $_GET['id_bay_area'];
	$posisi_stack_g = $_GET['pss_bay'];
	$occ_bay_g = $_GET['occ_bay'];
	
	if(($id_area_p!=NULL)&&($posisi_stack_p!=NULL)&&($occ_bay_p!=NULL))
	{
		$id_area=$id_area_p;
		$posisi_stack=$posisi_stack_p;
		$occ_stat=$occ_bay_p;
	}
	
	if(($id_area_g!=NULL)&&($posisi_stack_g!=NULL)&&($occ_bay_g!=NULL))
	{
		$id_area=$id_area_g;
		$posisi_stack=$posisi_stack_g;
		$occ_stat=$occ_bay_g;
	}
	
	//$row = $_GET['row'];
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="<?=HOME;?>js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?=HOME;?>js/jquery-ui-1.8.20.custom.min.js"></script>
<script type="text/javascript" src="<?=HOME;?>js/multi/jquery.blockUI.js"></script>
<link rel="stylesheet" type="text/css" href="<?=HOME;?>css/jquery-ui-1.8.20.custom.css" />
<script type="text/javascript" src="<?=HOME;?>js/ajax.js"></script>
<link rel="stylesheet" href="<?=HOME;?>redips/style.css" type="text/css" media="screen"/>
<script type="text/javascript" src="<?=HOME;?>redips/header.js"></script>
<script type="text/javascript" src="<?=HOME;?>redips/redips-drag-min.js"></script>
<link type="text/css" href="<?=HOME;?>css/default.css" rel="stylesheet" />
<link type="text/css" href="<?=HOME;?>css/application.css" rel="stylesheet" />

<script type="text/javascript">

function slot_minus()
{	
	var slot = <?=$slot?>;
	slot--;
	var block = "<?=$block?>";
	var vs_id = "<?=$id_vs?>";
	var yard = "<?=$yard?>";
	var block_id = "<?=$id_block?>";
	var bay_area_id = "<?=$id_area?>";
	var bay_pss = "<?=$posisi_stack?>";
	var bay_occ = "<?=$occ_stat?>";
	
	//window.location = '<?=HOME?>planning.stowage.ajax/stow_bay?block='+block+'&slot='+slot+'&id_vs='+vs_id+'&yard='+yard+'&id_block='+block_id+'&filter=0';
	window.location = '<?=HOME?>planning.stowage.ajax/stow_bay?block='+block+'&slot='+slot+'&id_vs='+vs_id+'&yard='+yard+'&id_block='+block_id+'&id_bay_area='+bay_area_id+'&pss_bay='+bay_pss+'&occ_bay='+bay_occ+'&filter=0';
}

function slot_plus()
{	
	var slot = <?=$slot?>;
	slot++;
	var block = "<?=$block?>";
	var vs_id = "<?=$id_vs?>";
	var yard = "<?=$yard?>";
	var block_id = "<?=$id_block?>";
	var bay_area_id = "<?=$id_area?>";
	var bay_pss = "<?=$posisi_stack?>";
	var bay_occ = "<?=$occ_stat?>";
	
	//window.location = '<?=HOME?>planning.stowage.ajax/stow_bay?block='+block+'&slot='+slot+'&id_vs='+vs_id+'&yard='+yard+'&id_block='+block_id+'&filter=0';
	window.location = '<?=HOME?>planning.stowage.ajax/stow_bay?block='+block+'&slot='+slot+'&id_vs='+vs_id+'&yard='+yard+'&id_block='+block_id+'&id_bay_area='+bay_area_id+'&pss_bay='+bay_pss+'&occ_bay='+bay_occ+'&filter=0';
}


/*
var time_left = 300; // timeout
var cinterval;

function time_dec(){	
	  time_left--;
	  
	if(time_left >= 0)
	{	
	  document.getElementById('countdown').innerHTML = '<blink><b><font color="red" size="+3">'+time_left+'</font></b></blink> s';
	}
	else if(time_left < 0)
	{
	  clearInterval(cinterval);
	}
}

cinterval = setInterval(function(){time_dec()},1000);

setTimeout(function()
{	
	var r = confirm("Waktu Stowage Habis...Ulangi Proses!!!");
	if(r == true)
	{	
		var block = "<?=$block?>";
		var slot = "<?=$slot?>";
		var vs_id = "<?=$id_vs?>";
		var yard = "<?=$yard?>";
		var block_id = "<?=$id_block?>";
		var filter = "0";
		window.location = '<?=HOME?>planning.stowage.ajax/stow_bay?block='+block+'&slot='+slot+'&id_vs='+vs_id+'&yard='+yard+'&id_block='+block_id+'&filter='+filter;
	}
	else
	{
		var vs_id = "<?=$id_vs?>";
		window.close();
		window.opener.location = "<?=HOME?>planning.stowage/view_yard?vs="+vs_id;
	}	
},300000);
*/
	
	// define redips_init variable
var redips_init;

	// reference to the REDIPS.drag lib
var rd = REDIPS.drag;	

// redips initialization
redips_init = function () {	
	var stat = document.getElementById('status');
	// initialization
	rd.init('drag');
	// dragged elements can be placed to the empty cells only
	rd.drop_option = 'single';
	// elements could be cloned with pressed SHIFT key
	rd.clone_shiftKey = true;
	// define dropped handler
	rd.myhandler_dropped = function (target_cell) {
		var tbl,	// table reference of dropped element
			id,		// id of scrollable container
			msg;	// message
		// find table of target cell
		tbl = rd.find_parent('TABLE', target_cell);
		// test if table belongs to scrollable container
		if (tbl.sca !== undefined) {
			// every table has defined scrollable container (if table belongs to scrollable container)
			// scrollable container has reference to the DIV container and DIV container has Id :)
			id = tbl.sca.div.id;
			// prepare message according to container Id
			// here can be called handler_dropped for scrollable containers
			switch (id) {
			case 'left':
				msg = 'Posisi CY';
				break;
			case 'right':
				msg = 'Posisi Kapal';
				break;
			default:
				msg = 'Container without Id';
			}
		}
		// table does not belong to any container
		else {
			msg = 'Table does not belong to any container!';
		}
		// display message
		document.getElementById('message').innerHTML = msg;
		stat.innerHTML = 'Dropped';		
		
		var id_vs_ = "<?=$id_vs?>";
		var url	= "get_width_tier";
		
		$.post(url,{ID_VS : id_vs_},function(data){
		console.log(data);
		
		// get target and source position (method returns positions as array)
		var pos = rd.get_position();
		var width_tier6 = data;
		var explode6 = width_tier6.split(',');
		var width_ = explode6[0];
		var palka_ = explode6[1];
		
		var bay_area_ = "<?=$id_area?>";
		var posisi_ = "<?=$posisi_stack?>";
		var cell_address = ((pos[1]*width_)+(pos[2]+1))-1;
		var url	= "get_row_tier";
		
		$.post(url,{CELL_ADDRESS : cell_address, WIDTH : width_, BAY_AREA : bay_area_, POSISI : posisi_, PALKA : palka_},function(data2){
		console.log(data2);
			
			var row_tier = data2;
			var explode = row_tier.split(',');
			var row_ = explode[0];
			var tier_ = explode[1];
			var stat_ = explode[2];

			if(stat_ == "A")
			{
				alert("Under Cell Still Empty...!!!");
				//document.getElementById(cell_address).setAttribute("class", "mark5");
			}
			
			// window.location = "<?=HOME?>planning.stowage.ajax/stow_bay?bay_area="+bay_area_+"&stat_stack="+stat_+"&id_vs="+id_vs_;
		
		});		
	  
	  });
		
	};
	
	rd.myhandler_clicked = function () {
		stat.innerHTML = 'Clicked';		
	};
	
	rd.myhandler_moved  = function () {
		stat.innerHTML = 'Moved';
	};
	
	rd.myhandler_notmoved = function () {
		stat.innerHTML = 'Not moved';
	};
		
	rd.myhandler_changed = function () {
			
		var id_vs_ = "<?=$id_vs?>";
		var url	= "get_width_tier";
		
		$.post(url,{ID_VS : id_vs_},function(data){
		console.log(data);
		
		// get target and source position (method returns positions as array)
		var pos = rd.get_position();
		var width_tier6 = data;
		var explode6 = width_tier6.split(',');
		var width_ = explode6[0];
		var palka_ = explode6[1];
		
		var bay_area_ = "<?=$id_area?>";
		var posisi_ = "<?=$posisi_stack?>";
		var cell_address = ((pos[1]*width_)+(pos[2]+1))-1;
		var url	= "get_row_tier";
		
		$.post(url,{CELL_ADDRESS : cell_address, WIDTH : width_, BAY_AREA : bay_area_, POSISI : posisi_, PALKA : palka_},function(data2){
		console.log(data2);
			
			var row_tier = data2;
			var explode = row_tier.split(',');
			var row_ = explode[0];
			var tier_ = explode[1];
			
			// display current row and current cell
			stat.innerHTML = 'Position: Row ' + row_ + ' Tier ' + tier_;
		
		});		
	  
	  });
		
	};
};
 

	// add onload event listener
	if (window.addEventListener) {
		window.addEventListener('load', redips_init, false);
	}
	else if (window.attachEvent) {
		window.attachEvent('onload', redips_init);
	}

	
/*function rubah()
{
	var bay_id = $(".ok").val();
	var vs = '<? echo $id_vs;?>';
	$("#right").html('<img src="<?=HOME?>images/loading_box.gif" />');
	$('#right').load('<?=HOME?>planning.stowage.ajax/bay_view?bay_area='+bay_id+'&id_vs='+vs);
}*/

function ubah()
{
	document.bay_form.submit();
}

function block_pilih()
{
	var vs_id = "<?=$id_vs?>";
	var yard = "<?=$yard?>";
	$('#block_option').load("<?=HOME?>planning.stowage.ajax/filter_block?id_vs="+vs_id+"&yard="+yard).dialog({modal:true, height:90,width:350,title: "Block Option"});
}

function reset_plan(no_ukk,nocont)
{
	var url	= "reset";
	var slot = <?=$slot?>;
	var block = "<?=$block?>";
	var vs_id = "<?=$id_vs?>";
	var yard = "<?=$yard?>";
	var block_id = "<?=$id_block?>";

	var bay_area_ = "<?=$id_area?>";
	var posisi_ = "<?=$posisi_stack?>";
	var occ_bay_ = "<?=$occ_stat?>";
	
	var r = confirm("Reset Planning "+nocont+" Are you sure?");
	if(r == true)
	{	
		$.post(url,{ID_VS : no_ukk, NO_CONTAINER : nocont},function(data6){
		console.log(data6);
						
		if(data6 == "OK")
		{
			alert("Success...!!!");
			//window.close();
			//window.opener.location = "<?=HOME?>planning.stowage/view_yard?vs="+id_vs_;
			window.location = '<?=HOME?>planning.stowage.ajax/stow_bay?block='+block+'&slot='+slot+'&id_vs='+vs_id+'&yard='+yard+'&id_block='+block_id+'&id_bay_area='+bay_area_+'&pss_bay='+posisi_+'&occ_bay='+occ_bay_+'&filter=0';
		}
		else
		{
			alert("Failed...!!!");
		}					
		});
	}
	else
	{
		return false;
	}
}

function shifting(ukk,nocont,szcont) 
{		
	$('#cont_shifting').load("<?=HOME?>planning.stowage.ajax/container_shift/?ukk="+ukk+"&cont="+nocont+"&sz="+szcont).dialog({modal:true, height:600,width:700, title : "Container Shifting "+nocont});
}

function reset_stow(vs,b,s,yard,id_block)
{
	alert("Ulangi Proses Stowage");
	window.location = '<?=HOME?>planning.stowage.ajax/stow_bay?block='+b+'&slot='+s+'&id_vs='+vs+'&yard='+yard+'&id_block='+id_block;
}

// show prepared content for saving
function save(type) {
	// define table_content variable
	var table_content;
	// prepare table content of first table in JSON format or as plain query string (depends on value of "type" variable)
	table_content = REDIPS.drag.save_content('table2', type);
	// if content doesn't exist
	if (!table_content) {
		alert('Data still empty!');
	}
	// display query string
	else if (type === 'plain') 
	{
		//window.open('/my/multiple-parameters.php?' + table_content, 'Mypop', 'width=350,height=160,scrollbars=yes');
		//window.open('multiple-parameters.php?' + table_content, 'Mypop', 'width=350,height=260,scrollbars=yes');
		
		// get target and source position (method returns positions as array)		
		
		var r = confirm("Are you sure?");
		if(r == true)
		{
			$.blockUI({ message: '<h1><br>Please wait...Saving Data</h1><br><br>' });
			var id_vs_ = "<?=$id_vs?>";
			var url	= "get_width_tier";
			
			$.post(url,{ID_VS : id_vs_},function(data){
			console.log(data);
							
			var width_tier = data;
			var explode3 = width_tier.split(',');
			var width_ = explode3[0];
			var palka_ = explode3[1];
			var slot = <?=$slot?>;
			var block = "<?=$block?>";
			var vs_id = "<?=$id_vs?>";
			var yard = "<?=$yard?>";
			var block_id = "<?=$id_block?>";
			
			var bay_area_ = "<?=$id_area?>";
			var posisi_ = "<?=$posisi_stack?>";
			var occ_bay_ = "<?=$occ_stat?>";
			var url	= "multiple-parameters?"+table_content;
			
			$.post(url,{ID_VS : id_vs_, BAY_AREA : bay_area_, WIDTH : width_, POSISI : posisi_, PALKA : palka_},function(data3){
			console.log(data3);
			
				var alokasi = data3;
				var explode = alokasi.split('_');
				var kondisi = explode[0];
				var no_cont = explode[1];
							
				if(kondisi == "OK")
				{
					$.unblockUI({
								onUnblock: function(){  }
								});
					alert("Success...!!!");
					//window.close();
					//window.opener.location = "<?=HOME?>planning.stowage/view_yard?vs="+id_vs_;
					window.location = '<?=HOME?>planning.stowage.ajax/stow_bay?block='+block+'&slot='+slot+'&id_vs='+vs_id+'&yard='+yard+'&id_block='+block_id+'&id_bay_area='+bay_area_+'&pss_bay='+posisi_+'&occ_bay='+occ_bay_+'&filter=0';
				}
				if(kondisi == "OKCAPACITY")
				{
					$.unblockUI({
								onUnblock: function(){  }
								});
					alert("Success With Overload Capacity...!!!");
					//window.close();
					//window.opener.location = "<?=HOME?>planning.stowage/view_yard?vs="+id_vs_;
					window.location = '<?=HOME?>planning.stowage.ajax/stow_bay?block='+block+'&slot='+slot+'&id_vs='+vs_id+'&yard='+yard+'&id_block='+block_id+'&id_bay_area='+bay_area_id+'&pss_bay='+bay_pss+'&occ_bay='+occ_bay_+'&filter=0';
				}
				else if(kondisi == "gagal")
				{
					$.unblockUI({
								onUnblock: function(){  }
								});
					alert("Data Gagal Disimpan...Silakan dicek kembali!!!");
				}
				else if(kondisi == "WEIGHT")
				{
					$.unblockUI({
								onUnblock: function(){  }
								});
					alert("Tidak Sesuai Alokasi Berat..."+no_cont);
				}
				else if(kondisi == "POD")
				{
					alert("Tidak Sesuai Alokasi Tujuan..."+no_cont);
				}
				else if(kondisi == "SIZE")
				{
					$.unblockUI({
								onUnblock: function(){  }
								});
					alert("Tidak Sesuai Alokasi Ukuran..."+no_cont);
				}
				else if(kondisi == "TYPE")
				{
					$.unblockUI({
								onUnblock: function(){  }
								});
					alert("Tidak Sesuai Alokasi Tipe..."+no_cont);
				}
				else if(kondisi == "STATUS")
				{
					$.unblockUI({
								onUnblock: function(){  }
								});
					alert("Tidak Sesuai Alokasi Status..."+no_cont);
				}
				else if(kondisi == "HZ")
				{
					$.unblockUI({
								onUnblock: function(){  }
								});
					alert("Tidak Sesuai Alokasi DG..."+no_cont);
				}
				else if(kondisi == "CAPACITY")
				{
					$.unblockUI({
								onUnblock: function(){  }
								});
					alert("Overload Bay Capacity");
				}
				else if(kondisi == "BAY")
				{
					$.unblockUI({
								onUnblock: function(){  }
								});
					alert("Bay Placement Incorrect");
				}
				else if(kondisi == "NOT")
				{
					$.unblockUI({
								onUnblock: function(){  }
								});
					alert("Data Tidak Lengkap");
				}
				else if(kondisi == "STATALOKASI")
				{
					$.unblockUI({
								onUnblock: function(){  }
								});
					alert("Tidak Sesuai Alokasi");
				}
						
			});		
		  
		  });
		}
		else
		{
			return false;
		}		
	}
}

function info_trim(no_ukk,vesvoy)
{
	$('#table_vessel').load('<?=HOME?>planning.stowage.ajax/info_vessel?id_vs='+no_ukk).dialog({modal:true, height:350,width:850, title:vesvoy});
}

function yard_filter()
{
	var block = "<?=$block?>";
	var slot = "<?=$slot?>";
	var vs_id = "<?=$id_vs?>";
	var yard = "<?=$yard?>";
	var block_id = "<?=$id_block?>";
	$('#yd_filter').load("<?=HOME?>planning.stowage.ajax/filter_yd?block="+block+"&slot="+slot+'&id_vs='+vs_id+'&yard='+yard+'&id_block='+block_id).dialog({modal:true, height:180,width:400,title: "Yard Realization Filter"});
}

function reset_filter()
{
	var block = "<?=$block?>";
	var slot = "<?=$slot?>";
	var vs_id = "<?=$id_vs?>";
	var yard = "<?=$yard?>";
	var block_id = "<?=$id_block?>";
	window.location = '<?=HOME?>planning.stowage.ajax/stow_bay?block='+block+'&slot='+slot+'&id_vs='+vs_id+'&yard='+yard+'&id_block='+block_id+'&filter=0';
}

function load_bay(bay_id,posisi,occ)
{		
	var slot = <?=$slot?>;
	var block = "<?=$block?>";
	var vs_id = "<?=$id_vs?>";
	var yard = "<?=$yard?>";
	var block_id = "<?=$id_block?>";
	//alert(occ);
	
	//window.location = '<?=HOME?>planning.stowage.ajax/stow_bay?block='+block+'&slot='+slot+'&id_vs='+vs_id+'&yard='+yard+'&id_block='+block_id+'&filter=0';
	window.location = '<?=HOME?>planning.stowage.ajax/stow_bay?block='+block+'&slot='+slot+'&id_vs='+vs_id+'&yard='+yard+'&id_block='+block_id+'&id_bay_area='+bay_id+'&pss_bay='+posisi+'&occ_bay='+occ+'&filter=0';
}

</script>

<title>STOWAGE : Blok <?=$block;?>, Slot <?=$slot;?></title>
</head>

<body>
<div class="content">	
	<div id="main_container">			
			<!-- tables inside tdis DIV could have draggable content -->
			<!--Time Out In <span id="countdown" align="right"><blink><b><font color="red" size="+3">300</font></b></blink> s</span>-->
			
			<!-- Vessel Profile maargin top:25px width: 1300
			
			margin="5px 5px 5px 5px;"
			-->
			<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px;background-image:url('<?=HOME;?>images/<?=$pct_v?>'); background-repeat:no-repeat;">
			<center>      
			<div style="margin-right:150px; margin-bottom:220px; margin-top:85px; width:600; height:100;">
			<p style="width:100%;">
			<div align="center">
			<table width="100%" cellspacing="0" border="0">
			<tbody>
			<td valign="bottom" colspan="4" align="center">
			<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
			<tbody>
				<tr>
					 <?php
						$db         = getDB();
						$query_blok = "SELECT ID, BAY, OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' ORDER BY ID DESC";
						$result_    = $db->query($query_blok);
						$blok       = $result_->getAll();
						
						// debug($blok2);die;
						foreach ($blok as $row){
							//echo $row['INDEX_CELL'];
							$bay_no = $row['BAY'];
							$ocu = $row['OCCUPY'];
							
							if($ocu == 'Y')
							{
								$bays = $bay_no."(".($bay_no+1).")";
							}
							else
							{
								$bays = $bay_no;
							}
						?>
									  <td align="center" style="width:20px;height:10px;font-size:8px; font-family:Tahoma;"><? if($bays!=0) { ?><b><? echo $bays;?></b><? } ?></td>
						  <?
							} ?>
				</tr>
				<!---------------------------- ABOVE -------------------------------->
				<?
					$db        = getDB();
					$query_tr  = "SELECT DISTINCT JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0";
					$result_tr = $db->query($query_tr);
					$tr_       = $result_tr->fetchRow();
					$jml_tr_abv = $tr_['JML_TIER_ON'];
					
					$tr_max = 78+($jml_tr_abv*2);
					for($tr=1;$tr<=$jml_tr_abv;$tr++)
					{
						
				?>
				<tr>
					 <?php
						$db         = getDB();
						$query_blok = "SELECT ID, BAY, ABOVE, OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' ORDER BY ID DESC";
						$result_    = $db->query($query_blok);
						$blok       = $result_->getAll();
						
						// debug($blok2);die;
						foreach ($blok as $row){
							//echo $row['INDEX_CELL'];
							$bay_id = $row['ID'];
							$occ_abv = $row['OCCUPY'];
							
							$cek_status = "SELECT COUNT(*) AS JML_STAT FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_id' AND TIER_ = '$tr_max' AND POSISI_STACK = 'ABOVE' AND STATUS_STACK IN ('A','P')";
							$result_stat = $db->query($cek_status);
							$stat_ = $result_stat->fetchRow();
							$cek_tr = $stat_['JML_STAT'];
							
							$cek_status_pln = "SELECT COUNT(*) AS JML_STAT FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_id' AND TIER_ = '$tr_maxx' AND POSISI_STACK = 'BELOW' AND STATUS_STACK IN ('P')";
							$result_stat_pln = $db->query($cek_status_pln);
							$stat_pln = $result_stat_pln->fetchRow();
							$cek_pln = $stat_pln['JML_STAT'];
							
							$cek_status_plc = "SELECT COUNT(*) AS JML_PLC FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_id' AND TIER_ = '$tr_max' AND POSISI_STACK = 'ABOVE' AND STATUS_STACK = 'R'";
							$result_stat_plc = $db->query($cek_status_plc);
							$stat_plc = $result_stat_plc->fetchRow();
							$cek_plc = $stat_plc['JML_PLC'];
							
						if ($row['ABOVE'] == 'NON AKTIF')
						{       
						?>
									  <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:20px;height:8px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF;" ><img src="<?=HOME;?>images/mty_stw.png"/></td>
						   <? }
						   else if($row['ABOVE'] == 'NONE')
							{
						   ?>
									  <td style="width:30px;height:8px;font-size:8px; font-family:Tahoma;"><img src="<?=HOME;?>images/mty_stw.png"/></td>
						   <?
							}
						   else if(($row['ABOVE']=='AKTIF')&&($cek_tr>0))
						   { 
								if($cek_plc>0)
								{
							?>	
									<td onclick="load_bay('<?=$bay_id?>','ABOVE','<?=$occ_abv?>')" data-tooltip="<?=$row['ID']?>above" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FFFF'" align="center" style="width:30px;height:8px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#66FFFF; "><img src="<?=HOME;?>images/mty_stw.png"/></td>
							<?							
								}
								else if($cek_pln>0)
								{
							?>	
									<td onclick="load_bay('<?=$bay_id?>','ABOVE','<?=$occ_abv?>')" data-tooltip="<?=$row['ID']?>above" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FFFF'" align="center" style="width:30px;height:8px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#66FFFF; "><img src="<?=HOME;?>images/mty_stw.png"/></td>
							<?		
								}
								else
								{
						   ?>
									  <td onclick="load_bay('<?=$bay_id?>','ABOVE','<?=$occ_abv?>')" data-tooltip="<?=$row['ID']?>above" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:30px;height:8px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><img src="<?=HOME;?>images/mty_stw.png"/></td>
						  <? 
								}
							}
							else if(($row['ABOVE']=='AKTIF')&&($tr_max==80)&&($cek_tr==0))
							{
						  ?>
									<td colspan="<?=$jml_bay;?>" align="center" style="width:30px;height:8px; border:1px solid #000000; background-color:#663300;"><img src="<?=HOME;?>images/mty_stw.png"/></td>
						  <?	
							}
							else if(($row['ABOVE']=='AKTIF')&&($tr_max==82)&&($cek_tr==0)&&($cek_plc==0))
							{
						  ?>
									<td colspan="<?=$jml_bay;?>" align="center" style="width:30px;height:8px; border:1px solid #000000; background-color:#663300;"><img src="<?=HOME;?>images/mty_stw.png"/></td>
						  <?	
							}
							else if($row['ABOVE']=='AKTIF')
							{
								if($cek_plc>0)
								{
						  ?>
									<td onclick="load_bay('<?=$bay_id?>','ABOVE','<?=$occ_abv?>')" data-tooltip="<?=$row['ID']?>above" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FFFF'" align="center" style="width:30px;height:8px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#66FFFF; "><img src="<?=HOME;?>images/mty_stw.png"/></td>
						  <?	
								}
							}
							else
							{
								if($tr_max==80)
								{
						  ?>
									<td colspan="<?=$jml_bay;?>" align="center" style="width:30px;height:8px; border:1px solid #000000; background-color:#663300;"><img src="<?=HOME;?>images/mty_stw.png"/></td>
						  <?
								}
								else if($cek_tr==0)
								{
								?>
									<td align="center" style="width:30px;height:8px;font-size:8px; font-family:Tahoma;"><img src="<?=HOME;?>images/mty_stw.png"/></td>
								<?
								}
							}						  
						} ?>
				</tr>
				<? $tr_max-=2;} ?>
				<!---------------------------- ABOVE -------------------------------->
				<tr>
					 <?php
						$db         = getDB();
						$query_jml  = "SELECT count(BAY) AS JML_BAY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' ORDER BY ID DESC";
						$result12_  = $db->query($query_jml);
						$jml        = $result12_->fetchRow();
						$jml_bay    = $jml['JML_BAY'];
								
						?>
									  <td colspan="<?=$jml_bay;?>" align="center" style="width:30px;height:8px; border:1px solid #000000; background-color:#663300;"><img src="<?=HOME;?>images/mty_stw.png"/></td>
				</tr>
				<!---------------------------- BELOW -------------------------------->
				<?
					$db        = getDB();
					$tr_under  = "SELECT DISTINCT JML_TIER_UNDER FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0";
					$tr_under_hsl = $db->query($tr_under);
					$tr_underx  = $tr_under_hsl->fetchRow();
					$jml_tr_blw = $tr_underx['JML_TIER_UNDER'];
					
					$tr_maxx = $jml_tr_blw*2;
					$tr_maxxx = $jml_tr_blw*2;
					for($trx=1;$trx<=$jml_tr_blw;$trx++)
					{
				?>
				<tr>
					 <?php
						$db         = getDB();
						$query_blok = "SELECT ID, BAY, BELOW, OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' ORDER BY ID DESC";
						$result_    = $db->query($query_blok);
						$blok       = $result_->getAll();
						
						// debug($blok2);die;
						foreach ($blok as $row){
							//echo $row['INDEX_CELL'];		
							
							$bay_id = $row['ID'];
							$occ_blw = $row['OCCUPY'];
							
							$cek_status = "SELECT COUNT(*) AS JML_STAT FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_id' AND TIER_ = '$tr_maxx' AND POSISI_STACK = 'BELOW' AND STATUS_STACK IN ('A','P')";
							$result_stat = $db->query($cek_status);
							$stat_ = $result_stat->fetchRow();
							$cek_tr = $stat_['JML_STAT'];
							
							$cek_status_pln = "SELECT COUNT(*) AS JML_STAT FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_id' AND TIER_ = '$tr_maxx' AND POSISI_STACK = 'BELOW' AND STATUS_STACK IN ('P')";
							$result_stat_pln = $db->query($cek_status_pln);
							$stat_pln = $result_stat_pln->fetchRow();
							$cek_pln = $stat_pln['JML_STAT'];
														
							$cek_status_plc = "SELECT COUNT(*) AS JML_PLC FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_id' AND TIER_ = '$tr_maxx' AND POSISI_STACK = 'BELOW' AND STATUS_STACK = 'R'";
							$result_stat_plc = $db->query($cek_status_plc);
							$stat_plc = $result_stat_plc->fetchRow();
							$cek_plc = $stat_plc['JML_PLC'];
							
							if($row['BELOW'] == 'NON AKTIF')
						{                    
								$jml_row = $row['JML_ROW'];
								$jml_tier_under = $row['JML_TIER_UNDER'];
								$jml_tier_on = $row['JML_TIER_ON'];
						?>
									  <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:30px;height:8px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><img src="<?=HOME;?>images/mty_stw.png"/></td>
						   <? }
							else if($row['BELOW'] == 'NONE')
							{
						   ?>
									  <td style="width:30px;height:8px;font-size:8px; font-family:Tahoma;"><img src="<?=HOME;?>images/mty_stw.png"/></td>
						   <?
							}
							else if(($row['BELOW']=='AKTIF')&&($cek_tr>0))
							{ 
								if($cek_plc>0)
								{
							?>	
									<td onclick="load_bay('<?=$bay_id?>','BELOW','<?=$occ_blw?>')" data-tooltip="<?=$row['ID']?>above" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FFFF'" align="center" style="width:30px;height:8px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#66FFFF; "><img src="<?=HOME;?>images/mty_stw.png"/></td>
							<?							
								}
								else if($cek_pln>0)
								{
							?>	
									<td onclick="load_bay('<?=$bay_id?>','ABOVE','<?=$occ_abv?>')" data-tooltip="<?=$row['ID']?>above" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FFFF'" align="center" style="width:30px;height:8px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#66FFFF; "><img src="<?=HOME;?>images/mty_stw.png"/></td>
							<?		
								}
								else
								{
						   ?>
									  <td onclick="load_bay('<?=$bay_id?>','BELOW','<?=$occ_blw?>')" data-tooltip="<?=$row['ID']?>above" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:30px;height:8px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><img src="<?=HOME;?>images/mty_stw.png"/></td>								
						  <? 
								}
							}
							else if(($row['BELOW']=='AKTIF')&&($tr_maxx == ($jml_tr_blw*2))&&(($cek_tr>0)||($cek_plc>0)))
							{
						?>	
								<td onclick="load_bay('<?=$bay_id?>','BELOW','<?=$occ_blw?>')" data-tooltip="<?=$row['ID']?>above" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FFFF'" align="center" style="width:30px;height:8px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#66FFFF; "><img src="<?=HOME;?>images/mty_stw.png"/></td>
						<?													
							}
							else if(($row['BELOW']=='AKTIF')&&($tr_maxx == ($jml_tr_blw*2)))
							{
						?>
								<td align="center" style="width:30px;height:8px; border:1px solid #000000; background-color:#663300;"><img src="<?=HOME;?>images/mty_stw.png"/></td>
						<?
							}
							else if($row['BELOW']=='AKTIF')
							{
								if($cek_plc>0)
								{
						?>	
									<td onclick="load_bay('<?=$bay_id?>','BELOW','<?=$occ_blw?>')" data-tooltip="<?=$row['ID']?>above" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FFFF'" align="center" style="width:30px;height:8px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#66FFFF; "><img src="<?=HOME;?>images/mty_stw.png"/></td>
						<?							
								}
							}							
							else 
							{ ?>
									  <td align="center" style="width:30px;height:8px;font-size:8px; font-family:Tahoma;"><img src="<?=HOME;?>images/mty_stw.png"/></td>
								
						  <? } 
							} ?>
				</tr>
				<!---------------------------- BELOW -------------------------------->				
				<? $tr_maxx-=2; } ?>
				<tr>
					 <?php
						$db         = getDB();
						$query_jml  = "SELECT count(BAY) AS JML_BAY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' ORDER BY ID DESC";
						$result12_  = $db->query($query_jml);
						$jml        = $result12_->fetchRow();
						$jml_bay    = $jml['JML_BAY'];
								
						?>
							<td colspan="<?=$jml_bay;?>" align="center" style="width:30px;height:90px;"><img src="<?=HOME;?>images/mty_stw.png"/></td>
				</tr>
				<tr>
					 <?php
						$db         = getDB();
						$query_blok = "SELECT ID, BAY, OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' ORDER BY ID DESC";
						$result_    = $db->query($query_blok);
						$blok       = $result_->getAll();
						
						// debug($blok2);die;
						foreach ($blok as $row){
							//echo $row['INDEX_CELL'];
							$bay_no = $row['BAY'];
							$bay_area = $row['ID'];
							$occ_by = $row['OCCUPY'];
							
							$counter_abv = "SELECT COUNT(*) AS JML_ABV FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' AND STATUS_STACK = 'A' AND POSISI_STACK = 'ABOVE'";
							$result_abv = $db->query($counter_abv);
							$jmls_abv   = $result_abv->fetchRow();
							$jml_abv    = $jmls_abv['JML_ABV'];
							
							$counter_blw = "SELECT COUNT(*) AS JML_BLW FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' AND STATUS_STACK = 'A' AND POSISI_STACK = 'BELOW'";
							$result_blw = $db->query($counter_blw);
							$jmls_blw   = $result_blw->fetchRow();
							$jml_blw    = $jmls_blw['JML_BLW'];
							
							$jml_wght = "SELECT ROUND(((SUM(GROSS_CONT))/1000),0) AS JML FROM
											(
											SELECT CASE WHEN SIZE_ = '40' OR SIZE_ = '45' THEN TO_NUMBER(GROSS)/2
																 ELSE TO_NUMBER(GROSS) END GROSS_CONT
												   FROM STW_PLACEMENT_BAY
												   WHERE ID_VS = '$id_vs'
													  AND TRIM(BAY) = '$bay_no'
													  AND ACTIVITY = 'MUAT'
													  AND STATUS_PLC = 'REALIZATION'
													  AND TRIM(ID_PEL_ASAL) <> 'IDJKT'
											)";
							$result_wg = $db->query($jml_wght);
							$jmls_wg = $result_wg->fetchRow();
							$jml_wg_real = $jmls_wg['JML'];
							
							$jml_wght_pln = "SELECT ROUND((NVL(SUM(GROSS_CONT)/1000,0)),0) AS JML FROM
												(          
												SELECT CASE WHEN SIZE_ = '40' OR SIZE_ = '45' THEN TO_NUMBER(GROSS)/2
																	 ELSE TO_NUMBER(GROSS) END GROSS_CONT
													   FROM STW_PLACEMENT_BAY
													   WHERE TRIM(ID_VS) = '$id_vs'
														  AND TRIM(BAY) = '$bay_no'
														  AND ACTIVITY = 'MUAT'
														  AND STATUS_PLC = 'PLANNING'
												)";
							$result_wg_pln = $db->query($jml_wght_pln);
							$jmls_wg_pln = $result_wg_pln->fetchRow();
							$jml_wg_pln = $jmls_wg_pln['JML'];
							
							$ton_bay = $jml_wg_real+$jml_wg_pln;
							
							if($occ_by=='Y')
							{
								$bays = $bay_no."(".($bay_no+1).")";
							}
							else
							{
								$bays = $bay_no;
							}
						?>
									  <td align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma;">
									  <? if($bay_no!=0) { ?>
									  ^<br/><b><? echo $jml_abv;?><br/>
									  <? echo $jml_blw; ?><br/>
									  <blink><font color="red"><?=$ton_bay?></font></blink>
									  <? } ?>
									  </b></td>
						  <?
							} ?>
				</tr>
			</tbody>
			</table>
			</td>
			</tr>
			</tbody>
			</table>
			</div>
			</p></div>
				</center>
			</fieldset>
			<!-- Vessel Profile -->
			
			<p><br/></p>
			<div id="drag">
				<div style="width: 400px; float: left;">
                <h2>BLOK : <b><?=$block;?></b>, SLOT : <b><?=$slot;?></b>
				&nbsp;&nbsp;
				<button onclick="slot_minus()" title="back slot"><img src='<?=HOME?>images/back_icon.png' width=15px height=15px border='0'></button>
				<button onclick="slot_plus()" title="next slot"><img src='<?=HOME?>images/next_icon.png' width=15px height=15px border='0'></button>
				</h2>
				<br/>
				<button onclick="block_pilih()" title="block select"><img src='<?=HOME?>images/find.png' width=15px height=15px border='0'></button>
				&nbsp;
				<button onclick="reset_filter()" title="reset filter"><img src='<?=HOME?>images/Refresh2.png' width=15px height=15px border='0'></button>
				&nbsp;
				<button onclick="yard_filter()" title="filter yard"><img src="<?=HOME?>images/filter.png" width="15px" height="15px" border="0"></button>
				&nbsp;
				Filtering Yard 
				<?
				$db = getDB();
				$query_lap = "SELECT NAME, TIER, (select count(1) from YD_BLOCKING_CELL b WHERE b.ID_BLOCKING_AREA='$id_block' and b.SLOT_='$slot') AS JML_ROW FROM YD_BLOCKING_AREA a WHERE a.ID_YARD_AREA='$yard' AND a.ID='$id_block'";
				
				//echo $query_lap;die;				
				$result_lap = $db->query($query_lap);	
				$hasil3 = $result_lap->fetchRow();
				$blok3 = $hasil3['NAME'];
				$tier3 = $hasil3['TIER'];
				$row3 = $hasil3['JML_ROW'];

				$width=$row3;
				$heigth=$tier3;
				$t=$heigth;
				$u=$width;
				?>				
				
				<!-- left container -->
				<div id="left">
					<? $lebar = 90+($width*90)+90; 
						if(($id_block!=NULL)&&($slot!=NULL))
						{
					?>
					<table id="table1" width="<?=$lebar?>">
						<tbody>
							<tr>
								<td width="85" align="center" class="mark"><b>TIER</b></td>
							</tr>
							<?
								for($x=1;$x<=$heigth;$x++)
								{
							?>
							<tr>								
								<td width="85" class="mark"><b><?=$t;?></b></td>
								<?
									for($i=1;$i<=$width;$i++)
									{
										$query2 = "SELECT A.ID_PLACEMENT, 
										                  A.NO_CONTAINER, 
														  A.SIZE_, 
														  TRIM(A.TYPE_CONT) TYPE_CONT, 
														  A.STATUS_CONT, 
														  A.ID_VS, 
														  A.ACTIVITY, 
														  A.HZ, 
														  A.TON, 
														  A.ID_PEL_ASAL, 
														  A.ID_PEL_TUJ, 
														  A.KODE_PBM, 
														  A.NO_BOOKING_SL, 
														  B.H_ISO,
														  A.ISO_CODE,
														  A.IMO_CLASS,
														  A.CELCIUS,
														  A.STOWAGE,
														  A.NAMA_BLOCK AS YARD_B,
														  A.SLOT_YARD AS YARD_S,
														  A.ROW_YARD AS YARD_R,
														  A.TIER_YARD AS YARD_T,
														  A.STATUS AS STATUS_YD
													FROM YD_PLACEMENT_YARD A, MASTER_ISO_CODE B 
													WHERE ID_BLOCKING_AREA='$id_block' 
													     AND SLOT_YARD='$slot' 
														 AND ROW_YARD='$i' 
														 AND TIER_YARD='$t'
														 AND A.ISO_CODE = B.ISO_CODE";
										$result3= $db->query($query2);	
										$hasil2 = $result3->fetchRow();
										
										$vs_id=$hasil2['ID_VS'];
										$kegiatan=$hasil2['ACTIVITY'];
										$no_cont=$hasil2['NO_CONTAINER'];
										$size_cont=$hasil2['SIZE_'];
										$type_cont=$hasil2['TYPE_CONT'];
										$status_cont=$hasil2['STATUS_CONT'];
										$id_plc=$hasil2['ID_PLACEMENT'];
										$hz_=$hasil2['HZ'];
										$gross_=$hasil2['TON'];
										$pl_asal=$hasil2['ID_PEL_ASAL'];
										$pl_tuj=$hasil2['ID_PEL_TUJ'];
										$pel_asal = str_replace(' ','',$pl_asal);
										$pel_tuj = str_replace(' ','',$pl_tuj);
										$kode_pbm=$hasil2['KODE_PBM'];
										$no_booking=$hasil2['NO_BOOKING_SL'];
										$height=$hasil2['H_ISO'];
										$tinggi=ceil($height*30.48);
										$isocode=$hasil2['ISO_CODE'];
										$imoclass=$hasil2['IMO_CLASS'];
										$celcius=$hasil2['CELCIUS'];
										$stat_stowage=$hasil2['STOWAGE'];
										$yard_b=$hasil2['YARD_B'];
										$yard_s=$hasil2['YARD_S'];
										$yard_r=$hasil2['YARD_R'];
										$yard_t=$hasil2['YARD_T'];
										$yard_stat=$hasil2['STATUS_YD'];
									
									if($filter==0)
									{
										if($no_cont != NULL)
										{
										  if(($stat_stowage=='T')&&($kegiatan=='MUAT'))
										  {
								?>
											<td width="85"><div id="<?=$vs_id?>,<?=$no_cont?>,<?=$size_cont?>,<?=$type_cont?>,<?=$status_cont?>,<?=$hz_?>,<?=$gross_?>,<?=$pel_asal?>,<?=$pel_tuj?>,<?=$kegiatan?>,<?=$kode_pbm?>,<?=$no_booking?>,<?=$id_plc?>,<? echo number_format($tinggi); ?>,<? echo $isocode; ?>,<? echo $imoclass; ?>,<? echo $celcius; ?>,<?=$yard_b?>,<?=$yard_s?>,<?=$yard_r?>,<?=$yard_t?>,<?=$yard_stat?>" class="drag blue">
											<? echo substr($pel_asal,2,3); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo substr($pel_tuj,2,3)?></br>
											<?=$no_cont?><br/>
											<span>MSK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo number_format($gross_/1000,1); ?></span><br/>
											<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $size_cont.$type_cont; ?></span><br/>
											<span><? echo $tinggi; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div></td>
								<? 
										  }
										  else if(($stat_stowage=='T')&&($kegiatan=='BONGKAR'))
										  {
								?>
											<td width="85" class="mark8"><div id="<?=$vs_id?>,<?=$no_cont?>,<?=$size_cont?>,<?=$type_cont?>,<?=$status_cont?>,<?=$hz_?>,<?=$gross_?>,<?=$pel_asal?>,<?=$pel_tuj?>,<?=$kegiatan?>,<?=$kode_pbm?>,<?=$no_booking?>,<?=$id_plc?>,<? echo number_format($tinggi); ?>,<? echo $isocode; ?>,<? echo $imoclass; ?>,<? echo $celcius; ?>"><font size="0">
											<? echo substr($pel_asal,2,3); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo substr($pel_tuj,2,3)?></br>
											<?=$no_cont?><br/>
											<span>MSK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo number_format($gross_/1000,1); ?></span><br/>
											<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $size_cont.$type_cont; ?></span><br/>
											<span><? echo $tinggi; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</font></div></td>
								<?
										  }
										  else
										  {
								?>
											<td width="85" class="mark4"><div id="<?=$vs_id?>,<?=$no_cont?>,<?=$size_cont?>,<?=$type_cont?>,<?=$status_cont?>,<?=$hz_?>,<?=$gross_?>,<?=$pel_asal?>,<?=$pel_tuj?>,<?=$kegiatan?>,<?=$kode_pbm?>,<?=$no_booking?>,<?=$id_plc?>,<? echo number_format($tinggi); ?>,<? echo $isocode; ?>,<? echo $imoclass; ?>,<? echo $celcius; ?>"><font size="0">
											<? echo substr($pel_asal,2,3); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo substr($pel_tuj,2,3)?></br>
											<?=$no_cont?><br/>
											<span>MSK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo number_format($gross_/1000,1); ?></span><br/>
											<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $size_cont.$type_cont; ?></span><br/>
											<span><? echo $tinggi; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</font></div></td>
								<?
										  }
										} 
										else
										{
									?>
											<td width="85" class="mark2">&nbsp;</td>
									<?
										}
									}
									else
									{
										if($no_cont != NULL)
										{
											$filter_sz = $_POST['size'];
											$filter_ty = $_POST['type'];
											$filter_st = $_POST['statusx'];
											$filter_ton = $_POST['tonase'];
											$filter_pod = $_POST['id_pel_tuj'];
											$filter_vs = $_POST['id_vs'];
											$filter_un = $filter_sz.$filter_ty.$filter_st.$filter_ton.$filter_pod.$filter_vs;
											
											//------cek category tonase-------//
											$cek_gross = "SELECT KLASIFIKASI FROM MASTER_TONAGE WHERE SIZE_ = '20' AND MIN_ < '$gross_' AND MAX_ > '$gross_'";
											$result33= $db->query($cek_gross);	
											$hasil33 = $result33->fetchRow();
											$ctg = $hasil33['KLASIFIKASI'];
											
											$actual_yd = "";
											
											if(($filter_sz==NULL)&&($filter_ty==NULL)&&($filter_st==NULL)&&($filter_ton==NULL)&&($filter_pod==NULL)&&($filter_vs==NULL))
											{
												$actual_yd .= "X";
											}
											else
											{
												if($filter_sz!=NULL)
												{
													$actual_yd .= $size_cont;
												}
												else 
												{
													$actual_yd .= "";
												}
												
												if($filter_ty!=NULL)
												{
													$actual_yd .= $type_cont;
												}
												else 
												{
													$actual_yd .= "";
												}
												
												if($filter_st!=NULL)
												{
													$actual_yd .= $status_cont;
												}
												else 
												{
													$actual_yd .= "";
												}
												
												if($filter_ton!=NULL)
												{
													$actual_yd .= $ctg;
												}
												else 
												{
													$actual_yd .= "";
												}											
												
												if($filter_pod!=NULL)
												{
													$actual_yd .= $pel_tuj;
												}
												else 
												{
													$actual_yd .= "";
												}
												
												if($filter_vs!=NULL)
												{
													$actual_yd .= $vs_id;
												}
												else
												{
													$actual_yd .= "";
												}
											}										
										
											if($actual_yd==$filter_un)
											{
									?>		
													<td width="85"><div id="<?=$vs_id?>,<?=$no_cont?>,<?=$size_cont?>,<?=$type_cont?>,<?=$status_cont?>,<?=$hz_?>,<?=$gross_?>,<?=$pel_asal?>,<?=$pel_tuj?>,<?=$kegiatan?>,<?=$kode_pbm?>,<?=$no_booking?>,<?=$id_plc?>,<? echo number_format($tinggi); ?>,<? echo $isocode; ?>,<? echo $imoclass; ?>,<? echo $celcius; ?>" class="drag red">
													<? echo substr($pel_asal,2,3); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo substr($pel_tuj,2,3)?></br>
													<?=$no_cont?><br/>
													<span>MSK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo number_format($gross_/1000,1); ?></span><br/>
													<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $size_cont.$type_cont; ?></span><br/>
													<span><? echo $tinggi; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
													</div></td>
									<?
											}
											else
											{
									?>
												<td width="85"><div id="<?=$vs_id?>,<?=$no_cont?>,<?=$size_cont?>,<?=$type_cont?>,<?=$status_cont?>,<?=$hz_?>,<?=$gross_?>,<?=$pel_asal?>,<?=$pel_tuj?>,<?=$kegiatan?>,<?=$kode_pbm?>,<?=$no_booking?>,<?=$id_plc?>,<? echo number_format($tinggi); ?>,<? echo $isocode; ?>,<? echo $imoclass; ?>,<? echo $celcius; ?>" class="drag blue">
												<? echo substr($pel_asal,2,3); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo substr($pel_tuj,2,3)?></br>
												<?=$no_cont?><br/>
												<span>MSK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo number_format($gross_/1000,1); ?></span><br/>
												<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $size_cont.$type_cont; ?></span><br/>
												<span><? echo $tinggi; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
												</div></td>
									<?
											}
										}
										else
										{
									?>	
											<td width="85" class="mark2">&nbsp;</td>
									<?	
										}
									}
									
									} 
									?>
							</tr>						
							<? $t--; } ?>
							<tr>
								<td width="85" align="center" class="mark">&nbsp;</td>
								<? for($i=1;$i<=$width;$i++){ ?>
									<td width="85" align="center" class="mark"><b><?=$i?></b></td>
								<? } ?>
								<td width="85" align="center" class="mark"><b>ROW</b></td>
							</tr>
						</tbody>
					</table>
					<? } ?>
				</div><!-- left container -->
				
				</div>			
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?
					$db = getDB();
					$ves_voy = "SELECT NM_KAPAL,
									   VOYAGE_IN|| ' - ' ||VOYAGE_OUT AS VOYAGE
									   FROM RBM_H
									   WHERE NO_UKK = '$id_vs'";
					$vvoy = $db->query($ves_voy);
					$hasil_vv = $vvoy->fetchRow();
					$vessel = $hasil_vv['NM_KAPAL'];
					$voyage = $hasil_vv['VOYAGE'];
					$vesvoy = $vessel." ".$voyage;
				?>
				<!--<a onclick="info_trim('<?=$id_vs?>','<?=$vesvoy?>')" style="padding-top:20px;"><img border='0' src='<?=HOME?>images/shipok.png' width='35' height='35'></a>-->
				<div style="width: 500px; float: right;">
				<?
					$id_bay = $_GET['bay_area'];					
					
					if(($id_area != NULL)||($id_bay != NULL))
					{
						if($id_area != NULL)
						{
							$area_id = $id_area;
						}
						else if($id_bay != NULL)
						{
							$area_id = $id_bay;
						}
						
						$get_bay = "SELECT BAY, OCCUPY FROM STW_BAY_AREA WHERE ID = '$area_id'";
						$bay_result = $db->query($get_bay);
						$bay_no = $bay_result->fetchRow();
						
						$bay_info = $bay_no['BAY'];
						$occx = $bay_no['OCCUPY'];
					}
				?>
				<h2><? echo $vessel." / ".$voyage; ?> <? if($id_area != NULL) { ?><font size="2px">Bay <? if ($occx=='Y') { ?><?echo $bay_info;?>(<? echo $bay_info+1; ?>)<?  } else if ($occx=='Y') { ?><? echo $bay_info;?>(<? echo $bay_info+1; ?>)<? } else { ?><? echo $bay_info;?><? } ?>&nbsp;<?=$posisi_stack;?></font><? } ?></h2>
				<br/>
				<form name="bay_form" id="bay_form" enctype="multipart/form-data" action="<?=HOME?>planning.stowage.ajax/stow_bay?id_vs=<?=$id_vs;?>&block=<?=$block;?>&slot=<?=$slot;?>&id_block=<?=$id_block?>&yard=<?=$yard?>" method="post">
				Bay : <select name="bay_select" id="bay_select" onChange="ubah()">
						<option value="">-Pilih-</option>				
				<?
					$db = getDB();
					$query_get_bay = "SELECT ID,BAY,OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0 ORDER BY ID ASC";
					$result_bay    = $db->query($query_get_bay);
					$row_bay       = $result_bay->getAll();
					
					foreach ($row_bay as $row5) {
						$no_bay = $row5['BAY'];
						$occ = $row5['OCCUPY'];
						$odd_bay = $no_bay+1;
				?>				
						<option value="<?=$row5['ID'];?>,<?=$occ?>"><? if($row5['BAY']<10) { if ($occ=='Y') { echo "00".$no_bay."(00".$odd_bay.")"; } else if($occ=='Y'){ if($no_bay==9) { echo "00".$no_bay."(0".$odd_bay.")"; } else { echo "00".$no_bay."(00".$odd_bay.")"; } } else { echo "00".$row5['BAY']; } } else { if($occ=='Y') {echo "0".$no_bay."(0".$odd_bay.")"; } else { echo "0".$row5['BAY']; } } ?></option>
						
				<? } ?>		
					  </select>&nbsp;&nbsp;&nbsp;
					  <select name="posisi_bay" id="posisi_bay">
						<option value="ALL">ALL</option>
						<option value="ABOVE">ABOVE</option>
						<option value="BELOW">BELOW</option>
					  </select>
				</form>
				<!-- right container -->
				<div id="right">
				<? 
					$db = getDB();						
					$lebar_bay = "SELECT DISTINCT JML_ROW FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0";
					$result_lebar = $db->query($lebar_bay);
					$bay_lebar = $result_lebar->fetchRow();
							
					$jumlah_row = $bay_lebar['JML_ROW'];
					$lebar_bay = 100*$jumlah_row; 
				?>
				<table id="table2" width="<?=$lebar_bay?>">
				<? 					
					if(($id_area != NULL)||($id_bay != NULL))
					{
						if($id_area != NULL)
						{
							$area_id = $id_area;
						}
						else if($id_bay != NULL)
						{
							$area_id = $id_bay;
						}
						
							$db = getDB();
							$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0";
							$result_   = $db->query($query_bay);
							$bay_      = $result_->fetchRow();
							
							$jumlah_row = $bay_['JML_ROW'];
							$jml_tier_under = $bay_['JML_TIER_UNDER'];
							$jml_tier_on = $bay_['JML_TIER_ON'];
							$width = $jumlah_row+1;
							
						?>
						<tbody>
							<tr>
								<?
									if($posisi_stack=='BELOW')
									{
										$query_cell2 = "SELECT ID,CELL_NUMBER,ROW_,TIER_,STATUS_STACK FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$area_id' AND POSISI_STACK IN ('BELOW','HATCH') ORDER BY CELL_NUMBER ASC";
										$result3 = $db->query($query_cell2);
										$blok2 = $result3->getAll();
									}
									else if($posisi_stack=='ABOVE')
									{
										$query_cell2 = "SELECT ID,CELL_NUMBER,ROW_,TIER_,STATUS_STACK FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$area_id' AND POSISI_STACK IN ('ABOVE','HATCH') ORDER BY CELL_NUMBER ASC";
										$result3 = $db->query($query_cell2);
										$blok2 = $result3->getAll();
									}
									else
									{
										$query_cell2 = "SELECT ID,CELL_NUMBER,ROW_,TIER_,STATUS_STACK FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$area_id' ORDER BY CELL_NUMBER ASC";
										$result3 = $db->query($query_cell2);
										$blok2 = $result3->getAll();
									}							 
									 
									 $n='';
									 $br='';
									 $tr='';
									 
									 foreach ($blok2 as $row8){
										$cell_id = $row8['CELL_NUMBER'];
										$index = $row8['CELL_NUMBER']+1;
										$idx_cll = $row8['ID'];
										$br = $n;
										$tr = $row8['TIER_'];
										$n = $tr;
										$rw = $row8['ROW_'];
										
								if ($index%$width != 0) 
								{ 
								  if ($row8['STATUS_STACK'] == 'A')
								 {
									?>
										<td width="85" id="<?=$row8['CELL_NUMBER']?>" style="background-color:#CCFF99;"></td>
									<?
								 }
									else if ($row8['STATUS_STACK'] == 'P')
								 {
									$bay_plc = "SELECT A.ID_PLACEMENT, 
										               A.NO_CONTAINER, 
													   TRIM(A.SIZE_) AS SIZE_, 
													   A.TYPE_, 
													   A.STATUS_, 
													   A.ID_VS, 
													   A.ACTIVITY, 
													   A.HZ, 
													   A.GROSS, 
													   A.ID_PEL_ASAL, 
													   A.ID_PEL_TUJ, 
													   A.KODE_PBM, 
													   A.NO_BOOKING_SL, 
													   A.ISO_CODE,
													   B.H_ISO,
													   A.IMO_CLASS,
													   A.CELCIUS,
													   A.CARRIER,
													   A.BAY
													FROM STW_PLACEMENT_BAY A, MASTER_ISO_CODE B
													WHERE A.ID_CELL = '$idx_cll'
														AND A.ISO_CODE = B.ISO_CODE
														AND A.ACTIVITY = 'MUAT'";
										$hasil_bay = $db->query($bay_plc);	
										$plc_bay = $hasil_bay->fetchRow();
										
										$vs_id=$plc_bay['ID_VS'];
										$kegiatan=$plc_bay['ACTIVITY'];
										$no_cont=$plc_bay['NO_CONTAINER'];
										$size_cont=$plc_bay['SIZE_'];
										$type_cont=$plc_bay['TYPE_'];
										$status_cont=$plc_bay['STATUS_'];
										$id_plc=$plc_bay['ID_PLACEMENT'];
										$hz_=$plc_bay['HZ'];
										$gross_=$plc_bay['GROSS'];
										$pel_asal=$plc_bay['ID_PEL_ASAL'];
										$pel_tuj=$plc_bay['ID_PEL_TUJ'];
										$kode_pbm=$plc_bay['KODE_PBM'];
										$no_booking=$plc_bay['NO_BOOKING_SL'];
										$height=$plc_bay['H_ISO'];
										$tinggi=ceil($height*30.48);
										$isocode=$plc_bay['ISO_CODE'];
										$imoclass=$plc_bay['IMO_CLASS'];
										$celcius=$plc_bay['CELCIUS'];
										$no_bay=$plc_bay['BAY'];										
									
									if((($size_cont=='40')||($size_cont=='45'))&&($occ_stat=='Y'))
									{										
										?>										
											<td width="85" class="mark4" onclick="reset_plan('<?=$vs_id;?>','<?=$no_cont;?>')">
											<font size="0">
											<? echo substr($pel_asal,2,3); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo substr($pel_tuj,2,3)?></br>
											<?=$no_cont?><br/>
											<span>MSK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo number_format($gross_/1000,1); ?></span><br/>
											<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $size_cont.$type_cont; ?></span><br/>
											<span><? echo $tinggi; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</font>
											</td>
										<?
										
									}
									else if((($size_cont=='40')||($size_cont=='45'))&&($occ_stat=='T'))
									{
										?>
											<td width="85" class="mark4">+</td>
										<?
									}									
									else if($size_cont=='20')
									{
										?>
											<td width="85" class="mark4" onclick="reset_plan('<?=$vs_id;?>','<?=$no_cont;?>')">
											<font size="0">
											<? echo substr($pel_asal,2,3); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo substr($pel_tuj,2,3)?></br>
											<?=$no_cont?><br/>
											<span>MSK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo number_format($gross_/1000,1); ?></span><br/>
											<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $size_cont.$type_cont; ?></span><br/>
											<span><? echo $tinggi; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</font>
											</td>
										<?
									}									
									else
									{
										?>
											<td width="85" class="mark4"></td>
										<?
									}
								}
								else if ($row8['STATUS_STACK'] == 'R')
								{
									$bay_plc = "SELECT A.ID_PLACEMENT, 
										               A.NO_CONTAINER, 
													   TRIM(A.SIZE_) AS SIZE_, 
													   A.TYPE_, 
													   A.STATUS_, 
													   A.ID_VS, 
													   A.ACTIVITY, 
													   A.HZ, 
													   A.GROSS, 
													   A.ID_PEL_ASAL, 
													   A.ID_PEL_TUJ, 
													   A.KODE_PBM, 
													   A.NO_BOOKING_SL, 
													   A.ISO_CODE,
													   B.H_ISO,
													   A.IMO_CLASS,
													   A.CELCIUS,
													   A.CARRIER,
													   A.BAY
													FROM STW_PLACEMENT_BAY A, MASTER_ISO_CODE B
													WHERE A.ID_CELL = '$idx_cll'
														AND A.ISO_CODE = B.ISO_CODE
														AND A.ACTIVITY = 'MUAT'";
										$hasil_bay = $db->query($bay_plc);	
										$plc_bay = $hasil_bay->fetchRow();
										
										$vs_id=$plc_bay['ID_VS'];
										$kegiatan=$plc_bay['ACTIVITY'];
										$no_cont=$plc_bay['NO_CONTAINER'];
										$size_cont=$plc_bay['SIZE_'];
										$type_cont=$plc_bay['TYPE_'];
										$status_cont=$plc_bay['STATUS_'];
										$id_plc=$plc_bay['ID_PLACEMENT'];
										$hz_=$plc_bay['HZ'];
										$gross_=$plc_bay['GROSS'];
										$pel_asal=$plc_bay['ID_PEL_ASAL'];
										$pel_tuj=$plc_bay['ID_PEL_TUJ'];
										$kode_pbm=$plc_bay['KODE_PBM'];
										$no_booking=$plc_bay['NO_BOOKING_SL'];
										$height=$plc_bay['H_ISO'];
										$tinggi=ceil($height*30.48);
										$isocode=$plc_bay['ISO_CODE'];
										$imoclass=$plc_bay['IMO_CLASS'];
										$celcius=$plc_bay['CELCIUS'];
										$no_bay=$plc_bay['BAY'];
										
									
									if((($size_cont=='40')||($size_cont=='45'))&&($occ_stat=='Y'))
									{										
										?>										
											<td width="85" class="mark8" onclick="shifting('<?=$vs_id;?>','<?=$no_cont;?>','<?=$size_cont?>')">
											<font size="0">
											<? echo substr($pel_asal,2,3); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo substr($pel_tuj,2,3)?></br>
											<?=$no_cont?><br/>
											<span>MSK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo number_format($gross_/1000,1); ?></span><br/>
											<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $size_cont.$type_cont; ?></span><br/>
											<span><? echo $tinggi; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</font>
											</td>
										<?
										
									}
									else if((($size_cont=='40')||($size_cont=='45'))&&($occ_stat=='T'))
									{
										?>
											<td width="85" class="mark8">+</td>
										<?
									}
									else if($size_cont=='20')
									{
										?>
											<td width="85" class="mark8" onclick="shifting('<?=$vs_id;?>','<?=$no_cont;?>','<?=$size_cont?>')">
											<font size="0">
											<? echo substr($pel_asal,2,3); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo substr($pel_tuj,2,3)?></br>
											<?=$no_cont?><br/>
											<span>MSK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo number_format($gross_/1000,1); ?></span><br/>
											<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $size_cont.$type_cont; ?></span><br/>
											<span><? echo $tinggi; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</font>
											</td>
										<?
									}									
									else
									{
										?>
											<td width="85" class="mark8"></td>
										<?
									}
								}
								else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
								{ ?>
								   
								<td width="85" class="mark3"></td>   
								   
								 <? }
									else if($row8['STATUS_STACK'] == 'N')
									{
										$cek_capacity_abv  = "SELECT PLAN_HEIGHT, PLAN_WEIGHT FROM STW_BAY_CAPACITY WHERE ID_BAY_AREA = '$area_id' AND ROW_ = '$rw' AND POSISI_STACK = 'ABOVE'";
										$result6_abv  = $db->query($cek_capacity_abv);
										$capacity_abv  = $result6_abv->fetchRow();
										$plan_w_abv = number_format($capacity_abv['PLAN_WEIGHT']/1000,1);
										$plan_h_abv = number_format($capacity_abv['PLAN_HEIGHT']/100,1);

										$cek_capacity_blw  = "SELECT PLAN_HEIGHT, PLAN_WEIGHT FROM STW_BAY_CAPACITY WHERE ID_BAY_AREA = '$area_id' AND ROW_ = '$rw' AND POSISI_STACK = 'BELOW'";
										$result6_blw  = $db->query($cek_capacity_blw);
										$capacity_blw  = $result6_blw->fetchRow();
										$plan_w_blw = number_format($capacity_blw['PLAN_WEIGHT']/1000,1);
										$plan_h_blw = number_format($capacity_blw['PLAN_HEIGHT']/100,1);	
										
										if(($index>=1)&&($index<$width)) 
									 {
									?>
										<td width="85" class="mark">
										<? 
											if($plan_h_abv!=0)
											{
										?>
												<font size="2"><?=$rw;?></font><br/>
												<font size="1"><b><?=$plan_w_abv;?></b><br/>
												<b><?=$plan_h_abv;?></b></font>
										<?
											}
										?>									
										</td>
									<? }
										else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
									?>
										<td width="85" class="mark">
										<? 
											if($plan_h_blw!=0)
											{
										?>
												<font size="4"><?=$rw;?></font><br/>
												<font size="1"><b><?=$plan_w_blw;?></b><br/>
												<b><?=$plan_h_blw;?></b></font>
										<?
											}
										?>
										</td>
									<? }
										else
									   {
									?>
										<td width="85" class="mark2"></td>
									<? } ?>								 
									 
								 <? }
								 }
								 else if(($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0))
								 { ?>
								 
									<td width="85" class="mark"><?=$br;?></td>
									</tr>
								 <? }
								 else if($index%$width == 0) 
								 {
									if ($br != 0)
									   { 
										 if ($index==$width)
										 { ?>
										 <td width="85" class="mark">&nbsp;</td>
										 <? }
										  else {
										 ?>
										<td width="85" class="mark"><?=$br;?></td>
										<? 
										  } 
									   }
									   else
									   {  
										  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
										 { ?>
										 <td width="85" class="mark"><b>&nbsp;</b></td>
										 <? }
											else { ?>
										 <td width="85" class="mark"><b>HATCH</b></td>
									<? }
										} ?>
								</tr>
							
							<? }
                               }	?>
							   
						</tbody>
						<? } ?>
					</table>		
				</div><!-- right container -->
					 
				</div>
			
			</div><!-- drag container -->
			<!-- needed for cloning DIV elements -->
			<div id="redips_clone"></div>
			<!-- message -->			
			<div id="status"></div>
			<div id="message">Clone element witd SHIFT key</div>
			<input id="id_SAVEBUT" class="butsave" type="button" onClick="save('plain')" value="S A V E">
			<div id="dialog-form">
				<form>
					<div id="table_vessel"></div>
				</form>
				<form>
					<div id="yd_filter"></div>
				</form>
				<form>
					<div id="cont_shifting"></div>
				</form>
				<form>
					<div id="block_option"></div>
				</form>
			</div>
		</div><!-- main container -->		
	<br/>
	</div>


	</body>
	</html>