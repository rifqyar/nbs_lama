<?php
$id = $_GET['id_vs'];
//print_r($id_vs);die;
?>
<script type="text/javascript" src="<?=HOME;?>js/stickytooltip.js"></script>
<link rel="stylesheet" type="text/css" href="<?=HOME;?>css/stickytooltip.css" />
<center>
<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; background-image:url('<?=HOME;?>images/profil6.png'); background-repeat:no-repeat;">
<center>      
<div style="margin-right:100px; margin-bottom:200px; margin-top:15px; width:600; height:100;">
<p style="width:100%;">
<div align="center">
<table width="100%" cellspacing="0" border="0">
<tbody>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>
	<tr>
         <?php
            $db         = getDB();
            $query_blok = "SELECT ID, BAY FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY BAY DESC";
            $result_    = $db->query($query_blok);
            $blok       = $result_->getAll();
            
			// debug($blok2);die;
            foreach ($blok as $row){
                //echo $row['INDEX_CELL'];
				$bay_no = $row['BAY'];
				
				if(($bay_no==1)||(($bay_no-1)%4==0))
				{
					$bays = $bay_no."(".($bay_no+1).")";
				}
				else
				{
					$bays = $bay_no;
				}
			?>
                          <td align="center" style="width:20px;height:10px;font-size:8px; font-family:Tahoma;"><b><? echo $bays;?></b></td>
              <?
			    } ?>
	</tr>
	<!---------------------------- ABOVE -------------------------------->
	<?
		$db        = getDB();
        $query_tr  = "SELECT DISTINCT JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id'";
        $result_tr = $db->query($query_tr);
        $tr_       = $result_tr->fetchRow();
		$jml_tr_abv = $tr_['JML_TIER_ON'];
		
		for($tr=1;$tr<=$jml_tr_abv;$tr++)
		{
			
	?>
    <tr>
         <?php
            $db         = getDB();
            $query_blok = "SELECT ID, BAY, ABOVE, JML_ROW, JML_TIER_UNDER, JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY BAY DESC";
            $result_    = $db->query($query_blok);
            $blok       = $result_->getAll();
            
			// debug($blok2);die;
            foreach ($blok as $row){
                //echo $row['INDEX_CELL'];
				
            if ($row['ABOVE'] == 'NON AKTIF')
			{                    
                    $jml_row = $row['JML_ROW'];
                    $jml_tier_under = $row['JML_TIER_UNDER'];
                    $jml_tier_on = $row['JML_TIER_ON'];
			?>
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:20px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF;" >&nbsp;</td>
               <? }
			   else if($row['ABOVE'] == 'NONE')
				{
			   ?>
                          <td style="width:30px;height:10px;font-size:8px; font-family:Tahoma;">&nbsp;</td>
			   <?
				}
			   else { ?>
                          <td data-tooltip="<?=$row['ID']?>above" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:20px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
                    
              <? } 
			    } ?>
	</tr>
	<? } ?>
	<!---------------------------- ABOVE -------------------------------->
	<tr>
         <?php
            $db         = getDB();
            $query_jml  = "SELECT count(BAY) AS JML_BAY FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY BAY DESC";
            $result12_  = $db->query($query_jml);
            $jml        = $result12_->fetchRow();
			$jml_bay    = $jml['JML_BAY'];
                    
			?>
                          <td colspan="<?=$jml_bay;?>" align="center" style="width:20px;height:8px; border:1px solid #000000; background-color:#663300;"></td>
	</tr>
	<!---------------------------- BELOW -------------------------------->
	<?
		$db        = getDB();
        $tr_under  = "SELECT DISTINCT JML_TIER_UNDER FROM STW_BAY_AREA WHERE ID_VS = '$id'";
        $tr_under_hsl = $db->query($tr_under);
        $tr_underx  = $tr_under_hsl->fetchRow();
		$jml_tr_blw = $tr_underx['JML_TIER_UNDER'];
		
		for($trx=1;$trx<=$jml_tr_blw;$trx++)
		{
	?>
	<tr>
         <?php
            $db         = getDB();
            $query_blok = "SELECT ID, BAY, BELOW, JML_ROW, JML_TIER_UNDER, JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY BAY DESC";
            $result_    = $db->query($query_blok);
            $blok       = $result_->getAll();
            
			// debug($blok2);die;
            foreach ($blok as $row){
                //echo $row['INDEX_CELL'];		
				
                if($row['BELOW'] == 'NON AKTIF')
			{                    
                    $jml_row = $row['JML_ROW'];
                    $jml_tier_under = $row['JML_TIER_UNDER'];
                    $jml_tier_on = $row['JML_TIER_ON'];
			?>
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
               <? }
				else if($row['BELOW'] == 'NONE')
				{
			   ?>
                          <td style="width:30px;height:10px;font-size:8px; font-family:Tahoma;">&nbsp;</td>
			   <?
				}
				else { ?>
                          <td data-tooltip="<?=$row['ID']?>below" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF;">&nbsp;</td>
                    
              <? } 
			    } ?>
	</tr>
	<? } ?>
	<!---------------------------- BELOW -------------------------------->
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</div>
</p></div>
    </center>
</fieldset>
</center>
<div id="mystickytooltip" class="stickytooltip">
                  <div style="padding:5px">
					<?
						$db           = getDB();
						$query_below  = "SELECT ID, BAY FROM STW_BAY_AREA WHERE ID_VS = '$id' AND BELOW = 'AKTIF' ORDER BY BAY DESC";
						$below_       = $db->query($query_below);
						$bay_below    = $below_->getAll();
						
						$query_above  = "SELECT ID, BAY FROM STW_BAY_AREA WHERE ID_VS = '$id' AND ABOVE = 'AKTIF' ORDER BY BAY DESC";
						$above_       = $db->query($query_above);
						$bay_above    = $above_->getAll();					
						
						foreach ($bay_below as $row_blw){
						$bay_area = $row_blw['ID'];
					?>
                       <div id="<?=$row_blw['ID']?>below" class="atip">                                    
						  <table width="100%" cellspacing="3" border="0">
							<tbody>
							<?
									$db = getDB();
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
											
											$query_cell2 = "SELECT CELL_NUMBER,ROW_,TIER_,STATUS_STACK,PLUGGING FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' AND POSISI_STACK IN ('BELOW','HATCH') ORDER BY CELL_NUMBER ASC";
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
												if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'T'))
													{ 						  
													 ?>
														<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
												<?
												   }
												   else if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
													{ 						  
													 ?>
														<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">O</td>
												<?
												   }
											   else if ($row8['STATUS_STACK'] == 'P')
												{ ?>
												
													 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0000; ">&nbsp;</td>
												  
											   <? 
											   }
											   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
											   {
											   ?>
													<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
													</td>
											   <?
											   }
											   else if ($row8['STATUS_STACK'] == 'N')
											   {
												if(($index>=1)&&($index<$width)) {
												
													$cek_capacity  = "SELECT PLAN_HEIGHT, PLAN_WEIGHT FROM STW_BAY_CAPACITY WHERE ID_BAY_AREA = '$bay_area' AND ROW_ = '$rw' AND POSISI_STACK = 'ABOVE'";
													$result6  = $db->query($cek_capacity);
													$capacity_cek  = $result6->fetchRow();
													$height_max = $capacity_cek['PLAN_HEIGHT'];
													$weight_max = $capacity_cek['PLAN_WEIGHT'];
												
													?>
														<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; ">
														<b><? echo $weight_max/1000; ?></b><br/>
														<b><? if($height_max=="") { ?>
														0
														<? } else { 
															echo $height_max/100;
														} ?></b><br/>
														<?=$rw;?>
														</td>
													<? }
														else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
														
														$cek_capacity  = "SELECT PLAN_HEIGHT, PLAN_WEIGHT FROM STW_BAY_CAPACITY WHERE ID_BAY_AREA = '$bay_area' AND ROW_ = '$rw' AND POSISI_STACK = 'BELOW'";
														$result6  = $db->query($cek_capacity);
														$capacity_cek  = $result6->fetchRow();
														$height_max = $capacity_cek['PLAN_HEIGHT'];
														$weight_max = $capacity_cek['PLAN_WEIGHT'];
														
													?>
														<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; ">	
														<? if(($height_max=="")||($height_max=="0")) { ?>
														&nbsp;
														<? } else { ?>
														<?=$rw;?>
														 <br/><b><? echo number_format($weight_max/1000,1); ?></b>
														 <br/><b><? echo number_format($height_max/100,1);?></b>
														<? } ?>
														</td>
													<? }
														else
													   {
													?>
													<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; background-color:#FFF; ">
													&nbsp;
													</td>
													<? } 
												} 
											}			
											else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
											{ 	?>	
													<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">
													<?=$br?>
													</td>
													</tr>
											  <? }
											else if ($index%$width == 0)
											{ ?>                    
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
													   {  
														  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
														 { ?>
														 <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
														 <? }
															else { ?>
														 <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">HATCH</td>
													<? }
														} ?>
													</tr>				
											<?
												}
											}
											?>
								</tbody>
								</table>
							 </td>
							</tbody>
						  </table>
                       </div>
					<? } 
						
						foreach ($bay_above as $row_abv){
						$bay_area = $row_abv['ID'];
					?>
						<div id="<?=$row_abv['ID']?>above" class="atip">
						  <table width="100%" cellspacing="3" border="0">
							<tbody>
							<?
									$db = getDB();
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
											
											$query_cell2 = "SELECT CELL_NUMBER,ROW_,TIER_,STATUS_STACK,PLUGGING FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' AND POSISI_STACK IN ('ABOVE','HATCH') ORDER BY CELL_NUMBER ASC";
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
												if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'T'))
													{ 						  
													 ?>
														<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
												<?
												   }
												   else if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
													{ 						  
													 ?>
														<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">O</td>
												<?
												   }
											   else if ($row8['STATUS_STACK'] == 'P')
												{ ?>
												
													 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0000; ">&nbsp;</td>
												  
											   <? 
											   }
											   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
											   {
											   ?>
													<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
													</td>
											   <?
											   }
											   else if ($row8['STATUS_STACK'] == 'N')
											   {
												if(($index>=1)&&($index<$width)) {
												
													$cek_capacity  = "SELECT PLAN_HEIGHT, PLAN_WEIGHT FROM STW_BAY_CAPACITY WHERE ID_BAY_AREA = '$bay_area' AND ROW_ = '$rw' AND POSISI_STACK = 'ABOVE'";
													$result6  = $db->query($cek_capacity);
													$capacity_cek  = $result6->fetchRow();
													$height_max = $capacity_cek['PLAN_HEIGHT'];
													$weight_max = $capacity_cek['PLAN_WEIGHT'];
												
													?>
														<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; ">			
														<? if(($height_max=="")||($height_max=="0")) { ?>
														&nbsp;
														<? } else { ?>
															<b><? echo number_format($weight_max/1000,1); ?></b><br/>
															<b><? echo number_format($height_max/100,1); ?></b><br/>
															<?=$rw;?>
														<? } ?>														
														</td>
													<? }
														else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
														
														$cek_capacity  = "SELECT PLAN_HEIGHT, PLAN_WEIGHT FROM STW_BAY_CAPACITY WHERE ID_BAY_AREA = '$bay_area' AND ROW_ = '$rw' AND POSISI_STACK = 'BELOW'";
														$result6  = $db->query($cek_capacity);
														$capacity_cek  = $result6->fetchRow();
														$height_max = $capacity_cek['PLAN_WEIGHT'];
														$weight_max = $capacity_cek['PLAN_WEIGHT'];
														
													?>
														<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; "><?=$rw;?>
														<? echo $weight_max/1000; ?>
														<? if($height_max=="") { ?>
														<br/>0
														<? } else { ?>
															<br/><b><? echo $height_max/100;?></b>
														<? } ?>
														</td>
													<? }
														else
													   {
													?>
													<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; background-color:#FFF; ">
													&nbsp;
													</td>
													<? } 
												} 
											}			
											else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
											{ 	?>	
													<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">
													<?=$br?>
													</td>
													</tr>
											  <? }
											else if ($index%$width == 0)
											{ ?>                    
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
													   {  
														  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
														 { ?>
														 <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
														 <? }
															else { ?>
														 <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">HATCH</td>
													<? }
														} ?>
													</tr>				
											<?
												}
											}
											?>
								</tbody>
								</table>
							 </td>
							</tbody>
						  </table>
                       </div>
					<? } ?>
                  </div>
                <div class="stickystatus"></div>
</div>