<?php
$db = getDB();
$idc= $_POST['IDC'];

$query 			= "select A.NO_CONTAINER,A.SIZE_, A.TYPE_CONT, A.STATUS_CONT, A.HZ, A.TON, A.PELABUHAN_TUJUAN, B.NM_KAPAL, B.VOYAGE_IN, 
					A.NAMA_BLOCK, A.SLOT_YARD, A.ROW_YARD, A.TIER_YARD
					FROM YD_PLACEMENT_YARD A, TR_VESSEL_SCHEDULE_ICT B
					WHERE 
					A.ID_VS=B.NO_UKK 
					AND
					A.ID_CELL= '$idc' ";
					//ECHO $query;die;
				
$f=$db->query($query);
$r=$f->fetchRow();


echo $r['NO_CONTAINER'].','.$r['SIZE_'].','.$r['TYPE_CONT'].','.$r['STATUS_CONT'].','.$r['HZ'].','.$r['TON'].','.$r['NM_KAPAL'].'-'.$r['VOYAGE_IN'].','.$r['PELABUHAN_TUJUAN'].',Blok '.$r['NAMA_BLOCK'].' Slot '.$r['SLOT_YARD'].' Row '.$r['ROW_YARD'].' Tier '.$r['TIER_YARD'];
?>