<div id="bay_alokasi">
<br>
<br>
<center>
<table width="80%" cellspacing="3" border="0">
<tbody>
<?
		$db = getDB();
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
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	   
       <tr>   
         <?php
            						
            $query_cell2 = "SELECT CELL_NUMBER,ROW_,TIER_,STATUS_STACK FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' ORDER BY CELL_NUMBER ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
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
				//echo $tr;			
            
			if ($index%$width != 0) 
			{
				if ($row8['STATUS_STACK'] == 'A')
				{ 
					$cek_alokasi  = "SELECT SIZE_,TYPE_,STATUS_,HZ_,IMO,STAT_ALOKASI FROM STW_BAY_ALLOCATION WHERE ID_BAY_AREA = '$bay_area' AND CELL_NUMBER = '$cell_address'";
					$result15  = $db->query($cek_alokasi);
					$alokasi_cek  = $result15->fetchRow();
					
					$cek_tipe  = $alokasi_cek['TYPE_'];
					$cek_size  = $alokasi_cek['SIZE_'];
					$cek_status = $alokasi_cek['STATUS_'];
					$cek_hz  = $alokasi_cek['HZ_'];
					$cek_imo  = $alokasi_cek['IMO'];
					$stat_alokasi = $alokasi_cek['STAT_ALOKASI'];
					
					if($cek_status=='MTY')
					{
					?>			
				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/EMPTY.png" height="18" width="20" /></td>
                     </div>
                  
               <?
					}
					else if($cek_hz=='Y')
					{
			   ?>	
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/CLASS.png" height="18" width="20" /></td>
                     </div>
			   <?	
					}
					else if($cek_tipe=='RFR')
					{
			   ?>	
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/REEFER.png" height="18" width="20" /></td>
                     </div>
			   <?	
					}
					else if($cek_tipe=='HC')
					{
			   ?>	
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/HC.png" height="18" width="20" /></td>
                     </div>
			   <?	
					}
					else if(($stat_alokasi=='40b')||($stat_alokasi=='40btop')||($stat_alokasi=='40bleft')||($stat_alokasi=='40bright'))
					{
			   ?>	
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/40FEETODD.png" height="18" width="20" /></td>
                     </div>
			   <?	
					}
					else if(($stat_alokasi=='40dtop')||($stat_alokasi=='top'))
					{
			   ?>	
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/OVDTOP.png" height="18" width="20" /></td>
                     </div>
			   <?	
					}
					else if(($stat_alokasi=='40dright')||($stat_alokasi=='right'))
					{
			   ?>	
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/OVDRIGHT.png" height="18" width="20" /></td>
                     </div>
			   <?	
					}
					else if(($stat_alokasi=='40dleft')||($stat_alokasi=='left'))
					{
			   ?>	
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/OVDLEFT.png" height="18" width="20" /></td>
                     </div>
			   <?	
					}
					else
					{
			   ?>
			   
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; ">&nbsp;</td>
                    </div>
			   
			   <?
					}
			   }
			   else if ($row8['STATUS_STACK'] == 'P')
				{ ?>
				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0000; ">&nbsp;</td>
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
			   {?>
                    <? if(($index>=1)&&($index<$width)) {
					?>
						<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else
					   {
					?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; border:1px solid #000000;background-color:#FFF; ">
					&nbsp;
					</td>
                    </div>
					<? } ?>
              <?} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>					
					<div id="x" class="drag blue">
                    <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">
					<?=$br?>
					</td>
                    </div>
					</tr>
				<tr>
				   <? for($f=1;$f<=$width;$f++) { ?>
					<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; border:1px solid #000000;"></td>
				   <? } ?>
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
						  else {
						 ?>
						<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  ?>
						<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">HATCH</td>
					<? } ?>
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
<input type="button" name="reset_alokasi" value="Reset Alokasi" onclick="reset_alokasi('<?=$id_vs?>','<?=$bay_area?>','<?=$no_bay?>')" />
</div>