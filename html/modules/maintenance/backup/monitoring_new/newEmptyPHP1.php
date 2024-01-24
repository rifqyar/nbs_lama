else if (($_POST['blok'] <> NULL) AND ($_POST['filter'] == NULL)) {
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
<table bordercolor="#037ACA" border="0" cellspacing="4" cellpadding="4">
<tbody>

       <?
    if ($_POST['view'] == 'SLOT') {
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
                    $query_blok_ = "SELECT ID_PLACEMENT FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$i' AND ROW_YARD = '$j' AND TIER_YARD = '$k'";
                    $result__    = $db->query($query_blok_);
                    $placement   = $result__->fetchRow();
                    
                    $id_place = $placement['ID_PLACEMENT'];
                    if (($k <> 0) AND ($id_place == NULL) AND ($i == 0)) {
?>
                                <td align="center" style="background-color:#CCCC33;border-radius: 5px 1px 1px 5px;-moz-border-radius: 5px 1px 1px 5px; 
-webkit-border-radius: 5px 1px 1px 5px;"><b>&nbsp  T <?= $k ?> &nbsp  </b></td>
                            <?
                    } else if (($k <> 0) AND ($id_place <> NULL) AND ($i <> 0)) {
?>
                                <td onMouseOver="this.style.backgroundColor='#FFCC66'" onMouseOut="this.style.backgroundColor='#FF4848'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FF4848; ">

	<img id="opener" src="./images/row_cont.png" width="50" height="50"></td>
                            <?
                    } else if (($k <> 0) AND ($id_place == NULL) AND ($i <> 0)) {
?>  
                                <td  align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;border-color:#000000">
                                    
                                

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
    } else {
        for ($j = 1; $j <= $jml_slot; $j++) {
?>
            <h3>SLOT <?= $j ?></h3>
            <tr><td><table>
                 <?
            for ($k = $tier; $k >= 0; $k--) {
?>
                     <tr>
                    <?
                for ($i = 0; $i <= $jml_row; $i++) {
                    $query_blok_ = "SELECT ID_PLACEMENT FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$j' AND ROW_YARD = '$i' AND TIER_YARD = '$k'";
                    $result__    = $db->query($query_blok_);
                    $placement   = $result__->fetchRow();
                    
                    $id_place = $placement['ID_PLACEMENT'];
                    if (($k <> 0) AND ($id_place == NULL) AND ($i == 0)) {
?>
                                <td align="center" style="background-color:#CCCC33;border-radius: 5px 1px 1px 5px;-moz-border-radius: 5px 1px 1px 5px; 
-webkit-border-radius: 5px 1px 1px 5px;"><b> &nbsp T <?= $k ?> &nbsp </b></td>
                            <?
                    } else if (($k <> 0) AND ($id_place <> NULL) AND ($i <> 0)) {
?>
                                <td onMouseOver="this.style.backgroundColor='#FFCC66'" onMouseOut="this.style.backgroundColor='#FF4848'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FF4848; "><img id="opener" src="./images/slot_cont.png"  width="100" height="50"></td>
                            <?
                    } else if (($k <> 0) AND ($id_place == NULL) AND ($i <> 0)) {
?>  
                                <td  align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;border-color:#000000"><img  src="./images/slot_cont_empty.png" width="100" height="50"></td> 
                            <?
                    } else if (($k == 0) AND ($id_place == NULL) AND ($i == 0)) {
?>
                                <td> &nbsp </td>
                             <?
                    } else if (($k == 0) AND ($id_place == NULL) AND ($i <> 0)) {
?>
                                <td  align="center" style="background-color:#CCCC33;border-radius: 1px 1px 5px 5px;-moz-border-radius: 1px 1px 5px 5px; 
-webkit-border-radius: 1px 1px 5px 5px;"><b> &nbsp R <?= $i ?> &nbsp</b></td>
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
