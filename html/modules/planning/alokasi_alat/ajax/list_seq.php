<?php
 $id_vs = $_GET['id'];
 $id_alat = $_GET['id_alat']
 $db = getDB();
 
?>

<table border="1" bordercolor="#4D5A77" width=60% style="border-collapse:collapse">
					  <tr>
						<th style="background-color:#607095" width="20" height="30">NO</th>
						<th style="background-color:#607095" width="50">ALAT</th>
						<th style="background-color:#607095" width="50">BAY</th>
						<th style="background-color:#607095" width="50">POSISI</th>
						<th style="background-color:#607095" width="40">DELETE</th>
					 </tr>
					 
					 <?						
						$query_seq_alat = "SELECT DISTINCT B.ID_ALAT,
                                            A.BAY, 
                                            B.POSISI_STACK,
                                            B.SEQ_ALAT
                                    FROM STW_BAY_AREA A,STW_BAY_CELL B 
                                    WHERE A.ID_VS = '$id_vs' 
                                    AND A.ID = B.ID_BAY_AREA
                                    AND B.ID_ALAT = '$id_alat' 
                                    ORDER BY B.SEQ_ALAT ASC";
						$result19_    = $db->query($query_seq_alat);
						$seq_alat     = $result19_->getAll();
						
						$no = 1;
						foreach ($seq_alat as $row19)
						{
					 ?>
					 
					 <tr>  
						<td align="center" bgcolor="#FAFAFA" height="30"><?=$no;?></td> 
						<td align="center" bgcolor="#FAFAFA"><?=$row19['ID_ALAT'];?></td>
						<td align="center" bgcolor="#FAFAFA"><?=$row19['BAY'];?></td>
						<td align="center" bgcolor="#FAFAFA"><?=$row19['POSISI_STACK'];?></td>
						<td align="center" bgcolor="#FAFAFA"><input type="button" onclick="del_alat('{$hasil_details.ID_DETAILS}','{$status_form}')" value="Hapus" name="Hapus"/></td>
					</tr>
					 
					 <? $no++; } ?>
					 
</table>