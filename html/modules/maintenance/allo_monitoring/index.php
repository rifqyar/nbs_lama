<style>
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
<script>
//select SIZE_,TYPE_,STATUS_CONT,ID_PEL_TUJ, ID_VS, STATUS_BM, COUNT(ID) from yd_yard_allocation_planning
setInterval(function()
		{
			$("#list_kategori").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data_2?q=list_kategori", datatype:"json"}).trigger("reloadGrid");
			$("#list_kategori_import").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data_2?q=list_kategori_import", datatype:"json"}).trigger("reloadGrid");
		},
		60000);

jQuery(function() {
 jQuery("#list_kategori").jqGrid({
	url:'<?=HOME?>datanya/data_2?q=list_kategori',
	mtype : "post",
	datatype: "json",
	colNames:['Warning','Allocation Block','Category','Colour Code','Vessel - Voy','Allocation Plan Qty', 'Counter Use','Available Counter','Alocation Date','User',], 
	colModel:[
	{name:'warn', width:140, align:"center",sortable:false,search:false},
		{name:'all', width:60, align:"center",sortable:false,search:false},
		{name:'kat', width:200, align:"center"},
		{name:'col_c', width:60, align:"center",sortable:false,search:false},
		{name:'ves', width:200, align:"center",sortable:false,search:false},
		{name:'apq', width:60, align:"center",sortable:false,search:false},
		{name:'cu', width:60, align:"center",sortable:false,search:false},
		{name:'ac', width:60, align:"center",sortable:false,search:false},
		{name:'dt', width:60, align:"center",sortable:false,search:false},
		{name:'us', width:60, align:"center",sortable:false,search:false}
		
	],
	rowNum:50,
	width: 900,
	height: "100%",//250

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_kat',
	viewrecords: true,
	shrinkToFit: false,
	//grid.setRowData ( id, false, {height: 30} ),
	caption:"Matrix Kategori Eksport"
 });
  jQuery("#list_kategori").jqGrid('navGrid','#pg_l_kat',{del:false,add:false,edit:false,search:false})
  .navButtonAdd('#pg_l_kat',{
   caption:"Print Allocation", 
   buttonicon:"ui-icon-add", 
   onClickButton: function(){		
		block_select();
		//shift('{$no_ukk}');
   }, 
   position:"last"
  }); 
  //jQuery("#list_kategori").jqGrid('navGrid','#pg_l_kat',{del:false,add:false,edit:false,search:false}); 
 jQuery("#list_kategori").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});


jQuery(function() {
 jQuery("#list_kategori_import").jqGrid({
	url:'<?=HOME?>datanya/data_2?q=list_kategori_import',
	mtype : "post",
	datatype: "json",
	colNames:['Warning','Kategori', 'Allocation Block','Category','Colour Code','Vessel - Voy','Allocation Plan Qty', 'Counter Use','Available Counter','Alocation Date','User',], 
	colModel:[
	{name:'warn', width:100, align:"center",sortable:false,search:false},
		{name:'all', width:60, align:"center",sortable:false,search:false},
		{name:'all', width:100, align:"center",sortable:false,search:false},
		{name:'kat', width:200, align:"center"},
		{name:'col_c', width:60, align:"center",sortable:false,search:false},
		{name:'ves', width:200, align:"center",sortable:false,search:false},
		{name:'apq', width:60, align:"center",sortable:false,search:false},
		{name:'cu', width:60, align:"center",sortable:false,search:false},
		{name:'ac', width:60, align:"center",sortable:false,search:false},
		{name:'dt', width:60, align:"center",sortable:false,search:false},
		{name:'us', width:60, align:"center",sortable:false,search:false}
		
	],
	rowNum:50,
	width: 900,
	height: "100%",//250

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_katim',
	viewrecords: true,
	shrinkToFit: false,
	//grid.setRowData ( id, false, {height: 30} ),
	caption:"Matrix Kategori Import"
 });
  jQuery("#list_kategori_import").jqGrid('navGrid','#pg_l_katim',{del:false,add:false,edit:false,search:false})
  .navButtonAdd('#pg_l_katim',{
   caption:"Print Allocation", 
   buttonicon:"ui-icon-add", 
   onClickButton: function(){		
		block_select();
		//shift('{$no_ukk}');
   }, 
   position:"last"
  }); 
  //jQuery("#list_kategori").jqGrid('navGrid','#pg_l_kat',{del:false,add:false,edit:false,search:false}); 
 jQuery("#list_kategori_import").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});

function print()
{
	var yd_id = "81";
	var yd_select = $("#yard_select").val();
	var block_id_nm = $("#block_select").val();
	var no_ukk = $("#id_kapal").val();
	var vessel = $("#nm_kapal").val();
	var voyage = $("#voyage").val();
	var explode = block_id_nm.split(',');
		var block_id = explode[0];
		var block_nm = explode[1];
	
	if(yd_select=='block')
	{
		window.open('<?=HOME?>maintenance.allo_monitoring.print/block_layout/?id_yard='+yd_id+'&id_block='+block_id+'&nm_block='+block_nm+'&no_ukk='+no_ukk+'&voyage='+voyage+'&vessel='+vessel,'_blank');
	}
	else if(no_ukk=="")
	{
		alert("Vessel/Voyage still empty...!!!");
	}
	else if(yd_select=='all1')
	{
		window.open('<?=HOME?>maintenance.allo_monitoring.print/yd_layout/?id_yard='+yd_id+'&voyage='+voyage+'&vessel='+vessel,'_blank');
	}	
	else if(yd_select=='all2')
	{
		window.open('<?=HOME?>maintenance.allo_monitoring.print/lapangan300/?id_yard='+yd_id+'&voyage='+voyage+'&vessel='+vessel+'&ukk='+no_ukk,'_blank');
	}
	else if(yd_select=="")
	{
		alert("Data Tidak Lengkap...!!!");
	}
}

function block_select()
{
	$('#table_block').load("<?=HOME?>maintenance.allo_monitoring.ajax/block_input").dialog({modal:true, height:210,width:320,title: "Yard Allocation Print"});
}

</script>

<?php

$db	= getDB();

if(!isset($_POST['yard_id']))
{
	?>
        <center>
         <fieldset style="margin: 30px 10px 10px 10px; height:100px; vertical-align:middle; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
            <form enctype="multipart/form-data" action="<?=HOME?>maintenance.allo_monitoring" method="post">
            	<table style="font-size: 12px; font-weight: bold;">
                   <tr height="10"><td colspan="3"> &nbsp </td></tr>
                       <tr>
                           <td> Pilih Lapangan </td>
                           <td> : </td>
                           <td>
                             <select name="yard_id" id="yard_id">
                                  <option value="" selected> -- Pilih --</option>
                                <?php 
                                    $query_get_yard     = "SELECT * FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
                                    $result_yard	= $db->query($query_get_yard);
                                    $row_yard		= $result_yard->getAll();
                                    foreach($row_yard as $row)
                                    {
                                ?>
                                <option value="<?php echo $row['ID']?>"><?php echo $row['NAMA_YARD'] ?></option>
                                <?php 
                                    }
                                ?>
                             </select>
                           </td>
                       </tr>
                        <tr>
                           <td colspan="3">
                                <input type="submit" value=" Go "> </input>
                           </td>
                       </tr>
                </table>
            
            </form>
        </fieldset>
    
        
		</center>
	<?php
}
else
{
    $yard_id     = $_POST['yard_id'];
    
    $query_        = "SELECT NAMA_YARD FROM YD_YARD_AREA WHERE ID = '$yard_id'";
    $result_       = $db->query($query_);
    $yard          = $result_->fetchRow();
    $nama_ya       = $yard['NAMA_YARD'];
    
    $kapasitas     = "SELECT COUNT(a.INDEX_CELL) * c.TIER KAPASITAS FROM YD_BLOCKING_CELL a, YD_BLOCKING_AREA b, (SELECT MAX(TIER) TIER FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA = '$yard_id') c WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' AND b.NAME <> 'NULL' GROUP BY c.TIER";
    $result_kap    = $db->query($kapasitas);
    $kap           = $result_kap->fetchRow();
    $available     = $kap['KAPASITAS'];
    
    $kapasitas40     = "SELECT COUNT(a.INDEX_CELL) * c.TIER KAPASITAS FROM YD_BLOCKING_CELL a, YD_BLOCKING_AREA b, (SELECT MAX(TIER) TIER FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA = '$yard_id') c WHERE a.ID_BLOCKING_AREA = b.ID AND a.SIZE_ = '40' AND b.ID_YARD_AREA = '$yard_id' AND b.NAME <> 'NULL' GROUP BY c.TIER";
    $result_kap40    = $db->query($kapasitas40);
    $kap40           = $result_kap40->fetchRow();
    $available40     = $kap40['KAPASITAS'];
	
    $query_used    = "SELECT COUNT(a.INDEX_CELL) * c.TIER USED FROM YD_YARD_ALLOCATION_PLANNING a, YD_BLOCKING_AREA b, (SELECT MAX(TIER) TIER FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA = '$yard_id') c WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' AND b.NAME <>  'NULL' GROUP BY c.TIER";
    $result_used   = $db->query($query_used);
    $used_         = $result_used->fetchRow();
    $used          = $used_['USED'];
    
    $query_40      = "SELECT COUNT(a.INDEX_CELL) * c.TIER USED FROM YD_YARD_ALLOCATION_PLANNING a, YD_BLOCKING_AREA b, (SELECT MAX(TIER) TIER FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA = '$yard_id') c WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' AND a.SIZE_ = '40' AND b.NAME <>  'NULL' GROUP BY c.TIER, a.SIZE_";
    $result_40     = $db->query($query_40);
    $used_40       = $result_40->fetchRow();
    $used40        = $used_40['USED'];
    
    $query_kategori     = "SELECT a.SIZE_, a.TYPE_, a.KATEGORI , COUNT(a.INDEX_CELL) * c.TIER USED FROM YD_YARD_ALLOCATION_PLANNING a, YD_BLOCKING_AREA b, (SELECT MAX(TIER) TIER FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA = '$yard_id') c WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' AND b.NAME <>  'NULL' GROUP BY c.TIER, a.SIZE_, a.TYPE_, a.KATEGORI";
    $result_kategori    = $db->query($query_kategori);
    $kategori_          = $result_kategori->getAll();
    
   // debug($kategori_);die;
    
    foreach ($kategori_ as $row){
    $ukuran             = $row['SIZE_'];
    $tipe               = $row['TYPE_'];
    $kategori           = $row['KATEGORI'];
    $used_kategori      = $row['USED'];
    
    if (($ukuran == '20') AND ($tipe == 'DRY') AND ($kategori == 'L2')){
        $L220 = $used_kategori;
    } else if (($ukuran == '20') AND ($tipe == 'DRY') AND ($kategori == 'L1')){
        $L120 = $used_kategori;
    } else if (($ukuran == '20') AND ($tipe == 'DRY') AND ($kategori == 'M')){
        $M20 = $used_kategori;
    } else if (($ukuran == '20') AND ($tipe == 'DRY') AND ($kategori == 'H')){
        $H20 = $used_kategori;
    } else if (($ukuran == '20') AND ($tipe == 'DRY') AND ($kategori == 'XH')){
        $XH20 = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'DRY') AND ($kategori == 'L2')){
        $L240 = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'DRY') AND ($kategori == 'L1')){
        $L140 = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'DRY') AND ($kategori == 'M')){
        $M40 = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'DRY') AND ($kategori == 'H')){
        $H40 = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'DRY') AND ($kategori == 'XH')){
        $XH40 = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'HQ') AND ($kategori == 'L2')){
        $L240HQ = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'HQ') AND ($kategori == 'L1')){
        $L140HQ = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'HQ') AND ($kategori == 'M')){
        $M40HQ = $used_kategori;
    } else if  (($ukuran == '40') AND ($tipe == 'HQ') AND ($kategori == 'H')){
        $H40HQ = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'HQ') AND ($kategori == 'XH')){
        $XH40HQ = $used_kategori;
    } else if (($tipe == 'DG') AND ($kategori == 'L2')){
        $L2DG = $used_kategori;
    } else if (($tipe == 'DG') AND ($kategori == 'L1')){
        $L1DG = $used_kategori;
    } else if (($tipe == 'DG') AND ($kategori == 'M')){
        $MDG = $used_kategori;
    } else if (($tipe == 'DG') AND ($kategori == 'H')){
        $HDG = $used_kategori;
    } else if (($tipe == 'DG') AND ($kategori == 'XH')){
        $XHDG = $used_kategori;
    }
    }
    
    $sisa          = $available - $used;
    
    $availableteus = $available - ($available40/2);
    $usedteus      = $used - ($used40/2);
    $sisateus      = $availableteus - $usedteus;
    
    
	
    // $s	= round((900 / $width) - (($m_div/100)*(900/$width)));
     
     $s = 25;
    
//echo "-----$yard_id---------";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	  
        
<script type='text/javascript'>
$(document).ready(function() 
{
        $( "#vessel" ).autocomplete({
		minLength: 3,
		source: "maintenance.yard_allocation.auto/parameter",
		focus: function( event, ui ) {
			$( "#vessel" ).val( ui.item.NAMA);
			return false;
		},
		select: function( event, ui ) {
			$( "#vessel" ).val( ui.item.NAMA);
                        $( "#voyage" ).val( ui.item.VOYAGE);
                        $( "#id_vessel" ).val( ui.item.ID);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NAMA + " VOY " + item.VOYAGE + "</a>")
			.appendTo( ul );
	};  
    $('#load_layout').html('<img src="<?=HOME?>images/loadingF.gif" />');
					$('#load_layout').load("<?=HOME?>planning.yard_allocation_import/load_layout?id=<?=$yard_id?> #load_layout");   
});

function konfirmasi() {	
	question = confirm("data akan disimpan, apakah anda sudah yakin?")
	if (question != "0")	return true;
	else			return false;
}
	
</script>

</head>
<body>
<center>
         <fieldset style="margin: 30px 10px 10px 10px; height:150px; vertical-align:middle; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
            <form enctype="multipart/form-data" action="<?=HOME?>maintenance.allo_monitoring" method="post">
            	<table style="font-size: 12px; font-weight: bold;">
                        <tr height="10"><td colspan="3"> &nbsp </td></tr>
                        
                       <tr>
                           <td> Pilih Lapangan </td>
                           <td> : </td>
                           <td>
                             <select name="yard_id" id="yard_id">
                                 <option value="<?=$_POST['yard_id']?>" selected><?php echo $nama_ya?></option>
                                <?php 
                                    $query_get_yard     = "SELECT * FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'	";
                                    $result_yard	= $db->query($query_get_yard);
                                    $row_yard		= $result_yard->getAll();
                                    
                                    foreach($row_yard as $row)
                                    {
                                ?>
                                
                                <option value="<?php echo $row['ID']?>"><?php echo $row['NAMA_YARD'] ?></option>
                                <?php 
                                    }
                                ?>
                             </select>
                           </td>
                       </tr>
                        <tr>
                            <td>Nama Vessel</td><td> : </td><td><input type="text" size="15" id="vessel" name="vessel" value="<?=$_POST['vessel']?>">
                                &nbsp<input type="text" size="5" id="voyage" name="voyage"value="<?=$_POST['voyage']?>">
                                <input type="hidden" id="id_vessel" name="id_vessel" value="<?=$_POST['id_vessel']?>">
                                 <input type="hidden" id="id_test" name="id_test" value="<?=$id_test;?>">
				</td>
                        </tr>
                        <tr>
                           <td colspan="3">
                                <input type="submit" value=" Go "> </input>
                           </td>
                       </tr>
                </table>
            
            </form>
        </fieldset>		
 </center>

<?php if($_POST['id_vessel'] <> NULL) { 
 $id_vessel = $_POST['id_vessel'];
 $vessel = $_POST['vessel'];
 $voyage = $_POST['voyage'];
 $query_kategori     = "SELECT a.SIZE_, a.TYPE_, a.KATEGORI , COUNT(a.INDEX_CELL) * c.TIER USED FROM YD_YARD_ALLOCATION_PLANNING a, YD_BLOCKING_AREA b, (SELECT MAX(TIER) TIER FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA = '$yard_id') c WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' AND b.NAME <>  'NULL' AND a.ID_VS = '$id_vessel' GROUP BY c.TIER, a.SIZE_, a.TYPE_, a.KATEGORI";
    $result_kategori    = $db->query($query_kategori);
    $kategori_          = $result_kategori->getAll();
    
    foreach($kategori_ as $row){
    $ukuran             = $row['SIZE_'];
    $tipe               = $row['TYPE_'];
    $kategori           = $row['KATEGORI'];
    $used_kategori      = $row['USED'];
    
    if (($ukuran == '20') AND ($tipe == 'DRY') AND ($kategori == 'L2')){
        $L220_ = $used_kategori;
    } else if (($ukuran == '20') AND ($tipe == 'DRY') AND ($kategori == 'L1')){
        $L120_ = $used_kategori;
    } else if (($ukuran == '20') AND ($tipe == 'DRY') AND ($kategori == 'M')){
        $M20_ = $used_kategori;
    } else if (($ukuran == '20') AND ($tipe == 'DRY') AND ($kategori == 'H')){
        $H20_ = $used_kategori;
    } else if (($ukuran == '20') AND ($tipe == 'DRY') AND ($kategori == 'XH')){
        $XH20_ = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'DRY') AND ($kategori == 'L2')){
        $L240_ = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'DRY') AND ($kategori == 'L1')){
        $L140_ = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'DRY') AND ($kategori == 'M')){
        $M40_ = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'DRY') AND ($kategori == 'H')){
        $H40_ = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'DRY') AND ($kategori == 'XH')){
        $XH40_ = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'HQ') AND ($kategori == 'L2')){
        $L240HQ_ = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'HQ') AND ($kategori == 'L1')){
        $L140HQ_ = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'HQ') AND ($kategori == 'M')){
        $M40HQ_ = $used_kategori;
    } else if  (($ukuran == '40') AND ($tipe == 'HQ') AND ($kategori == 'H')){
        $H40HQ_ = $used_kategori;
    } else if (($ukuran == '40') AND ($tipe == 'HQ') AND ($kategori == 'XH')){
        $XH40HQ_ = $used_kategori;
    } else if (($tipe == 'DG') AND ($kategori == 'L2')){
        $L2DG_ = $used_kategori;
    } else if (($tipe == 'DG') AND ($kategori == 'L1')){
        $L1DG_ = $used_kategori;
    } else if (($tipe == 'DG') AND ($kategori == 'M')){
        $MDG_ = $used_kategori;
    } else if (($tipe == 'DG') AND ($kategori == 'H')){
        $HDG_ = $used_kategori;
    } else if (($tipe == 'DG') AND ($kategori == 'XH')){
        $XHDG_ = $used_kategori;
    } }  
    ?>
<div style="padding-left: 15px; float:left">
  <fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 10px">
         <table align="center"> 
                <tr>
                        <td class="grid-header">Kategori Alokasi</td>
                        <td class="grid-header">L2</td>
                        <td class="grid-header">L1</td>
                        <td class="grid-header">M</td>
                        <td class="grid-header">H</td>
                        <td class="grid-header">XH</td>
                </tr>
                <tr>
                       <td class="grid-cell"><img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_22_1484e6_40x40.png" width="15px" height="15px"> 20 DRY</td>
                        <td class="grid-cell"><?if ($L220_ == NULL) {$L220_ = 0; echo $L220_;}  else  echo $L220_;?></td>
                        <td class="grid-cell"><?if ($L120_ == NULL) {$L120_ = 0; echo $L120_;}  else  echo $L120_;?></td>
                        <td class="grid-cell"><?if ($M20_ == NULL) {$M20_ = 0; echo $M20_;}  else  echo $M20_;?></td>
                        <td class="grid-cell"><?if ($H20_ == NULL) {$H20_ = 0; echo $H20_;}  else  echo $H20_;?></td>
                        <td class="grid-cell"><?if ($XH20_ == NULL) {$XH20_ = 0; echo $XH20_;}  else  echo $XH20_;?></td>
                </tr>
                <tr><td class="grid-cell"><img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_95_ffdc2e_40x40.png" width="15px" height="15px"> 40 DRY</td>
                        <td class="grid-cell"><?if ($L240_ == NULL) {$L240_ = 0; echo $L240_;}  else  echo $L240_;?></td>
                        <td class="grid-cell"><?if ($L140_ == NULL) {$L140_ = 0; echo $L140_;}  else  echo $L140_;?></td>
                        <td class="grid-cell"><?if ($M40_ == NULL) {$M40_ = 0; echo $M40_;}  else  echo $M40_;?></td>
                        <td class="grid-cell"><?if ($H40_ == NULL) {$H40_ = 0; echo $H40_;}  else  echo $H40_;?></td>
                        <td class="grid-cell"><?if ($XH40_ == NULL) {$XH40_ = 0; echo $XH40_;}  else  echo $XH40_;?></td>
              
                </tr>
                <tr><td class="grid-cell"><img src="yard/src/css/excite-bike/images/ungu.png" width="15px" height="15px"> 40 HQ</td>
                         <td class="grid-cell"><?if ($L240HQ_ == NULL) {$L240HQ_ = 0; echo $L240HQ_;}  else  echo $L240HQ_;?></td>
                        <td class="grid-cell"><?if ($L140HQ_ == NULL) {$L140HQ_ = 0; echo $L140HQ_;}  else  echo $L140HQ_;?></td>
                        <td class="grid-cell"><?if ($M40HQ_ == NULL) {$M40HQ_ = 0; echo $M40HQ_;}  else  echo $M40HQ_;?></td>
                        <td class="grid-cell"><?if ($H40HQ_ == NULL) {$H40HQ_ = 0; echo $H40HQ_;}  else  echo $H40HQ_;?></td>
                        <td class="grid-cell"><?if ($XH40HQ_ == NULL) {$XH40HQ_ = 0; echo $XH40HQ_;}  else  echo $XH40HQ_;?></td>
                </tr>
                <tr>
                        <td align="left" class="grid-cell"><img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_18_b81900_40x40green.png" width="15px" height="15px"> 45 DRY</td>
                         <td class="grid-cell"><?if ($L245_ == NULL) {$L245_ = 0; echo $L245_;}  else  echo $L245_;?></td>
                        <td class="grid-cell"><?if ($L145_ == NULL) {$L145_ = 0; echo $L145_;}  else  echo $L145_;?></td>
                        <td class="grid-cell"><?if ($M45_ == NULL) {$M45_ = 0; echo $M45_;}  else  echo $M45_;?></td>
                        <td class="grid-cell"><?if ($H45_ == NULL) {$H45_ = 0; echo $H45_;}  else  echo $H45_;?></td>
                        <td class="grid-cell"><?if ($XH45_ == NULL) {$XH45_ = 0; echo $XH45_;}  else  echo $XH45_;?></td>
                </tr>
                <tr>
                        <td align="left" class="grid-cell"><img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png" width="15px" height="15px"> 20,40,45 DG</td>
                       <td class="grid-cell"><?if ($L2DG_ == NULL) {$L2DG_ = 0; echo $L2DG_;}  else  echo $L2DG_;?></td>
                        <td class="grid-cell"><?if ($L1DG_ == NULL) {$L1DG_ = 0; echo $L1DG_;}  else  echo $L1DG_;?></td>
                        <td class="grid-cell"><?if ($MDG_ == NULL) {$MDG_ = 0; echo $MDG_;}  else  echo $MDG_;?></td>
                        <td class="grid-cell"><?if ($HDG_ == NULL) {$HDG_ = 0; echo $HDG_;}  else  echo $HDG_;?></td>
                        <td class="grid-cell"><?if ($XHDG_ == NULL) {$XHDG_ = 0; echo $XHDG_;}  else  echo $XHDG_;?></td>
                </tr>
              
        </table>
      <br>
      <b>Keterangan : <br>
          Alokasi untuk Vessel / Voy : <font color="red"><b><?=$vessel.' / '.$voyage;?></b></font></b>
    </fieldset>
</div>
<? }?>
<br >
<div style="padding-left:10px; float:left;">
<div style="margin-top:10px;border:1px solid black;width:900;height:500;overflow-y:scroll;overflow-x:scroll;">
<p style="width:300%;">
<div id="load_layout" ALIGN="center" ></div>
</p>
</div>
</div>
<p>
<br>
<br>
</p>
<div style="padding-left:10px; float:left; margin-top:30px;">
<table id='list_kategori' width="100%"></table> <div id='pg_l_kat'></div>
<br>
<table id='list_kategori_import' width="100%"></table> <div id='pg_l_katim'></div>
</div>
<div style="padding-left:10px; float:left;">
<table border="0">
    <tr height="20"><td></td></tr>
</table>
</div>
<?}?>

<form>
 <div id="table_block"></div>
</form>

</body>
</html>
