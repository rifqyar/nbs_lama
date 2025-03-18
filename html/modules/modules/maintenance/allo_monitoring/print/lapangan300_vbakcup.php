<?
$nm_user = $_SESSION["NAMA_LENGKAP"];
$vessel = $_GET['vessel'];
$voyage = $_GET['voyage'];
$ukk    = $_GET['ukk'];
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
<h2 align="center"><b>Layout Yard Allocation Import <?=$name?></b></h2>
<br>
<center>
<table align="center" width='450'>	
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
</center>
<br>
<center>
		<table border='1'>
		<tr align='center'>
			<td rowspan='2'>No</td>
			<td rowspan='2'>Category</td>
			<td rowspan='2'>Colour Code</td>
			<td rowspan='2'>Block</td>
			<td colspan='7'>Container Type</td>
			<td rowspan='2'>BOX</td>
			<td rowspan='2'>TEUS</td>
		</tr>
		<tr align='center'>
			<td>Size</td>
			<td>Type</td>
			<td>Status</td>
			<td>HZ</td>
			<td>Height</td>
			<td>Plug</td>
			<td>BOX</td>
		</tr>
		
		<?php
			
			$query="select a.kategori, a.box, a.teus, a.allocated, a.id_kategori, CONCAT(CONCAT(CONCAT(CONCAT(b.nm_kapal, ' '),b.voyage_in), ' - '),b.voyage_out) KAPAL, a.BLOCK, a.CREATE_DATE, a.ID_USER, a.ID_VS, BLOCK from tb_booking_cont_area_gr a, rbm_h b where a.id_vs = b.no_ukk and a.id_vs = '$ukk' and E_I = 'I' order by a.KATEGORI asc";
			$result1 = $db->query($query);
			$data    = $result1->getAll();
			$i=1;
			foreach ($data as $row){
				$kategori = $row['ID_KATEGORI'];
				$idvs     = $row['ID_VS'];
				$query3   = "select  a.kategori_group, a.size_cont, a.type_cont, a.status_cont, a.height, a.hz, a.type_reffer, create_date, id_user, BOX from tb_booking_cont_area a where a.id_vs = '$idvs'  and a.e_i = 'I' and a.id_kategori = '$kategori' ";
				$result3  = $db->query($query3);
				$data3	  = $result3->getAll();
				$detail   = '';
				foreach ($data3 as $de){
					$detail .= $de['SIZE_CONT']." ".$de['TYPE_CONT']." ".$de['STATUS_CONT']." ".$de['HZ']." ".$de['HEIGHT']." ".$de['TYPE_REFFER']." - ".$de['BOX']." BOX <br>";
				}
			
?>		<tr align='center'>
			<td><?=$i?></td>
			<td>Category <?=$row['KATEGORI']?></td>
			<td><?if ($row['KATEGORI'] ==1)
				{
					?><img src='<?=HOME?>yard/src/css/excite-bike/images/abu2.png' height='15px' width='15px'><?
				}
				else if ($row['KATEGORI'] ==2)
				{
					?><img src='<?=HOME?>yard/src/css/excite-bike/images/40rfr.png' height='15px' width='15px'><?
				}
				else if ($row['KATEGORI'] ==3)
				{
					?><img src='<?=HOME?>yard/src/css/excite-bike/images/20flt.png' height='15px' width='15px'><?
				}
				else if ($row['KATEGORI'] ==4)
				{
					?><img src='<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png' height='15px' width='15px'><?
				}
				ELSE if ($row['KATEGORI'] ==5)
				{
					?><img src='<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_18_b81900_40x40green.png' height='15px' width='15px'><?
				}
				ELSE if ($row['KATEGORI'] ==6)
				{
					?><img src='<?=HOME?>yard/src/css/excite-bike/images/ungu.png' height='15px' width='15px'><?
				} 
				else if ($row['KATEGORI'] == 7)
				{ 
                    ?><img src='<?=HOME?>yard/src/css/excite-bike/images/OVD.png' height='15px' width='15px'> <?
				}
				else if ($row['KATEGORI'] == 8)
				{ 
                    ?><img src='<?=HOME?>yard/src/css/excite-bike/images/empty.png' height='15px' width='15px'>  <?
				} 
				else if ($row['KATEGORI'] == 9)
				{ 
                    ?><img src='<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_95_ffdc2e_40x40.png' height='15px' width='15px'>  <?
			    }
				else if ($row['KATEGORI'] == 10)
				{ 
                    ?><img src='<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_22_1484e6_40x40.png' height='15px' width='15px'><?
				} ?>	   
						  
</td>
			<td><?=$row['BLOCK']?></td>
			<td colspan="7"><?=$detail?></td>
			<td><?=$row['BOX']?></td>
			<td><?=$row['TEUS']?></td>
		</tr>

				
				
				
			<? $i++; } ?>
	</table></center>
<!-- article-content -->
    <br/>    
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

            $query_cell2 = "SELECT a.INDEX_CELL as INDEX_CELL, b.ID AS ID, b.NAME AS NAME, a.SLOT_ AS SLOT_, a.ROW_ AS ROW_, a.STATUS_BM,b.POSISI, a.ID_KATEGORI FROM YD_BLOCKING_AREA b, 		
							YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' AND a.SIZE_PLAN_ALLO IS NULL 
							UNION 
							SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_, d.STATUS_BM,c.POSISI, d.ID_KATEGORI FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '$yard_id' AND d.SIZE_PLAN_ALLO = '40d'
							UNION
							SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_,d.STATUS_BM,c.POSISI, d.ID_KATEGORI FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '$yard_id' AND d.SIZE_PLAN_ALLO = '20'
							ORDER BY INDEX_CELL ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
            
            foreach ($blok2 as $row){
                //echo $row['INDEX_CELL'];
					$id_block    = $row['ID'];
                    $slot_       = $row['SLOT_'];
                    $row_        = $row['ROW_'];
                    $name_       = $row['NAME'];
                    $index_cell_ = $row['INDEX_CELL'];
					$id_kat      = $row['ID_KATEGORI'];
					$st_bm		 = $row['STATUS_BM'];
					$pos		 = $row['POSISI'];
					
					 IF (($id_kat<>'') OR ($id_kat<> NULL))
					 {
						$cr="";
					 }
					 else
					 {
							if($pos=='vertical')
							{
								$cr="rowspan=2";
							}
							else
								$cr="colspan=2";
					 }
					 
                if ($row['NAME'] <> 'NULL')
				{
                    
                    
                 //  echo $index_cell_."-".$row['INDEX_CELL']."-".$row['NAME']."<br>";
                // echo " SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$row_' AND ID_CELL = '$index_cell_' ";
                      $query_place = "SELECT distinct a.INDEX_CELL ,a.SIZE_, trim(a.TYPE_) AS TYPE_,a.STATUS_CONT, a.KATEGORI,a.HZ,a.ID_PEL_TUJ,a.STATUS_BM, a.ID_VS FROM YD_YARD_ALLOCATION_PLANNING a, YD_BLOCKING_AREA b, YD_YARD_AREA c WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = c.ID AND c.ID='$yard_id' AND a.INDEX_CELL = '$index_cell_' ";
                     $result2     = $db->query($query_place);
                     $place       = $result2->fetchRow();
                     
					$stbm_       = $place['STATUS_BM'];
                    $size_       = $place['SIZE_'];
					$status_	 = $place['STATUS_CONT'];
					$type_       = $place['TYPE_'];
					$idvs       = $place['ID_VS'];
						
					 
					 IF($stbm_=='Bongkar')
					 {
						$kategori2   = $place['KATEGORI'];
					 }
					 else
					 {
						$kategori2   = $place['KATEGORI'];
					 }
					 
					 $hz		= $place['HZ'];                     
             
                     $placement   = $place['JUM'];      
                     
					 
					
					if ($ukk != $idvs){ ?>
						 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-color:#86BCFF;"> <?//=$index_cell_?><?//=$name_?> </td><?
					} else {
						 if(($size_ == '40') && ($hz =='Y')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;  background-size:10px 5px" <?=$cr?>><?=$kategori2?></td>
					<?     } else if (($size_ == '20') && ($hz =='Y')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;  background-size:10px 5px" ><font color="#ffffff"><?=$status_?></font></td>
					<?     } else if (($size_ == '20') && ($type_ =='OVD')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-image:url('<?=HOME?>yard/src/css/excite-bike/images/OVD.png'); background-size:10px 5px" ><font color="#ffffff"><?=$status_?></font></td>		
						   <?	}else if (($size_ == '40') && ($type_ == 'OVD')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-image:url('<?=HOME?>yard/src/css/excite-bike/images/OVD.png'); background-size:10px 5px" <?=$cr?>><?=$kategori2?></td>
                  <?	}else if (($size_ == '40') && ($type_ == 'DRY') &&($status_ =='FCL')){ ?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px" <?=$cr?>><font color="#ffffff"><?//=$status_?></font></td>
					<?     } else if (($size_ == '40') && ($status_ =='MTY')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px" <?=$cr?>><font color="#ffffff"><?//=$status_?></font></td>
						   
					<?     } else if (($size_ == '20') && ($status_ =='MTY')){ ?>
                             <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px"><font color="#ffffff"><?//=$status_?></font></td>
					
                   <?     } else if (($size_ == '40') && ($type_ == 'HQ')){ ?>
                              <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px" <?=$cr?>><font color="#ffffff"><?//=$status_?></font></td>
                   <?     } else if (($size_ == '40') && ($type_ == 'DG')){ ?>
                              <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px" <?=$cr?>><font color="#ffffff"><?//=$status_?></font></td>
                   <?     } else if (($size_ == '45') && ($type_ == 'DRY')){?>
                            <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px" <?=$cr?>><font color="#ffffff"><?//=$status_?></font></td>
                   <?    } else if (($size_ == '45') && ($type_ == 'HQ')){ ?>
                               <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px" <?=$cr?>><font color="#ffffff"><?//=$status_?></font></td>
                   <?     } else if (($size_ == '45') && ($type_ == 'DG')){ ?>
                             <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px" <?=$cr?>><font color="#ffffff"><?//=$status_?></font></td>
						   <?     } else if (($size_ == '20') && ($type_ == 'DRY')&&($stbm_=='Bongkar')){ ?>
                             <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px" ><font color="#ffffff"><?//=$status_?></font></td>
                   <?     } else if (($size_ == '20') && ($type_ == 'DRY')){ ?>
                               <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px" ><font color="#ffffff"><?//=$status_?></font></td>
                   <?     } else if (($size_ == '20') && ($type_ == 'HQ')){ ?>
                                <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px" ><font color="#ffffff"><?//=$status_?></font></td>
                   <?    } else if (($size_ == '20') && ($type_ == 'DG')){ ?>
                              <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px"><font color="#ffffff"><?//=$status_?></font></td>
					<?    } else if (($size_ == '40') && ($type_ == 'RFR')){ ?>
                             <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px"  <?=$cr?>><font color="#ffffff"><?//=$status_?></font></td>
					<?    } else if (($size_ == '20') && ($type_ == 'RFR')){ ?>
                             <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px" ><font color="#ffffff"><?//=$status_?></font></td>
					<?    } else if (($size_ == '20') && ($type_ == 'TNK')){ ?>
                             <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px" ><font color="#ffffff"><?//=$status_?></font></td>
					<?    } else if (($size_ == '40') && ($type_ == 'TNK')){ ?>
                             <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px" <?=$cr?>><font color="#ffffff"><?//=$status_?></font></td>
					<?    } else if (($size_ == '20') && ($type_ == 'OT')){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px"><font color="#ffffff"><?//=$status_?></font></td>
					<?    } else if (($size_ == '40') && ($type_ == 'OT')){ ?>
                             <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma; background-color:#86BCFF; background-size:10px 5px" <?=$cr?>><font color="#ffffff"><?//=$status_?></font></td>
					<?	}  else if ($kategori2 == '1'){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/abu2.png');background-size:10px 5px"  <?=$cr?>>kat <?=$kategori2?></td>
					 <?  } else if ($kategori2 == '2'){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/40rfr.png');background-size:10px 5px"  <?=$cr?>>kat <?=$kategori2?></td>
				   <?    } else if ($kategori2 == '3'){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/20flt.png');background-size:10px 5px"  <?=$cr?>>kat <?=$kategori2?></td>
					 <?    } else if ($kategori2 == '4'){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png');background-size:10px 5px"  <?=$cr?>>kat <?=$kategori2?></td>
						   	 <?    } else if ($kategori2 == '5'){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_18_b81900_40x40green.png');background-size:10px 5px"  <?=$cr?>>kat <?=$kategori2?></td>
						   	 <?    } else if ($kategori2 == '6'){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/ungu.png');background-size:10px 5px"  <?=$cr?>><font color="white">kat <?=$kategori2?></font></td>
				   	 <?    } else if ($kategori2 == '7'){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/OVD.png');background-size:10px 5px"  <?=$cr?>><font color="white">kat <?=$kategori2?></font></td>
					<?    } else if ($kategori2 == '8'){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/empty.png');background-size:10px 5px"  <?=$cr?>>kat <?=$kategori2?></td>
						   	 <?    } else if ($kategori2 == '9'){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_95_ffdc2e_40x40.png');background-size:10px 5px"  <?=$cr?>>kat <?=$kategori2?></td>
				   	 <?    } else if ($kategori2 == '10'){ ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-image:url('<?=HOME?>yard/src/css/excite-bike/images/ui-bg_diagonals-thick_22_1484e6_40x40.png');background-size:10px 5px"  <?=$cr?>>kat <?=$kategori2?></td>
					<?    } else { ?>
                           <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;background-color:#86BCFF;" <?=$cr?>> <?//=$index_cell_?><?//=$name_?> </td>
                    
               <? } 
			   }}
			   else 
			   
			   {?>
                      <td align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;">
					<? if (($slot_ == NULL) AND ($row_ <> NULL) AND ($st_bm == NULL)) {
							?><?echo $row_;?><?
					   } else if (($slot_ <> NULL) AND ($row_ == NULL) AND ($st_bm == NULL)) {
							?><?echo $slot_;?><?
					   } else if (($slot_ == NULL) AND ($row_ == NULL) AND ($st_bm <> NULL)) {
							?><font size="1pt"><b><?=$st_bm;?></b></font></font><?
						} else { echo '';
						}?></td>
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


	
	
	
	