<html lang="en">
<head>
<script type="text/javascript">
        $(document).ready(function() 
		{
                    
                        $('#tes').change(function(){
                            //alert('dama');
                            $('#preview_'+this.val).show("slow");
                        });
                
			$( "#kategori" ).autocomplete({
				minLength: 3,
				source: "maintenance.monitoring.auto/parameter",
				focus: function( event, ui ) {
					$( "#kategori" ).val( ui.item.KODE);
					return false;
				},
				select: function( event, ui ) {
					$( "#kategori" ).val( ui.item.NAMA);
					$( "#id_kategori" ).val( ui.item.ID);
					$( "#id_kategori2" ).val( ui.item.INFO);
					return false;
				}
			})
			.data( "autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.NAMA + "<br />" + item.INFO + "</a>")
					.appendTo( ul );
			};
	});
</script>


	<script>
	// increase the default animation speed to exaggerate the effect
	$.fx.speeds._default = 1000;
	$(function() {
		$( "#dialog" ).dialog({
			autoOpen: false,
			show: "blind",
			hide: "explode"
		});

		$( "#opener" ).click(function() {
			$( "#dialog" ).dialog( "open" );
			return false;
		});
	});
        
        $(function() {
		$( "#dialog2" ).dialog({
			autoOpen: false,
			show: "blind",
			hide: "explode"
		});

		$( "#opener2" ).click(function() {
			$( "#dialog2" ).dialog( "open" );
			return false;
		});
	});
        
function view_layout() 
{
        var yard_               = $("#yard").val();
	var blok_               = $("#blok").val();	
	var filter_             = $("#filter").val();	
	var view_               = $("#view").val();
        var kategori            = $("#kategori").val();
	var id_kategori 	= $("#id_kategori").val();
        var id_kategori2 	= $("#id_kategori2").val();
	var url                 = "<?= HOME ?>maintenance.monitoring/layout_lapangan #list";
	 
	$("#request_list").load(url,{NO_REQ : no_req_, FROM : from_, TO : to_, CARI : cari_}, function(data){
	
	});

}	



</script>
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
</style>

<script>
   function DisEnableDDL()
    {
    var preview = document.getElementById("preview")
    var blok = document.getElementById("blok")
    var view = document.getElementById("view")

    if(preview.options[preview.selectedIndex].value == "3")
    {
        blok.disabled = true;
        view.disabled = true;
    } else {
        preview.selectedIndex = 0;
        blok.disabled = false;
        view.disabled = false;
    }
    }
</script>


<script type="text/javascript" src="tooltip/stickytooltip.js"></script>
<link rel="stylesheet" type="text/css" href="tooltip/stickytooltip.css" />
</head>
<body>
    <span class="graybrown"><img src='images/document-open.png' border='0' class="icon"/><font color="#0378C6"> Monitoring</font> Lapangan </span><br/><br/>
<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
    <center>
    <form enctype="multipart/form-data" action="<?= HOME ?>maintenance.monitoring/index" method="post">
    <table style="margin: 30px 30px 30px 30px;" border="0" style="font-size: 10px; font-weight: bold;">
    	<tr>
            <td >Pilih kategori monitoring lapangan </td>
            <td> : </td>
            <td>  <select name="preview">
                  <option value=""> -- Pilih --</option>
                  <option value="all">Preview All</option>
                  <option value="block">Preview Per Block</option>
                </select></td>

    	</tr>
          <tr>
            <td>Pilih Blok</td>
            <td> : </td>
            <td>    <select name="blok" id="blok">
                    <option value="">-- Pilih --</option>
                    <?php
                    $db             = getDB();
                    $query_get_yard = "SELECT a.ID, a.NAME FROM YD_YARD_AREA b, YD_BLOCKING_AREA a WHERE a.ID_YARD_AREA = b.ID AND b.STATUS = 'AKTIF' AND a.NAME <> 'NULL'";
                    $result_yard    = $db->query($query_get_yard);
                    $row_yard       = $result_yard->getAll();
                    foreach ($row_yard as $row) {
                    ?>
                                    <option value="<?php
                        echo $row['ID'];
                    ?>"><?php
                        echo $row['NAME'];
                    ?></option>
                                    <?php
                    }
                    ?>
                </select>
                </select></td>
    	</tr>
        <tr>
            <td>Preview berdasarkan </td>
            <td> : </td>
            <td>    <select name="view" id="view">
                         <option value="">-- Pilih --</option>
                        <option value="SLOT">SLOT</option>
                        <option value="ROW">ROW</option>
                </select></td>
    	</tr>
        <tr>
            <td>Filter berdasarkan</td>
            <td> : </td>
            <td>    <select name="filter" id="filter">
                         <option value="">-- Pilih --</option>
                        <option value="size">Size</option>
                        <option value="kapal">Kapal</option>
                        <option value="consignee">Consignee</option>
                        <option value="dest">Destination</option>
                </select></td>
    	</tr>
          <tr>
            <td>Masukkan Parameter</td>
            <td> : </td>
            <td>  <input type="text" name="kategori" id="kategori" />
            <input type="hidden" name="id_kategori" id="id_kategori" />
            <input type="hidden" name="id_kategori2" id="id_kategori2" />
            </td>
    	</tr>    
  <tr>
      <td colspan="3"><input type="submit" value=" Proses " /></td>
           
    	</tr>  

	</table>
    </form>
    </center>
</fieldset>

<br/>

<?php
if (($_POST['preview'] == 'block') AND ($_POST['blok'] <> NULL) AND ($_POST['filter'] <> NULL)) {
?>
 <fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
    <center>
      <span class="graybrown">
          <br>
          <h3>Layout Lapangan Ex TBB</h3>
          <br>
          <?php
    // echo $_POST['blok'];die;
    $id_blok    = $_POST['blok'];
    //echo "SELECT TIER, NAME FROM YD_BLOCKING_AREA WHERE ID = '$id_blok'";die;
    $query_blok = "SELECT TIER, NAME FROM YD_BLOCKING_AREA WHERE ID = '$id_blok'";
    $result_    = $db->query($query_blok);
    $blok       = $result_->fetchRow();
    //debug($blok);die;
    $nama_blok  = $blok['NAME'];
    $tier       = $blok['TIER'];
?>
          <h4 >Blok <?php
    echo $blok['NAME'];
?></h4>
          <br>

<?php
    $query        = "SELECT MAX(SLOT_) JML_SLOT, MAX(ROW_) JML_ROW FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_blok'";
    $result       = $db->query($query);
    $max_slot_row = $result->fetchRow();
    
    $jml_slot = $max_slot_row['JML_SLOT'];
    $jml_row  = $max_slot_row['JML_ROW'];
    //echo "-----$yard_id---------";
?>

<div style="margin-top:0px;border:1px solid black;width:900;height:400;overflow-y:scroll;overflow-x:scroll;">
<p style="width:80%;">
<div align="center" style="background-color:#F6F4E4;">
<table width="100%" cellspacing="3" border="0" align="center">
<tbody>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<td valign="bottom" colspan="4">
<table bordercolor="#037ACA" border="0" cellspacing="4" cellpadding="4" align="center">
<tbody>
    <tr>

<? // echo $_POST['filter']; die;
    //echo $_POST['kategori'];die;
    //echo $_POST['view'];die;
?>
<?
    if ($_POST['view'] == 'SLOT') {
        for ($j = 1; $j <= $jml_row; $j++) {
?>
        <h3 align="center">SLOT <?= $j ?></h3>
            <tr><td><table align="center">
                 <?
            for ($k = $tier; $k >= 0; $k--) {
?>
                     <tr>
                    <?
                for ($i = 0; $i <= $jml_slot; $i++) {
                    if ($_POST['filter'] == 'size') {
                        $kategori    = $_POST['kategori'];
                        //echo "SELECT ID_PLACEMENT FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$i' AND ROW_YARD = '$j' AND TIER_YARD = '$k' AND SIZE_ = '$kategori'";die;
                        $query_blok_ = "SELECT ID_CELL FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$i' AND ROW_YARD = '$j' AND TIER_YARD = '$k' AND SIZE_ = '$kategori'";
                        $result__    = $db->query($query_blok_);
                        $placement   = $result__->fetchRow();
                    } else if ($_POST['filter'] == 'kapal') {
                        $id_kategori = $_POST['id_kategori'];
                        $query_blok_ = "SELECT ID_CELL FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$i' AND ROW_YARD = '$j' AND TIER_YARD = '$k' AND ID_VS = '$id_kategori'";
                        $result__    = $db->query($query_blok_);
                        $placement   = $result__->fetchRow();
                        
                    } else if ($_POST['filter'] == 'consignee') {
                        $id_kategori2 = $_POST['id_kategori2'];
                        $query_blok_  = "SELECT ID_CELL FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$i' AND ROW_YARD = '$j' AND TIER_YARD = '$k' AND KODE_PBM = '$id_kategori2'";
                        $result__     = $db->query($query_blok_);
                        $placement    = $result__->fetchRow();
                    }
                    
                    // debug($placement);die;
                    
                    $id_place = $placement['ID_CELL'];
                    if (($k <> 0) AND ($id_place == NULL) AND ($i == 0)) {
?>
                               <td align="center" style="background-color:#CCCC33;border-radius: 5px 1px 1px 5px;-moz-border-radius: 5px 1px 1px 5px; 
-webkit-border-radius: 5px 1px 1px 5px;"><b> T <?= $k ?> </b></td>
                            <?
                    } else if (($k <> 0) AND ($id_place <> NULL) AND ($i <> 0)) {
?>                      <div id="x" class="drag blue">
                                <td data-tooltip="sticky<?=$placement['ID_CELL']?>" onMouseOver="this.style.backgroundColor='#FFCC66'" onMouseOut="this.style.backgroundColor='#FF4848'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FF4848; "><img id="opener2" src="./images/row_cont.png" width="50" height="50"></td>
                        </div>
                 <div class="stickystatus"></div>
                </div>
                            <?
                    } else if (($k <> 0) AND ($id_place == NULL) AND ($i <> 0)) {
?>  
                                <td data-tooltip ="sticky<?=$placement['ID_CELL']?>" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;border-color:#000000"><img id="opener2" src="./images/row_cont_empty.png" width="50" height="50"></td> 
                            <?
                    } else if (($k == 0) AND ($id_place == NULL) AND ($i == 0)) {
?>
                                <td> &nbsp </td>
                             <?
                    } else if (($k == 0) AND ($id_place == NULL) AND ($i <> 0)) {
?>
                                <td  align="center" style="background-color:#CCCC33;border-radius: 1px 1px 5px 5px;-moz-border-radius: 1px 1px 5px 5px; 
-webkit-border-radius: 1px 1px 5px 5px;"><b>R <?= $i ?></b></td>
                            <?
                    }
?>
                    <?
                }
?></tr>
         <?
            }
?> </table><br />
         <?
        }
    } else {
        for ($j = 1; $j <= $jml_slot; $j++) {
?>
            <h3>ROW <?= $j ?></h3>
            <tr><td><table align="center">
                 <?
            for ($k = $tier; $k >= 0; $k--) {
?>
                     <tr>
                    <?
                for ($i = 0; $i <= $jml_row; $i++) {
                    
                     if ($_POST['filter'] == 'size') {
                        $kategori    = $_POST['kategori'];
                        //echo "SELECT ID_PLACEMENT FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$i' AND ROW_YARD = '$j' AND TIER_YARD = '$k' AND SIZE_ = '$kategori'";die;
                        $query_blok_ = "SELECT ID_CELL FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$i' AND ROW_YARD = '$j' AND TIER_YARD = '$k' AND SIZE_ = '$kategori'";
                        $result__    = $db->query($query_blok_);
                        $placement   = $result__->fetchRow();
                    } else if ($_POST['filter'] == 'kapal') {
                        $id_kategori = $_POST['id_kategori'];
                        $query_blok_ = "SELECT ID_CELL FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$i' AND ROW_YARD = '$j' AND TIER_YARD = '$k' AND ID_VS = '$id_kategori'";
                        $result__    = $db->query($query_blok_);
                        $placement   = $result__->fetchRow();
                        
                    } else if ($_POST['filter'] == 'consignee') {
                        $id_kategori2 = $_POST['id_kategori2'];
                        $query_blok_  = "SELECT ID_CELL FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$i' AND ROW_YARD = '$j' AND TIER_YARD = '$k' AND KODE_PBM = '$id_kategori2'";
                        $result__     = $db->query($query_blok_);
                        $placement    = $result__->fetchRow();
                    }
                    
                    $id_place = $placement['ID_CELL'];
                    if (($k <> 0) AND ($id_place == NULL) AND ($i == 0)) {
?>
                                <td align="center" style="background-color:#CCCC33;border-radius: 5px 1px 1px 5px;-moz-border-radius: 5px 1px 1px 5px; 
-webkit-border-radius: 5px 1px 1px 5px;"><b> T <?= $k ?> </b></td>
                            <?
                    } else if (($k <> 0) AND ($id_place <> NULL) AND ($i <> 0)) {
?>  <div id="x" class="drag blue">
                                <td data-tooltip="sticky<?=$placement['ID_CELL']?>" onMouseOver="this.style.backgroundColor='#FFCC66'" onMouseOut="this.style.backgroundColor='#FF4848'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FF4848; "><img id="opener2" src="./images/slot_cont.png" width="100" height="50"></td></div>
                            <?
                    } else if (($k <> 0) AND ($id_place == NULL) AND ($i <> 0)) {
?>  
                                <td data-toolip="sticky<?=$placement['ID_CELL']?>" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;border-color:#000000"><img id="opener2" src="./images/slot_cont_empty.png" width="100" height="50"></td> 
                            <?
                    } else if (($k == 0) AND ($id_place == NULL) AND ($i == 0)) {
?>
                                <td> &nbsp </td>
                             <?
                    } else if (($k == 0) AND ($id_place == NULL) AND ($i <> 0)) {
?>
                                <td  align="center" style="background-color:#CCCC33;border-radius: 1px 1px 5px 5px;-moz-border-radius: 1px 1px 5px 5px; 
-webkit-border-radius: 1px 1px 5px 5px;"><b>S <?= $i ?></b></td>
                            <?
                    }
?>
                    <?
                }
?></tr>
         <?
            }
?> </table><br />
         <?
        }
    }
?>
</tbody>
</table>
</td>
</tr>
<tr>
<tr>
<tr>
<tr>
</tbody>
</table>
</div>
</tbody>
</p></div>

    </center>
</fieldset>
<?
} else if (($_POST['preview'] == 'block') AND ($_POST['blok'] <> NULL) AND ($_POST['filter'] == NULL)) {
?>
 <fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
    <center>
      <span class="graybrown">
          <br>
          <h3>Layout Lapangan Ex TBB</h3>
          <br>
          <?php
    // echo $_POST['blok'];die;
    $id_blok    = $_POST['blok'];
    //echo "SELECT TIER, NAME FROM YD_BLOCKING_AREA WHERE ID = '$id_blok'";die;
    $query_blok = "SELECT TIER, NAME FROM YD_BLOCKING_AREA WHERE ID = '$id_blok'";
    $result_    = $db->query($query_blok);
    $blok       = $result_->fetchRow();
    //debug($blok);die;
    $nama_blok  = $blok['NAME'];
    $tier       = $blok['TIER'];
?>
          <h4>Blok <?php
    echo $blok['NAME'];
?></h4>
          <br>

<?php
    $query        = "SELECT MAX(SLOT_) JML_SLOT, MAX(ROW_) JML_ROW FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_blok'";
    $result       = $db->query($query);
    $max_slot_row = $result->fetchRow();
    
    $jml_slot = $max_slot_row['JML_SLOT'];
    $jml_row  = $max_slot_row['JML_ROW'];
    //echo "-----$yard_id---------";
?>
<div style="margin-top:0px;border:1px solid black;width:900;height:400;overflow-y:scroll;overflow-x:scroll;">
<p style="width:80%;">
<div align="center" style="background-color:#F6F4E4;">
<table width="100%" cellspacing="3" border="0" align="center">
<tbody>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="4" cellpadding="4" align="center">
<tbody>

       <?
    if ($_POST['view'] == 'SLOT') {
        for ($j = 1; $j <= $jml_row; $j++) {
?>
        <h3>SLOT <?= $j ?></h3>
            <tr><td><table>
                 <?
            for ($k = $tier; $k >= 0; $k--) {
?>
                     <tr>
                    <?
                for ($i = 0; $i <= $jml_slot; $i++) {
                    $query_blok_ = "SELECT ID_CELL FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$i' AND ROW_YARD = '$j' AND TIER_YARD = '$k'";
                    $result__    = $db->query($query_blok_);
                    $placement   = $result__->fetchRow();
                    
                    $id_place = $placement['ID_CELL'];
                    if (($k <> 0) AND ($id_place == NULL) AND ($i == 0)) {
?>
                                <td align="center" style="background-color:#CCCC33;border-radius: 5px 1px 1px 5px;-moz-border-radius: 5px 1px 1px 5px; 
-webkit-border-radius: 5px 1px 1px 5px;"><b>&nbsp  T <?= $k ?> &nbsp  </b></td>
                            <?
                    } else if (($k <> 0) AND ($id_place <> NULL) AND ($i <> 0)) {
?>  <div id="x" class="drag blue">
                                <td data-tooltip="sticky<?=$placement['ID_CELL']?>" onMouseOver="this.style.backgroundColor='#FFCC66'" onMouseOut="this.style.backgroundColor='#FF4848'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FF4848; ">

	<img id="opener" src="./images/row_cont.png" width="50" height="50"></td></div>
                            <?
                    } else if (($k <> 0) AND ($id_place == NULL) AND ($i <> 0)) {
?>  
                                <td  data-tooltip="sticky<?=$placement['ID_CELL']?>" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;border-color:#000000">
                                    
                                

     <img  src="./images/row_cont_empty.png" width="50" height="50">
     
   
                                    
                                    </td> 
                                
                                
                                
                            <?
                    } else if (($k == 0) AND ($id_place == NULL) AND ($i == 0)) {
?>
                                <td> &nbsp </td>
                             <?
                    } else if (($k == 0) AND ($id_place == NULL) AND ($i <> 0)) {
?>
                                <td  align="center" style="background-color:#CCCC33;border-radius: 1px 1px 5px 5px;-moz-border-radius: 1px 1px 5px 5px; 
-webkit-border-radius: 1px 1px 5px 5px;"><b> &nbsp R <?= $i ?> &nbsp </b></td>
                            <?
                    }
?>
                    <?
                }
?></tr>
         <?
            }
?> </table><br />
         <?
        }
    } else {
        for ($j = 1; $j <= $jml_slot; $j++) {
?>
            <h3 align="center">ROW <?= $j ?></h3>
            <tr><td><table>
                 <?
            for ($k = $tier; $k >= 0; $k--) {
?>
                     <tr>
                    <?
                for ($i = 0; $i <= $jml_row; $i++) {
                    $query_blok_ = "SELECT ID_CELL FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$j' AND ROW_YARD = '$i' AND TIER_YARD = '$k'";
                    $result__    = $db->query($query_blok_);
                    $placement   = $result__->fetchRow();
                    
                    $id_place = $placement['ID_CELL'];
                    if (($k <> 0) AND ($id_place == NULL) AND ($i == 0)) {
?>
                                <td align="center" style="background-color:#CCCC33;border-radius: 5px 1px 1px 5px;-moz-border-radius: 5px 1px 1px 5px; 
-webkit-border-radius: 5px 1px 1px 5px;"><b> &nbsp T <?= $k ?> &nbsp </b></td>
                            <?
                    } else if (($k <> 0) AND ($id_place <> NULL) AND ($i <> 0)) {
?>  <div id="x" class="drag blue">
                                <td data-tooltip="sticky<?=$placement['ID_CELL']?>"onMouseOver="this.style.backgroundColor='#FFCC66'" onMouseOut="this.style.backgroundColor='#FF4848'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FF4848; "><img id="opener" src="./images/slot_cont.png"  width="100" height="50"></td></div>
                            <?
                    } else if (($k <> 0) AND ($id_place == NULL) AND ($i <> 0)) {
?>  
                                <td data-tooltip="sticky<?=$placement['ID_CELL']?>" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;border-color:#000000"><img  src="./images/slot_cont_empty.png" width="100" height="50"></td> 
                            <?
                    } else if (($k == 0) AND ($id_place == NULL) AND ($i == 0)) {
?>
                                <td> &nbsp </td>
                             <?
                    } else if (($k == 0) AND ($id_place == NULL) AND ($i <> 0)) {
?>
                                <td  align="center" style="background-color:#CCCC33;border-radius: 1px 1px 5px 5px;-moz-border-radius: 1px 1px 5px 5px; 
-webkit-border-radius: 1px 1px 5px 5px;"><b> &nbsp S <?= $i ?> &nbsp</b></td>
                            <?
                    }
?>
                    <?
                }
?></tr>
         <?
            }
?> </table><br />
         <?
        }
    }
?>
    </tr>
</tbody>
</table>
</td>
</tr>
<tr>
<tr>
<tr>
<tr>
</tbody>
</table>
</div>
</tbody>
</p></div>

    </center>
</fieldset>   

<?php
} else if (($_POST['preview'] == 'all') AND ($_POST['blok'] == NULL) AND ($_POST['filter'] <> NULL)) {
?>

 <fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
    <center>
      <span class="graybrown">
          <br>
          <h3>Layout Lapangan Ex TBB</h3>
          <br>
        

          <br>

<div style="margin-top:0px;border:1px solid black;width:900;height:400;overflow-y:scroll;overflow-x:scroll;">
<p style="width:80%;">
<div align="center" style="background-color:#F6F4E4;">
<table width="100%" cellspacing="3" border="0">
<tbody>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="4" cellpadding="4" align="center">
<tbody>
    <tr>
         <?php
            $db             = getDB();
    //echo "SELECT TIER, NAME FROM YD_BLOCKING_AREA WHERE ID = '$id_blok'";die;
        //   echo "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE STATUS = 'STATUS'";die;
            $query_blok = "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
            $result_    = $db->query($query_blok);
            $blok       = $result_->fetchRow();
            //debug($blok);die;
            $width      = $blok['WIDTH'];
            $yard_id    = $blok['ID'];

           // echo "SELECT (a.INDEX_CELL+1) INDEX_CELL, b.ID, b.NAME, a.SLOT_, a.ROW_ FROM YD_BLOCKING_AREA b, YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' ORDER BY a.INDEX_CELL ASC";
            $query_cell2 = "SELECT a.INDEX_CELL, b.ID, b.NAME, a.SLOT_, a.ROW_ FROM YD_BLOCKING_AREA b, YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' ORDER BY a.INDEX_CELL ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
            
         // debug($blok2);die;
            foreach ($blok2 as $row){
                //echo $row['INDEX_CELL'];
                if ($row['NAME'] <> 'NULL'){
                    
                    $id_block   = $row['ID'];
                    $slot_       = $row['SLOT_'];
                    $row_        = $row['ROW_'];
                    $name        = $row['NAME'];
                    $index_cell_  = $row['INDEX_CELL'];
                 //  echo $index_cell_."-".$row['INDEX_CELL']."-".$row['NAME']."<br>";
                // echo "SELECT ID_PLACEMENT FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND ID_CELL = '$index_cell_' AND ID_VS = '$id_kategori'";
                     if ($_POST['filter'] == 'size') {
                        $kategori    = $_POST['kategori'];
                        //echo "SELECT ID_PLACEMENT FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$i' AND ROW_YARD = '$j' AND TIER_YARD = '$k' AND SIZE_ = '$kategori'";die;
                        $query_blok_ = "SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND ID_CELL = '$index_cell_' AND SIZE_ = '$kategori'";
                        $result__    = $db->query($query_blok_);
                        $placement   = $result__->fetchRow();
                    } else if ($_POST['filter'] == 'kapal') {
                        $id_kategori = $_POST['id_kategori'];
                        $query_blok_ = "SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND ID_CELL = '$index_cell_' AND ID_VS = '$id_kategori'";
                        $result__    = $db->query($query_blok_);
                        $placement   = $result__->fetchRow();
                        
                    } else if ($_POST['filter'] == 'consignee') {
                        $id_kategori2 = $_POST['id_kategori2'];
                        $query_blok_  = "SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND ID_CELL = '$index_cell_' AND KODE_PBM = '$id_kategori2'";
                        $result__     = $db->query($query_blok_);
                        $placement    = $result__->fetchRow();
                    }
                    // debug($place);die;
                     $place    = $placement['JUM'];
                   // echo $placement;
                     if ($place <> 0){ ?>
                          <div id="x" class="drag blue">
                          <td data-tooltip="sticky<?=$index_cell_?>" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0033'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0033; "><?//$index_cell_?><?echo $place ?></td>
                         </div>
                   <?  } else { ?>
                             <div id="x" class="drag blue">
                          <td data-tooltip="sticky<?=$index_cell_?>" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#86BCFF; "><?//=$index_cell_?><?echo $row['NAME']?></td>
                                                   </div>

                     <? } ?>
                  
               <? } else {?>
                    <td align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;"></td>
              <? }
                    if (($row['INDEX_CELL']+1) % $width == 0){ ?>
    </tr>
                    <? }
            } ?>
</tbody>
</table>
</td>
</tr>
<tr>
<tr>
<tr>
<tr>
</tbody>
</table>
</div>
</tbody>
</p></div>

    </center>
</fieldset> 

<?
} else { ?>

 <fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
    <center>
      <span class="graybrown">
          <br>
          <h3>Layout Lapangan Ex TBB</h3>
          <br>
        

          <br>

<div style="margin-top:0px;border:1px solid black;width:900;height:400;overflow-y:scroll;overflow-x:scroll;">
<p style="width:80%;">
<div align="center" style="background-color:#F6F4E4;">
<table width="100%" cellspacing="3" border="0">
<tbody>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<tr>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="4" cellpadding="4" align="center">
<tbody>
    <tr>
         <?php
            $db             = getDB();
    //echo "SELECT TIER, NAME FROM YD_BLOCKING_AREA WHERE ID = '$id_blok'";die;
        //   echo "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE STATUS = 'STATUS'";die;
            $query_blok = "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
            $result_    = $db->query($query_blok);
            $blok       = $result_->fetchRow();
            //debug($blok);die;
            $width      = $blok['WIDTH'];
            $yard_id    = $blok['ID'];

           // echo "SELECT (a.INDEX_CELL+1) INDEX_CELL, b.ID, b.NAME, a.SLOT_, a.ROW_ FROM YD_BLOCKING_AREA b, YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' ORDER BY a.INDEX_CELL ASC";
            $query_cell2 = "SELECT a.INDEX_CELL, b.ID, b.NAME, a.SLOT_, a.ROW_ FROM YD_BLOCKING_AREA b, YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' ORDER BY a.INDEX_CELL ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
            
         // debug($blok2);die;
            foreach ($blok2 as $row){
                //echo $row['INDEX_CELL'];
                if ($row['NAME'] <> 'NULL'){
                    
                    $id_block   = $row['ID'];
                    $slot_       = $row['SLOT_'];
                    $row_        = $row['ROW_'];
                    $name        = $row['NAME'];
                    $index_cell_  = $row['INDEX_CELL'];
                 //  echo $index_cell_."-".$row['INDEX_CELL']."-".$row['NAME']."<br>";
                // echo " SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$row_' AND ID_CELL = '$index_cell_'";
                     $query_place = "SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$row_' AND ID_CELL = '$index_cell_' ";
                     $result2    = $db->query($query_place);
                     $place       = $result2->fetchRow();
                     
                    // debug($place);die;
                     $placement   = $place['JUM']; ?>
                   
                <?     if ($placement <> 0){ ?>
<!--                     <div id="x" class="drag blue">-->
                        <td data-tooltip="sticky<?=$index_cell_?>" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0033'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0033; "><?//$index_cell_?><?echo $placement ?></td>
<!--                     </div>-->

                   <? } else { ?>
<!--                          <div id="x" class="drag blue">-->
                          <td data-tooltip="sticky<?=$index_cell_?>" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#86BCFF; "><?//=$index_cell_?><?echo $row['NAME']?></td>
<!--                         </div>-->
               <?     }  } else {?>
                    <td align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;"></td>
              <? }
                    if (($row['INDEX_CELL']+1) % $width == 0){ ?>
    </tr>
                    <? }
                    
                    
                    ?>      
                    
        <?    } ?>
</tbody>
</table>
</td>
</tr>
<tr>
<tr>
<tr>
<tr>
</tbody>
</table>
</div>
</tbody>
</p></div>

    </center>
</fieldset>   
<? }?> 

                       
	<!-- data tooltip -->
		
 <div id="mystickytooltip" class="stickytooltip">
                    <div style="padding:5px">                    
                    <? 
                                    $db         = getDB();
                                    //echo "SELECT TIER, NAME FROM YD_BLOCKING_AREA WHERE ID = '$id_blok'";die;
                                    //   echo "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE STATUS = 'STATUS'";die;
                                    $query_blok = "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
                                    $result_    = $db->query($query_blok);
                                    $blok       = $result_->fetchRow();
                                    //debug($blok);die;
                                    $yard_id    = $blok['ID'];
                                //  echo "SELECT a.INDEX_CELL, b.ID, b.NAME, a.SLOT_, a.ROW_ FROM YD_BLOCKING_AREA b, YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' ORDER BY a.INDEX_CELL ASC";
                                    $query_cell4 = "SELECT a.INDEX_CELL, b.ID, b.NAME, a.SLOT_, a.ROW_ FROM YD_BLOCKING_AREA b, YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' ORDER BY a.INDEX_CELL ASC";
                                    $result4    = $db->query($query_cell4);
                                    $blok4      = $result4->getAll();

                                //  debug ($blok4);
                                    foreach($blok4 as $dama){
                            ?>
                                    <div id="sticky<? echo $dama['INDEX_CELL']; ?>" class="atip">                                    
                                    <? 
                                    $query_place = "SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = ".$dama['ID']." AND SLOT_YARD = ".$dama['SLOT_']." AND ROW_YARD = ".$dama[ROW_]." AND ID_CELL = ".$dama['INDEX_CELL']."";
                                    $result2     = $db->query($query_place);
                                    $place       = $result2->fetchRow();
                                    
                                    if ($place['JUM'] == 0){?><i> -- There is no container here -- </i>
                                    <? } else {?>
                                       <?php
                                            $query_index_cell   = "SELECT  NO_CONTAINER, SIZE_, TON, DEST FROM YD_PLACEMENT_YARD WHERE ID_CELL = ".$dama['INDEX_CELL']."";
                                            $result_index_cell  = $db->query($query_index_cell);
                                            $tier               = $result_index_cell->getAll();
                                            //debug($blok);die;
                                       
                                            foreach($tier as $row){
                                       ?>
                                    <br>
                                           <img src="images/row_cont.png" width="40" height="40">
                                           <br> No Container : <?=$row['NO_CONTAINER']?><br>Size : <?=$row['SIZE_']?><br>TONASE : <?=$row['TON']?><br>Tujuan : <?=$row['DEST']?>
                                       <? } ?>
                                     <?  }?>
                                    </div>
                   <? }?> 
                        
                                    </div>

                    <div class="stickystatus"></div>
                    </div>
        
         <div id="mystickytooltip" class="stickytooltip">
                    <div style="padding:5px">
                                    <div id="sticky_" class="atip">                                    
                                   <img src="images/row_cont.png" width="40" height="40">  MSKU1234567 <br> 20 / DRY / FCL
                                    </div>
                                    </div>

                    <div class="stickystatus"></div>
                    </div>
</body>
</html>