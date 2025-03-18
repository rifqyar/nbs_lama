<div id="bay_alokasi">
<br>
<br>
<center>
<table width="80%" cellspacing="3" border="0">
<tbody>
<?
		$db = getDB();
		$posisi = $_GET['posisi'];
		$id_vs = $_GET['id_vs'];
		$bay_area = $_GET['bay_area'];
		$no_bay = $_GET['no_bay'];
        $query_bay = "SELECT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID = '$bay_area'";
        $result_   = $db->query($query_bay);
        $bay_      = $result_->fetchRow();
		
        $jumlah_row = $bay_['JML_ROW'];
        $jml_tier_under = $bay_['JML_TIER_UNDER'];
		$jml_tier_on = $bay_['JML_TIER_ON'];
		$width = $jumlah_row+1;
	?>
<td valign="bottom" colspan="4" align="center">
<form id="form_height" method="post" action="<?=HOME?>planning.bay_allocation.ajax/set_height?id_vs=<?=$id_vs?>&bay_area=<?=$bay_area?>&jml_row=<?=$jumlah_row?>&posisi=<?=$posisi?>&no_bay=<?=$no_bay?>">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	   
       <tr>   
         <?php
            
			if($posisi=='below')
			{
				$query_cell2 = "SELECT CELL_NUMBER,ROW_,TIER_,STATUS_STACK,PLUGGING,POSISI_STACK FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' AND POSISI_STACK IN ('BELOW','HATCH') ORDER BY CELL_NUMBER ASC";
				$result3    = $db->query($query_cell2);
				$blok2      = $result3->getAll();
			}
			else
			{
				$query_cell2 = "SELECT CELL_NUMBER,ROW_,TIER_,STATUS_STACK,PLUGGING,POSISI_STACK FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' AND POSISI_STACK IN ('ABOVE','HATCH') ORDER BY CELL_NUMBER ASC";
				$result3    = $db->query($query_cell2);
				$blok2      = $result3->getAll();
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
				$pss = $row8['POSISI_STACK'];
				//echo $tr;			
				           
			if ($index%$width != 0) 
			{
				if ((($row8['STATUS_STACK'] == 'A')||($row8['STATUS_STACK'] == 'P')||($row8['STATUS_STACK'] == 'R'))&&($row8['PLUGGING'] == 'T'))
				{ 						  
				 ?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
                    </div>
			<?
			   }
			   else if ((($row8['STATUS_STACK'] == 'A')||($row8['STATUS_STACK'] == 'P')||($row8['STATUS_STACK'] == 'R'))&&($row8['PLUGGING'] == 'Y'))
				{ 						  
				 ?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">O</td>
                    </div>
			<?
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
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
						<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; ">
						<? if($height_max=="") { ?>
						<input name="row<?=$rw?>" size="1" style="background-color:#FFFF66;"/>
						<? } else { ?>
							<b><? echo $height_max/100; ?></b>
						<? } ?><br/>
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
						<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; "><?=$rw;?>
						<? if($height_max=="") { ?>
						<br/><input name="row<?=$rw?>" size="1" style="background-color:#FFFF66;"/>
						<? } else { ?>
							<br/><b><? echo $height_max/100;?></b>
						<? } ?>
						</td>
					<? }
						else
					   {
					?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; background-color:#FFF; ">
					&nbsp;
					</td>
                    </div>
					<? } 
				} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>					
					<div id="x" class="drag blue">
                    <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">
					<?
						if($br==0)
						{
					?>	
							HATCH
					<?
						}
						else
						{
							echo $br;
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
						 <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						  else if ($pss=="HATCH")
						  {
						 ?> 
						 <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">HATCH</td>
						 <?
						  }
						  else {
						 ?>
						<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  
						  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						 { ?>
						 <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						    else { ?>
						 <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">HATCH</td>
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
</form>
</td>
</tr>
</tbody>
</table>
</center>
<br/>
<input type="button" name="reset_alokasi" value="Reset Alokasi" onclick="reset_alokasi('<?=$id_vs?>','<?=$bay_area?>','<?=$no_bay?>','<?=$posisi?>')" />
<? if($height_max=="") { ?>&nbsp;&nbsp;&nbsp;
<input type="button" name="submit_capacity" value="Set Height Capacity" onclick="submit_capacity('<?=$jumlah_row?>')" />
<? } ?>
<br/>
</div>