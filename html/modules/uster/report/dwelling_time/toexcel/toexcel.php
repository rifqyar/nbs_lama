<?php
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=LaporanDwellingTime-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

	$id_area = $_GET["lokasi"];
	$mlo = $_GET["mlo"];
	$status = $_GET["status"];
	$db 	= getDB("storage");
	if($mlo == 'T'){
		$qplacement = "SELECT PLACEMENT.NO_CONTAINER NO_CONTAINER, MASTER_CONTAINER.SIZE_ , MASTER_CONTAINER.TYPE_, 
			  PLACEMENT.STATUS, PLACEMENT.NO_REQUEST_RECEIVING,  TRUNC(PLACEMENT.TGL_PLACEMENT) TGL_PLACEMENT, SYSDATE TGL_CETAK, 
			  BLOCKING_AREA.NAME BLOK_, PLACEMENT.SLOT_, PLACEMENT.ROW_, PLACEMENT.TIER_, TO_DATE(TO_CHAR(SYSDATE,'YYYYMMDD HH:MI:SS AM'),'YYYYMMDD HH:MI:SS AM') -TO_DATE (TO_CHAR (PLACEMENT.TGL_PLACEMENT, 'YYYYMMDD HH:MI:SS AM'),'YYYYMMDD HH:MI:SS AM') DWELLING, YARD_AREA.NAMA_YARD LAPANGAN
			  FROM PLACEMENT INNER JOIN MASTER_CONTAINER
			  ON PLACEMENT.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
		      INNER JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID
			  INNER JOIN YARD_AREA ON BLOCKING_AREA.ID_YARD_AREA = YARD_AREA.ID
			  WHERE YARD_AREA.ID = '$id_area' AND MASTER_CONTAINER.NO_CONTAINER NOT IN(SELECT NO_CONTAINER FROM MASTER_CONTAINER WHERE MLO = 'MLO') AND PLACEMENT.STATUS='$status'
			  ORDER BY DWELLING DESC";
	} else if($mlo == 'Y'){
		$qplacement = "SELECT PLACEMENT.NO_CONTAINER NO_CONTAINER, MASTER_CONTAINER.SIZE_ , MASTER_CONTAINER.TYPE_, 
			  PLACEMENT.STATUS, PLACEMENT.NO_REQUEST_RECEIVING,  TRUNC(PLACEMENT.TGL_PLACEMENT) TGL_PLACEMENT, SYSDATE TGL_CETAK, 
			  BLOCKING_AREA.NAME BLOK_, PLACEMENT.SLOT_, PLACEMENT.ROW_, PLACEMENT.TIER_, TO_DATE(TO_CHAR(SYSDATE,'YYYYMMDD HH:MI:SS AM'),'YYYYMMDD HH:MI:SS AM') -TO_DATE (TO_CHAR (PLACEMENT.TGL_PLACEMENT, 'YYYYMMDD HH:MI:SS AM'),'YYYYMMDD HH:MI:SS AM') DWELLING, YARD_AREA.NAMA_YARD LAPANGAN
			  FROM PLACEMENT INNER JOIN MASTER_CONTAINER
			  ON PLACEMENT.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
		      INNER JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID
			  INNER JOIN YARD_AREA ON BLOCKING_AREA.ID_YARD_AREA = YARD_AREA.ID
			  WHERE YARD_AREA.ID = '$id_area' AND MASTER_CONTAINER.MLO = 'MLO' AND PLACEMENT.STATUS='$status'
			  ORDER BY DWELLING DESC";
	} else {
		$qplacement = "SELECT PLACEMENT.NO_CONTAINER NO_CONTAINER, MASTER_CONTAINER.SIZE_ , MASTER_CONTAINER.TYPE_, 
			  PLACEMENT.STATUS, PLACEMENT.NO_REQUEST_RECEIVING,  TRUNC(PLACEMENT.TGL_PLACEMENT) TGL_PLACEMENT, SYSDATE TGL_CETAK, 
			  BLOCKING_AREA.NAME BLOK_, PLACEMENT.SLOT_, PLACEMENT.ROW_, PLACEMENT.TIER_, TO_DATE(TO_CHAR(SYSDATE,'YYYYMMDD HH:MI:SS AM'),'YYYYMMDD HH:MI:SS AM') -TO_DATE (TO_CHAR (PLACEMENT.TGL_PLACEMENT, 'YYYYMMDD HH:MI:SS AM'),'YYYYMMDD HH:MI:SS AM') DWELLING, YARD_AREA.NAMA_YARD LAPANGAN
			  FROM PLACEMENT INNER JOIN MASTER_CONTAINER
			  ON PLACEMENT.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
		      INNER JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID
			  INNER JOIN YARD_AREA ON BLOCKING_AREA.ID_YARD_AREA = YARD_AREA.ID
			  WHERE YARD_AREA.ID = '$id_area' AND PLACEMENT.STATUS='$status'
			  ORDER BY DWELLING DESC";
	}
		
	$rplace = $db->query($qplacement);
	$rowplace = $rplace->getAll();


?>

 <div id="list">
	<table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
			  <tr style=" font-size:10pt">
				  <th width="2%" valign="top" class="grid-header"  style="font-size:8pt">No </th>
				  <th valign="top" class="grid-header"  style="font-size:8pt">NO. CONTAINER </th>
				  <th valign="top" class="grid-header"  style="font-size:8pt">SIZE</th>
				  <th valign="top" class="grid-header"  style="font-size:8pt">TYPE</th> 
				  <th valign="top" class="grid-header"  style="font-size:8pt">STATUS</th> 
				  <th valign="top" class="grid-header"  style="font-size:8pt">NO REQUEST</th>
				  <th valign="top" class="grid-header"  style="font-size:8pt">TGL PLACEMENT</th>  
				  <th valign="top" class="grid-header"  style="font-size:8pt">TGL CETAK</th>  
				  <th Valign="top" class="grid-header"  style="font-size:8pt">LAPANGAN</th>  
				  <th Valign="top" class="grid-header"  style="font-size:8pt">BLOK SLOT ROW TIER</th>  
				  <th Valign="top" class="grid-header"  style="font-size:8pt">DWELLING TIME</th>  
			  </tr>
			<?php $i=0;?>  
			<?php foreach($rowplace as $rowp){?>  
			  <tr <?php if($rowp["DWELLING"] > 6){ ?> bgcolor="yellow" <?php } else { ?>bgcolor="#f9f9f3" <?php } ?> onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?php $i++; echo $i;?> </td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?php echo $rowp["NO_CONTAINER"];?>  </td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?php echo $rowp["SIZE_"];?> </td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?php echo $rowp["TYPE_"];?> </td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?php echo $rowp["STATUS"];?> </td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?php echo $rowp["NO_REQUEST_RECEIVING"];?> </td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?php echo $rowp["TGL_PLACEMENT"];?> </td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?php echo $rowp["TGL_CETAK"];?> </td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?php echo $rowp["LAPANGAN"];?> </td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?php echo $rowp["BLOK_"]."-".$rowp["SLOT_"]."-".$rowp["ROW_"]."-".$rowp["TIER_"];?></td>				  
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt">
				  <?php $dwell = $rowp["DWELLING"];
						$time = strstr($dwell, '.');
						$dec = "0".$time;									
						$days = floor($dwell);
						$hours = $dec*24;										
						/*$seconds = $hours * 3600;
						$seconds -= $hours * 3600;
						$minutes = floor($seconds / 60);
						$seconds -= $minutes * 60;
						$h = (strlen($hours) < 2) ? "0{$hours}" : $hours;
						$m = (strlen($minutes) < 2) ? "0{$minutes}" : $minutes;
						$s = (strlen($seconds) < 2) ? "0{$seconds}" : $seconds;
						$clock = $h.":".$m.":".round($s);*/
						$clock = gmdate('H:i:s', floor($hours * 3600));						
						echo $days." Hari - ".$clock;
				  
				  ?> </td>
				  
				 
			  </tr>
           <?php } ?> 
        </tbody>
    </table>

</div>