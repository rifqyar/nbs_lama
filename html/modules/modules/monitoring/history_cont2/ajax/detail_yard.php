<?php

?>
<table>
<tr>
	<?php
			$query_cell2 = "SELECT a.INDEX_CELL as INDEX_CELL, b.ID AS ID, b.NAME AS NAME, a.SLOT_ AS SLOT_, a.ROW_ AS ROW_, a.STATUS_BM,b.POSISI,a.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA b,         
                            YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '23' AND a.SIZE_PLAN_PLC IS NULL 
                            UNION 
                            SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_, d.STATUS_BM,c.POSISI,d.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '23' AND d.SIZE_PLAN_PLC = '40d'
                            UNION
                            SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_,d.STATUS_BM,c.POSISI,d.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '23' AND d.SIZE_PLAN_PLC = '20'
                           -- UNION 
                           -- SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_,d.STATUS_BM,c.POSISI,d.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '23' AND d.SIZE_PLAN_PLC = '40b'
                            ORDER BY INDEX_CELL ASC";
			$result3     = $db->query($query_cell2);
			
            $blok2       = $result3->getAll();
           
         
            foreach ($blok2 as $row){
			// $index_cell_ = $row['INDEX_CELL'];
					$id_block     = $row['ID'];
                    $slot_        = $row['SLOT_'];
                    $row_         = $row['ROW_'];
                    $name         = $row['NAME'];
                    $index_cell_  = $row['INDEX_CELL'];
					$st_bm		  = $row['STATUS_BM'];
					$pos		  = $row['POSISI'];
					$sz_plc	      = $row['SIZE_PLAN_PLC'];
					
					if ($sz_plc=='40d')
					{
						if($pos=='vertical')
						{
							$cr="rowspan=2";
						}
						else if($pos=='horizontal')
							$cr="colspan=2";
					}
					else
						$cr='';
			//echo $index_cell_;
                if ($row['NAME'] <> 'NULL')
				{                
					$id_block     = $row['ID'];
                    $slot_        = $row['SLOT_'];
                    $row_         = $row['ROW_'];
                    $name         = $row['NAME'];
                    $index_cell_  = $row['INDEX_CELL'];
					$st_bm		  = $row['STATUS_BM'];
					
					
					$query_place = "SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$row_' AND ID_CELL = '$index_cell_' AND FLAG_HP IS NULL";
					$filter=0;
					$result2     = $db->query($query_place);
                    $place       = $result2->fetchRow();
                     
                    // debug($place);die;
                     $placement   = $place['JUM']; ?>
                   
                <?  if (($filter==0)&&($placement <> 0))
				
					{
						if ($placement>=5)
						{
							$color_plc='#FF0033';
						}
						else if ($placement==4)
						{
							$color_plc='#f89311';
						}
						else if ($placement==3)
						{
							$color_plc='#f8ea11';
						}
						else if ($placement==2)
						{
							$color_plc='#df11f8';
						}
						else if ($placement==1)
						{
							$color_plc='#5b0cad';
						}
						
						
						?>
							<td onMouseOut="this.style.backgroundColor=<?=$color_plc?>" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff; background-color:<?=$color_plc?>; " <?=$cr?>><blink><font color="white"><?=$placement//echo $row['NAME'];=$index_cell_?><a><? //echo $placement ?><?//=$st_bm;?><?//=$id_block;?></a></font></blink></td>
<!--                     <div id="x" class="drag blue">-->


<!--                     </div>-->

                   <? }						
				   else 
				   { ?>

				   <? 
					if ($st_bm=='Muat')
					{?>
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#86BCFF; " <?=$cr?>>
						  &nbsp;<?//=$name;?></td>
					
					
					<? 
						
					}
					else if ($st_bm=='Bongkar')
					{
					?>
							<td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#82ff07'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#82ff07; ">
							&nbsp;</td>
						
					<? 
					}
					?>	  
               <? }  
				} 
				else 
				{
				?>
                     <td align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;">
					<? if (($slot_ == NULL) AND ($row_ <> NULL) AND ($st_bm == NULL)) {
							?><?echo $row_;?><?
					   } else if (($slot_ <> NULL) AND ($row_ == NULL) AND ($st_bm == NULL)) {
							?><?echo $slot_;?><?
					   } else if (($slot_ == NULL) AND ($row_ == NULL) AND ($st_bm <> NULL)) {
							?><font size="1pt"><b><?=$st_bm;?></b></font></font><?
						} ?>
						<?//=$name;?>
						</td>
              <?}
                if (($row['INDEX_CELL']+1) % $width == 0)
				{ ?>
                 </tr>
                <? 
				}
                ?>      
        <?    } ?>
		
</tbody>
</table>