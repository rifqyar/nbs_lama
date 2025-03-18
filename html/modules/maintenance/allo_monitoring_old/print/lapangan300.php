<?
$nm_user = $_SESSION["NAMA_LENGKAP"];
$vessel = $_GET['vessel'];
$voyage = $_GET['voyage'];
?>

<table align="center">
 <tr>
 <td width="350" align="left"><img src="<?=HOME?>images/logopl.png" height="70%" width="70%" align="left" /></td>
 <td width="350" align="left"><img src="<?=HOME?>images/sgs2.png" height="21%" width="21%" align="right" valign="top" /><img src="<?=HOME?>images/sgs.gif" height="10%" width="10%" align="right" /></td>
 </tr>
 </table>   
<br>
<?
     $db                    = getDB();
     $yard_id               = $_GET['id_yard'];
     $query_yard            = "SELECT NAMA_YARD FROM YD_YARD_AREA WHERE ID = '$yard_id'";
     $result_yard           = $db->query($query_yard);
     $yard_name             = $result_yard->fetchRow();
     $name                  = $yard_name['NAMA_YARD'];
?>
<h2 align="center"><b>Layout Allocation <?=$name?></b></h2>
<br>

<table align="center" width='720'>	
    <tr>
        <td align="left">User</td>
        <td>:</td>
        <td align="left"><font size="2" face="verdana"><? echo $nm_user;?></font></td>
    </tr>
	<tr>
        <td align="left">Tanggal Proses</td>
        <td>:</td>
        <td align="left"><font size="2" face="verdana"><? echo date('Y-m-d H:i:s');?></font></td>
    </tr>
    <tr>
        <td align="left">Vessel / Voyage</td>
        <td>:</td>
        <td align="left"><font size="2" face="verdana"><b><?=$vessel?> / <?=$voyage?></b></font></td>
    </tr>
</table>
<!-- article-content -->
    <br/>    
    <center>
	<div align="center" width="100%">
	</div>
	<div align="center" style="background-color:#FFFFFF; height:80%">
	<table width="150%" cellspacing="3" border="0">
	<tbody>
		<tr>
			<td valign="bottom" colspan="4" align="center">
				<table bordercolor="#037ACA" border="0" cellspacing="4" cellpadding="4" align="center">
				<tbody>
				<tr>
         <?php
            $query_blok = "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE ID = '$yard_id'";
            $result_    = $db->query($query_blok);
            $blok       = $result_->fetchRow();
            $width      = $blok['WIDTH'];
            $yard_id    = $blok['ID'];

            $query_cell2 = "SELECT a.INDEX_CELL as INDEX_CELL, b.ID AS ID, b.NAME AS NAME, a.SLOT_ AS SLOT_, a.ROW_ AS ROW_, a.STATUS_BM,b.POSISI FROM YD_BLOCKING_AREA b, 		
							YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = $yard_id AND a.SIZE_PLAN_ALLO IS NULL 
							UNION 
							SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_, d.STATUS_BM,c.POSISI FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = $yard_id AND d.SIZE_PLAN_ALLO = '40d'
							UNION
							SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_,d.STATUS_BM,c.POSISI FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = $yard_id AND d.SIZE_PLAN_ALLO = '20'
							ORDER BY INDEX_CELL ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
            
            foreach ($blok2 as $row){
                //echo $row['INDEX_CELL'];
				$id_block   = $row['ID'];
                    $slot_       = $row['SLOT_'];
                    $row_        = $row['ROW_'];
                    $name_        = $row['NAME'];
                    $index_cell_  = $row['INDEX_CELL'];
					$st_bm		  = $row['STATUS_BM'];
					$pos	=$row['POSISI'];
					
					if($pos=='vertical')
					{
						$cr="rowspan=2";
					}
					else
						$cr="colspan=2";
                if ($row['NAME'] <> 'NULL')
				{
                    
                    
                 //  echo $index_cell_."-".$row['INDEX_CELL']."-".$row['NAME']."<br>";
                // echo " SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$row_' AND ID_CELL = '$index_cell_' ";
                      $query_place = "SELECT distinct a.INDEX_CELL ,a.SIZE_, a.TYPE_,a.STATUS_CONT, a.KATEGORI,a.HZ,a.ID_PEL_TUJ FROM YD_YARD_ALLOCATION_PLANNING a, YD_BLOCKING_AREA b, YD_YARD_AREA c WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = c.ID AND c.ID=$yard_id AND a.INDEX_CELL = '$index_cell_'";
                     $result2     = $db->query($query_place);
                     $place       = $result2->fetchRow();
                     $size_       = $place['SIZE_'];
					 $status_	  = $place['STATUS_CONT'];
                     $type_       = $place['TYPE_'];
                     $kategori2   = $place['KATEGORI'];
					 $hz		= $place['HZ'];
                     
             
                     $placement   = $place['JUM'];
                     
					 
					if (($size_ == '40') && ($hz =='Y')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma; background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png'); background-size:10px 5px" <?=$cr?>><?=$kategori2?></td>
					<?     } else if (($size_ == '20') && ($hz =='Y')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma; background-image:url('yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png'); background-size:10px 5px" ><font color="#ffffff"><?=$status_?></font></td>
					<?     } else if (($size_ == '20') && ($type_ =='OVD')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma; background-image:url('yard/src/css/excite-bike/images/OVD.png'); background-size:10px 5px" ><font color="#ffffff"><?=$status_?></font></td>		
						   <?	}else if (($size_ == '40') && ($type_ == 'OVD')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma; background-image:url('yard/src/css/excite-bike/images/OVD.png'); background-size:10px 5px" <?=$cr?>><?=$kategori2?></td>
                  <?	}else if (($size_ == '40') && ($type_ == 'DRY') &&($status_ =='FCL')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma; background-image:url('<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_95_ffdc2e_40x40.png'); background-size:10px 5px" <?=$cr?>><?=$kategori2?></td>
					<?     } else if (($size_ == '40') && ($status_ =='MTY')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma; background-image:url('<?=HOME?>yard/src/css/excite-bike/images/empty.png'); background-size:10px 5px" <?=$cr?>><font color="#ffffff"><?=$status_?></font></td>
						   
					<?     } else if (($size_ == '20') && ($status_ =='MTY')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma; background-image:url('<?=HOME?>yard/src/css/excite-bike/images/empty.png'); background-size:10px 5px" ><font color="#ffffff"><?=$status_?></font></td>
					
                   <?     } else if (($size_ == '40') && ($type_ == 'HQ')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma; background-image:url('<?=HOME?>yard/src/css/excite-bike/images/ungu.png'); background-size:10px 5px" <?=$cr?>><font color="#ffffff"><?=$kategori2?></font></td>
                   <?     } else if (($size_ == '40') && ($type_ == 'DG')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png'); background-size:10px 5px" <?=$cr?>><?=$kategori2?></td>
                   <?     } else if (($size_ == '45') && ($type_ == 'DRY')){?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_18_b81900_40x40green.png'); background-size:10px 5px" <?=$cr?>><font color="#ffffff"><?=$kategori2?></font></td>
                   <?    } else if (($size_ == '45') && ($type_ == 'HQ')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'"  onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_18_b81900_40x40green.png');background-size:10px 5px" <?=$cr?>><?=$kategori2?></td>
                   <?     } else if (($size_ == '45') && ($type_ == 'DG')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png'); background-size:10px 5px" <?=$cr?>><?=$kategori2?></td>
                   <?     } else if (($size_ == '20') && ($type_ == 'DRY')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_22_1484e6_40x40.png'); background-size:10px 5px"><font color="#ffffff"><?=$kategori2?></font></td>
                   <?     } else if (($size_ == '20') && ($type_ == 'HQ')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_22_1484e6_40x40.png'); background-size:10px 5px"><?=$kategori2?></td>
                   <?    } else if (($size_ == '20') && ($type_ == 'DG')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png');background-size:10px 5px"><<?=$kategori2?></td>
					<?    } else if (($size_ == '40') && ($type_ == 'RFR')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/20flt.png');background-size:10px 5px"  <?=$cr?>><?=$kategori2?></td>
					<?    } else if (($size_ == '20') && ($type_ == 'RFR')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/20flt.png');background-size:10px 5px"><<?=$kategori2?></td>
					<?    } else if (($size_ == '20') && ($type_ == 'TNK')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/40rfr.png');background-size:10px 5px"><?=$kategori2?></td>
					<?    } else if (($size_ == '40') && ($type_ == 'TNK')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/40rfr.png');background-size:10px 5px"  <?=$cr?>><?=$kategori2?></td>
					<?    } else if (($size_ == '20') && ($type_ == 'OT')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/abu2.png');background-size:10px 5px"><?=$kategori2?></td>
					<?    } else if (($size_ == '40') && ($type_ == 'OT')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:60px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/abu2.png');background-size:10px 5px"  <?=$cr?>><?=$kategori2?></td>
					
                   <?    } else { ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-color:#86BCFF;"> <?//=$index_cell_?><?=$name_?> </td>
                    
               <? } 
			   }
			   else 
			   
			   {?>
                      <td align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;">
					<? if (($slot_ == NULL) AND ($row_ <> NULL) AND ($st_bm == NULL)) {
							?><?echo $row_;?><?
					   } else if (($slot_ <> NULL) AND ($row_ == NULL) AND ($st_bm == NULL)) {
							?><?echo $slot_;?><?
					   } else if (($slot_ == NULL) AND ($row_ == NULL) AND ($st_bm <> NULL)) {
							?><font size="1pt"><b><?=$st_bm;?></b></font></font><?
						} ?></td>
              <? }
                    if (($row['INDEX_CELL']+1) % $width == 0){ ?>
    </tr>
                    <? }
            } ?>
		</tbody>
		</table>
		</td>
	</tr>
	</tbody>
	</table>
	</div>
	</tbody>
    </center>    