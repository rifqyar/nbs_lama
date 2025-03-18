<div id="load_block">
    
  <div style="padding-left: 0px; float:left;">
    <fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; background-color:#ffffff; vertical-align:middle; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
        <table>
         <tr>
            <td><b>Parameter Pencarian :</b></td><td>Preview</td><td> : </td><td> 
                <select name="view" id="view">
                        <option value="">-- Pilih --</option>
                        <option value="SLOT">SLOT</option>
                        <option value="ROW">ROW</option>
                </select></td>
                <td></td>
                <td>Vessel <td> : </td>
                <td><input type="text" name="vessel" size="10"  id="vessel"><input type="text" size="5" name="voyage" id="voyage"></td>
                <td></td>
                <td>Tujuan <td> : </td>
                <td> <input type="text" name="tonase" id="tonase"></td>
                <td colspan="2"></td>
    	</tr>   
        <tr>
            <td></td><td>Size <td> : </td>
            <td><select name="size" id="size">
                        <option value="">-- Pilih --</option>
                        <option value="20">20"</option>
                        <option value="40">40"</option>
                         <option value="45">45"</option>
                </select></td>
            <td></td><td>Type <td> : </td>
            <td> <select name="type" id="type">
                        <option value="">-- Pilih --</option>
                        <option value="DRY">DRY</option>
                        <option value="HQ">HQ</option>
                         <option value="DG">DG</option>
                </select></td>
            <td></td>
                <td>Tonase <td> : </td>
                <td>  <select name="tonase" id="tonase">
                        <option value="">-- Pilih --</option>
                        <option value="L2">L2</option>
                        <option value="L1">L1</option>
                         <option value="M">M</option>
                          <option value="H">H</option>
                         <option value="XH">XH</option>
                </select></td>
                <td colspan="2"></td>
    	</tr>   
  <tr>
      <td colspan="12" align="right"><input type="submit" value=" Proses " /></td>
           
    	</tr>  
        </table>
    </fieldset>
</div>  
 <div style="padding-left: 0px; float:left;">    
  <fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; background-color:#ffffff; vertical-align:middle; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
    <center>
          <?php
    // echo $_POST['blok'];die;
          $db             = getDB();
    $id_blok    = $_GET['id'];
    //echo "SELECT TIER, NAME FROM YD_BLOCKING_AREA WHERE ID = '$id_blok'";die;
    $query_blok = "SELECT TIER, NAME FROM YD_BLOCKING_AREA WHERE ID = '$id_blok'";
    $result_    = $db->query($query_blok);
    $blok       = $result_->fetchRow();
    //debug($blok);die;
    $nama_blok  = $blok['NAME'];
    $tier       = $blok['TIER'];
?>
    <br>            <h2><b>BLOK <?=$nama_blok;?></b></h2>

          <br>

<?php
    $query        = "SELECT MAX(SLOT_) JML_SLOT, MAX(ROW_) JML_ROW FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_blok'";
    $result       = $db->query($query);
    $max_slot_row = $result->fetchRow();
    
    $jml_slot = $max_slot_row['JML_SLOT'];
    $jml_row  = $max_slot_row['JML_ROW'];
    //echo "-----$yard_id---------";
?>
<div align="left" style="background-color:#F6F4E4;">
<table width="120%" cellspacing="3" border="0" align="left">
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
<td valign="bottom" colspan="4" align="left">
<table bordercolor="#037ACA" border="0" cellspacing="4" cellpadding="4" align="left">
<tbody>

       <?
        for ($j = 1; $j <= $jml_row; $j++) {
?>
        <h3>ROW <?= $j ?></h3>
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
                                <td data-tooltip="sticky<?=$placement['ID_CELL']?>" onMouseOver="this.style.backgroundColor='#FFCC66'" onMouseOut="this.style.backgroundColor='#FF4848'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:2px solid #000000; ">

<img id="opener" src="<?=HOME?>images/row_cont.png" width="15" height="15"></td></div>
                            <?
                    } else if (($k <> 0) AND ($id_place == NULL) AND ($i <> 0)) {
?>  
                                <td  data-tooltip="sticky<?=$placement['ID_CELL']?>" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma;background-color:#ffffff; BORDER-RIGHT: orange solid; BORDER-TOP: orange solid; BORDER-LEFT: orange solid; BORDER-BOTTOM: orange solid">
                                    
                                

<!--     <img  src="<?=HOME?>images/row_cont_empty.png" width="30" height="30">-->
     
   
                                    
                                    </td> 
                                
                                
                                
                            <?
                    } else if (($k == 0) AND ($id_place == NULL) AND ($i == 0)) {
?>
                                <td> &nbsp </td>
                             <?
                    } else if (($k == 0) AND ($id_place == NULL) AND ($i <> 0)) {
?>
                                <td  align="center" style="background-color:#CCCC33;border-radius: 1px 1px 5px 5px;-moz-border-radius: 1px 1px 5px 5px; 
-webkit-border-radius: 1px 1px 5px 5px;"><b> &nbsp S <?= $i ?> &nbsp </b></td>
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

    </center>
</fieldset>
 </div>
        
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
                                    $query_place = "SELECT NO_CONTAINER FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = ".$dama['ID']." AND SLOT_YARD = ".$dama['SLOT_']." AND ROW_YARD = ".$dama[ROW_]." AND ID_CELL = ".$dama['INDEX_CELL']."";
                                    $result2     = $db->query($query_place);
                                    $place       = $result2->fetchRow();
                                    
                                    if ($place['NO_CONTAINER'] == 0){?><b>BLOK <?=$dama['NAME']?></b><br>
                                    <i> -- There is no container here -- </i>
                                    <? } else {?>
                                       <?php
                                            $query_index_cell   = "SELECT  NO_CONTAINER, SIZE_, TON, DEST FROM YD_PLACEMENT_YARD WHERE ID_CELL = ".$dama['INDEX_CELL']."";
                                            $result_index_cell  = $db->query($query_index_cell);
                                            $tier               = $result_index_cell->getAll();
                                            //debug($blok);die;
                                       ?><table>
                                           <tr><td colspan="2"><b>BLOK <?=$dama['NAME']?></b><td/></tr><?
                                            foreach($tier as $row){
                                       ?>
                                           <tr><td><img src="<?=HOME?>images/row_cont.png" width="40" height="40"></td><td>
                                           No Container : <?=$row['NO_CONTAINER']?><br>Size : <?=$row['SIZE_']?><br>TONASE : <?=$row['TON']?><br>Tujuan : <?=$row['DEST']?></td></tr>
                                       <? } ?>
                                     <?  }?>
                                    </div>
                   <? }?> 
                        
                                    </div>

                    <div class="stickystatus"></div>
                    </div>
</div>