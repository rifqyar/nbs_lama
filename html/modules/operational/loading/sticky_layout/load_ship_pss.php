<html lang="en">
	<head>
        <meta charset="utf-8">
    </head>

<?
	$db		= getDB();
	$nocont3	= $_GET['no_cont'];
	$cont_no = str_replace("'","",$nocont3);
	//print_r($cont_no);die;
	
	$cek_bay = "SELECT DISTINCT TO_NUMBER(SUBSTR(TRIM(ilc.BAYPLAN),2,2)) AS BAY_NO,
                       TO_NUMBER(SUBSTR(TRIM(ilc.BAYPLAN),4,2)) AS ROW_BAY,
                       TO_NUMBER(SUBSTR(TRIM(ilc.BAYPLAN),6,2)) AS TIER_BAY,
                       ilc.NO_UKK
                FROM YD_PLACEMENT_YARD ypy, ISWS_LIST_CONTAINER ilc
                WHERE ypy.STOWAGE = 'P'
                    AND ypy.NO_CONTAINER = '$cont_no'
                    AND ypy.ID_VS = ilc.NO_UKK
                    AND ypy.NO_CONTAINER = ilc.NO_CONTAINER";	
    $result_by = $db->query($cek_bay);	
	$hasil_by = $result_by->fetchRow();	
	$by = $hasil_by['BAY_NO'];
	$ukk = $hasil_by['NO_UKK'];
	
	if($by%2==0)
	{
		$by_no = $by-1;
	}
	else
	{
		$by_no = $by;
	}
	
	$cek_id_bay = "SELECT ID
					FROM STW_BAY_AREA
					WHERE BAY = '$by_no'
						AND ID_VS = '$ukk'";	
    $result_by_id = $db->query($cek_id_bay);	
	$hasil_by_id = $result_by_id->fetchRow();	
	$bay_area = $hasil_by_id['ID'];	
?>
<div id="mapping2w2">
<center>
<table width="100%" cellspacing="3" border="0">
<tbody>
<?
		$db = getDB();
		$id_vs = $ukk;
		$no_bay = $by_no;
        $query_bay = "SELECT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID = '$bay_area'";
        $result_ = $db->query($query_bay);
        $bay_ = $result_->fetchRow();
		
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
            
			$query_cell2 = "SELECT ID,CELL_NUMBER,ROW_,TIER_,STATUS_STACK,PLUGGING FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' ORDER BY CELL_NUMBER ASC";
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
				$id_cellx = $row8['ID'];
				//echo $tr;			
				
				$cek_cont_plan = "SELECT A.BAY,
									A.NO_CONTAINER,
									A.SIZE_,
									CASE WHEN TRIM(A.TYPE_)='DRY' THEN 'DC'
										 WHEN TRIM(A.TYPE_)='HQ' THEN 'HC'
										 WHEN TRIM(A.TYPE_)='TNK' THEN 'TK'
										 WHEN TRIM(A.TYPE_)='RFR' THEN 'RF'
								    END TYPE_CONT,
									A.SIZE_||'/'||TRIM(A.TYPE_)||'/'||A.STATUS_ AS SPEC_CONT,
									substr(A.ID_PEL_ASAL,3,3) AS POL,
									substr(A.ID_PEL_TUJ,3,3) AS POD,
									A.CARRIER,
									RPAD(round((A.GROSS/1000),2),4,0) AS GROSS,
									round((B.H_ISO*30.48)) AS HEIGHT 
							FROM STW_PLACEMENT_BAY A, MASTER_ISO_CODE B 
								WHERE A.ID_CELL = '$id_cellx'
								AND A.ACTIVITY = 'MUAT'
								AND A.STATUS_PLC = 'PLANNING'
								AND B.ISO_CODE = A.ISO_CODE";
					$result_cont_plan = $db->query($cek_cont_plan);
					$cont_plan = $result_cont_plan->fetchRow();
									
					$nocont_plan = $cont_plan['NO_CONTAINER'];
					$pol_plan = $cont_plan['POL'];
					$pod_plan= $cont_plan['POD'];
					$carrier_plan = $cont_plan['CARRIER'];
					$gross_plan = $cont_plan['GROSS'];
					$sz_plan = $cont_plan['SIZE_'];
					$ty_plan = $cont_plan['TYPE_CONT'];
					$ht_plan = $cont_plan['HEIGHT'];
					$by_plan = $cont_plan['BAY'];
					$spec_plan = $cont_plan['SPEC_CONT'];
				
				$cek_cont = "SELECT A.BAY,
									A.NO_CONTAINER,
									A.SIZE_,
									CASE WHEN TRIM(A.TYPE_)='DRY' THEN 'DC'
										 WHEN TRIM(A.TYPE_)='HQ' THEN 'HC'
										 WHEN TRIM(A.TYPE_)='TNK' THEN 'TK'
										 WHEN TRIM(A.TYPE_)='RFR' THEN 'RF'
								    END TYPE_CONT,
									A.SIZE_||'/'||TRIM(A.TYPE_)||'/'||A.STATUS_ AS SPEC_CONT,
									substr(A.ID_PEL_ASAL,3,3) AS POL,
									substr(A.ID_PEL_TUJ,3,3) AS POD,
									A.CARRIER,
									RPAD(round((A.GROSS/1000),2),4,0) AS GROSS,
									round((B.H_ISO*30.48)) AS HEIGHT 
							FROM STW_PLACEMENT_BAY A, MASTER_ISO_CODE B 
								WHERE A.ID_CELL = '$id_cellx'
								AND A.ACTIVITY = 'MUAT'
								AND A.STATUS_PLC = 'REALIZATION'
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
				           
			if ($index%$width != 0) 
			{
				if ((($row8['STATUS_STACK'] == 'A')||($row8['STATUS_STACK'] == 'P')||($row8['STATUS_STACK'] == 'R'))&&($row8['PLUGGING'] == 'T'))
				{
					if($nocont!=NULL)
					{
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
		    ?>
							<div id="x" class="drag blue">
							<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#C2EBFF'" align="center" style="width:50px;height:50px;font-size:7px; color:#FF0000; font-family:Tahoma; border:1px solid #000000;background-color:#C2EBFF; ">
							<?=$pol?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$pod?><br/>
							<b><?=$nocont?></b><br/>
							<?=$carrier?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$gross?><br/>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$sz?><?=$ty?><br/>
							<?=$ht?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							</td>
							</div>
			<?
						}
					}
					else if($nocont_plan!=NULL)
					{
						if((($sz_plan=='40')||($sz_plan=='45'))&&($by_plan!=1)&&(($by_plan-1)%4!=0))
									{
						?>
										<div id="x" class="drag blue">
										<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFCC99'" align="center" style="width:50px;height:50px;font-size:7px; color:#FF0000; font-family:Tahoma; border:1px solid #000000;background-color:#FFCC99; ">
										<b>+++</b>
										</td>
										</div>
						<?			
									}
									else
									{
						?>
										<div id="x" class="drag blue">
										<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFCC99'" align="center" style="width:50px;height:50px;font-size:7px; color:#FF0000; font-family:Tahoma; border:1px solid #000000;background-color:#FFCC99; ">
										<?=$pol_plan?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$pod_plan?><br/>
										<b><?
										  if($cont_no==$nocont_plan)	
										  { echo "<blink>".$nocont_plan."</blink>"; }
										  else
										  { echo $nocont_plan; }?></b><br/>
										<?=$carrier_plan?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$gross_plan?><br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$sz_plan?><?=$ty_plan?><br/>
										<?=$ht_plan?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										</td>
										</div>
						<?
									}
					}
					else
					{
			?>		
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:50px;height:50px;font-size:6px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">
					&nbsp;					
					</td>
                    </div>	
			<?		
					}
			   }
			   else if ((($row8['STATUS_STACK'] == 'A')||($row8['STATUS_STACK'] == 'P')||($row8['STATUS_STACK'] == 'R'))&&($row8['PLUGGING'] == 'Y'))
			   {
					if($nocont!=NULL)
					{
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
		    ?>
							<div id="x" class="drag blue">
							<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#C2EBFF'" align="center" style="width:50px;height:50px;font-size:7px; color:#FF0000; font-family:Tahoma; border:1px solid #000000;background-color:#C2EBFF; ">
							<?=$pol?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$pod?><br/>
							<b><?=$nocont?></b><br/>
							<?=$carrier?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$gross?><br/>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$sz?><?=$ty?><br/>
							<?=$ht?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							</td>
							</div>
			<?
						}
					}
					else if($nocont_plan!=NULL)
					{
						if((($sz_plan=='40')||($sz_plan=='45'))&&($by_plan!=1)&&(($by_plan-1)%4!=0))
									{
						?>
										<div id="x" class="drag blue">
										<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFCC99'" align="center" style="width:50px;height:50px;font-size:7px; color:#FF0000; font-family:Tahoma; border:1px solid #000000;background-color:#FFCC99; ">
										<b>+++</b>
										</td>
										</div>
						<?			
									}
									else
									{
						?>
										<div id="x" class="drag blue">
										<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFCC99'" align="center" style="width:50px;height:50px;font-size:7px; color:#FF0000; font-family:Tahoma; border:1px solid #000000;background-color:#FFCC99; ">
										<?=$pol_plan?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$pod_plan?><br/>
										<b><?
										  if($cont_no==$nocont_plan)	
										  { echo "<blink>".$nocont_plan."</blink>"; }
										  else
										  { echo $nocont_plan; }?></b><br/>
										<?=$carrier_plan?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$gross_plan?><br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$sz_plan?><?=$ty_plan?><br/>
										<?=$ht_plan?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										</td>
										</div>
						<?
									}
					}
					else
					{
			?>		
					<div id="x" class="drag blue">
                    <td	onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:50px;height:50px;font-size:6px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;					
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
</form>
</td>
</tr>
</tbody>
</table>
</center>	
</div>
</html>