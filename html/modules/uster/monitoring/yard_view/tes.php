<?php

$id = $_GET['id'];

// echo $id;
$isi = (explode("-",$id));

$blok = $isi[0];
$slot = $isi[1];
$row  = $isi[2]; 

// print_r($isi);die;

			$db 		= getDB("storage");
			
			$isi = NULL;
			
			
			$query_		= "SELECT c.NAME, a.NO_CONTAINER,
								b.SIZE_, b.TYPE_ , d.STATUS_CONT STATUS, a.TIER_,
								a.SLOT_,a.ROW_
												FROM PLACEMENT a, MASTER_CONTAINER b, BLOCKING_AREA c, HISTORY_CONTAINER d WHERE 
												a.NO_CONTAINER = b.NO_CONTAINER
												AND b.NO_CONTAINER = d.NO_CONTAINER
												AND d.TGL_UPDATE = (SELECT MAX(TGL_UPDATE) FROM HISTORY_CONTAINER WHERE NO_CONTAINER = d.NO_CONTAINER)
												AND a.ID_BLOCKING_AREA = c.ID 
												AND c.NAME = '$blok'
												AND a.SLOT_ = '$slot'
												AND a.ROW_ = '$row'
												AND b.LOCATION = 'IN_YARD'
												ORDER BY a.TIER_ DESC";
			$result__	= $db->query($query_);
			$data_		= $result__->getAll();
			
			// debug($data_);
			// debug($blok);
			
			if (count($data_) == 0)
			{
				$isi .="<table><tr><td colspan=\"2\" align=\"center\">Ra Ono Container</td></tr></table>";
			}
			else
			{
				$isi .="<table><tr><td colspan=\"2\" align=\"center\"><b>BLOK ".$blok."</b></td></tr>";
				
				foreach($data_ as $row_)
				{
					$isi .="<tr><td><img src=\"images/row_cont.png\" width=\"40\" height=\"40\"><br/>TIER".$row_['TIER_']."</td><td>".$row_['NO_CONTAINER']."<br>".$row_['SIZE_']."|".$row_['TYPE_']."|".$row_['STATUS']."</td></tr>";
				}
				
				$isi .="</table>";
				
			}
			
			
			echo($isi);
			
			

?>