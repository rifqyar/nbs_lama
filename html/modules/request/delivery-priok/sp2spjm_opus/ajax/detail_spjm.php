<?php
$id_del=$_GET['id_del'];
	
$db = getDB();
$db2 = getDB('dbint');

$query1= "SELECT A.NO_CONT, B.NO_UKK FROM BIL_DELSPJM_D A INNER JOIN BIL_DELSPJM_H B ON (A.ID_DEL=B.ID_DEL) WHERE A.ID_DEL='$id_del' ORDER BY A.NO_CONT";
$res1 = $db->query($query1);
				 
?>

<script type="text/javascript">
$(document).ready(function(){
	$("#btnClose").click(function(){
		
		$('#detail_reqspjm').dialog('destroy').remove();
		$('#mainform').append('<div id="detail_reqspjm"></div>');
	});
	
});



</script>

	<br />
	<table>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
		<tr>
			<td colspan="3">
				<div id="tableinput" style="width:550px; overflow-x: scroll ;padding-top:10px; margin-left:10px; margin-bottom:10px">
					<table border="0" cellpadding="2" cellspacing="2" id="tableedit" width="550px">
						<tr height="20" bgcolor="#0066FF" align="center"> 
							<td class="grid-header" width="20">No</td>
							<td class="grid-header">NO CONTAINER</td>
							<td class="grid-header">STATUS CONTAINER</td>
						</tr>
						<?php
						$i=0;
						while ($row = $res1->fetchRow()) {
						    $i++;
							/*$query2="SELECT A.CONT_NO_BP NO_CONT, STATUS_CONT
									FROM TTD_BP_CONT A INNER JOIN TTM_BP_CONT B ON (A.BP_ID = B.BP_ID)
								   WHERE B.KD_CABANG = '01'
										 AND no_ukk LIKE '".$row[NO_UKK]."'
										 AND A.CONT_NO_BP LIKE '".$row[NO_CONT]."'
										 ORDER BY A.CONT_NO_BP";*/
							$query2="SELECT NO_CONTAINER NO_CONT, ACTIVITY STATUS_CONT
										FROM M_CYC_CONTAINER A INNER JOIN M_VSB_VOYAGE B ON (A.VESSEL = B.VESSEL AND A.VOYAGE_IN = B.VOYAGE_IN AND A.VOYAGE_OUT = B.VOYAGE_OUT)
									   WHERE B.ID_VSB_VOYAGE LIKE '".$row[NO_UKK]."'
											 AND A.NO_CONTAINER IN ('".$row[NO_CONT]."')";
							//echo($query2);die();
							$res2 = $db2->query($query2);
							$row2 = $res2->fetchRow();
							?>
							<tr bgcolor="#CEE3F6" height="20" align="center"> 
								<td width="20"><?=$i?></td>
								<td><?=$row2[NO_CONT]?></td>
								<td><?=$row2[STATUS_CONT]?></td>
							</tr>
							<?php
						}
						
						?>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" align="center">
				<input type="button" id="btnClose" value="&nbsp;Close&nbsp;"/>&nbsp;
			</td>
		</tr>
	</table>		