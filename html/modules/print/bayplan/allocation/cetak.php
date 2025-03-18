<?php
	$id_vs 	= $_GET['id'];
	$db 	= getDB();
	$row 	= $db->query("SELECT B.NAMA_VESSEL, A.VOYAGE, A.ID_VS
					      FROM VESSEL_SCHEDULE A, MASTER_VESSEL B
					      WHERE A.ID_VES = B.KODE_KAPAL AND A.ID_VS = '$id_vs'");
				
	$hasil_vv 	= $row->fetchRow();
	$vessel 	= $hasil_vv['NAMA_VESSEL'];
	$voyage 	= $hasil_vv['VOYAGE'];
	$id			= $hasil_vv['ID_VS'];
 ?>

<table align="center">
<tr>
	<td width="350" align="left"><img src="<?=HOME?>images/logopl.png" height="70%" width="70%" align="left" /></td>
	<td width="350" align="left"><img src="<?=HOME?>images/sgs2.png" height="21%" width="21%" align="right" valign="top" />
	<img src="<?=HOME?>images/sgs.gif" height="10%" width="10%" align="right" /></td>
</tr>
</table>   
<br>
<h2 align="center"><b>ALLOCATION BAYPLAN</b></h2>
<br>

<table align="center" width='720'>	
    <tr>
        <td align="left">Vessel / Voyage</td>
        <td>:</td>
        <td align="left"><font size="2" face="verdana"><b><? echo $vessel." / ".$voyage; ?></b></font></td>
    </tr>    
</table>

	<p style="width:400%;">
	<div align="center" style="background-color:white;">
	<table width="720" cellspacing="3" border="0">
	<tbody>
	<tr>
	<?
		$db		   = getDB();
		$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id'";
		$result_   = $db->query($query_bay);
		$bay_      = $result_->fetchRow();
			
		$jumlah_row 	= $bay_['JML_ROW'];
		$jml_tier_under = $bay_['JML_TIER_UNDER'];
		$jml_tier_on 	= $bay_['JML_TIER_ON'];
		$width 			= $jumlah_row+1;
			
		$query_cell3 = "SELECT ID,BAY FROM STW_BAY_AREA WHERE ID_VS = '$id' AND ROWNUM<11 ORDER BY BAY ASC";
		$result8     = $db->query($query_cell3);
		$blok8       = $result8->getAll();
			 
		foreach ($blok8 as $row18)
		{
			$id_area = $row18['ID'];
			$bay_name = $row18['BAY'];
			
			if(($bay_name==1)||(($bay_name-1)%4==0))
			{
		?>
				<td valign="bottom" colspan="4" align="center">
				<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
				<tbody>	
					<tr>
						<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
						<font size="1px"><b>Bay 
						<? 	if ($bay_name==1) 
							{ 
								echo $bay_name;?>(<? echo $bay_name+1; ?>)
						<? 	} 
							else if (($bay_name-1)%4==0) 
							{  
								echo $bay_name;?>(<? echo $bay_name+1; ?>) 
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
		 
					$query_cell2 = "SELECT CELL_NUMBER,ROW_,TIER_,STATUS_STACK FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$id_area' 
									ORDER BY CELL_NUMBER ASC";
					$result3     = $db->query($query_cell2);
					$blok2       = $result3->getAll();
					$n='';
					$br='';
					$tr='';
					foreach ($blok2 as $row8)
					{
						$index = $row8['CELL_NUMBER']+1;
						$cell_address = $index-1;
						$br = $n;
						$tr = $row8['TIER_'];
						$n = $tr;
						$rw = $row8['ROW_'];
			
						if ($index%$width != 0) 
						{
							if ($row8['STATUS_STACK'] == 'A')
							{ 
								$cek_alokasi  = "SELECT SIZE_,TYPE_,STATUS_,HZ_,IMO FROM STW_BAY_ALLOCATION WHERE ID_BAY_AREA = '$id_area' 
												 AND CELL_NUMBER = '$cell_address'";
								$result15  = $db->query($cek_alokasi);
								$alokasi_cek  = $result15->fetchRow();
					
								$cek_tipe   = $alokasi_cek['TYPE_'];
								$cek_size   = $alokasi_cek['SIZE_'];
								$cek_status = $alokasi_cek['STATUS_'];
								$cek_hz  	= $alokasi_cek['HZ_'];
								$cek_imo 	= $alokasi_cek['IMO'];
					
								if($cek_status=='MTY')
								{
					?>						
									<div id="x" class="drag blue">
									<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;">
									<img src="<?=HOME?>images/cont_print/EMPTY.png" height="8" width="9" /></td>
									</div>                
								<?
								}
								else if($cek_hz=='Y')
								{
								?>		
									<div id="x" class="drag blue">
									<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;">
									<img src="<?=HOME?>images/cont_print/CLASS.png" height="8" width="9" /></td>
									</div>
								<?	
								}
								else if($cek_tipe=='RFR')
								{
								?>	
									<div id="x" class="drag blue">
									<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;">
									<img src="<?=HOME?>images/cont_print/REEFER.png" height="8" width="9" /></td>
									</div>
								<?	
								}
								else
								{
								?>			   
									<div id="x" class="drag blue">
									<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;"><img src="<?=HOME?>images/cont_print/full.png" height="8" width="9" /></td>
									</div>		   
								<?
								}
							}
							else if ($row8['STATUS_STACK'] == 'P')
							{ ?>				
								<div id="x" class="drag blue">
								<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" 
								style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#FF0000; ">&nbsp;</td>
								</div>             
							<? 
							}
							else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
							{
							?>
								<div id="x" class="drag blue">
								<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;">
								<img src="<?=HOME?>images/cont_print/hatch.png" height="8" width="9"/>						
								</td>
								</div>	
							<?
							}
							else if ($row8['STATUS_STACK'] == 'N')
							{
								if(($index>=1)&&($index<$width)) 
								{
								?>
									<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
						<? 		}
								else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) 
								{
								?>
									<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
						<? 		}
								else
								{
						?>
									<div id="x" class="drag blue">
									<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;"><img src="<?=HOME?>images/cont_print/kosong.png" height="8" width="9"/>
									</td>
									</div>
					<? 			} 
							} 
						}			
			
						else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
						{ 	?>					
							<div id="x" class="drag blue">
							<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
							<?=$br?>
							</td>
							</div>
						</tr>
					<? 	}
						else if ($index%$width == 0)
						{ ?>
							<div id="x" class="drag blue">                    
					<? 		if ($br != 0)
							{ 
								if ($index==$width)
								{ ?>
									<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
						 <? 	}
								else 
								{
								?>
									<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><?=$br;?></td>
								<? 
								} 
							}
							else
							{  
								if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
								{ ?>
									<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
							 <? }
								else 
								{ ?>
									<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">HATCH</td>
							<?  }
							} ?>
							</div>
		</tr>				
			<?
						}
					}
			?>
		
				</tbody>
				</table>

<? 			} 
	} ?>

<tr>
<?
	$db = getDB();
	$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id'";
	$result_   = $db->query($query_bay);
	$bay_      = $result_->fetchRow();
			
	$jumlah_row = $bay_['JML_ROW'];
	$jml_tier_under = $bay_['JML_TIER_UNDER'];
	$jml_tier_on = $bay_['JML_TIER_ON'];
	$width = $jumlah_row+1;
			
	$query_cell3 = "SELECT ID,BAY FROM STW_BAY_AREA WHERE ID_VS = '$id' AND ROWNUM<11 ORDER BY BAY ASC";
	$result8    = $db->query($query_cell3);
	$blok8      = $result8->getAll();
			 
	foreach ($blok8 as $row18)
	{
		$id_area = $row18['ID'];
		$bay_name = $row18['BAY'];
			
		if(($bay_name==3)||(($bay_name-1)%4!=0))
		{
?>
			<td valign="bottom" colspan="4" align="center">
			<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
			<tbody>	
			<tr>
				<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay 
				<? if ($bay_name==1) { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if (($bay_name-1)%4==0) { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
				<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
			</tr>
			<tr>   
         <?php
		 
            $query_cell2 = "SELECT CELL_NUMBER,ROW_,TIER_,STATUS_STACK FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$id_area' ORDER BY CELL_NUMBER ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
            $n='';
			$br='';
			$tr='';
			foreach ($blok2 as $row8)
			{
            	$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
			
				if ($index%$width != 0) 
				{
					if ($row8['STATUS_STACK'] == 'A')
					{ 
						$cek_alokasi  = "SELECT SIZE_,TYPE_,STATUS_,HZ_,IMO FROM STW_BAY_ALLOCATION WHERE ID_BAY_AREA = '$id_area' 
										 AND CELL_NUMBER = '$cell_address'";
						$result15  = $db->query($cek_alokasi);
						$alokasi_cek  = $result15->fetchRow();
					
						$cek_tipe  = $alokasi_cek['TYPE_'];
						$cek_size  = $alokasi_cek['SIZE_'];
						$cek_status = $alokasi_cek['STATUS_'];
						$cek_hz  = $alokasi_cek['HZ_'];
						$cek_imo  = $alokasi_cek['IMO'];
					
						if($cek_status=='MTY')
						{
						?>			
				
							<div id="x" class="drag blue">
							<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" 
							style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#66FF33; ">
							<img src="<?=HOME?>images/cont_print/EMPTY.png" height="8" width="9" /></td>
							</div>
                  
					<?
						}
						else if($cek_hz=='Y')
						{
					?>		
							<div id="x" class="drag blue">
							<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" 
							style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#66FF33; ">
							<img src="<?=HOME?>images/cont_print/CLASS.png" height="8" width="9" /></td>
							</div>
					<?	
						}
						else if($cek_tipe=='RFR')
						{
					?>	
						<div id="x" class="drag blue">
						<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" 
						style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#66FF33; ">
						<img src="<?=HOME?>images/cont_print/REEFER.png" height="8" width="9" /></td>
						</div>
				<?	
						}
						else
						{
			   ?>			   
							<div id="x" class="drag blue">
							<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" 
							style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#66FF33; "><img src="<?=HOME?>images/cont_print/full.png" height="8" width="9" /></td>
							</div>		   
			   <?
						}
					}
					else if ($row8['STATUS_STACK'] == 'P')
					{ ?>			
						<div id="x" class="drag blue">
						<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" 
						style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#FF0000; ">&nbsp;</td>
						</div>                 
               <? 
					}
					else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
					{
			   ?>
						<div id="x" class="drag blue">
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;">
						<img src="<?=HOME?>images/cont_print/hatch.png" height="8" width="9"/>
						</td>
						</div>	
			   <?
					}
					else if ($row8['STATUS_STACK'] == 'N')
					{  
							if(($index>=1)&&($index<$width)) 
							{
						?>
								<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
						<? 	}
							else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) 
							{
					?>
								<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
						<?  }
							else
							{
					?>
								<div id="x" class="drag blue">
								<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;"><img src="<?=HOME?>images/cont_print/kosong.png" height="8" width="9"/>
								</td>
								</div>
					<? 		} 	
					} 
				}			
				else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
				{ 	?>					
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
					<?=$br?>
					</td>
                    </div>
			</tr>
			  <?}
			else if ($index%$width == 0)
			{ ?>
				<div id="x" class="drag blue">                    
					<? if ($br != 0)
					   { 
						 if ($index==$width)
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						  else {
						 ?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  
						  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						    else { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">HATCH</td>
					<? }
						} ?>
                </div>
					</tr>				
			<?
				}
            }
			?>
		
		</tbody>
		</table>

<? 		} 
	} ?>

	<tr>
		<?
			$db = getDB();
			$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id'";
			$result_   = $db->query($query_bay);
			$bay_      = $result_->fetchRow();
			
			$jumlah_row = $bay_['JML_ROW'];
			$jml_tier_under = $bay_['JML_TIER_UNDER'];
			$jml_tier_on = $bay_['JML_TIER_ON'];
			$width = $jumlah_row+1;
			
			$query_cell3 = "SELECT ID,BAY FROM STW_BAY_AREA WHERE ID_VS = '$id' AND BAY>20 ORDER BY BAY ASC";
			$result8    = $db->query($query_cell3);
			$blok8      = $result8->getAll();
			 
			foreach ($blok8 as $row18){
			$id_area = $row18['ID'];
			$bay_name = $row18['BAY'];
			
			if(($bay_name==1)||(($bay_name-1)%4==0))
			{
		?>
				<td valign="bottom" colspan="4" align="center">
				<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
				<tbody>	
					<tr>
						<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
						<font size="1px"><b>Bay 
						<? 	if ($bay_name==1) 
							{ 
								echo $bay_name;?>(<? echo $bay_name+1; ?>)
						<? 	} 
							else if (($bay_name-1)%4==0) 
							{  
								echo $bay_name;?>(<? echo $bay_name+1; ?>) 
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
		 
					$query_cell2 = "SELECT CELL_NUMBER,ROW_,TIER_,STATUS_STACK FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$id_area' ORDER BY CELL_NUMBER ASC";
					$result3    = $db->query($query_cell2);
					$blok2      = $result3->getAll();
					$n='';
					$br='';
					$tr='';
					foreach ($blok2 as $row8)
					{
						$index = $row8['CELL_NUMBER']+1;
						$cell_address = $index-1;
						$br = $n;
						$tr = $row8['TIER_'];
						$n = $tr;
						$rw = $row8['ROW_'];
			
						if ($index%$width != 0) 
						{
							if ($row8['STATUS_STACK'] == 'A')
							{ 
								$cek_alokasi  = "SELECT SIZE_,TYPE_,STATUS_,HZ_,IMO FROM STW_BAY_ALLOCATION WHERE ID_BAY_AREA = '$id_area' 
												 AND CELL_NUMBER = '$cell_address'";
								$result15  = $db->query($cek_alokasi);
								$alokasi_cek  = $result15->fetchRow();
					
								$cek_tipe   = $alokasi_cek['TYPE_'];
								$cek_size   = $alokasi_cek['SIZE_'];
								$cek_status = $alokasi_cek['STATUS_'];
								$cek_hz  	= $alokasi_cek['HZ_'];
								$cek_imo 	= $alokasi_cek['IMO'];
					
								if($cek_status=='MTY')
								{
					?>						
									<div id="x" class="drag blue">
									<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" 
									style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#66FF33; ">
									<img src="<?=HOME?>images/cont_print/EMPTY.png" height="8" width="9" /></td>
									</div>                
								<?
								}
								else if($cek_hz=='Y')
								{
								?>		
									<div id="x" class="drag blue">
									<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" 
									style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#66FF33; ">
									<img src="<?=HOME?>images/cont_print/CLASS.png" height="8" width="9" /></td>
									</div>
								<?	
								}
								else if($cek_tipe=='RFR')
								{
								?>	
									<div id="x" class="drag blue">
									<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" 
									style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#66FF33; ">
									<img src="<?=HOME?>images/cont_print/REEFER.png" height="8" width="9" /></td>
									</div>
								<?	
								}
								else
								{
								?>			   
									<div id="x" class="drag blue">
									<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" 
									style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#66FF33; "><img src="<?=HOME?>images/cont_print/full.png" height="8" width="9" /></td>
									</div>		   
								<?
								}
							}
							else if ($row8['STATUS_STACK'] == 'P')
							{ ?>				
								<div id="x" class="drag blue">
								<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" 
								style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#FF0000; ">&nbsp;</td>
								</div>             
							<? 
							}
							else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
							{
							?>
								<div id="x" class="drag blue">
								<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;">
								<img src="<?=HOME?>images/cont_print/hatch.png" height="8" width="9"/>					
								</td>
								</div>	
							<?
							}
							else if ($row8['STATUS_STACK'] == 'N')
							{
								if(($index>=1)&&($index<$width)) 
								{
								?>
									<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
						<? 		}
								else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) 
								{
								?>
									<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
						<? 		}
								else
								{
						?>
									<div id="x" class="drag blue">
									<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;"><img src="<?=HOME?>images/cont_print/kosong.png" height="8" width="9"/>
									</td>
									</div>
					<? 			} 
							} 
						}			
			
						else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
						{ 	?>					
							<div id="x" class="drag blue">
							<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
							<?=$br?>
							</td>
							</div>
						</tr>
					<? 	}
						else if ($index%$width == 0)
						{ ?>
							<div id="x" class="drag blue">                    
					<? 		if ($br != 0)
							{ 
								if ($index==$width)
								{ ?>
									<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
						 <? 	}
								else 
								{
								?>
									<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><?=$br;?></td>
								<? 
								} 
							}
							else
							{  
								if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
								{ ?>
									<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
							 <? }
								else 
								{ ?>
									<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">HATCH</td>
							<?  }
							} ?>
							</div>
		</tr>				
			<?
						}
					}
			?>
		
				</tbody>
				</table>

<? 			} 
	} ?>

<tr>
<?
	$db = getDB();
	$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id'";
	$result_   = $db->query($query_bay);
	$bay_      = $result_->fetchRow();
			
	$jumlah_row = $bay_['JML_ROW'];
	$jml_tier_under = $bay_['JML_TIER_UNDER'];
	$jml_tier_on = $bay_['JML_TIER_ON'];
	$width = $jumlah_row+1;
			
	$query_cell3 = "SELECT ID,BAY FROM STW_BAY_AREA WHERE ID_VS = '$id' AND BAY>20 ORDER BY BAY ASC";
	$result8    = $db->query($query_cell3);
	$blok8      = $result8->getAll();
			 
	foreach ($blok8 as $row18)
	{
		$id_area = $row18['ID'];
		$bay_name = $row18['BAY'];
			
		if(($bay_name==3)||(($bay_name-1)%4!=0))
		{
?>
			<td valign="bottom" colspan="4" align="center">
			<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
			<tbody>	
			<tr>
				<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay 
				<? if ($bay_name==1) { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if (($bay_name-1)%4==0) { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
				<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
			</tr>
			<tr>   
         <?php
		 
            $query_cell2 = "SELECT CELL_NUMBER,ROW_,TIER_,STATUS_STACK FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$id_area' ORDER BY CELL_NUMBER ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
            $n='';
			$br='';
			$tr='';
			foreach ($blok2 as $row8)
			{
            	$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
			
				if ($index%$width != 0) 
				{
					if ($row8['STATUS_STACK'] == 'A')
					{ 
						$cek_alokasi  = "SELECT SIZE_,TYPE_,STATUS_,HZ_,IMO FROM STW_BAY_ALLOCATION WHERE ID_BAY_AREA = '$id_area' 
										 AND CELL_NUMBER = '$cell_address'";
						$result15  = $db->query($cek_alokasi);
						$alokasi_cek  = $result15->fetchRow();
					
						$cek_tipe  = $alokasi_cek['TYPE_'];
						$cek_size  = $alokasi_cek['SIZE_'];
						$cek_status = $alokasi_cek['STATUS_'];
						$cek_hz  = $alokasi_cek['HZ_'];
						$cek_imo  = $alokasi_cek['IMO'];
					
						if($cek_status=='MTY')
						{
						?>			
				
							<div id="x" class="drag blue">
							<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" 
							style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#66FF33; ">
							<img src="<?=HOME?>images/cont_print/EMPTY.png" height="8" width="9" /></td>
							</div>
                  
					<?
						}
						else if($cek_hz=='Y')
						{
					?>		
							<div id="x" class="drag blue">
							<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" 
							style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#66FF33; ">
							<img src="<?=HOME?>images/cont_print/CLASS.png" height="8" width="9" /></td>
							</div>
					<?	
						}
						else if($cek_tipe=='RFR')
						{
					?>	
						<div id="x" class="drag blue">
						<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" 
						style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#66FF33; ">
						<img src="<?=HOME?>images/cont_print/REEFER.png" height="8" width="9" /></td>
						</div>
				<?	
						}
						else
						{
			   ?>			   
							<div id="x" class="drag blue">
							<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" 
							style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#66FF33; "><img src="<?=HOME?>images/cont_print/full.png" height="8" width="9" /></td>
							</div>		   
			   <?
						}
					}
					else if ($row8['STATUS_STACK'] == 'P')
					{ ?>			
						<div id="x" class="drag blue">
						<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" 
						style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;background-color:#FF0000; ">&nbsp;</td>
						</div>                 
               <? 
					}
					else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
					{
			   ?>
						<div id="x" class="drag blue">
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;">
						<img src="<?=HOME?>images/cont_print/hatch.png" height="8" width="9"/>
						
						</td>
						</div>	
			   <?
					}
					else if ($row8['STATUS_STACK'] == 'N')
					{  
							if(($index>=1)&&($index<$width)) 
							{
						?>
								<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
						<? 	}
							else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) 
							{
					?>
								<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
						<?  }
							else
							{
					?>
								<div id="x" class="drag blue">
								<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:0px solid #000000;"><img src="<?=HOME?>images/cont_print/kosong.png" height="8" width="9"/>
								</td>
								</div>
					<? 		} 	
					} 
				}			
				else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
				{ 	?>					
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
					<?=$br?>
					</td>
                    </div>
			</tr>
			  <?}
			else if ($index%$width == 0)
			{ ?>
				<div id="x" class="drag blue">                    
					<? if ($br != 0)
					   { 
						 if ($index==$width)
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						  else {
						 ?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  
						  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						    else { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">HATCH</td>
					<? }
						} ?>
                </div>
					</tr>				
			<?
				}
            }
			?>
		
		</tbody>
		</table>

<? 		} 
	} ?>
	
		</tbody>
		</table>
<!--</div>-->
		</p></div>
    </center>
</fieldset>





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
