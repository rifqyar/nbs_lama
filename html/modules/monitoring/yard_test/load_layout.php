<div id="load_layout" width='100%' height='100%' style="background-color:#F6F4E4;">
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

<table cellspacing="3" border="0">
<tbody>
<tr>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="4" cellpadding="4" align="center">
<tbody>
    <tr>
         <?php
           $db = getDB();
    //echo "SELECT TIER, NAME FROM YD_BLOCKING_AREA WHERE ID = '$id_blok'";die;
        //   echo "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE STATUS = 'STATUS'";die;
            $query_blok = "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE ID = 41";
            $result_    = $db->query($query_blok);
            $blok       = $result_->fetchRow();
            //debug($blok);die;
            $width      = $blok['WIDTH'];
            $yard_id    = $blok['ID'];

           // echo "SELECT (a.INDEX_CELL+1) INDEX_CELL, b.ID, b.NAME, a.SLOT_, a.ROW_ FROM YD_BLOCKING_AREA b, YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' ORDER BY a.INDEX_CELL ASC";
            $query_cell2 = "
SELECT a.INDEX_CELL as INDEX_CELL, b.ID AS ID, b.NAME AS NAME, a.SLOT_ AS SLOT_, a.ROW_ AS ROW_ FROM YD_BLOCKING_AREA b, YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = 41 AND a.SIZE_PLAN_ALLO IS NULL 
UNION 
SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_ FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = 41 AND d.SIZE_PLAN_ALLO = '40d'
UNION
SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_ FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = 41 AND d.SIZE_PLAN_ALLO = '20'
 ORDER BY INDEX_CELL ASC
			";
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
                      $query_place = "SELECT distinct a.INDEX_CELL ,a.SIZE_, a.TYPE_, a.KATEGORI FROM YD_YARD_ALLOCATION_PLANNING a, YD_BLOCKING_AREA b, YD_YARD_AREA c WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = c.ID AND c.ID=$yard_id AND a.INDEX_CELL = '$index_cell_'";
                     $result2     = $db->query($query_place);
                     $place       = $result2->fetchRow();
                     $size_       = $place['SIZE_'];
                     $type_       = $place['TYPE_'];
                     $kategori2   = $place['KATEGORI'];
                     
                    // debug($place);die;
                     $placement   = $place['JUM'];
                     
                  if (($size_ == '40') && ($type_ == 'DRY')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma; background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_95_ffdc2e_40x40.png'); background-size:10px 5px" colspan=2><?=$kategori2?></td>
                   <?     } else if (($size_ == '40') && ($type_ == 'HQ')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma; background-image:url('yard/src/css/excite-bike/images/ungu.png'); background-size:10px 5px" colspan=2><font color="#ffffff"><?=$kategori2?></font></td>
                   <?     } else if (($size_ == '40') && ($type_ == 'DG')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png'); background-size:10px 5px" colspan=2><?=$kategori2?></td>
                   <?     } else if (($size_ == '45') && ($type_ == 'DRY')){?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_18_b81900_40x40green.png'); background-size:10px 5px" colspan=2><font color="#ffffff"><?=$kategori2?></font></td>
                   <?    } else if (($size_ == '45') && ($type_ == 'HQ')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'"  onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_18_b81900_40x40green.png');background-size:10px 5px" colspan=2><?=$kategori2?></td>
                   <?     } else if (($size_ == '45') && ($type_ == 'DG')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png'); background-size:10px 5px" colspan=2><?=$kategori2?></td>
                   <?     } else if (($size_ == '20') && ($type_ == 'DRY')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_22_1484e6_40x40.png'); background-size:10px 5px"><font color="#ffffff"><?=$kategori2?></font></td>
                   <?     } else if (($size_ == '20') && ($type_ == 'HQ')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_22_1484e6_40x40.png'); background-size:10px 5px"><?=$kategori2?></td>
                   <?    } else if (($size_ == '20') && ($type_ == 'DG')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png');background-size:10px 5px"><<?=$kategori2?></td>
                   <?    } else { ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-color:#86BCFF;"> <?//=$index_cell_?><?=$name_?> </td>
                    
               <? } } else {?>
                    <td align="center" style="width:30px;height:15px;font-size:2px; font-family:Tahoma; border:1px solid #000000;"><?=$index_cell?></td>
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
</tbody>

    </center>
    
</div>