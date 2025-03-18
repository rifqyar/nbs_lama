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

     
<script type='text/javascript'>
$(document).ready(function() 
{
        $( "#vessel" ).autocomplete({
		minLength: 3,
		source: "maintenance.monitoring.auto/parameter",
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
        
        $( "#load_layout" ).dialog({
		autoOpen: false,
		height: 400,
		width:600,
		show: "blind",
		hide: "explode"
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

function detail(yard_id)
{
	//alert($no_request);
	//var url = "<?=HOME?><?=APPID?>.ajax/list_cont.php";
	//alert(url);
     
	$("#load_layout" ).load('<?=HOME?>maintenance.monitoring/load_layout?id='+yard_id+' #load_layout', function(data){
		
			$( "#load_layout" ).dialog( "open" );
		
		});
	
}


</script>


<script type="text/javascript" src="tooltip/stickytooltip.js"></script>
<link rel="stylesheet" type="text/css" href="tooltip/stickytooltip.css" />
</head>
<body>
    <div id="load_layout" title="Daftar Container"></div>
    <span class="graybrown"><img src='images/document-open.png' border='0' class="icon"/><font color="#0378C6"> Monitoring</font> Lapangan </span><br/><br/>
<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
    <center>
    <form enctype="multipart/form-data" action="<?= HOME ?>maintenance.monitoring/index" method="post">
    <table style="margin: 10px 10px 10px 10px;" border="0" style="font-size: 10px; font-weight: bold;">
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
                        <option value="ID_PEL_TUJ">ID_PEL_TUJination</option>
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
<br>
<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
      <center>
      <span class="graybrown">
       <table align="center">
           <tr>
               <th class="grid-header" valign="top" style="font-size:10px;color:#ffffff" width='20' align="center" bgcolor="#12e0fa">NO</th>
               <th class="grid-header" valign="top" style="font-size:10px;color:#ffffff" width='100' align="center" bgcolor="#12e0fa">NAMA BLOK</th>
               <th class="grid-header" valign="top" style="font-size:10px;color:#ffffff" width='40' align="center" bgcolor="#12e0fa">KAPASITAS</th>
               <th class="grid-header" valign="top" style="font-size:10px;color:#ffffff" width='40' align="center" bgcolor="#12e0fa">REALISASI</th>
               <th class="grid-header" valign="top" style="font-size:10px;color:#ffffff" width='40' align="center" bgcolor="#12e0fa">ALOKASI</th>
           </tr>
           <?
            $i = 1;
            $query  = " SELECT a.NAME, (COUNT(c.INDEX_CELL)*a.TIER) KAPASITAS, d.USED, e.ALOKASI  FROM YD_BLOCKING_CELL c, YD_BLOCKING_AREA a, YD_YARD_AREA b,
 (SELECT COUNT(h.ID_CELL) USED FROM YD_PLACEMENT_YARD h, YD_BLOCKING_AREA i, YD_YARD_AREA j WHERE h.ID_BLOCKING_AREA = i.ID AND i.ID_YARD_AREA = j.ID AND j.STATUS = 'AKTIF') d,
 (SELECT COUNT(v.INDEX_CELL) ALOKASI FROM YD_YARD_ALLOCATION_PLANNING v, YD_BLOCKING_AREA w, YD_YARD_AREA x WHERE v.ID_BLOCKING_AREA = w.ID AND w.ID_YARD_AREA = x.ID AND x.STATUS = 'AKTIF')  e
 WHERE a.ID_YARD_AREA = b.ID AND b.STATUS = 'AKTIF' AND a.NAME <> 'NULL' GROUP BY  a.NAME, a.TIER, d.USED, e.ALOKASI ";
            $result = $db->query($query);
            $name   = $result->getAll();
            foreach ($name as $row){
           ?>
            <tr>
               <td class="grid-cell" valign="top" style="font-size:10px;color:#000000" width='20' align="center" bgcolor="#ffffff"><?=$i?></td>
               <td class="grid-cell" valign="top" style="font-size:10px;color:#000000" width='20' align="center" bgcolor="#ffffff">BLOK <?=$row['NAME']?></td>
               <td class="grid-cell" valign="top" style="font-size:10px;color:#000000" width='20' align="center" bgcolor="#ffffff"><?=$row['KAPASITAS']?> BOX</td>
               <td class="grid-cell" valign="top" style="font-size:10px;color:#000000" width='20' align="center" bgcolor="#ffffff"><?=$row['USED']?> BOX</td>
               <td class="grid-cell" valign="top" style="font-size:10px;color:#000000" width='20' align="center" bgcolor="#ffffff"><?=$row['ALOKASI']?> BOX</td>
           </tr>
           <? $i++;} ?>
       </table>
      </span>
      </center>
</fieldset>
<br>

 <fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
    <center>
      <span class="graybrown">
            <?php
             $db             = getDB();
    //echo "SELECT TIER, NAME FROM YD_BLOCKING_AREA WHERE ID = '$id_blok'";die;
        //   echo "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE STATUS = 'STATUS'";die;
            $query_blok = "SELECT WIDTH, NAMA_YARD, ID FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
            $result_    = $db->query($query_blok);
            $blok       = $result_->fetchRow();
            //debug($blok);die;
            $width      = $blok['WIDTH'];
            $yard_id    = $blok['ID'];
            $name_yard  = $blok['NAMA_YARD'];
        ?>
          <br>
          <h3>Layout <?=$name_yard?></h3>
          <br>
<a href="maintenance.monitoring.layout/load_layout?id=<?=$yard_id?>" target="_blank">  View Detail</a>
<br><br>
<div style="margin-top:0px;border:1px solid black;width:900;height:600;overflow-y:scroll;overflow-x:scroll;">
<p style="width:200%;">
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
    <tr align="center"><td colspan="<?=$width?>"><img src="images/dermaga_2.png"></td></tr>
    <tr align="center">
         <?php
           // echo "SELECT (a.INDEX_CELL+1) INDEX_CELL, b.ID, b.NAME, a.SLOT_, a.ROW_ FROM YD_BLOCKING_AREA b, YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' ORDER BY a.INDEX_CELL ASC";
            $query_cell2 = "SELECT a.INDEX_CELL, b.ID, b.NAME, a.SLOT_, a.ROW_ FROM YD_BLOCKING_AREA b, YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' ORDER BY a.INDEX_CELL ASC";
            $result3     = $db->query($query_cell2);
            $blok2       = $result3->getAll();
            
         // debug($blok2);die;
            foreach ($blok2 as $row){
                //echo $row['INDEX_CELL'];
                if ($row['NAME'] <> 'NULL'){                
                    $id_block     = $row['ID'];
                    $slot_        = $row['SLOT_'];
                    $row_         = $row['ROW_'];
                    $name         = $row['NAME'];
                    $index_cell_  = $row['INDEX_CELL'];
                 //  echo $index_cell_."-".$row['INDEX_CELL']."-".$row['NAME']."<br>";
                // echo " SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$row_' AND ID_CELL = '$index_cell_'";
                     $query_place = "SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$row_' AND ID_CELL = '$index_cell_' ";
                     $result2     = $db->query($query_place);
                     $place       = $result2->fetchRow();
                     
                    // debug($place);die;
                     $placement   = $place['JUM']; ?>
                   
                <?     if ($placement <> 0){ ?>
<!--                     <div id="x" class="drag blue">-->
<td data-tooltip="sticky<?=$index_cell_?>"  onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0033'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#FF0033; "><blink><font color="white"> <a onclick="detail('{$yard_id}')"> <?//=$index_cell_?><?echo $placement ?></a></font></blink></td>
<!--                     </div>-->

                   <? } else { ?>
<!--                          <div id="x" class="drag blue">-->
                          <td data-tooltip="sticky<?=$index_cell_?>" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#86BCFF; "><a onclick="detail('{$yard_id}')"> <?//=$index_cell_?><?echo $row['NAME']?></a></td>
<!--                         </div>-->
               <?     }  } else {?>
                    <td align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;"><?//=$index_cell_?></td>
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
<br /><br />
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
                                    //echo $query_place;
									$result2     = $db->query($query_place);
                                    $place       = $result2->fetchRow();
                                    
                                    if ($place['JUM'] == 0){?><i> -- There is no container heres -- </i>
                                    <? } else {?>
                                       <?php
                                            $query_index_cell   = "SELECT  NO_CONTAINER, SIZE_, TON, ID_PEL_TUJ FROM YD_PLACEMENT_YARD WHERE ID_CELL = ".$dama['INDEX_CELL']."";
                                            $result_index_cell  = $db->query($query_index_cell);
                                            $tier               = $result_index_cell->getAll();
                                            //debug($blok);die;
                                       
                                            foreach($tier as $row){
                                       ?>
                                    <br>
                                           <img src="images/row_cont.png" width="40" height="40">
                                           <br> No Container : <?=$row['NO_CONTAINER']?><br>Size : <?=$row['SIZE_']?><br>TONASE : <?=$row['TON']?><br>Tujuan : <?=$row['ID_PEL_TUJ']?>
                                       <? } ?>
                                     <?  }?>
                                    </div>
                   <? }?> 
                        
                                    </div>

                    <div class="stickystatus"></div>
                    </div>
</table>
</body>
</html>