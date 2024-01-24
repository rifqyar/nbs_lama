<div id="bay_alokasi">
<br>
<br>
<center>
<table width="100%" cellspacing="3" border="0">
<tbody>
<?
		$db = getDB();
		$posisi = $_GET['posisi'];
		$id_vs = $_GET['id_vs'];
		$bay_area = $_GET['bay_area'];
		$no_bay = $_GET['no_bay'];
        $query_bay = "SELECT JML_ROW,JML_TIER_UNDER,JML_TIER_ON,OCCUPY FROM STW_BAY_AREA WHERE ID = '$bay_area'";
        $result_ = $db->query($query_bay);
        $bay_ = $result_->fetchRow();
		
		$occ = $bay_['OCCUPY'];
        $jumlah_row = $bay_['JML_ROW'];
        $jml_tier_under = $bay_['JML_TIER_UNDER'];
		$jml_tier_on = $bay_['JML_TIER_ON'];
		$width = $jumlah_row+1;
	
	
	
	?>
<td valign="bottom" colspan="4" align="center">
<?
	$db=getDb();
	$query_alat	="SELECT ID_ALAT, NAMA_ALAT FROM MASTER_ALAT WHERE PENEMPATAN='BAY'";
				
	$result_alat= $db->query($query_alat);
	$row_alat	= $result_alat->getAll();
?>
	<b>Alat</b>&nbsp;			
	<select id="alat" name="alat">
<?	
	foreach ($row_alat as $r_alt)
	{
		$id_alt=$r_alt['ID_ALAT'];
		$name_alt=$r_alt['NAMA_ALAT'];
		echo "<option value=".$id_alt.">".$name_alt."</option>";
	}
?>
	</select>&nbsp;&nbsp;&nbsp;<b>OP Alat</b>&nbsp;
	<select id="op_alat" name="op_alat">
		<option value='OP.OJA'>OP OJA</option>
		<option value='OP.TSJ'>OP TSJ</option>
	</select>
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	   
       <tr>   
         <?php
            
			if($posisi=='below')
			{
				$query_cell2 = "SELECT ID,CELL_NUMBER,ROW_,TIER_,STATUS_STACK,PLUGGING FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' AND POSISI_STACK IN ('BELOW','HATCH') ORDER BY CELL_NUMBER ASC";
				$result3    = $db->query($query_cell2);
				$blok2      = $result3->getAll();
			}
			else
			{
				$query_cell2 = "SELECT ID,CELL_NUMBER,ROW_,TIER_,STATUS_STACK,PLUGGING FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' AND POSISI_STACK IN ('ABOVE','HATCH') ORDER BY CELL_NUMBER ASC";
				$result3    = $db->query($query_cell2);
				$blok2      = $result3->getAll();
				//echo 'coba';
			}
			
            $n='';
			$br='';
			$tr='';
			// debug($blok2);die;
            foreach ($blok2 as $row8){
                //echo $row['INDEX_CELL'];
				$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
				$id_cellx = $row8['ID'];
				//echo $tr;			
				
				$cek_cont = "SELECT A.BAY,
									A.BAY||'-'||A.ROW_||'-'||A.TIER_ AS BRT,
									A.NO_CONTAINER,
									A.SIZE_,
									CASE WHEN TRIM(A.TYPE_)='DRY' THEN 'DC'
										 WHEN TRIM(A.TYPE_)='HQ' THEN 'HC'
										 WHEN TRIM(A.TYPE_)='TNK' THEN 'TK'
										 WHEN TRIM(A.TYPE_)='RFR' THEN 'RF'
								    END TYPE_CONT,
									A.SIZE_||'/'||TRIM(A.TYPE_)||'/'||A.STATUS_ AS SPEC_CONT,
									TRIM(A.STATUS_) AS ST,
									substr(A.ID_PEL_ASAL,3,3) AS POL,
									substr(A.ID_PEL_TUJ,3,3) AS POD,
									A.CARRIER,
									RPAD(round((A.GROSS/1000),2),4,0) AS GROSS,
									round((B.H_ISO*30.48)) AS HEIGHT
							FROM STW_PLACEMENT_BAY A, MASTER_ISO_CODE B 
								WHERE A.ID_CELL = '$id_cellx'
								AND A.ACTIVITY = 'BONGKAR'
								AND B.ISO_CODE = A.ISO_CODE";
					$result_cont = $db->query($cek_cont);
					$cont_ = $result_cont->fetchRow();
									
					$nocont = $cont_['NO_CONTAINER'];
					$pol = $cont_['POL'];
					$pod = $cont_['POD'];
					$carrier = $cont_['CARRIER'];
					$gross = $cont_['GROSS'];
					$sz = $cont_['SIZE_'];
					$ty = $cont_['TYPE_CONT'];
					$ht = $cont_['HEIGHT'];
					$by = $cont_['BAY'];
					$spec = $cont_['SPEC_CONT'];
					$brt = $cont_['BRT'];
					$st = $cont_['ST'];
					
					//echo $st;
				           
			if ($index%$width != 0) 
			{
				//echo $nocont;
				//echo ' status stack= '.$row8['STATUS_STACK'];
				//echo ' plugging = '.$row8['PLUGGING'];
				//please read sentence below
				//status stack hanya berlaku untuk loading atau kegiatan muat. Pada kegiatan bongkar seharusmya diabaikan
				
				if ((($row8['STATUS_STACK'] == 'A')||($row8['STATUS_STACK'] == 'P')||($row8['STATUS_STACK'] == 'R'))&&($row8['PLUGGING'] == 'T'))
				{
					
					if($nocont!=NULL)
					{
						if((($sz=='40')||($sz=='45'))&&($occ=='T'))
						{
			?>
							<div id="x" class="drag blue">
							<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#C2EBFF'" align="center" style="width:50px;height:50px;font-size:7px; color:#FF0000; font-family:Tahoma; border:1px solid #000000;background-color:#C2EBFF; ">
							<b>+++</b>
							</td>
							</div>
			<?			
						}
						else
						{
							/*if($pod!='JKT')
							{
			?>				
								<div id="x" class="drag blue">
								<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#E3E3EC'" align="center" style="width:50px;height:50px;font-size:7px; color:#FF0000; font-family:Tahoma; border:1px solid #000000;background-color:#E3E3EC; ">
								<?=$pol?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$pod?><br/>
								<b><?=$nocont?></b><br/>
								<?=$carrier?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$gross?><br/>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$sz?><?=$ty?><br/>
								<?=$ht?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</td>
								</div>
			<?				
							}
							else
							{*/
		    ?>
								<div id="x" class="drag blue">
								<td onclick="v_confirm_disch('<?=$id_vs?>','<?=$nocont?>','<?=$spec?>','<?=$brt?>','<?=$st?>')" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#C2EBFF'" align="center" style="width:50px;height:50px;font-size:7px; color:#FF0000; font-family:Tahoma; border:1px solid #000000;background-color:#C2EBFF; ">
								<?=$pol?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$pod?><br/>
								<b><?=$nocont?></b><br/>
								<?=$carrier?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$gross?><br/>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$sz?><?=$ty?><br/>
								<?=$ht?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</td>
								</div>
			<?
							//}
						}
					}
					else
					{
			?>		
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:50px;height:50px;font-size:6px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;
					
					</td>
                    </div>	
			<?		
					}
			   }
			   else if ((($row8['STATUS_STACK'] == 'A')||($row8['STATUS_STACK'] == 'P'))&&($row8['PLUGGING'] == 'Y'))
			   {
					if($nocont!=NULL)
					{
						//echo 'coba';
						if((($sz=='40')||($sz=='45'))&&($by!=1)&&(($by-1)%4!=0))
						{
			?>
							<div id="x" class="drag blue">
							<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#C2EBFF'" align="center" style="width:50px;height:50px;font-size:7px; color:#FF0000; font-family:Tahoma; border:1px solid #000000;background-color:#C2EBFF; ">
							<b>+++</b>
							</td>
							</div>
			<?			
						}
						else
						{
							/*if($pod!='JKT')
							{
			?>				
								<div id="x" class="drag blue">
								<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#E3E3EC'" align="center" style="width:50px;height:50px;font-size:7px; color:#FF0000; font-family:Tahoma; border:1px solid #000000;background-color:#E3E3EC; ">
								<?=$pol?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$pod?><br/>
								<b><?=$nocont?></b><br/>
								<?=$carrier?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$gross?><br/>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$sz?><?=$ty?><br/>
								<?=$ht?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</td>
								</div>
			<?				
							}
							else
							{*/
		    ?>
								<div id="x" class="drag blue">
								<td onclick="v_confirm_disch('<?=$id_vs?>','<?=$nocont?>','<?=$spec?>','<?=$brt?>','<?=$st?>')" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#C2EBFF'" align="center" style="width:50px;height:50px;font-size:7px; color:#FF0000; font-family:Tahoma; border:1px solid #000000;background-color:#C2EBFF; ">
								<?=$pol?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$pod?><br/>
								<b><?=$nocont?></b><br/>
								<?=$carrier?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$gross?><br/>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$sz?><?=$ty?><br/>
								<?=$ht?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</td>
								</div>
			<?
							//}
						}
					}
					else
					{
			?>		
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:50px;height:50px;font-size:6px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;
					
					</td>
                    </div>	
			<?		
					}
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:50px;height:50px;font-size:7px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
                </div>	
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'N')
			   {
				if(($index>=1)&&($index<$width)) {
				
					$pss = strtoupper($posisi);
					$cek_capacity  = "SELECT HEIGHT_CAPACITY FROM STW_BAY_CAPACITY WHERE ID_BAY_AREA = '$bay_area' AND ROW_ = '$rw' AND POSISI_STACK = '$pss'";
					$result6  = $db->query($cek_capacity);
					$capacity_cek  = $result6->fetchRow();
					$height_max = $capacity_cek['HEIGHT_CAPACITY'];
				
					?>
						<td align="center" style="width:50px;height:50px;font-size:10px; font-family:Tahoma; ">
						<?=$rw;?>
						</td>
					<? }
						else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
						
						$pss = strtoupper($posisi);
						$cek_capacity  = "SELECT HEIGHT_CAPACITY FROM STW_BAY_CAPACITY WHERE ID_BAY_AREA = '$bay_area' AND ROW_ = '$rw' AND POSISI_STACK = '$pss'";
						$result6  = $db->query($cek_capacity);
						$capacity_cek  = $result6->fetchRow();
						$height_max = $capacity_cek['HEIGHT_CAPACITY'];
						
					?>
						<td align="center" style="width:50px;height:50px;font-size:10px; font-family:Tahoma; "><?=$rw;?>
						</td>
					<? }
						else
					   {
					?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:50px;height:50px;font-size:7px; font-family:Tahoma; background-color:#FFF; ">&nbsp;
					
					</td>
                    </div>
					<? } 
				} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>					
					<div id="x" class="drag blue">
                    <td align="center" style="width:50px;height:50px;font-size:10px; font-family:Tahoma;">
					<? 
					  if($br!=0)
					  {
					    echo $br;
					  }
					  else
					  {
						echo "HATCH";
					  }
					 ?>
					</td>
                    </div>
					</tr>
			  <? }
			else if ($index%$width == 0)
			{ ?>
				<div id="x" class="drag blue">                    
					<? if ($br != 0)
					   { 
						 if ($index==$width)
						 { ?>
						 <td align="center" style="width:50px;height:50px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }						 
						  else {
						 ?>
						<td align="center" style="width:50px;height:50px;font-size:10px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  
						  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						 { ?>
						 <td align="center" style="width:50px;height:50px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						    else { ?>
						 <td align="center" style="width:50px;height:50px;font-size:10px; font-family:Tahoma;">HATCH</td>
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
</td>
</tr>
</tbody>
</table>
</center>
<br/>
</div>