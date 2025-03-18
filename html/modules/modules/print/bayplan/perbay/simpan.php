<?php
 	$id_vs  = $_GET['id_vs'];
	$bay	= $_POST['bay'];
	$posisi = $_POST['posisi'];
	$db     = getDB();
	$row    = $db->query("SELECT B.NAMA_VESSEL, A.VOYAGE, A.ID_VS
				          FROM VESSEL_SCHEDULE A, MASTER_VESSEL B
					      WHERE A.ID_VES = B.KODE_KAPAL AND A.ID_VS = '$id_vs'");
				
	$hasil_vv = $row->fetchRow();
	$vessel   = $hasil_vv['NAMA_VESSEL'];
	$voyage   = $hasil_vv['VOYAGE'];
	$id		  = $hasil_vv['ID_VS'];
?>

<table align="center">
	<tr>
		<td width="350" align="left"><img src="<?=HOME?>images/logopl.png" height="70%" width="70%" align="left" /></td>
		<td width="350" align="left"><img src="<?=HOME?>images/sgs2.png" height="21%" width="21%" align="right" valign="top" />
		<img src="<?=HOME?>images/sgs.gif" height="10%" width="10%" align="right" /></td>
	</tr>
 </table>   
<br>
<h2 align="center"><b>LAPORAN BAYPLAN</b></h2>
<br>

<table align="center" width='720'>	
    <tr>
        <td align="left">Vessel / Voyage  </td>
        <td>:</td>
        <td align="left"><font size="2" face="verdana"><b><? echo $vessel." / ".$voyage; ?></b></font></td>
    </tr>    
</table>

<div align="center" style="background-color:white;">
<br>
<br>
<center>
<table width="100%" cellspacing="3" border="0">
<tbody>
<?
		
	$query_bay = "SELECT ID, BAY, JML_ROW, JML_TIER_UNDER, JML_TIER_ON FROM STW_BAY_AREA WHERE BAY = '$bay'";
    $result_   = $db->query($query_bay);
    $bay_      = $result_->fetchRow();
	$id_bay = $bay_['ID'];
	$bay_name= $bay_['BAY'];
    $jumlah_row = $bay_['JML_ROW'];
    $jml_tier_under = $bay_['JML_TIER_UNDER'];
	$jml_tier_on = $bay_['JML_TIER_ON'];
	$width = $jumlah_row+1;
					
?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	   
	<tr>
		<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
		<font size="3px"><b>Bay 
		<? 	if ($bay_name==1) 
		{ 
			echo $bay_name;?>(<? echo $bay_name+1; ?>)&nbsp;&nbsp;<? echo $posisi; ?>
	<? 	} 
		else if (($bay_name-1)%4==0) 
		{  
			echo $bay_name;?>(<? echo $bay_name+1; ?>) &nbsp;&nbsp;<? echo $posisi; ?>
	<?	}
		else 
		{ 
			echo $bay_name; 
		} ?></b>
		</font>
		
		</td>
		<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
	</tr>
    
	<tr>   
    <?php    
		if($posisi=='below')
		{
			$query_cell2 = "SELECT ID, CELL_NUMBER, ROW_, TIER_, STATUS_STACK 
							FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$id_bay' 
							AND POSISI_STACK IN ('BELOW','HATCH') ORDER BY CELL_NUMBER ASC";
			$result3    = $db->query($query_cell2);
			$blok2      = $result3->getAll();
		}
		else
		{
			$query_cell2 = "SELECT ID, CELL_NUMBER, ROW_, TIER_, STATUS_STACK 
							FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$id_bay' 
							AND POSISI_STACK IN ('ABOVE','HATCH') ORDER BY CELL_NUMBER ASC";
			$result3    = $db->query($query_cell2);
			$blok2      = $result3->getAll();
		}
			
		$n='';
		$br='';
		$tr='';
		foreach ($blok2 as $row8)
		{
			$id_cell	  =	$row8['ID'];
        	$index 		  = $row8['CELL_NUMBER']+1;
			$cell_address = $index-1;
			$br 		  = $n;
			$tr 		  = $row8['TIER_'];
			$n 			  = $tr;
			$rw 		  = $row8['ROW_'];
				
			if ($index%$width != 0) 
			{				     
				if ($row8['STATUS_STACK'] == 'A')
				{ 
					$query2 = "SELECT ID_PLACEMENT, NO_CONTAINER, SIZE_, TYPE_, STATUS_, ID_VS, ACTIVITY, HZ,
							   GROSS, ID_PEL_ASAL, ID_PEL_TUJ, KODE_PBM, NO_BOOKING_SL 
							   FROM STW_PLACEMENT_BAY
							   WHERE ID_CELL='$id_cell' AND BAY='$bay_name' AND ID_VS='$id_vs'";
					$result3= $db->query($query2);	
					$hasil2 = $result3->fetchRow();
					
					$vs_id		=$hasil2['ID_VS'];
					$kegiatan	=$hasil2['ACTIVITY'];
					$no_cont	=$hasil2['NO_CONTAINER'];
					$size_cont	=$hasil2['SIZE_'];
					$type_cont	=$hasil2['TYPE_'];
					$status_cont=$hasil2['STATUS_'];
					$id_plc		=$hasil2['ID_PLACEMENT'];
					$hz_		=$hasil2['HZ'];
					$gross_		=$hasil2['GROSS'];
					$pl_asal	=$hasil2['ID_PEL_ASAL'];
					$pl_tuj		=$hasil2['ID_PEL_TUJ'];
					$pel_asal 	= str_replace(' ','',$pl_asal);
					$pel_tuj 	= str_replace(' ','',$pl_tuj);
					$kode_pbm	=$hasil2['KODE_PBM'];
					$no_booking =$hasil2['NO_BOOKING_SL'];
					$height		=$hasil2['H_ISO'];
					$tinggi		=ceil($height*30.48);
										
					if($no_cont != NULL)
					{
					?>
						<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" 
						align="center" style="width:60px;height:60px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33;">&nbsp;
							<td width="85">
							<div id="<?=$vs_id?>,<?=$no_cont?>,<?=$size_cont?>,<?=$type_cont?>,<?=$status_cont?>,<?=$hz_?>,
							<?=$gross_?>,<?=$pel_asal?>,<?=$pel_tuj?>,<?=$kegiatan?>,<?=$kode_pbm?>,<?=$no_booking?>,<?=$id_plc?>,
							<? echo number_format($tinggi); ?>" class="drag blue">
							<?=$pel_asal?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$pel_tuj?></br>
							<?=$no_cont?><br/>
							<span>MSK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo number_format($gross_/1000,1); ?></span><br/>
							<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $size_cont.$type_cont; ?></span><br/>
							<span><? echo $tinggi; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
							</div>
							</td>
						</td>
					<? 
					} else
					{
					?>
						<td width="85" class="mark2" align="center" style="width:60px;height:60px;font-size:5px; font-family:Tahoma; border:1px solid #000000;">&nbsp;</td>
						<?
					}  			   
				}
				else if ($row8['STATUS_STACK'] == 'P')
				{ ?>				
                    <div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" style="width:60px;height:60px;font-size:10px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0000; ">&nbsp;</td>
                    </div>                  
               <? 
			    }
			    else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			    {
			   ?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:60px;height:60px;font-size:5px; font-family:Tahoma; border:1px solid #000000;">
						<img src="<?=HOME?>images/cont_print/hatch.png" height="60px" width="60px"/>
						</td>
					</div>	
			   <?
			    }
			    else if ($row8['STATUS_STACK'] == 'N')
			    {?>
                    <? if(($index>=1)&&($index<$width)) 
					{
					?>
						<td align="center" style="width:60px;height:60px;font-size:10px; font-family:Tahoma; "><?=$rw;?></td>
				<?  }
					else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) 
					{
					?>
						<td align="center" style="width:60px;height:60px;font-size:10px; font-family:Tahoma; "><?=$rw;?></td>
				<?  }
					else
					{
					?>
						<div id="x" class="drag blue">
						<td align="center" style="width:60px;height:60px;font-size:10px; font-family:Tahoma; border:1px solid #000000;background-color:#FFF; ">&nbsp;
						</td>
						</div>
				<?  } ?>
              <?} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>					
				<div id="x" class="drag blue">
                <td align="center" style="width:60px;height:60px;font-size:10px; font-family:Tahoma;">
				<?=$br?>
				</td>
                </div>
	</tr>
		<?  }
			else if ($index%$width == 0)
			{ ?>
				<div id="x" class="drag blue">                    
				<? if ($br != 0)
				   { 
						if ($index==$width)
						{ ?>
							<td align="center" style="width:60px;height:60px;font-size:10px; font-family:Tahoma;">&nbsp;</td>
					 <? }						 
						else 
						{
					 ?>
							<td align="center" style="width:60px;height:60px;font-size:10px; font-family:Tahoma;"><?=$br;?></td>
					<? 
						} 
					}
					else
					{  
						if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						{ ?>
							<td align="center" style="width:60px;height:60px;font-size:10px; font-family:Tahoma;">&nbsp;</td>
					 <? }
					    else 
						{ ?>
							<td align="center" style="width:60px;height:60px;font-size:10px; font-family:Tahoma;">HATCH</td>
				<? 		}
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
</tr>
</tbody>
</table>
</center>
<br/>
</div>


<!-- article-content -->
    <table align="center">
        <tr>
            <td width='140'></td>
            <td width='140'></td>
            <td width='140'></td>
            <td width='140'></td>
            <td width='210' align="center"><b>Tanjung Priok, <? echo date("d-m-Y");?></b></td>            
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="center"><font size="2" face="verdana"><b>DIVISI OPERASI TERMINAL II</b><br/>SUPERVISOR PERALATAN</font></td>
        </tr>
        <?php for($i=0; $i<=20; $i++){ ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php } ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="center">DWI WANTO</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="center">NIPP: 268085727</td>
        </tr>
    </table>