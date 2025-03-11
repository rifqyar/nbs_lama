<?php if (!defined("XLITE_INCLUSION")) die(); ?><div id="list"><table width="100%"><thead><tr><th class="grid-header" width="5px">No.</th><th class="grid-header">No CONTAINER</th><th class="grid-header">ASAL CONT</th><th class="grid-header">SIZE</th><th class="grid-header">TIPE</th><th class="grid-header">HZ</th><th class="grid-header">COMMODITY</th><th class="grid-header">VIA</th><th class="grid-header">START STACK EMPTY</th><th class="grid-header">EMPTY S/D</th><?php if($_GET["overview"] != 'yes'){ ?><th class="grid-header">APPROVE</th><th width="70px" class="grid-header">&nbsp;</th><?php } ?></tr></thead><tbody><?php $i = 0;?><?php $__ctr=0;
foreach( $row_list as $rows ): 
$__ctr++; ?><?php $_ALT_V[0] = explode(',',"#f9f9f3,#ffe"); $_ALT_I[0]  = (isset($_ALT_I[0])?++$_ALT_I[0]:0) % count($_ALT_V[0]); $bg = $_ALT_V[0][$_ALT_I[0]]; ?><tr bgcolor="<?php echo($bg); ?>" style="cursor:pointer;" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor=""><td class="grid-cell" align="center" style="font-size:14px;"><?php echo($__ctr); ?></td><input id="no" type="hidden" value="<?php echo($__ctr); ?>" /><td class="grid-cell" align="center" valign="middle" style="font-family:Arial; font-size:11pt; color:#555555"><b><? print_r($rows['NO_CONTAINER']); ?></b><input type="hidden" id="no_cont" value="<?php echo($rows["NO_CONTAINER"]); ?>" /></td><td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo($rows["ASAL_CONT"]); ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo($rows["KD_SIZE"]); ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo($rows["KD_TYPE"]); ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo($rows["HZ"]); ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo($rows["COMMO"]); ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo($rows["TYPE_STUFFING"]); ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo($rows["STACK"]); ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php if($_GET["overview"] != 'yes'){ ?><? if (($_SESSION["ID_ROLE"] == 5) OR ($_SESSION["ID_ROLE"] == 1)) {?><input type="text" id="TGL_APPROVE_<?php echo($__ctr); ?>" name="TGL_APPROVE_<?php echo($__ctr); ?>" value="<?php echo($rows["APPROVE"]); ?>" placeholder="<?php echo($rows["APPROVE"]); ?>" /><!-- <input type="text" name="TGL_APPROVE_<?php echo($__ctr); ?>" value="<?php echo($rows["APPROVE"]); ?>" placeholder="<?php echo($rows["APPROVE"]); ?>" /> --><? } else {?><input type="text" name="TGL_APPROVE_<?php echo($__ctr); ?>" value="<?php echo($rows["APPROVE"]); ?>" placeholder="<?php echo($rows["APPROVE"]); ?>" readonly="readonly" /><? } ?></td><?
					//menquery 	container stuffing, untuk mengecek stuffing sudah diapprove atau belum
					$db 	= getDB("storage");
					$no_req_stuf = str_replace('P', 'S', $rows['NO_REQUEST']);
					$no_cont=$rows['NO_CONTAINER'];
					
					$query_list_cek		= "SELECT DISTINCT CONTAINER_STUFFING.NO_REQUEST
											   FROM CONTAINER_STUFFING LEFT JOIN MASTER_CONTAINER M        
											   ON CONTAINER_STUFFING.NO_CONTAINER = M.NO_CONTAINER
											   WHERE CONTAINER_STUFFING.NO_REQUEST = '$no_req_stuf'
											   AND CONTAINER_STUFFING.NO_CONTAINER = '$no_cont'
											   ";
					
					$result_list_cek	= $db->query($query_list_cek);
					$row_list_cek		= $result_list_cek->fetchRow();
					$cek 				= $row_list_cek["NO_REQUEST"];
					
					
					if($cek == NULL)
					{
					?><td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><input id="demo8" value="Approve" type="button" onclick="update_tgl_approve('<?php echo($rows["NO_CONTAINER"]); ?>',$('#TGL_APPROVE_<?php echo($__ctr); ?>').val(),'<?php echo($rows["ASAL_CONT"]); ?>');" /><input value="Info" type="button" onclick="info_lapangan();" /></td><?
					}
					else{
					?><td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><input id="demo8" value="Approved" style="color:red" type="button" readonly="1" /><input value="Info" type="button" onclick="info_lapangan();" /></td><?
					}
					?><td style="padding: 5px 5px 5px 5px;"><button value=" Hapus " onclick="del_cont('<?php echo($rows["NO_CONTAINER"]); ?>','<?php echo($rows["NO_REQ_SP2"]); ?>')" > &nbsp;Hapus&nbsp; </button></td><?php } else{  ?><input type="text" name="TGL_APPROVE_<?php echo($__ctr); ?>" placeholder="<?php echo($rows["APPROVE"]); ?>" readonly="readonly" /><?php }  ?></tr><?php $i++;?><?php endforeach; ?></tbody></table><input type="hidden" id="total" value="<?php echo($__ctr); ?>" /><div id="button_save"><button type="button" onclick="save_request($('#total').val())" value="Save"> Save </button></div></div>