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
	//$id_vs = $_GET['id_vs'];
	$block_select = $_POST['block_select'];
	$blk = explode(",",$block_select);
	$idblock = $blk[0];
	$nmblock = $blk[1];
	$view_slot = $_POST['view_slot'];
	
	$nocont = $_GET['no_container'];
	$block = $_GET['block'];
	$id_block = $_GET['id_block'];
	$sz = $_GET['size_cont'];
	$slots = $_GET['slot'];
	//print_r($nocont);die;
	
	if($sz=='40')
	{
		$slt = explode("-",$slots);
		$slot = $slt[0];
		$slot2 = $slt[1];
	}
	else
	{
		$slot = $slots;
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

/*
var time_left = 120; // timeout
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
	var r = confirm("Waktu Relocation Habis...Ulangi Proses!!!");
	if(r == true)
	{	
		var no_cont = "<?=$nocont?>";
		var sz = "<?=$sz?>";
		var block_id = "<?=$id_block?>";
		var b = "<?=$block?>";
		var s = "<?=$slot?>";
		window.location = '<?=HOME?>operational.relokasi.export.plc/relocate_cont?no_container='+no_cont+'&size_cont='+sz+'&id_block='+block_id+'&block='+b+'&slot='+s;
	}
	else
	{
		window.close();
		window.opener.location = '<?=HOME?>operational.relokasi.export/';
	}	
},120000);
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
				msg = 'Source Block';
				break;
			case 'right':
				msg = 'Destination Block';
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
			
		var id_yard_ = "81";
		var block_id = "<?=$idblock?>";
		var slot_yd = "<?=$view_slot?>";
		var pos = rd.get_position();
		var row_yd = pos[2];
		var url	= "get_tier";
		
		$.post(url,{ID_YARD : id_yard_, ID_BLOCK : block_id, SLOT_YARD : slot_yd, ROW_YARD : row_yd},function(data){
		console.log(data);	
		
		var alokasi = data;
		var explode = alokasi.split(',');
			var tier_ = explode[0];
			var sz = explode[1];
			var ty = explode[2];
			var st = explode[3];
			var pod = explode[4];
			var ctg = explode[5];
		
		stat.innerHTML = '<b>Position</b>: Row ' +pos[2]+ ' Tier ' +(tier_-(pos[1]-1))+ ' ::<b>Allocation</b>: ' +sz+ ' ' +ty+ ' ' +st+ ' ::POD: ' +pod+ ' ::CATEGORY: ' +ctg;	
	  
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

function view_bs()
{
	document.relocate_form.submit();
}


// show prepared content for saving
function save(type) {
	// define table_content variable
	var table_content;
	// prepare table content of first table in JSON format or as plain query string (depends on value of "type" variable)
	table_content = REDIPS.drag.save_content('table2', type);
	// if content doesn't exist
	if (!table_content) {
		alert('Table is empty!');
	}
	// display query string
	else if (type === 'plain') 
	{
		//window.open('/my/multiple-parameters.php?' + table_content, 'Mypop', 'width=350,height=160,scrollbars=yes');
		//window.open('multiple-parameters.php?' + table_content, 'Mypop', 'width=350,height=260,scrollbars=yes');
		
		var r = confirm("Anda Yakin?")
		if (r==true)
		  {
			// get target and source position (method returns positions as array)
				$.blockUI({ message: '<h1><br>Please wait...Saving Data</h1><br><br>' });
				var id_yard_ = "81";
				var block_id = "<?=$idblock?>";
				var url	= "get_tier";
				
				$.post(url,{ID_YARD : id_yard_, ID_BLOCK : block_id},function(data){
				console.log(data);
						
				var tier_ = data;
				var s = "<?=$view_slot?>";
				var nm_block = "<?=$nmblock?>";
				var url	= "multiple-parameters?"+table_content;
				
				$.post(url,{ID_YARD : id_yard_, ID_BLOCK : block_id, TIER : tier_, SLOT : s, NM_BLOCK : nm_block},function(data3){
				    console.log(data3);
				    alert(data3);
					if(data3 == "OK")
					{
						$.unblockUI({
								onUnblock: function(){  }
								});
						alert("Data Berhasil Disimpan...!!!");
						window.close();
						window.opener.location = "<?=HOME?>operational.relokasi.export/";
					}
					else if(data3 == "EXIST")
					{
						$.unblockUI({
								onUnblock: function(){  }
								});
						alert("Lokasi sudah terpakai!!!");
						window.close();
						window.opener.location = "<?=HOME?>operational.relokasi.export/";
					}
					else if(data3 == "ALOKASIOK")
					{
						$.unblockUI({
								onUnblock: function(){  }
								});
						alert("Tidak Sesuai Alokasi...Silakan dicek kembali!!!");
						window.close();
						window.opener.location = "<?=HOME?>operational.relokasi.export/";
					}
					else
					{
						$.unblockUI({
								onUnblock: function(){  }
								});
						alert("Data Gagal Disimpan...Silakan dicek kembali!!!");
						window.close();
						window.opener.location = "<?=HOME?>operational.relokasi.export/";
					}
							
				});				
			  
			  });
		  }
		else
		  {
			window.close();
			window.opener.location = '<?=HOME?>operational.relokasi.export/';
		  }		
			
	}
}

</script>

<title>RELOCATION EXPORT [<?=$nocont;?>]</title>
</head>

<body>
<div class="content">	
	<div id="main_container">			
			<!-- tables inside tdis DIV could have draggable content -->
			<!--Time Out In <span id="countdown" align="right"><blink><b><font color="red" size="+3">120</font></b></blink> s</span>-->
			<p><br/></p>
			<div id="drag">
				<div style="width: 400px; float: left;">
                <h2>SOURCE BLOCK</h2>
				<br/>
				<font size="3">[<b><?=$block;?>&nbsp;<?=$slot;?></b>]</font>
				<?
				$db = getDB();
				$query_lap = "SELECT NAME, TIER, (select count(1) from YD_BLOCKING_CELL b WHERE b.ID_BLOCKING_AREA='$id_block' and b.SLOT_='$slot') AS JML_ROW FROM YD_BLOCKING_AREA a WHERE a.ID_YARD_AREA='81' AND a.ID='$id_block'";
				
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
					<? $lebar = 90+($width*90)+90; ?>
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
														  A.TYPE_CONT, 
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
														  A.CELCIUS
													FROM YD_PLACEMENT_YARD A, MASTER_ISO_CODE B 
													WHERE ID_BLOCKING_AREA='$id_block' 
													     AND SLOT_YARD='$slot' 
														 AND ROW_YARD='$i' 
														 AND TIER_YARD='$t' 
														 --AND STOWAGE = 'T' 
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
										
									if($no_cont != NULL)
									{
										if($no_cont == $nocont)
										{
									?>
										<td width="85"><div id="<?=$vs_id?>,<?=$no_cont?>,<?=$size_cont?>,<?=$type_cont?>,<?=$status_cont?>,<?=$hz_?>,<?=$gross_?>,<?=$pel_asal?>,<?=$pel_tuj?>,<?=$kegiatan?>,<?=$kode_pbm?>,<?=$no_booking?>,<?=$id_plc?>,<? echo number_format($tinggi); ?>,<? echo $isocode; ?>,<? echo $imoclass; ?>,<? echo $celcius; ?>" class="drag red">
										<? echo substr($pel_asal,2,3) ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo substr($pel_tuj,2,3)?></br>
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
										<td width="85"><div id="<?=$vs_id?>,<?=$no_cont?>,<?=$size_cont?>,<?=$type_cont?>,<?=$status_cont?>,<?=$hz_?>,<?=$gross_?>,<?=$pel_asal?>,<?=$pel_tuj?>,<?=$kegiatan?>,<?=$kode_pbm?>,<?=$no_booking?>,<?=$id_plc?>,<? echo number_format($tinggi); ?>,<? echo $isocode; ?>,<? echo $imoclass; ?>,<? echo $celcius; ?>" class="drag grey">
										<? echo substr($pel_asal,2,3) ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo substr($pel_tuj,2,3)?></br>
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
									} ?>
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
				</div><!-- left container -->
				
				</div>
				
				
				
				<div style="width: 500px; float: right;">							
				<h2>
					DESTINATION BLOCK
					<? if($nmblock!=NULL){?> [<? echo $nmblock;?>&nbsp;<?=$view_slot?>] <?} ?>
				</h2>
				<br/>
				<form name="relocate_form" id="relocate_form" enctype="multipart/form-data" action="<?=HOME?>operational.relokasi.export.plc/relocate_cont?no_container=<?=$nocont;?>&block=<?=$block;?>&slot=<?=$slots;?>&id_block=<?=$id_block?>&size_cont=<?=$sz?>" method="post">
				Block : <select name="block_select" id="block_select">
						<option value="">-Pilih-</option>				
				<?
					$db = getDB();
					$query_get_block = "SELECT ID,NAME FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA = '81' ORDER BY ID ASC";
					$result_block    = $db->query($query_get_block);
					$row_block       = $result_block->getAll();
					
					foreach ($row_block as $row5) {
						$nm_block = $row5['NAME'];;
				?>				
						<option value="<?=$row5['ID'];?>,<?=$nm_block?>"><?=$nm_block?></option>
						
				<? } ?>		
					  </select>&nbsp;&nbsp;&nbsp;
					  Slot : <input type="text" name="view_slot" id="view_slot" size="5" onblur="view_bs()" />
				</form>
				<!-- right container -->
				<div id="right">
				<? 
					$db = getDB();						
										
					$query_lap_tuj = "SELECT NAME, TIER, (select count(1) from YD_BLOCKING_CELL b WHERE b.ID_BLOCKING_AREA='$idblock' and b.SLOT_='$view_slot') AS JML_ROW FROM YD_BLOCKING_AREA a WHERE a.ID_YARD_AREA='81' AND a.ID='$idblock'";
					
					//echo $query_lap;die;				
					$result_lap_tuj = $db->query($query_lap_tuj);	
					$hasil6 = $result_lap_tuj->fetchRow();
					$blok6 = $hasil6['NAME'];
					$tier6 = $hasil6['TIER'];
					$row6 = $hasil6['JML_ROW'];

					$widths=$row6;
					$heigths=$tier6;
					$tr=$heigths;
					$rw=$widths;
				   
					$lebar_tuj = 90+($widths*90)+90;
					
					if(($idblock!=NULL)&&($view_slot!=NULL))
					{
				?>
					<table id="table2" width="<?=$lebar_tuj?>">
						<tbody>
							<tr>
								<td width="85" align="center" class="mark"><b>TIER</b></td>
							</tr>
							<?
								for($x=1;$x<=$heigths;$x++)
								{
							?>
							<tr>								
								<td width="85" class="mark"><b><?=$tr;?></b></td>
								<?
									for($i=1;$i<=$widths;$i++)
									{
										$query6 = "SELECT A.ID_PLACEMENT, 
										                  A.NO_CONTAINER, 
														  A.SIZE_, 
														  A.TYPE_CONT, 
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
														  A.CELCIUS
													FROM YD_PLACEMENT_YARD A, MASTER_ISO_CODE B 
													WHERE ID_BLOCKING_AREA='$idblock' 
													     AND SLOT_YARD='$view_slot' 
														 AND ROW_YARD='$i' 
														 AND TIER_YARD='$tr' 
														-- AND STOWAGE = 'T' 
														 AND A.ISO_CODE = B.ISO_CODE";
										$result6= $db->query($query6);	
										$hasil6 = $result6->fetchRow();
										
										$vs_id6=$hasil6['ID_VS'];
										$kegiatan6=$hasil6['ACTIVITY'];
										$no_cont6=$hasil6['NO_CONTAINER'];
										$size_cont6=$hasil6['SIZE_'];
										$type_cont6=$hasil6['TYPE_CONT'];
										$status_cont6=$hasil6['STATUS_CONT'];
										$id_plc6=$hasil6['ID_PLACEMENT'];
										$hz_6=$hasil6['HZ'];
										$gross_6=$hasil6['TON'];
										$pl_asal6=$hasil6['ID_PEL_ASAL'];
										$pl_tuj6=$hasil6['ID_PEL_TUJ'];
										$pel_asal6 = str_replace(' ','',$pl_asal6);
										$pel_tuj6 = str_replace(' ','',$pl_tuj6);
										$kode_pbm6=$hasil6['KODE_PBM'];
										$no_booking6=$hasil6['NO_BOOKING_SL'];
										$height6=$hasil6['H_ISO'];
										$tinggi6=ceil($height6*30.48);
										$isocode6=$hasil6['ISO_CODE'];
										$imoclass6=$hasil6['IMO_CLASS'];
										$celcius6=$hasil6['CELCIUS'];
										
									if($no_cont6 != NULL)
									{
									?>
										<td width="85" class="mark4"><div id="<?=$vs_id6?>,<?=$no_cont6?>,<?=$size_cont6?>,<?=$type_cont6?>,<?=$status_cont6?>,<?=$hz_6?>,<?=$gross_6?>,<?=$pel_asal6?>,<?=$pel_tuj6?>,<?=$kegiatan6?>,<?=$kode_pbm6?>,<?=$no_booking6?>,<?=$id_plc6?>,<? echo number_format($tinggi6); ?>,<? echo $isocode6; ?>,<? echo $imoclass6; ?>,<? echo $celcius6; ?>">
										<font size="1"><? echo substr($pel_asal6,2,3) ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo substr($pel_tuj6,2,3)?></font></br>
										<font size="1"><?=$no_cont6?></font><br/>
										<span><font size="1">MSK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo number_format($gross_6/1000,1); ?></font></span><br/>
										<span><font size="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $size_cont6.$type_cont6; ?></font></span><br/>
										<span><font size="1"><? echo $tinggi6; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></span>
										</div></td>
									<?
									} 
										else
									{
								?>
										<td width="85"></td>
								<?
									}
									} ?>
							</tr>						
							<? $tr--; } ?>
							<tr>
								<td width="85" align="center" class="mark">&nbsp;</td>
								<? for($i=1;$i<=$widths;$i++){ ?>
									<td width="85" align="center" class="mark"><b><?=$i?></b></td>
								<? } ?>
								<td width="85" align="center" class="mark"><b>ROW</b></td>
							</tr>
						</tbody>
					</table>
					<? } ?>
				</div><!-- right container -->
					 
				</div>
			
			</div><!-- drag container -->
			<!-- needed for cloning DIV elements -->
			<div id="redips_clone"></div>
			<!-- message -->			
			<div id="status"></div>
			<div id="message">Clone element witd SHIFT key</div>
			<input id="id_SAVEBUT" class="butsave" type="button" onClick="save('plain')" value="S A V E">
		</div><!-- main container -->
	<br/>
	</div>
	
<script>
if (!document.layers)
document.write('<div id="divStayTopLeft" style="position:absolute">')
</script>

<layer id="divStayTopLeft">

<!--EDIT BELOW CODE TO YOUR OWN MENU
<br/>
<table border="0" width="110" cellspacing="0" cellpadding="0">
  <tr>
	<td bgcolor="#FFFFFF" align="center">&nbsp;      
       
	</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF" align="center">      
       <input type="button" value="Reset Stowage" class="button" onClick="reset_stow('<?=$id_vs?>','<?=$block?>','<?=$slot?>','<?=$yard?>','<?=$id_block?>')"/>
	</td>
  </tr>
  <tr>
	<td bgcolor="#FFFFFF" align="center">&nbsp;      
       
	</td>
  </tr>
</table>
<br/>
END OF EDIT-->

</layer>


<script type="text/javascript">

/*
Floating Menu script-  Roy Whittle (http://www.javascript-fx.com/)
Script featured on/available at http://www.dynamicdrive.com/
This notice must stay intact for use
*/

//Enter "frombottom" or "fromtop"
var verticalpos="frombottom"

if (!document.layers)
document.write('</div>')

function JSFX_FloatTopDiv()
{
	var startX = 1,
	startY = 350;
	var ns = (navigator.appName.indexOf("Netscape") != -1);
	var d = document;
	function ml(id)
	{
		var el=d.getElementById?d.getElementById(id):d.all?d.all[id]:d.layers[id];
		if(d.layers)el.style=el;
		el.sP=function(x,y){this.style.left=x;this.style.top=y;};
		el.x = startX;
		if (verticalpos=="fromtop")
		el.y = startY;
		else{
		el.y = ns ? pageYOffset + innerHeight : document.body.scrollTop + document.body.clientHeight;
		el.y -= startY;
		}
		return el;
	}
	window.stayTopLeft=function()
	{
		if (verticalpos=="fromtop"){
		var pY = ns ? pageYOffset : document.body.scrollTop;
		ftlObj.y += (pY + startY - ftlObj.y)/8;
		}
		else{
		var pY = ns ? pageYOffset + innerHeight : document.body.scrollTop + document.body.clientHeight;
		ftlObj.y += (pY - startY - ftlObj.y)/8;
		}
		ftlObj.sP(ftlObj.x, ftlObj.y);
		setTimeout("stayTopLeft()", 10);
	}
	ftlObj = ml("divStayTopLeft");
	stayTopLeft();
}
JSFX_FloatTopDiv();
</script>
	</body>
	</html>