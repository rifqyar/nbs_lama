<html lang="en">
<head>
    <meta charset="utf-8">
	<link rel="stylesheet" href="yard/src/css/excite-bike/jquery-ui-1.8.16.custom.css">
	<script src="yard/src/js/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script src="yard/src/js/jquery-migrate-1.1.0.min.js" type="text/javascript"></script>
	<script src="yard/src/js/jquery.maphilight.js" type="text/javascript"></script>
	<script src="yard/src/js/jquery.imagemapster.js" type="text/javascript"></script>
	<script src="yard/src/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="yard/src/css/main.css">
        
        
    <script type="text/javascript" src="tooltip/stickytooltip.js"></script>
    <link rel="stylesheet" type="text/css" href="tooltip/stickytooltip.css" />
	
	<script type="text/javascript">
	

		
	/*  
	  $(function() 
			 {
				  $('.map').maphilight({
				  fillColor: '003D14',
				  alwaysOn: true
					});
			 var data = $('#areaID').data('maphilight') || {};

			 data.alwaysOn = !data.alwaysOn;
			 $('#id').data('maphilight', data).trigger('alwaysOn.maphilight');
			 });
	*/		 
			 
		
 
	</script>

</head>
<body>
<img src="images/LAYOUT_USTER_18OCT2012.jpg" class="map" usemap="#mapuster">

<map name="mapuster">
									<?	$db         = getDB("storage");
									 $query_yard    = "SELECT blocking_cell.X1, 
															  blocking_cell.X2,
															  blocking_cell.Y1,
															  blocking_cell.Y2,
															  blocking_cell.SLOT_, 
															  blocking_cell.ID_BLOCKING_AREA, 
															  blocking_cell.ROW_, concat(concat(concat(concat(concat('D_',blocking_cell.ID_BLOCKING_AREA),'_'),blocking_cell.slot_),'_'),blocking_cell.row_) LOKASI 
			                                                    FRoM BLOCKING_CELL, blocking_area 
			                                                    WHERE blocking_cell.ID_BLOCKING_AREA = blocking_area.ID 
			                                                    AND blocking_area.ID_YARD_AREA ='46'";
									$result		    = $db->query($query_yard);
									$data		    = $result->getAll();
									//debug ($data);
									foreach ($data as $row){
									?>
<div id = "areaID">								
  <area  bgcolor="red" id="rect" shape="rect" coords="<?=$row['X1']?>,<?=$row['Y1']?>, <?=$row['X2']?>, <?=$row['Y2']?>" alt="Sun" data-tooltip="<?=$row['LOKASI']?>"  />
</div>  
  
  <?}?>
</map>

   <div id="mystickytooltip" class="stickytooltip">
                    <div style="padding:5px">   
					<?	$db         = getDB("storage");
									 $query_yard_    = "SELECT blocking_cell.X1, blocking_cell.X2,blocking_cell.Y1,blocking_cell.Y2,blocking_cell.SLOT_, blocking_cell.ID_BLOCKING_AREA, blocking_area.NAME, blocking_cell.ROW_, concat(concat(concat(concat(concat('D_',blocking_cell.ID_BLOCKING_AREA),'_'),blocking_cell.slot_),'_'),blocking_cell.row_) LOKASI 
			                                                    FRoM BLOCKING_CELL, blocking_area 
			                                                    WHERE blocking_cell.ID_BLOCKING_AREA = blocking_area.ID 
			                                                    AND blocking_area.ID_YARD_AREA ='46'";
									$result_		    = $db->query($query_yard_);
									$data2		    = $result_->getAll();
									//debug ($data);
									foreach ($data2 as $row3){
									//echo $row3['LOKASI'];		
									?>
					<div id="<?=$row3['LOKASI']?>" class="atip">  
								<? 
                                    $id_blocking_area = $row3['ID_BLOCKING_AREA'];
									$slot			  = $row3['SLOT_'];
									$row2			  = $row3['ROW_'];
									$query_			  = "SELECT a.NO_CONTAINER,b.SIZE_, b.TYPE_ , a.STATUS, a.TIER_
														FROM PLACEMENT a, MASTER_CONTAINER b, BLOCKING_AREA c WHERE 
														a.NO_CONTAINER = b.NO_CONTAINER
														AND a.ID_BLOCKING_AREA = c.ID 
														AND a.ID_BLOCKING_AREA = '$id_blocking_area'
														AND a.SLOT_ = '$slot'
														AND a.ROW_ = '$row2'";
									$result__	= $db->query($query_);
									$data_		= $result__->getAll();
								
                                    if (count($data_) == 0){ ?>
                                    <i> -- There is no container here -- </i>
                                    <? } else {?>
										<table>
                                           <tr><td colspan="2" align="center"><b>BLOK <?=$row3['NAME']?></b></td></tr><?
                                            foreach($data_ as $row_){
                                       ?>
                                           <tr><td><img src="images/row_cont.png" width="40" height="40"><br/>TIER <?=$row_['TIER_']?></td><td>
                                           <?=$row_['NO_CONTAINER']?><br><?=$row_['SIZE_']?> | <?=$row_['TYPE_']?> | <?=$row_['STATUS']?></td></tr>
                                       <? } ?>
									  </table>
									<?}?>
                                    </div>
                   <? }?> 
                        
                    </div>

                    <div class="stickystatus"></div>
                    </div>
</table>

</body>
</html>