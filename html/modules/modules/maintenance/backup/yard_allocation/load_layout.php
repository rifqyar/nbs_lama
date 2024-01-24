<?
     $db                    = getDB();
     $yard_id               = $_GET['id'];
     $query_yard            = "SELECT NAMA_YARD FROM YD_YARD_AREA WHERE ID = '$yard_id'";
     $result_yard           = $db->query($query_yard);
     $yard_name             = $result_yard->fetchRow();
     $name                  = $yard_name['NAMA_YARD'];
   // echo $yard_id;die;
?>
<script type="text/javascript" src="./tooltip/stickytooltip.js"></script>
<link rel="stylesheet" type="text/css" href="./tooltip/stickytooltip.css" />
<div id="load_layout">
    <!-- data tooltip -->
<!--				<div id="mystickytooltip" class="stickytooltip">
                  <div style="padding:5px"> 
                       <div id="sticky1" class="atip">                                    
                         <img src="images/cont_orange.jpg" width="100"/><br/>
						 <b>MSKU7831111</b><br/>
						 <b>20/DRY/FCL</b>
                       </div>                        
                  </div>
                 <div class="stickystatus"></div>
                </div>-->
				<!-- data tooltip -->

 <fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
    <center>
      <span class="graybrown">
          <br>
          <h4>Layout <?=$name?></h4>
          <br>
      </span>
<div align="center" style="background-color:#F6F4E4;">
<table width="100%" cellspacing="3" border="0">
<tbody>
<tr>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="4" cellpadding="4" align="center">
<tbody>
    <tr>
         <?php
           
    //echo "SELECT TIER, NAME FROM YD_BLOCKING_AREA WHERE ID = '$id_blok'";die;
        //   echo "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE STATUS = 'STATUS'";die;
            $query_blok = "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE ID = '$yard_id'";
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
                    $name_        = $row['NAME'];
                    $index_cell_  = $row['INDEX_CELL'];
                 //  echo $index_cell_."-".$row['INDEX_CELL']."-".$row['NAME']."<br>";
                // echo " SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$row_' AND ID_CELL = '$index_cell_' ";
                     $query_place = "SELECT SIZE_, TYPE_, KATEGORI FROM YD_YARD_ALLOCATION_PLANNING WHERE INDEX_CELL = '$index_cell_'";
                     $result2     = $db->query($query_place);
                     $place       = $result2->fetchRow();
                     $size_       = $place['SIZE_'];
                     $type_       = $place['TYPE_'];
                     $kategori2   = $place['KATEGORI'];
                     
                    // debug($place);die;
                     $placement   = $place['JUM'];
                     
                     if (($size_ == '40') && ($type_ == 'DRY')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma; background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_95_ffdc2e_40x40.png'); background-size:20px 15px"><?=$kategori2?></td>
                   <?     } else if (($size_ == '40') && ($type_ == 'HQ')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma; background-image:url('yard/src/css/excite-bike/images/ungu.png'); background-size:20px 15px"><font color="#ffffff"><?=$kategori2?></font></td>
                   <?     } else if (($size_ == '40') && ($type_ == 'DG')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma;background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png'); background-size:20px 15px"><?=$kategori2?></td>
                   <?     } else if (($size_ == '45') && ($type_ == 'DRY')){?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma;background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_18_b81900_40x40green.png'); background-size:20px 15px"><font color="#ffffff"><?=$kategori2?></font></td>
                   <?    } else if (($size_ == '45') && ($type_ == 'HQ')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'"  onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma;background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_18_b81900_40x40green.png');background-size:20px 15px"><?=$kategori2?></td>
                   <?     } else if (($size_ == '45') && ($type_ == 'DG')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma;background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png'); background-size:20px 15px"><?=$kategori2?></td>
                   <?     } else if (($size_ == '20') && ($type_ == 'DRY')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma;background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_22_1484e6_40x40.png'); background-size:20px 15px"><font color="#ffffff"><?=$kategori2?></font></td>
                   <?     } else if (($size_ == '20') && ($type_ == 'HQ')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma;background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_22_1484e6_40x40.png'); background-size:20px 15px"><?=$kategori2?></td>
                   <?    } else if (($size_ == '20') && ($type_ == 'DG')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma;background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png');background-size:20px 15px"><<?=$kategori2?></td>
                   <?    } else { ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma;background-color:#86BCFF;"> <?=$name_?> </td>
                    
               <? } } else {?>
                    <td align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma; border:1px solid #000000;"></td>
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

    </center>
</fieldset>
    
</div>