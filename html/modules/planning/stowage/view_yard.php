<script type="text/javascript" src="<?=HOME;?>js/stickytooltip.js"></script>
<link rel="stylesheet" type="text/css" href="<?=HOME;?>css/stickytooltip.css" />
<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
.main_side{
	width:100%;
	float:left;
	text-align:left;
}
.tabss{
	margin-top: 20px;
}
.ui-jqgrid .ui-jqgrid-htable th div {
    height:auto;
    overflow:hidden;
    padding-right:4px;
    padding-top:2px;
    position:relative;
    vertical-align:text-top;
    white-space:normal !important;
}
</style>

<? $vs1 = $_POST['id_vs'];
   $vs2 = $_GET['vs'];
   $vs3 = $_GET['no_ukk'];
		 
		 if($vs1 != NULL)
		 {
			$id_vs = $vs1;
		 }
		 else if($vs2 != NULL)
		 {
			$id_vs = $vs2;
		 }
		 else if($vs3 != NULL)
		 {
			$id_vs = $vs3;
		 }
		 
		 ?>

<script type="text/javascript">
      	
function stow(b,d,e)
{
	$('#table_stow').load('<?=HOME?>planning.stowage.ajax/stow_bay?bay='+b).dialog({modal:true, height:500,width:800, title:"STOWAGE : Block-"+b+" Row-"+d+" Slot-"+e});
}

function stowage(b,d,e)
{
	//alert(b);
	$('#table_stow').load("<?=HOME?><?=APPID?>/stowage_bay?bay="+b+" #baystow_input").dialog({modal:true, height:500,width:800, title:"STOWAGE : Block-"+b+" Row-"+d+" Slot-"+e});
}

function openWin(yard,id_block,b,s,r,vs)
{
	var filter = "0";
	myWindow=window.open('<?=HOME?>planning.stowage.ajax/stow_bay?block='+b+'&slot='+s+'&row='+r+'&id_vs='+vs+'&yard='+yard+'&id_block='+id_block+'&filter='+filter,'','width=1400,height=700,scrollbars=yes,resizable=no');
	myWindow.focus();
}

function view_layout() 
{
    var yard_           = $("#yard").val();
	var blok_           = $("#blok").val();	
	var filter_         = $("#filter").val();	
	var view_           = $("#view").val();
    var kategori        = $("#kategori").val();
	var id_kategori 	= $("#id_kategori").val();
    var id_kategori2 	= $("#id_kategori2").val();
	var url             = "<?=HOME;?>planning.stowage/layout_lapangan #list";
	 
	$("#request_list").load(url,{NO_REQ : no_req_, FROM : from_, TO : to_, CARI : cari_}, function(data){
	
	});
}

$(document).ready(function()
        {				
			var watermark = 'Autocomplete';						
			<!------------------- watermark Vessel ------------>
			$('#vessel').val(watermark).addClass('watermark');
			//if blur and no value inside, set watermark text and class again.
			$('#vessel').blur(function(){
				if ($(this).val().length == 0){
					$(this).val(watermark).addClass('watermark');
				}
			});
		 
			//if focus and text is watermrk, set it to empty and remove the watermark class
			$('#vessel').focus(function(){
				if ($(this).val() == watermark){
					$(this).val('').removeClass('watermark');
				}
		    });
			<!------------------- watermark Vessel ------------>
											
	   });
		
		
$(function() {
	
	<!------------------- autocomplete Vessel ------------>
	$( "#vessel" ).autocomplete({
		minLength: 3,
		source: "<?=HOME?>planning.bay_allocation.auto/vessel",
		focus: function( event, ui ) {
			$( "#vessel" ).val( ui.item.NM_KAPAL );
			return false;
		},
		select: function( event, ui ) {
			$( "#vessel" ).val( ui.item.NM_KAPAL );
			$( "#voyage" ).val( ui.item.VOYAGE );
			$( "#id_vs" ).val( ui.item.ID_VS );
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NM_KAPAL + " [" + item.VOYAGE + "] " + "</a>" )
			.appendTo( ul );
	};
	<!------------------- autocomplete Vessel ------------>
	
});	


var ukk = "<?=$id_vs?>";

function edit_cont(ukk,nocont) 
{		
	$('#cont_edit').load("<?=HOME?>planning.stowage.ajax/container_edit/?ukk="+ukk+"&cont="+nocont).dialog({modal:true, height:380,width:420, title : "Container Editor"});
}

function update_cont(ukk,nocont) 
{
	var un_   = $("#un_number").val();
	var imo_  = $("#dg_class").val();	
	var crr_  = $("#carrier").val();
	var oog_  = $("#oog_stat").val();
	var oh_  = $("#oh").val();
	var ol_  = $("#ol").val();
	var ow_  = $("#ow").val();
	var hi_  = $("#hi_stat").val();
	var tmp_  = $("#tmp_rfr").val();
	var url   = "<?=HOME?>planning.stowage.ajax/container_update";	
			
	var r=confirm("Are you sure?");
	if (r==true)
	{
		$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
		$.post(url,{NO_UKK : ukk, NO_CONT : nocont, UN_NUMBER : un_, IMO : imo_, CARRIER : crr_, OOG : oog_, OH : oh_, OL : ol_, OW : ow_, HI : hi_, TMP : tmp_},function(data){
			if(data == "OK")
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Success...");
					window.location = "<?=HOME?>planning.stowage/view_yard/?no_ukk="+ukk;
				}
			else
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Failed...");
					window.location = "<?=HOME?>planning.stowage/view_yard/?no_ukk="+ukk;
				}
		});
	}
	else
	{
		return false;
	}	
}

jQuery(function() {
		 jQuery("#list_stowage").jqGrid({
			url:"<?=HOME?>datanya/data_stowage?q=list_stowage&noukk="+ukk,
			mtype : "post",
			datatype: "json",
			colNames:['Action','Container ID', 'Size','Type','Stat','Hz', 'Gross','Temp','Height','Handling','POD','IMO','UN Number','Line Operator','Yard<br>Placement<br>B - S - R - T','Bay Plan<br>B - R - T','Bay Realization<br>B - R - T'], 
			colModel:[				
				{name:'act',index:'act', width:50, align:"center",sortable:false,search:false},
				{name:'cont',index:'cont', width:100, align:"center"},
				{name:'sz',index:'sz', width:30, align:"center"},
				{name:'ty',index:'ty', width:30, align:"center"},
				{name:'st',index:'st', width:30, align:"center"},
				{name:'hz',index:'hz', width:20, align:"center"},
				{name:'gross',index:'gross', width:60, align:"center"},
				{name:'temp',index:'temp', width:80, align:"center"},
				{name:'hg',index:'hg', width:60, align:"center"},
				{name:'hl',index:'hl', width:80, align:"center"},
				{name:'pod',index:'pod', width:50, align:"center"},
				{name:'imo',index:'imo', width:80, align:"center"},
				{name:'un',index:'un', width:80, align:"center"},
				{name:'crr',index:'crr', width:80, align:"center"},
				{name:'ydplc',index:'ydplc', width:100, align:"center"},
				{name:'bp',index:'bp', width:80, align:"center"},
				{name:'bp_real',index:'bp_real', width:100, align:"center"}
			],
			rowNum:1000,
			width: 870,
			height: "100%",//250

			//rowList:[10,20,30,40,50,60],
			loadonce:true,
			rownumbers: true,
			rownumWidth: 15,
			gridview: true,
			pager: '#pg_l_booking',
			viewrecords: true,
			shrinkToFit: false,
			caption:"Stowage Status"
		 });
		  jQuery("#list_stowage").jqGrid('navGrid','#pg_l_booking',{del:false,add:false,edit:false,search:false}); 
		 jQuery("#list_stowage").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
		 
		});

</script>

<div class="content">
	<div class="main_side">
	<h2><span class="graybrown">
    	<img class="icon" border="0" src="images/stevedoring.gif" height="9%" width="9%" />
        <font color="#0378C6">Stowage</font> Plan
        </span></h2>
	<br/>
	<hr width="870" color="#e1e0de"></hr>
	<p><br/></p>
	<form enctype="multipart/form-data" action="<?=HOME;?>planning.stowage/view_yard" method="post">
	<table>
        <tr>
			<td>VESSEL / VOYAGE</td>
			<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td><input type="text" name="vessel" id="vessel" title="Autocomplete" style="background-color:#CCFFFF" />
                <input type="hidden" name="id_vs" id="id_vs" /> / <input type="text" name="voyage" id="voyage" size="10" style="background-color:#CCFFFF" readonly="readonly"/></td>
            <td>&nbsp;&nbsp;&nbsp;</td>
			<td><input type="submit" value=" Proses " /></td>
		</tr>
		<tr>
			<td><b>CONTAINER FILTER</b></td>
			<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td>Sz&nbsp;<select name="cont_sz" id="cont_sz">
					<option value="">-pilih-</option>
					<option value="20">20</option>
					<option value="40">40</option>
					<option value="45">45</option>
				</select>&nbsp;&nbsp;&nbsp;&nbsp;
				Wg&nbsp;
				<select name="cont_wg" id="cont_wg" data-tooltip="weight_class">
					<option value="">-pilih-</option>
					<option value="L2">L2</option>
					<option value="L1">L1</option>
					<option value="M">M</option>
					<option value="H">H</option>
					<option value="XH">XH</option>
				</select>
			</td>
            <td>&nbsp;&nbsp;&nbsp;</td>			
		</tr>
      </table>
	  <div id="mystickytooltip" class="stickytooltip">
                  <div style="padding:5px"> 
                       <div id="weight_class" class="atip">                                    
                         <table class="grid-table" border='0' cellpadding="1" cellspacing="1" width="40%">
							<tr>
								<th colspan='4' class="grid-header" width='20' align="center"><b><font size="1px">Container 20"</font></th>
							    <th colspan='4' class="grid-header" width='20' align="center"><font size="1px"><b>Container 40"</b></font></th>
							</tr>
							<tr>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">L2</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">1500</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">9999</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>L2</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>1500</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>9999</td>
							</tr>
							<tr>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">L1</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">10000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">14999</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>L1</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>10000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>14999</td>
							</tr>
							<tr>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">M</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">15000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">19999</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>M</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>15000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>19999</td>
							</tr>
							<tr>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">H</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">20000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">24999</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>H</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>20000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>24999</td>
							</tr>
							<tr>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">XH</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">25000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">35000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>XH</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>25000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>35000</td>
							</tr>
						 </table>
                       </div>
					   <? 
                                    $db         = getDB();
                                    
                                    $query_blok = "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
                                    $result_    = $db->query($query_blok);
                                    $blok       = $result_->fetchRow();
                                
                                    $yard_id    = $blok['ID'];
                               
                                    $query_cell4 = "SELECT a.INDEX_CELL, 
														   b.ID, 
														   b.NAME, 
														   a.SLOT_, 
														   a.ROW_ 
												    FROM YD_BLOCKING_AREA b, YD_BLOCKING_CELL a 
													WHERE a.ID_BLOCKING_AREA = b.ID 
													    AND b.ID_YARD_AREA = '$yard_id' 
														AND b.NAME <> 'NULL' 
														ORDER BY a.INDEX_CELL ASC";
                                    $result4    = $db->query($query_cell4);
                                    $blok4      = $result4->getAll();

                              
                                    foreach($blok4 as $dama){
                            ?>
                                    <div id="sticky<? echo $dama['INDEX_CELL']; ?>" class="atip">                                    
                                    <? 
                                    $query_place = "SELECT NO_CONTAINER,
														   SLOT_YARD,
														   ROW_YARD 
												    FROM YD_PLACEMENT_YARD 
													WHERE ID_BLOCKING_AREA = ".$dama['ID']." 
														AND SLOT_YARD = ".$dama['SLOT_']." 
														AND ROW_YARD = ".$dama['ROW_']." 
														AND ID_CELL = ".$dama['INDEX_CELL']."";
									
                                    $result2     = $db->query($query_place);
                                    $place       = $result2->fetchRow();
                                    
                                    if ($place['NO_CONTAINER'] == ''){?><b>BLOK <?=$dama['NAME']?> SLOT <?=$dama['SLOT_']?> 
									ROW <?=$dama['ROW_']?></b><br>
                                   <font color="red"> <i> -- There is no container heres -- </i></font>
                                    <? } else {
										
									?>
                                       <?php
                                            $query_index_cell   = "SELECT a.ACTIVITY, b.NM_KAPAL, CONCAT(CONCAT(b.VOYAGE_IN,'-'),b.VOYAGE_OUT) VOYAGE, a.NO_CONTAINER, a.SIZE_, a.TON,a.TIER_YARD,a.TYPE_CONT,a.STATUS_CONT, a.ID_PEL_TUJ FROM YD_PLACEMENT_YARD a, RBM_H b WHERE a.ID_VS = b.NO_UKK AND  a.ID_CELL = ".$dama['INDEX_CELL']." AND a.FLAG_HP IS NULL ORDER BY a.TIER_YARD DESC";
                                            $result_index_cell  = $db->query($query_index_cell);
                                            $tier               = $result_index_cell->getAll();
                                            
                                       ?>
									   <b>BLOK <?=$dama['NAME']?> SLOT <?=$place['SLOT_YARD']?> ROW <?=$place['ROW_YARD']?></b>
									   <table>
                                        <?
										
                                            foreach($tier as $row){
                                       ?>
                                           <tr><td width=10 align='center' style="background-color:#fbfd4a;border-radius: 5px 1px 1px 5px;-moz-border-radius: 5px 1px 1px 5px; -webkit-border-radius: 5px 1px 1px 5px;"><?=$row['TIER_YARD']?></td><td><img src="<?=HOME?>images/row_cont.png" width="40" height="40"></td>
										   <td>
                                           <b>No Container : </b> <font color="blue"><?=$row['NO_CONTAINER']?></font> &nbsp <b>Tujuan : </b> <font color="blue"><?=$row['ID_PEL_TUJ']?></font><br>
										   <b>Size/Type/Status :</b> <font color="blue"> <?=$row['SIZE_']?>/<?=$row['TYPE_CONT']?>/<?=$row['STATUS_CONT']?></font><BR>
										   <b>Vessel/Voyage :</b> <font color="blue"> <?=$row['NM_KAPAL']?>/<?=$row['VOYAGE']?></font><BR>
										   <b>Activity :</b> <font color="blue"> <?=$row['ACTIVITY']?></font> &nbsp <b>Tonase : </b> <font color="blue"><?=$row['TON']?></font><br><BR>

										   </td></tr>
										   
                                       <? }
									   ?>
									   </table>
                                     <?  }?>
                                    </div>
                   <? }?>
                  </div>
                <div class="stickystatus"></div>
	  </div>
	  </form>	  
<br/>
<hr width="870" color="#e1e0de"></hr><p><br/></p>
	<h2>Yard View</h2>
	<br/>
<?php
if ($id_vs != NULL) {
	$sz = $_POST['cont_sz'];
	$wg = $_POST['cont_wg'];

	if($sz!=NULL)
	{
		$query_sz = " AND A.SIZE_ = '$sz'";
	}
	else
	{
		$query_sz = "";
	}
	
	if($wg!=NULL)
	{
		$query_wg = " AND B.KATEGORI_BERAT = '$wg'";
	}
	else
	{
		$query_wg = "";
	}
?>
 
 <fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
    <center>      
            <?php
            $db = getDB();
            $query_blok = "SELECT WIDTH, NAMA_YARD, ID FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
            $result_    = $db->query($query_blok);
            $blok       = $result_->fetchRow();
            $width      = $blok['WIDTH'];
            $yard_id    = $blok['ID'];
            $name_yard  = $blok['NAMA_YARD'];
        ?>
          <br>
          <span class="graybrown"><h3>Layout <?=$name_yard?></h3></span>
          <br/>
<br/>
<div style="margin-top:0px;margin-bottom:20px;border:1px solid black;width:800;height:600;overflow-y:scroll;overflow-x:scroll;">
<p style="width:100%;">
<div align="center" style="background-color:#F6F4E4;">
<table width="100%" cellspacing="3" border="0">
<tbody>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="0" cellpadding="0" align="center">
<tbody>
    <tr><td colspan="<?=$width?>"><!--<img src="<?=HOME?>images/Dermaga_2.png">-->
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td style="width:800px;height:80px;font-size:8px; font-family:Tahoma; background-color:#a2e1f9;" align="left">
			&nbsp;&nbsp;&nbsp;&nbsp;			
			</td>
		</tr>		
		<tr>
			<td style="width:800px;height:60px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#eaeaea;" align="center">
			&nbsp;
			</td>
			
		</tr>
		</table>
	</td>		
	</tr>
	
    <tr>
         <?php
		 
			$query_cell2 = "SELECT a.INDEX_CELL as INDEX_CELL, b.ID AS ID, b.NAME AS NAME, a.SLOT_ AS SLOT_, a.ROW_ AS ROW_, a.STATUS_BM,b.POSISI,a.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA b,         
                            YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '23' AND a.SIZE_PLAN_PLC IS NULL 
                            UNION 
                            SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_, d.STATUS_BM,c.POSISI,d.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '23' AND d.SIZE_PLAN_PLC = '40d'
                            UNION
                            SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_,d.STATUS_BM,c.POSISI,d.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '23' AND d.SIZE_PLAN_PLC = '20'
                           -- UNION 
                           -- SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_,d.STATUS_BM,c.POSISI,d.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '23' AND d.SIZE_PLAN_PLC = '40b'
                            ORDER BY INDEX_CELL ASC";
			$result3     = $db->query($query_cell2);
			
            $blok2       = $result3->getAll();
           
         
            foreach ($blok2 as $row){
			// $index_cell_ = $row['INDEX_CELL'];
					$id_block     = $row['ID'];
                    $slot_        = $row['SLOT_'];
                    $row_         = $row['ROW_'];
                    $name         = $row['NAME'];
                    $index_cell_  = $row['INDEX_CELL'];
					$st_bm		  = $row['STATUS_BM'];
					$pos		  = $row['POSISI'];
					$sz_plc	      = $row['SIZE_PLAN_PLC'];
					
					if ($sz_plc=='40d')
					{
						if($pos=='vertical')
						{
							$cr="rowspan=2";
						}
						else if($pos=='horizontal')
							$cr="colspan=2";
					}
					else
						$cr='';
			//echo $index_cell_;
                if ($row['NAME'] <> 'NULL')
				{                
					$id_block     = $row['ID'];
                    $slot_        = $row['SLOT_'];
                    $row_         = $row['ROW_'];
                    $name         = $row['NAME'];
                    $index_cell_  = $row['INDEX_CELL'];
					$st_bm		  = $row['STATUS_BM'];
					
					if(isset($_POST["id_vessel"])||isset($_POST["tonase"])||isset($_POST["tuj"])||isset($_POST["size"])||isset($_POST["type"]))
					{
						/*$idvs=$_POST["id_vessel"];
						$ton=$_POST["tonase"];
						$tuj=$_POST["tuj"];
						$sz=$_POST["size"];
						$type=$_POST["type"];
						
						$query_place = "SELECT filter_cont($id_block,$slot_,$row_,$index_cell_,'$idvs','$ton','$tuj','$sz','$type') AS JUM from dual";
						$filter=1;*/
						
						$idvs	=	$_POST["id_vs"];
						$ton	=	$_POST["tonase"];
						$tuj	=	$_POST["id_pel_tuj"];
						$sz		=	$_POST["size"];
						$type	=	$_POST["type"];
						$sta	=	$_POST["status"];
						
						if ($type <> NULL){
							$query_type = " and a.TYPE_CONT = '$type'";
						} else {
							$query_type = " ";
						}
						
						if ($sz <> NULL){
							$query_sz = " and a.SIZE_ = '$sz'";
						} else {
							$query_sz = " ";
						}
						
						if ($sta <> NULL){
							$query_sta = " and REGEXP_REPLACE (a.STATUS_CONT, '[[:space:]]', '') = '$sta'";
						} else {
							$query_sta = " ";
						}
						
						if ($idvs <> NULL){
							$query_idvs = " and a.ID_VS = '$idvs'";
						} else {
							$query_idvs = " ";
						}
						
						if ($ton <> NULL){
							$query_ton = " and b.KATEGORI_BERAT = '$ton'";
						} else {
							$query_ton = " ";
						}
						
						if ($tuj <> NULL){
							$query_tuj = " and REGEXP_REPLACE (a.ID_PEL_TUJ, '[[:space:]]', '') = '$tuj'";
						} else {
							$query_tuj = " ";
						}
						
						$query_place = "SELECT COUNT(a.ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD a, TB_CONT_JOBSLIP b WHERE a.ID_VS = '$id_vs' AND a.ID_JOBSLIP = b.ID_JOB_SLIP AND a.ID_BLOCKING_AREA = '$id_block' AND a.SLOT_YARD = '$slot_' AND a.ROW_YARD = '$row_' AND a.ID_CELL = '$index_cell_' ". $query_sz . $query_type. $query_idvs . $query_tuj. $query_ton . $query_sta; 
						$filter=1;
                    }
					else
					{
						$query_place = "SELECT COUNT(A.ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD A, TB_CONT_JOBSLIP B WHERE A.ID_VS = '$id_vs' AND A.ID_JOBSLIP = B.ID_JOB_SLIP AND A.ID_BLOCKING_AREA = '$id_block' AND A.SLOT_YARD = '$slot_' AND A.ROW_YARD = '$row_' AND A.ID_CELL = '$index_cell_' AND STOWAGE = 'T'";
						
						$cek_filter = "SELECT COUNT(A.ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD A, TB_CONT_JOBSLIP B WHERE A.ID_VS = '$id_vs' AND A.ID_JOBSLIP = B.ID_JOB_SLIP AND A.ID_BLOCKING_AREA = '$id_block' AND A.SLOT_YARD = '$slot_' AND A.ROW_YARD = '$row_' AND A.ID_CELL = '$index_cell_' AND STOWAGE = 'T' ".$query_sz.$query_wg;
						$result6    = $db->query($cek_filter);
						$filter     = $result6->fetchRow();
						$cek_jml    = $filter['JUM'];
						
						$filter=0;
					}
                    $result2     = $db->query($query_place);
                    $place       = $result2->fetchRow();
                     
                    // debug($place);die;
                     $placement   = $place['JUM']; ?>
                   
                <?  if (($filter==0)&&($placement <> 0))				
					{
						if ($placement>=5)
						{
							if($cek_jml>0)
							{
								$color_plc='#FF0066';
							}
							else
							{
								$color_plc='#0066FF';
							}							
						}
						else if ($placement==4)
						{
							if($cek_jml>0)
							{
								$color_plc='#FF0066';
							}
							else
							{
								$color_plc='#0066FF';
							}
						}
						else if ($placement==3)
						{
							if($cek_jml>0)
							{
								$color_plc='#FF0066';
							}
							else
							{
								$color_plc='#0066FF';
							}
						}
						else if ($placement==2)
						{
							if($cek_jml>0)
							{
								$color_plc='#FF0066';
							}
							else
							{
								$color_plc='#0066FF';
							}
						}
						else if ($placement==1)
						{
							if($cek_jml>0)
							{
								$color_plc='#FF0066';
							}
							else
							{
								$color_plc='#0066FF';
							}
						}					
						
						?>
							<td data-tooltip="sticky<?=$index_cell_?>" onclick="openWin('<?=$yard_id;?>','<?=$id_block?>','<?=$row['NAME'];?>','<?=$row['SLOT_'];?>','<?=$row['ROW_'];?>','<?=$id_vs?>')"  onMouseOut="this.style.backgroundColor='<?=$color_plc?>'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:<?=$color_plc?>; " <?=$cr?>><blink><font color="white"><?=$placement//echo $row['NAME'];=$index_cell_?><a><? //echo $placement ?><?//=$st_bm;?><?//=$id_block;?></a></font></blink></td>
<!--                     <div id="x" class="drag blue">-->


<!--                     </div>-->

                   <? }
				   else
				   { ?>

				   <? 
					if ($st_bm=='Muat')
					{?>
                          <td data-tooltip="sticky<?=$index_cell_?>" onclick="openWin('<?=$yard_id;?>','<?=$id_block?>','<?=$row['NAME'];?>','<?=$row['SLOT_'];?>','<?=$row['ROW_'];?>','<?=$id_vs?>')" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#86BCFF; " <?=$cr?>><?echo $placement ;// echo $row['NAME'];// echo $index_cell_ ; // echo $index_cell_ ;//echo $st_bm;?><?//=$id_block;?></td>
					<?}
					else if ($st_bm=='Bongkar')
					{
					?>
							<td data-tooltip="sticky<?=$index_cell_?>" onclick="openWin('<?=$yard_id;?>','<?=$id_block?>','<?=$row['NAME'];?>','<?=$row['SLOT_'];?>','<?=$row['ROW_'];?>','<?=$id_vs?>')" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#82ff07'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#82ff07; " <?=$cr?>>
							<? echo $placement; //echo $row['NAME']; //echo $index_cell_;echo $row['NAME'];echo $index_cell_;//echo $st_bm; ?><?//=$id_block;?></a></td>
					
					<?
					}
					?>	  
<!--                         </div>-->
               <? }  
				} 
				else 
				{
				?>
                     <td align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;">
					<? if (($slot_ == NULL) AND ($row_ <> NULL) AND ($st_bm == NULL)) {
							?><?echo $row_;?><?
					   } else if (($slot_ <> NULL) AND ($row_ == NULL) AND ($st_bm == NULL)) {
							?><?echo $slot_;?><?
					   } else if (($slot_ == NULL) AND ($row_ == NULL) AND ($st_bm <> NULL)) {
							?><font size="1pt"><b><?=$st_bm;?></b></font></font><?
						} ?></td>
              <?}
                if (($row['INDEX_CELL']+1) % $width == 0)
				{ ?>
                 </tr>
                <? 
				}
                ?>      
        <?    } ?>
		
</tbody>
</table>
</td>
</tbody>
</table>
</div>
</p></div>
    </center>
</fieldset>
 
<?
} else { ?>

  <fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
    <center>      
            <?php
            $db = getDB();
            $query_blok = "SELECT WIDTH, NAMA_YARD, ID FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
            $result_    = $db->query($query_blok);
            $blok       = $result_->fetchRow();
            $width      = $blok['WIDTH'];
            $yard_id    = $blok['ID'];
            $name_yard  = $blok['NAMA_YARD'];
        ?>
          <br>
          <span class="graybrown"><h3>Layout <?=$name_yard?></h3></span>
          <br/>
<br/>
<div style="margin-top:0px;margin-bottom:20px;border:1px solid black;width:800;height:600;overflow-y:scroll;overflow-x:scroll;">
<p style="width:360%;">
<div align="center" style="background-color:#F6F4E4;">
<table width="100%" cellspacing="0" border="0" bgcolor="#F6F4E4">
	<tbody>
		<td valign="bottom" colspan="4" align="center">
		<table bordercolor="#037ACA" border="0" cellspacing="0" cellpadding="0" align="center">
		<tbody>
    <tr><td colspan="<?=$width?>"><!--<img src="<?=HOME?>images/Dermaga_2.png">-->
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td style="width:800px;height:80px;font-size:8px; font-family:Tahoma; background-color:#a2e1f9;" align="left" >
			&nbsp;&nbsp;&nbsp;&nbsp;			
			</td>
		</tr>		
		<tr>
			
			<td style="width:800px;height:60px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#eaeaea;" align="center">
			&nbsp;
			</td>
		</tr>
		</table>
	</td>		
	</tr>
	
    <tr>
         <?php
		 
			$query_cell2 = "SELECT a.INDEX_CELL as INDEX_CELL, b.ID AS ID, b.NAME AS NAME, a.SLOT_ AS SLOT_, a.ROW_ AS ROW_, a.STATUS_BM,b.POSISI,a.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA b,         
                            YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '23' AND a.SIZE_PLAN_PLC IS NULL 
                            UNION 
                            SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_, d.STATUS_BM,c.POSISI,d.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '23' AND d.SIZE_PLAN_PLC = '40d'
                            UNION
                            SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_,d.STATUS_BM,c.POSISI,d.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '23' AND d.SIZE_PLAN_PLC = '20'
                           -- UNION 
                           -- SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_,d.STATUS_BM,c.POSISI,d.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '23' AND d.SIZE_PLAN_PLC = '40b'
                            ORDER BY INDEX_CELL ASC";
			$result3     = $db->query($query_cell2);
			
            $blok2       = $result3->getAll();
           
         
            foreach ($blok2 as $row){
			// $index_cell_ = $row['INDEX_CELL'];
					$id_block     = $row['ID'];
                    $slot_        = $row['SLOT_'];
                    $row_         = $row['ROW_'];
                    $name         = $row['NAME'];
                    $index_cell_  = $row['INDEX_CELL'];
					$st_bm		  = $row['STATUS_BM'];
					$pos		  = $row['POSISI'];
					$sz_plc	      = $row['SIZE_PLAN_PLC'];
					
					if ($sz_plc=='40d')
					{
						if($pos=='vertical')
						{
							$cr="rowspan=2";
						}
						else if($pos=='horizontal')
							$cr="colspan=2";
					}
					else
						$cr='';
			//echo $index_cell_;
                if ($row['NAME'] <> 'NULL')
				{                
					$id_block     = $row['ID'];
                    $slot_        = $row['SLOT_'];
                    $row_         = $row['ROW_'];
                    $name         = $row['NAME'];
                    $index_cell_  = $row['INDEX_CELL'];
					$st_bm		  = $row['STATUS_BM'];
					
					if(isset($_POST["id_vessel"])||isset($_POST["tonase"])||isset($_POST["tuj"])||isset($_POST["size"])||isset($_POST["type"]))
					{
						/*$idvs=$_POST["id_vessel"];
						$ton=$_POST["tonase"];
						$tuj=$_POST["tuj"];
						$sz=$_POST["size"];
						$type=$_POST["type"];
						
						$query_place = "SELECT filter_cont($id_block,$slot_,$row_,$index_cell_,'$idvs','$ton','$tuj','$sz','$type') AS JUM from dual";
						$filter=1;*/
						
						$idvs	=	$_POST["id_vs"];
						$ton	=	$_POST["tonase"];
						$tuj	=	$_POST["id_pel_tuj"];
						$sz		=	$_POST["size"];
						$type	=	$_POST["type"];
						$sta	=	$_POST["status"];
						
						if ($type <> NULL){
							$query_type = " and a.TYPE_CONT = '$type'";
						} else {
							$query_type = " ";
						}
						
						if ($sz <> NULL){
							$query_sz = " and a.SIZE_ = '$sz'";
						} else {
							$query_sz = " ";
						}
						
						if ($sta <> NULL){
							$query_sta = " and REGEXP_REPLACE (a.STATUS_CONT, '[[:space:]]', '') = '$sta'";
						} else {
							$query_sta = " ";
						}
						
						if ($idvs <> NULL){
							$query_idvs = " and a.ID_VS = '$idvs'";
						} else {
							$query_idvs = " ";
						}
						
						if ($ton <> NULL){
							$query_ton = " and b.KATEGORI_BERAT = '$ton'";
						} else {
							$query_ton = " ";
						}
						
						if ($tuj <> NULL){
							$query_tuj = " and REGEXP_REPLACE (a.ID_PEL_TUJ, '[[:space:]]', '') = '$tuj'";
						} else {
							$query_tuj = " ";
						}
						
						$query_place = "SELECT COUNT(a.ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD a, TB_CONT_JOBSLIP b WHERE a.ID_JOBSLIP = b.ID_JOB_SLIP AND a.ID_BLOCKING_AREA = '$id_block' AND a.SLOT_YARD = '$slot_' AND a.ROW_YARD = '$row_' AND a.ID_CELL = '$index_cell_' AND a.STOWAGE = 'T' ". $query_sz . $query_type. $query_idvs . $query_tuj. $query_ton . $query_sta; 
						$filter=1;
                    }
					else
					{
						$query_place = "SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$row_' AND ID_CELL = '$index_cell_' AND STOWAGE = 'T'";
						$filter=0;
					}
                    $result2     = $db->query($query_place);
                    $place       = $result2->fetchRow();
                     
                    // debug($place);die;
                     $placement   = $place['JUM']; ?>
                   
                <?  if (($filter==0)&&($placement <> 0))
				
					{
						if ($placement>=5)
						{
							$color_plc='#0066FF';
						}
						else if ($placement==4)
						{
							$color_plc='#0066FF';
						}
						else if ($placement==3)
						{
							$color_plc='#0066FF';
						}
						else if ($placement==2)
						{
							$color_plc='#0066FF';
						}
						else if ($placement==1)
						{
							$color_plc='#0066FF';
						}
						
						
						?>
							<td data-tooltip="sticky<?=$index_cell_?>" onclick="load_block('<?=$id_block?>','<?=$slot_?>','<?=$row_?>','<?=$yard_id?>')"  onMouseOut="this.style.backgroundColor='<?=$color_plc?>'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:<?=$color_plc?>; " <?=$cr?>><blink><font color="white"><?=$placement//echo $row['NAME'];=$index_cell_?><a><? //echo $placement ?><?//=$st_bm;?><?//=$id_block;?></a></font></blink></td>
<!--                     <div id="x" class="drag blue">-->


<!--                     </div>-->

                   <? } 
						else if (($filter==1)&&($placement<>0))
						{
							if ($placement>=5)
							{
								$color_plc='#0066FF';
							}
							else if ($placement==4)
							{
								$color_plc='#0066FF';
							}
							else if ($placement==3)
							{
								$color_plc='#0066FF';
							}
							else if ($placement==2)
							{
								$color_plc='#0066FF';
							}
							else if ($placement==1)
							{
								$color_plc='#0066FF';
							}
						?>
							
							<td data-tooltip="sticky<?=$index_cell_?>" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#fd78f2'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:<?=$color_plc?>; " onclick="load_block('<?=$id_block?>','<?=$slot_?>','<?=$row_?>','<?=$yard_id?>')" <?=$cr?>><blink><font color="white"><a><? echo $placement ?><?//=$st_bm;?><?//=$id_block;?></a></font></blink></td>
						<?
						}
				   else 
				   { ?>

				   <? 
					if ($st_bm=='Muat')
					{?>
                          <td data-tooltip="sticky<?=$index_cell_?>" onclick="load_block('<?=$id_block?>','<?=$slot_?>','<?=$row_?>','<?=$yard_id?>')" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#86BCFF; " <?=$cr?>><?echo $placement ;// echo $row['NAME'];// echo $index_cell_ ; // echo $index_cell_ ;//echo $st_bm;?><?//=$id_block;?></td>
					<?}
					else if ($st_bm=='Bongkar')
					{
					?>
							<td data-tooltip="sticky<?=$index_cell_?>" onclick="('<?=$id_block?>','<?=$slot_?>','<?=$row_?>','<?=$yard_id?>')" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#82ff07'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#82ff07; ">
							<? echo $placement; //echo $row['NAME']; //echo $index_cell_;echo $row['NAME'];echo $index_cell_;//echo $st_bm; ?><?//=$id_block;?></a></td>
					
					<?
					}
					?>	  
<!--                         </div>-->
               <? }  
				} 
				else 
				{
				?>
                     <td align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;">
					<? if (($slot_ == NULL) AND ($row_ <> NULL) AND ($st_bm == NULL)) {
							?><?echo $row_;?><?
					   } else if (($slot_ <> NULL) AND ($row_ == NULL) AND ($st_bm == NULL)) {
							?><?echo $slot_;?><?
					   } else if (($slot_ == NULL) AND ($row_ == NULL) AND ($st_bm <> NULL)) {
							?><font size="1pt"><b><?=$st_bm;?></b></font></font><?
						} ?></td>
              <?}
                if (($row['INDEX_CELL']+1) % $width == 0)
				{ ?>
                 </tr>
                <? 
				}
                ?>      
        <?    } ?>
		
</tbody>
</table>
</div>
</p>
</div>
    </center>
</fieldset>   
<br/>				
</table>
</div>
</p></div>
    </center>
</fieldset>  
<? }?> 

<br/>
<? 
	if($id_vs != NULL)
	{
?>
<hr width="870" color="#e1e0de" size="3"></hr><p><br/></p>
<h2>Bay View</h2>
<p><br/></p>
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
?>
<table id='list_stowage' width="100%"></table> <div id='pg_l_booking'></div>
<br/>
 <fieldset class="form-fieldset" style="margin: 2px 2px 2px 2px;">
    <center>
      <span class="graybrown">
          <h3><? echo $vessel." / ".$voyage; ?></h3>
	  </span>
          <br/>
      
<div style="margin-top:0px;margin-bottom:20px;border:1px solid black;width:800;height:400;overflow-y:scroll;overflow-x:scroll;">
<p style="width:400%;">
<div align="center" style="background-color:#F6F4E4;">
<table width="250%" cellspacing="3" border="0">
<tbody>
<tr>
<?				
			$db = getDB();
			$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0";
			$result_   = $db->query($query_bay);
			$bay_      = $result_->fetchRow();
			
			$jumlah_row = $bay_['JML_ROW'];
			$jml_tier_under = $bay_['JML_TIER_UNDER'];
			$jml_tier_on = $bay_['JML_TIER_ON'];
			$width = $jumlah_row+1;
			
			$query_cell3 = "SELECT ID,BAY,OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0 ORDER BY ID ASC";
			$result8    = $db->query($query_cell3);
			$blok8      = $result8->getAll();
			 
			foreach ($blok8 as $row18){
			$id_area = $row18['ID'];
			$bay_name = $row18['BAY'];
			$occ2 = $row18['OCCUPY'];
			
			if($occ2=='Y')
			{
?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	
	   <tr>
		<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay <? if ($occ2=='Y') { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if ($occ2=='Y') { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
		<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
	   </tr>
       <tr>   
         <?php
		 
            $query_cell2 = "SELECT ID,
								   CELL_NUMBER,
								   ROW_,
								   TIER_,
								   STATUS_STACK,
								   PLUGGING 
						    FROM STW_BAY_CELL 
							WHERE ID_BAY_AREA = '$id_area' 
							ORDER BY CELL_NUMBER ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
            $n='';
			$br='';
			$tr='';
			//debug($blok2);die;
			
            foreach ($blok2 as $row8){                
				$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
				$idx_cell = $row8['ID'];            
				
				//echo $index."_".$row8['STATUS_STACK']."<br/>";
				
				$cek_pol = "SELECT TRIM(ID_PEL_ASAL) AS POL FROM STW_PLACEMENT_BAY WHERE ID_CELL = '$idx_cell'";
				$hsl_pol = $db->query($cek_pol);
				$pol2    = $hsl_pol->fetchRow();
				$pol_bay = $pol2['POL'];
			
			if ($index%$width != 0) 
			{
				if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'T'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
                    </div>	
				<?
			   }
			   else if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">O</td>
                    </div>	
				<?
			   }
			   else if ($row8['STATUS_STACK'] == 'P')
				{ ?>
				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0000; ">&nbsp;</td>
                     </div>
                  
               <? 
			   }
			   else if ($row8['STATUS_STACK'] == 'R')
				{
					if ($pol_bay<>'IDJKT')
					{
				?>				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#B84DB8'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#B84DB8; ">&nbsp;</td>
                     </div>                  
               <? 	
					}
					else
					{
				?>				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#33CC33'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#33CC33; ">&nbsp;</td>
                     </div>                  
               <? 
					}
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
                </div>	
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'N')
			   {	
					if(($index>=1)&&($index<$width)) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else
					   {
					?>
						<div id="x" class="drag blue">
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; background-color:#efedd9; ">
						&nbsp;
						</td>
						</div>
					<? } 
				} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>					
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
					<?=$br?>
					</td>
                    </div>
					</tr>
			  <? }
			else if ($index%$width == 0)
			{ ?>
				<div id="x" class="drag blue">                    
					<? if ($br != 0)
					   { 
						 if ($index==$width)
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						  else {
						 ?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  
						  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						    else { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">HATCH</td>
					<? }
						} ?>
                </div>
					</tr>				
			<?
				}
            }
			?>
		
</tbody>
</table>
</td>
<? } } ?>
</tr>
<tr>
<?
			$db = getDB();
			$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0";
			$result_   = $db->query($query_bay);
			$bay_      = $result_->fetchRow();
			
			$jumlah_row = $bay_['JML_ROW'];
			$jml_tier_under = $bay_['JML_TIER_UNDER'];
			$jml_tier_on = $bay_['JML_TIER_ON'];
			$width = $jumlah_row+1;
			
			$query_cell3 = "SELECT ID,BAY,OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0 ORDER BY ID ASC";
			$result8    = $db->query($query_cell3);
			$blok8      = $result8->getAll();
			 
			foreach ($blok8 as $row18){
			$id_area = $row18['ID'];
			$bay_name = $row18['BAY'];
			$occ3 = $row18['OCCUPY'];
			
			if($occ3=='T')
			{
?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	
	   <tr>
		<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay <? if ($occ3=='Y') { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if ($occ3=='Y') { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
		<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
	   </tr>
       <tr>   
         <?php
		 
            $query_cell2 = "SELECT ID,CELL_NUMBER,ROW_,TIER_,STATUS_STACK,PLUGGING FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$id_area' ORDER BY CELL_NUMBER ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
            $n='';
			$br='';
			$tr='';
			// debug($blok2);die;
            foreach ($blok2 as $row8){
                //echo $row['INDEX_CELL'];
				$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
				$id_cell = $row8['ID'];
				//echo $tr;			
            
				$idx_cell = $row8['ID'];
            
				$cek_polx = "SELECT TRIM(ID_PEL_ASAL) AS POL FROM STW_PLACEMENT_BAY WHERE ID_CELL = '$idx_cell'";
				$hsl_polx = $db->query($cek_polx);
				$pol2x    = $hsl_polx->fetchRow();
				$pol_bayx = $pol2x['POL'];
			
			if ($index%$width != 0) 
			{
				if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'T'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
                    </div>	
				<?
			   }
			   else if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">O</td>
                    </div>	
				<?
			   }
			   else if ($row8['STATUS_STACK'] == 'P')
				{
					$query_sz = "select SIZE_ from STW_PLACEMENT_BAY where ID_CELL = '$id_cell' and ACTIVITY = 'MUAT'";
					$hsl_sz = $db->query($query_sz);
					$sz_cont = $hsl_sz->fetchRow();
					$size_cont = $sz_cont['SIZE_'];
					
					if(($size_cont!='40')&&($size_cont!='45'))
					{
					
				?>
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0000; ">&nbsp;</td>
                     </div>	           
               <? 
					}
					else
					{
					
				?>
							
                     <div id="x" class="drag blue">
					 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><b>+</b></td>
                     </div>
                  
               <? 	
					
					}
			   
			   }
			    else if ($row8['STATUS_STACK'] == 'R')
				{ 
					$query_sz = "select SIZE_ from STW_PLACEMENT_BAY where ID_CELL = '$id_cell'";
					$hsl_sz = $db->query($query_sz);
					$sz_cont = $hsl_sz->fetchRow();
					$size_cont3 = $sz_cont['SIZE_'];
					
					if(($size_cont3=='40')||($size_cont3=='45'))
					{
					
				?>
							
                     <div id="x" class="drag blue">
					 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><b>+</b></td>
                     </div>
                  
               <? 
					}
					else
					{
						if($pol_bayx<>'IDJKT')
						{
				?>
						 <div id="x" class="drag blue">
						 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#B84DB8'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#B84DB8; ">&nbsp;</td>
						 </div>
				<?		
						}
						else
						{
				?>							
						 <div id="x" class="drag blue">
						 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#33CC33'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#33CC33; ">&nbsp;</td>
						 </div>                  
               <?	
						}
					}
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
                </div>	
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'N')
			   {?>
                    <? if(($index>=1)&&($index<$width)) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else
					   {
					?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; background-color:#efedd9; ">
					&nbsp;
					</td>
                    </div>
					<? } ?>
              <?} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>					
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
					<?=$br?>
					</td>
                    </div>
					</tr>
			  <? }
			else if ($index%$width == 0)
			{ ?>
				<div id="x" class="drag blue">                    
					<? if ($br != 0)
					   { 
						 if ($index==$width)
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						  else {
						 ?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  
						  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						    else { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">HATCH</td>
					<? }
						} ?>
                </div>
					</tr>				
			<?
				}
            }
			?>
		
</tbody>
</table>
</td>
<? } } ?>
</tr>
</tbody>
</table>
</div>
</p></div>
    </center>
<table>
	<tr>
		<td width="20" style="background-color:#FF0000;"></td><td>&nbsp;Stowage Plan</td>
		<td width="20">&nbsp;&nbsp;&nbsp;</td>
		<td width="20" style="background-color:#33CC33;"></td><td>&nbsp;Loading Confirm <i></i></td>
	</tr>
</table>
</fieldset>
<? } ?>
<br/>
<div id="dialog-form">
	<form>
		<div id="table_stow"></div>
		<div id="cont_edit"></div>
	</form>
</div>
<!-- data tooltip -->
</div>
</div>