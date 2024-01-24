<?		
	$db = getDB();
	$id_vs = $_GET['id'];
	$ves_voy = "SELECT NM_KAPAL,
					   VOYAGE_IN|| ' - ' ||VOYAGE_OUT AS VOYAGE
					FROM RBM_H
                    WHERE NO_UKK = '$id_vs'";
	$vvoy = $db->query($ves_voy);
	$hasil_vv = $vvoy->fetchRow();
	$vessel = $hasil_vv['NM_KAPAL'];
	$voyage = $hasil_vv['VOYAGE'];
?>
<center>
<h3><? echo $vessel." / ".$voyage; ?></h3>
<br/>
<div align="center">
<table width="100%" cellspacing="3" border="0">
<tbody>
<tr>
<?				
			$db = getDB();
			$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0";
			$result_   = $db->query($query_bay);
			$bay_      = $result_->fetchRow();
			
			$jumlah_row = $bay_['JML_ROW'];
			$jml_tier_under = $bay_['JML_TIER_UNDER'];
			$jml_tier_on = $bay_['JML_TIER_ON'];
			$width = $jumlah_row+1;
			
			$query_cell3 = "SELECT ID,BAY,OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0 ORDER BY ID DESC";
			$result8    = $db->query($query_cell3);
			$blok8      = $result8->getAll();
			 
			foreach ($blok8 as $row18){
			$id_area = $row18['ID'];
			$bay_name = $row18['BAY'];
			$occ2 = $row18['OCCUPY'];
			
			if(($occ2=='Y')&&($bay_name<24))
			{
?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	
	   <tr>
		<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay <? if ($occ2=='Y') { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if ($occ2=='Y') { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
		<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
	   </tr>
       <tr>   
         <?php
		 
            $query_cell2 = "SELECT ID,
								   CELL_NUMBER,
								   ROW_,
								   TIER_,
								   STATUS_STACK,
								   PLUGGING 
						    FROM STW_BAY_CELL 
							WHERE ID_BAY_AREA = '$id_area' 
							ORDER BY CELL_NUMBER ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
            $n='';
			$br='';
			$tr='';
			//debug($blok2);die;
			
            foreach ($blok2 as $row8){                
				$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
				$idx_cell = $row8['ID'];            
				
				//echo $index."_".$row8['STATUS_STACK']."<br/>";
				
				$cek_pol = "SELECT TRIM(ID_PEL_ASAL) AS POL,
								   ROUND((GROSS/1000),0) AS GROSS
						    FROM STW_PLACEMENT_BAY 
							WHERE ID_CELL = '$idx_cell'
								AND ACTIVITY = 'MUAT'";
				$hsl_pol = $db->query($cek_pol);
				$pol2    = $hsl_pol->fetchRow();
				$pol_bay = $pol2['POL'];
				$gross_bay = $pol2['GROSS'];
			
			if ($index%$width != 0) 
			{
				if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'T'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
                    </div>	
				<?
			   }
			   else if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">O</td>
                    </div>	
				<?
			   }
			   else if ($row8['STATUS_STACK'] == 'P')
				{ ?>
				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0000; "><?=$gross_bay?></td>
                     </div>
                  
               <? 
			   }
			   else if ($row8['STATUS_STACK'] == 'R')
				{
					if ($pol_bay<>'IDJKT')
					{
				?>				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#B84DB8'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#B84DB8; "><?=$gross_bay?></td>
                     </div>                  
               <? 	
					}
					else
					{
				?>				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#33CC33'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#33CC33; "><?=$gross_bay?></td>
                     </div>                  
               <? 
					}
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
                </div>	
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'N')
			   {	
					if(($index>=1)&&($index<$width)) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else
					   {
					?>
						<div id="x" class="drag blue">
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; background-color:#efedd9; ">
						&nbsp;
						</td>
						</div>
					<? } 
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
			  <? }
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
</td>
<? } } ?>
</tr>
<tr>
<?
			$db = getDB();
			$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0";
			$result_   = $db->query($query_bay);
			$bay_      = $result_->fetchRow();
			
			$jumlah_row = $bay_['JML_ROW'];
			$jml_tier_under = $bay_['JML_TIER_UNDER'];
			$jml_tier_on = $bay_['JML_TIER_ON'];
			$width = $jumlah_row+1;
			
			$query_cell3 = "SELECT ID,BAY,OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0 ORDER BY ID DESC";
			$result8    = $db->query($query_cell3);
			$blok8      = $result8->getAll();
			 
			foreach ($blok8 as $row18){
			$id_area = $row18['ID'];
			$bay_name = $row18['BAY'];
			$occ3 = $row18['OCCUPY'];
			
			if(($occ3=='T')&&($bay_name<24))
			{
?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	
	   <tr>
		<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay <? if ($occ3=='Y') { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if ($occ3=='Y') { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
		<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
	   </tr>
       <tr>   
         <?php
		 
            $query_cell2 = "SELECT ID,CELL_NUMBER,ROW_,TIER_,STATUS_STACK,PLUGGING FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$id_area' ORDER BY CELL_NUMBER ASC";
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
				$id_cell = $row8['ID'];
				//echo $tr;			
            
				$idx_cell = $row8['ID'];
            
				$cek_pol = "SELECT TRIM(ID_PEL_ASAL) AS POL,
								   ROUND((GROSS/1000),0) AS GROSS
						    FROM STW_PLACEMENT_BAY 
							WHERE ID_CELL = '$idx_cell'
								AND ACTIVITY = 'MUAT'";
				$hsl_pol = $db->query($cek_pol);
				$pol2    = $hsl_pol->fetchRow();
				$pol_bay = $pol2['POL'];
				$gross_bay = $pol2['GROSS'];
			
			if ($index%$width != 0) 
			{
				if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'T'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
                    </div>	
				<?
			   }
			   else if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">O</td>
                    </div>	
				<?
			   }
			   else if ($row8['STATUS_STACK'] == 'P')
				{
					$query_sz = "select SIZE_ from STW_PLACEMENT_BAY where ID_CELL = '$id_cell' and ACTIVITY = 'MUAT'";
					$hsl_sz = $db->query($query_sz);
					$sz_cont = $hsl_sz->fetchRow();
					$size_cont = $sz_cont['SIZE_'];
					
					if(($size_cont!='40')&&($size_cont!='45'))
					{
					
				?>
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0000; "><?=$gross_bay?></td>
                     </div>	           
               <? 
					}
					else
					{
					
				?>
							
                     <div id="x" class="drag blue">
					 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><b>+</b></td>
                     </div>
                  
               <? 	
					
					}
			   
			   }
			    else if ($row8['STATUS_STACK'] == 'R')
				{ 
					$query_sz = "select SIZE_ from STW_PLACEMENT_BAY where ID_CELL = '$id_cell'";
					$hsl_sz = $db->query($query_sz);
					$sz_cont = $hsl_sz->fetchRow();
					$size_cont3 = $sz_cont['SIZE_'];
					
					if(($size_cont3=='40')||($size_cont3=='45'))
					{
					
				?>
							
                     <div id="x" class="drag blue">
					 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><b>+</b></td>
                     </div>
                  
               <? 
					}
					else
					{
						if($pol_bayx<>'IDJKT')
						{
				?>
						 <div id="x" class="drag blue">
						 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#B84DB8'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#B84DB8; "><?=$gross_bay?></td>
						 </div>
				<?		
						}
						else
						{
				?>							
						 <div id="x" class="drag blue">
						 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#33CC33'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#33CC33; "><?=$gross_bay?></td>
						 </div>                  
               <?	
						}
					}
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
                </div>	
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'N')
			   {?>
                    <? if(($index>=1)&&($index<$width)) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else
					   {
					?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; background-color:#efedd9; ">
					&nbsp;
					</td>
                    </div>
					<? } ?>
              <?} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>					
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
					<?=$br?>
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
</td>
<? } } ?>
</tr>
<tr>
<?				
			$db = getDB();
			$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0";
			$result_   = $db->query($query_bay);
			$bay_      = $result_->fetchRow();
			
			$jumlah_row = $bay_['JML_ROW'];
			$jml_tier_under = $bay_['JML_TIER_UNDER'];
			$jml_tier_on = $bay_['JML_TIER_ON'];
			$width = $jumlah_row+1;
			
			$query_cell3 = "SELECT ID,BAY,OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0 ORDER BY ID DESC";
			$result8    = $db->query($query_cell3);
			$blok8      = $result8->getAll();
			 
			foreach ($blok8 as $row18){
			$id_area = $row18['ID'];
			$bay_name = $row18['BAY'];
			$occ2 = $row18['OCCUPY'];
			
			if(($occ2=='Y')&&($bay_name>24))
			{
?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	
	   <tr>
		<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay <? if ($occ2=='Y') { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if ($occ2=='Y') { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
		<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
	   </tr>
       <tr>   
         <?php
		 
            $query_cell2 = "SELECT ID,
								   CELL_NUMBER,
								   ROW_,
								   TIER_,
								   STATUS_STACK,
								   PLUGGING 
						    FROM STW_BAY_CELL 
							WHERE ID_BAY_AREA = '$id_area' 
							ORDER BY CELL_NUMBER ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
            $n='';
			$br='';
			$tr='';
			//debug($blok2);die;
			
            foreach ($blok2 as $row8){                
				$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
				$idx_cell = $row8['ID'];            
				
				//echo $index."_".$row8['STATUS_STACK']."<br/>";
				
				$cek_pol = "SELECT TRIM(ID_PEL_ASAL) AS POL,
								   ROUND((GROSS/1000),0) AS GROSS
						    FROM STW_PLACEMENT_BAY 
							WHERE ID_CELL = '$idx_cell'
								AND ACTIVITY = 'MUAT'";
				$hsl_pol = $db->query($cek_pol);
				$pol2    = $hsl_pol->fetchRow();
				$pol_bay = $pol2['POL'];
				$gross_bay = $pol2['GROSS'];
			
			if ($index%$width != 0) 
			{
				if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'T'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
                    </div>	
				<?
			   }
			   else if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">O</td>
                    </div>	
				<?
			   }
			   else if ($row8['STATUS_STACK'] == 'P')
				{ ?>
				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0000; "><?=$gross_bay?></td>
                     </div>
                  
               <? 
			   }
			   else if ($row8['STATUS_STACK'] == 'R')
				{
					if ($pol_bay<>'IDJKT')
					{
				?>				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#B84DB8'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#B84DB8; "><?=$gross_bay?></td>
                     </div>                  
               <? 	
					}
					else
					{
				?>				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#33CC33'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#33CC33; "><?=$gross_bay?></td>
                     </div>                  
               <? 
					}
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
                </div>	
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'N')
			   {	
					if(($index>=1)&&($index<$width)) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else
					   {
					?>
						<div id="x" class="drag blue">
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; background-color:#efedd9; ">
						&nbsp;
						</td>
						</div>
					<? } 
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
			  <? }
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
</td>
<? } } ?>
</tr>
<tr>
<?
			$db = getDB();
			$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0";
			$result_   = $db->query($query_bay);
			$bay_      = $result_->fetchRow();
			
			$jumlah_row = $bay_['JML_ROW'];
			$jml_tier_under = $bay_['JML_TIER_UNDER'];
			$jml_tier_on = $bay_['JML_TIER_ON'];
			$width = $jumlah_row+1;
			
			$query_cell3 = "SELECT ID,BAY,OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0 ORDER BY ID DESC";
			$result8    = $db->query($query_cell3);
			$blok8      = $result8->getAll();
			 
			foreach ($blok8 as $row18){
			$id_area = $row18['ID'];
			$bay_name = $row18['BAY'];
			$occ3 = $row18['OCCUPY'];
			
			if(($occ3=='T')&&($bay_name>24))
			{
?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	
	   <tr>
		<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay <? if ($occ3=='Y') { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if ($occ3=='Y') { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
		<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
	   </tr>
       <tr>   
         <?php
		 
            $query_cell2 = "SELECT ID,CELL_NUMBER,ROW_,TIER_,STATUS_STACK,PLUGGING FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$id_area' ORDER BY CELL_NUMBER ASC";
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
				$id_cell = $row8['ID'];
				//echo $tr;			
            
				$idx_cell = $row8['ID'];
            
				$cek_pol = "SELECT TRIM(ID_PEL_ASAL) AS POL,
								   ROUND((GROSS/1000),0) AS GROSS
						    FROM STW_PLACEMENT_BAY 
							WHERE ID_CELL = '$idx_cell'
								AND ACTIVITY = 'MUAT'";
				$hsl_pol = $db->query($cek_pol);
				$pol2    = $hsl_pol->fetchRow();
				$pol_bay = $pol2['POL'];
				$gross_bay = $pol2['GROSS'];
			
			if ($index%$width != 0) 
			{
				if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'T'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
                    </div>	
				<?
			   }
			   else if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">O</td>
                    </div>	
				<?
			   }
			   else if ($row8['STATUS_STACK'] == 'P')
				{
					$query_sz = "select SIZE_ from STW_PLACEMENT_BAY where ID_CELL = '$id_cell' and ACTIVITY = 'MUAT'";
					$hsl_sz = $db->query($query_sz);
					$sz_cont = $hsl_sz->fetchRow();
					$size_cont = $sz_cont['SIZE_'];
					
					if(($size_cont!='40')&&($size_cont!='45'))
					{
					
				?>
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0000; "><?=$gross_bay?></td>
                     </div>	           
               <? 
					}
					else
					{
					
				?>
							
                     <div id="x" class="drag blue">
					 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><b>+</b></td>
                     </div>
                  
               <? 	
					
					}
			   
			   }
			    else if ($row8['STATUS_STACK'] == 'R')
				{ 
					$query_sz = "select SIZE_ from STW_PLACEMENT_BAY where ID_CELL = '$id_cell'";
					$hsl_sz = $db->query($query_sz);
					$sz_cont = $hsl_sz->fetchRow();
					$size_cont3 = $sz_cont['SIZE_'];
					
					if(($size_cont3=='40')||($size_cont3=='45'))
					{
					
				?>
							
                     <div id="x" class="drag blue">
					 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><b>+</b></td>
                     </div>
                  
               <? 
					}
					else
					{
						if($pol_bayx<>'IDJKT')
						{
				?>
						 <div id="x" class="drag blue">
						 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#B84DB8'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#B84DB8; "><?=$gross_bay?></td>
						 </div>
				<?		
						}
						else
						{
				?>							
						 <div id="x" class="drag blue">
						 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#33CC33'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#33CC33; "><?=$gross_bay?></td>
						 </div>                  
               <?	
						}
					}
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
                </div>	
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'N')
			   {?>
                    <? if(($index>=1)&&($index<$width)) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else
					   {
					?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; background-color:#efedd9; ">
					&nbsp;
					</td>
                    </div>
					<? } ?>
              <?} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>					
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
					<?=$br?>
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
</td>
<? } } ?>
</tr>
</tbody>
</table>
</div>
</center>
<br/>
<?
	$cek_e_20 = "SELECT COUNT(NO_CONTAINER) AS JML_E_20
                FROM ISWS_LIST_CONTAINER
                WHERE TRIM(NO_UKK) = '$id_vs' 
                    AND E_I = 'E'
					AND TRIM(SIZE_) = '20'
					AND TRIM(STATUS) = 'MTY'
					AND KODE_STATUS > '51'";
	$hsl_e_20 = $db->query($cek_e_20);
	$hasil_e_20 = $hsl_e_20->fetchRow();
	$e_20 = $hasil_e_20['JML_E_20'];
	
	$cek_e_40 = "SELECT COUNT(NO_CONTAINER) AS JML_E_40
                FROM ISWS_LIST_CONTAINER
                WHERE TRIM(NO_UKK) = '$id_vs' 
                    AND E_I = 'E'
					AND TRIM(SIZE_) = '40'
					AND TRIM(STATUS) = 'MTY'
					AND KODE_STATUS > '51'";
	$hsl_e_40 = $db->query($cek_e_40);
	$hasil_e_40 = $hsl_e_40->fetchRow();
	$e_40 = $hasil_e_40['JML_E_40'];
	
	$cek_e_45 = "SELECT COUNT(NO_CONTAINER) AS JML_E_45
                FROM ISWS_LIST_CONTAINER
                WHERE TRIM(NO_UKK) = '$id_vs' 
                    AND E_I = 'E'
					AND TRIM(SIZE_) = '45'
					AND TRIM(STATUS) = 'MTY'
					AND KODE_STATUS > '51'";
	$hsl_e_45 = $db->query($cek_e_45);
	$hasil_e_45 = $hsl_e_45->fetchRow();
	$e_45 = $hasil_e_45['JML_E_45'];
	
	$cek_f_20 = "SELECT COUNT(NO_CONTAINER) AS JML_F_20
                FROM ISWS_LIST_CONTAINER
                WHERE TRIM(NO_UKK) = '$id_vs' 
                    AND E_I = 'E'
					AND TRIM(SIZE_) = '20'
					AND TRIM(STATUS) = 'FCL'
					AND KODE_STATUS > '51'";
	$hsl_f_20 = $db->query($cek_f_20);
	$hasil_f_20 = $hsl_f_20->fetchRow();
	$f_20 = $hasil_f_20['JML_F_20'];
	
	$cek_f_40 = "SELECT COUNT(NO_CONTAINER) AS JML_F_40
                FROM ISWS_LIST_CONTAINER
                WHERE TRIM(NO_UKK) = '$id_vs' 
                    AND E_I = 'E'
					AND TRIM(SIZE_) = '40'
					AND TRIM(STATUS) = 'FCL'
					AND KODE_STATUS > '51'";
	$hsl_f_40 = $db->query($cek_f_40);
	$hasil_f_40 = $hsl_f_40->fetchRow();
	$f_40 = $hasil_f_40['JML_F_40'];
	
	$cek_f_45 = "SELECT COUNT(NO_CONTAINER) AS JML_F_45
                FROM ISWS_LIST_CONTAINER
                WHERE TRIM(NO_UKK) = '$id_vs' 
                    AND E_I = 'E'
					AND TRIM(SIZE_) = '45'
					AND TRIM(STATUS) = 'FCL'
					AND KODE_STATUS > '51'";
	$hsl_f_45 = $db->query($cek_f_45);
	$hasil_f_45 = $hsl_f_45->fetchRow();
	$f_45 = $hasil_f_45['JML_F_45'];
	
	$cek_r_20 = "SELECT COUNT(NO_CONTAINER) AS JML_R_20
                FROM ISWS_LIST_CONTAINER
                WHERE TRIM(NO_UKK) = '$id_vs' 
                    AND E_I = 'E'
					AND TRIM(SIZE_) = '20'
					AND TRIM(TYPE_) = 'RFR'
					AND KODE_STATUS > '51'";
	$hsl_r_20 = $db->query($cek_r_20);
	$hasil_r_20 = $hsl_r_20->fetchRow();
	$r_20 = $hasil_r_20['JML_R_20'];
	
	$cek_r_40 = "SELECT COUNT(NO_CONTAINER) AS JML_R_40
                FROM ISWS_LIST_CONTAINER
                WHERE TRIM(NO_UKK) = '$id_vs' 
                    AND E_I = 'E'
					AND TRIM(SIZE_) = '40'
					AND TRIM(TYPE_) = 'RFR'
					AND KODE_STATUS > '51'";
	$hsl_r_40 = $db->query($cek_r_40);
	$hasil_r_40 = $hsl_r_40->fetchRow();
	$r_40 = $hasil_r_40['JML_R_40'];
	
	$cek_r_45 = "SELECT COUNT(NO_CONTAINER) AS JML_R_45
                FROM ISWS_LIST_CONTAINER
                WHERE TRIM(NO_UKK) = '$id_vs' 
                    AND E_I = 'E'
					AND TRIM(SIZE_) = '45'
					AND TRIM(TYPE_) = 'RFR'
					AND KODE_STATUS > '51'";
	$hsl_r_45 = $db->query($cek_r_45);
	$hasil_r_45 = $hsl_r_45->fetchRow();
	$r_45 = $hasil_r_45['JML_R_45'];
	
	$jml_cont = $e_20+$e_40+$e_45+$f_20+$f_40+$f_45+$r_20+$r_40+$r_45;
	
?>
<table border="1" bordercolor="#4D5A77" width=45% style="border-collapse:collapse">
	<tr>
		<td align="center" style="background-color:#607095" width="40" rowspan="2">POL</td>
		<td align="center" style="background-color:#607095" colspan="3">E</td>
		<td align="center" style="background-color:#607095" colspan="3">F</td>
		<td align="center" style="background-color:#607095" colspan="3">R</td>
		<td align="center" style="background-color:#607095" rowspan="2">Totals</td>
	</tr>
	<tr>
		<td style="background-color:#607095" align="center">20'</td>
		<td style="background-color:#607095" align="center">40'</td>
		<td style="background-color:#607095" align="center">45'</td>
		<td style="background-color:#607095" align="center">20'</td>
		<td style="background-color:#607095" align="center">40'</td>
		<td style="background-color:#607095" align="center">45'</td>
		<td style="background-color:#607095" align="center">20'</td>
		<td style="background-color:#607095" align="center">40'</td>
		<td style="background-color:#607095" align="center">45'</td>
	</tr>
	<tr>
		<td align="center" bgcolor="#FAFAFA">IDJKT</td>
		<td align="center" bgcolor="#FAFAFA"><?=$e_20?></td>
		<td align="center" bgcolor="#FAFAFA"><?=$e_40?></td>
		<td align="center" bgcolor="#FAFAFA"><?=$e_45?></td>
		<td align="center" bgcolor="#FAFAFA"><?=$f_20?></td>
		<td align="center" bgcolor="#FAFAFA"><?=$f_40?></td>
		<td align="center" bgcolor="#FAFAFA"><?=$f_45?></td>
		<td align="center" bgcolor="#FAFAFA"><?=$r_20?></td>
		<td align="center" bgcolor="#FAFAFA"><?=$r_40?></td>
		<td align="center" bgcolor="#FAFAFA"><?=$r_45?></td>
		<td align="center" bgcolor="#FAFAFA"><?=$jml_cont?></td>
	</tr>
</table>
<div align="right"><i>Generated by ISWS</i><br/>&copy; Sistem Informasi Tanjung Priok 2012</div>