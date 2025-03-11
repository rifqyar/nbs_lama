<?php if (!defined("XLITE_INCLUSION")) die(); ?><div id="list"><table width="100%"><thead><tr style="font-size:8pt"><th class="grid-header" width="10px">No.</th><th class="grid-header">No CONTAINER</th><th class="grid-header">SIZE/TYPE</th><th class="grid-header">ASAL CONT</th><th class="grid-header">TGL BONGKAR</th><th class="grid-header">TGL MULAI</th><th class="grid-header">TGL APP MULAI</th><th class="grid-header">TGL SELESAI </th><th class="grid-header">TGL APP SELESAI</th><th class="grid-header">REMARKS</th><?php if($_GET["overview"] != 'yes') {?><th class="grid-header">APPROVE</th><th width="70px" class="grid-header">&nbsp;</th><?php }?></tr></thead><tbody><?php $__ctr=0;
foreach( $row_list as $rows ): 
$__ctr++; ?><?php $_ALT_V[0] = explode(',',"#f9f9f3,#ffe"); $_ALT_I[0]  = (isset($_ALT_I[0])?++$_ALT_I[0]:0) % count($_ALT_V[0]); $bg = $_ALT_V[0][$_ALT_I[0]]; ?><tr bgcolor="<?php echo($bg); ?>" style="cursor:pointer;" onMouseOver=this.style.backgroundColor="#BAD5FC" 
			onMouseOut=this.style.backgroundColor=""><td class="grid-cell" style="font-size:9px;"><?php echo($__ctr); ?></td><td class="grid-cell" align="center" valign="middle" style="font-family:Arial; font-size:8pt; color:#555555"><b><?php echo($rows["NO_CONTAINER"]); ?></b><input type="hidden" id="NO_CONT_<?php echo($__ctr); ?>" value="<?php echo($rows["NO_CONTAINER"]); ?>" /></td><td align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?php if($rows[ASAL_CONT] == 'TPK') 
				{ ?> <?php echo($rows["KD_SIZE"]); ?>/<?php echo($rows["KD_TYPE"]); ?> <?php 
				} 
				else 
				{ ?> <?php echo($rows["UKURAN"]); ?>/<?php echo($rows["TYPE"]); ?> <?php 
				} ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?php echo($rows["ASAL_CONT"]); ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?php echo($rows["TGL_BONGKAR"]); ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?php echo($rows["TGL_MULAI"]); ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?php if($_GET["overview"] != 'yes') 
				{ if($close != "CLOSED") { ?><? if (($_SESSION["ID_GROUP"] == 'J') OR ($_SESSION["ID_GROUP"] == 'K')) 
							{?><input type="text" class="tgl_approve" id="TGL_APPROVE_<?php echo($__ctr); ?>" name="TGL_APPROVE_<?php echo($__ctr); ?>" placeholder="<?php echo($rows["TGL_APPROVE"]); ?>" /><?  } 
							else if ($_SESSION["ID_GROUP"] == 6) 
							{?><input type="text" class="tgl_approve" name="TGL_APPROVE_<?php echo($__ctr); ?>" placeholder="<?php echo($rows["TGL_APPROVE"]); ?>" readonly="readonly" /><?  } 
					} else { ?><input type="text" class="tgl_approve" name="TGL_APPROVE_<?php echo($__ctr); ?>" placeholder="<?php echo($rows["TGL_APPROVE"]); ?>" readonly="readonly" /><?php }
				}
				else 
				{?><input type="text" class="tgl_approve" name="TGL_APPROVE_<?php echo($__ctr); ?>" placeholder="<?php echo($rows["TGL_APPROVE"]); ?>" readonly="readonly" /><?
				} ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?php echo($rows["TGL_SELESAI"]); ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?php 
				if($_GET["overview"] != 'yes') 
				{ if($close != "CLOSED") {?><? 
					if (($_SESSION["ID_GROUP"] == 'J') OR ($_SESSION["ID_GROUP"] == 'K')) 
					{?><input type="text" class="tgl_approve_selesai" id="TGL_APPROVE_SELESAI_<?php echo($__ctr); ?>" name="TGL_APPROVE_SELESAI_<?php echo($__ctr); ?>" placeholder="<?php echo($rows["TGL_APP_SELESAI"]); ?>" /><? 
					} 
					else if ($_SESSION["ID_GROUP"] == 6) 
					{?><input type="text" class="tgl_approve_selesai" name="TGL_APPROVE_SELESAI_<?php echo($__ctr); ?>" placeholder="<?php echo($rows["TGL_APP_SELESAI"]); ?>" readonly="readonly" /><? 
					} 
				  } else { ?><input type="text" class="tgl_approve_selesai" name="TGL_APPROVE_SELESAI_<?php echo($__ctr); ?>" placeholder="<?php echo($rows["TGL_APP_SELESAI"]); ?>" readonly="readonly" /><?php  } 
				} 
				else 
				{?><input type="text" class="tgl_approve_selesai" name="TGL_APPROVE_SELESAI_<?php echo($__ctr); ?>" placeholder="<?php echo($rows["TGL_APP_SELESAI"]); ?>" readonly="readonly" /><? 
				} ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?php if($_GET["overview"] != 'yes') 
				{?><?php 
					if (($_SESSION["ID_GROUP"] == 'J') OR ($_SESSION["ID_GROUP"] == 'K')) 
					{?><input type="text" name="remarks_<?php echo($__ctr); ?>" id="remarks_<?php echo($__ctr); ?>" placeholder="<?php echo($rows["REMARK"]); ?>" /><?php
					} 
					else if ($_SESSION["ID_GROUP"] == 6) 
					{?><input type="text" name="remarks_<?php echo($__ctr); ?>" placeholder="<?php echo($rows["REMARK"]); ?>" readonly="readonly" /><?php   
					} 
				}
				else 
				{ ?><input type="text" name="remarks_<?php echo($__ctr); ?>" placeholder="<?php echo($rows["REMARK"]); ?>" readonly="readonly" /><?php 
				} ?></td><?php if($_GET["overview"] != 'yes') 
				{
				
					$db 	= getDB("storage");
					$no_req_strip = str_replace('P', 'S', $rows['NO_REQUEST']);
					$no_cont=$rows['NO_CONTAINER'];
					
					$query_list_cek		= "SELECT DISTINCT CONTAINER_STRIPPING.NO_REQUEST
											   FROM CONTAINER_STRIPPING LEFT JOIN MASTER_CONTAINER M        
											   ON CONTAINER_STRIPPING.NO_CONTAINER = M.NO_CONTAINER
											   WHERE CONTAINER_STRIPPING.NO_REQUEST = '$no_req_strip'
											   AND CONTAINER_STRIPPING.NO_CONTAINER = '$no_cont'
											   ";
					
					$result_list_cek	= $db->query($query_list_cek);
					$row_list_cek		= $result_list_cek->fetchRow();
					$cek 				= $row_list_cek["NO_REQUEST"];
					
					
					if($cek == NULL && $close != "CLOSED")
					{ ?><td align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?php if (($_SESSION["ID_GROUP"] == 'J') OR ($_SESSION["ID_GROUP"] == 'K'))  { ?><input id="app_bt" value="Approve" type="button" onclick="update_tgl_approve_praya('<?php echo($rows["NO_CONTAINER"]); ?>',$('#TGL_APPROVE_<?php echo($__ctr); ?>').val(),'<?php echo($rows["ASAL_CONT"]); ?>', $('#TGL_APPROVE_SELESAI_<?php echo($__ctr); ?>').val(), $('#remarks_<?php echo($__ctr); ?>').val())" /><input value="Info" type="button" onclick="info_lapangan();" /><?php } else { ?> Approval di Uster <?php }?></td><?
					}
					else if($cek == NULL && $close == "CLOSED") { 
						if (($_SESSION["ID_GROUP"] == 'J') OR ($_SESSION["ID_GROUP"] == 'K'))  { ?><td align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><input id="demo8" value="Unapproved" style="color:red" type="button" readonly="1" /><input value="Info" type="button" onclick="info_lapangan();" /></td><?php } else { ?> Approval di Uster <?php } 
					}
					else
					{ if (($_SESSION["ID_GROUP"] == 'J') OR ($_SESSION["ID_GROUP"] == 'K'))  {
					?><td align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><input id="demo8" value="Approved" style="color:red" type="button" readonly="1" /><input value="Info" type="button" onclick="info_lapangan();" /></td><?
					} else {?> Approval di Uster <?php } }
					?><td style="padding: 5px 5px 5px 5px;"><button value=" Hapus " onclick="del_cont('<?php echo($rows["NO_CONTAINER"]); ?>')" > &nbsp;Hapus&nbsp; </button></td><?php 
				}?></tr><?php endforeach; ?></tbody></table><input type="hidden" id="total" value="<?php echo($__ctr); ?>" /><?php if (($_SESSION["ID_GROUP"] == 'J') OR ($_SESSION["ID_GROUP"] == 'K')) 
	{
		if($close == "CLOSED")
		{?><div id="button_save"><button type="button" onclick="unsave_request()" value="Unsave"> Unsave </button></div><?php } 
		else  {?><div id="button_save"><button type="button" onclick="save_request($('#total').val())" value="Save"> Save </button></div><?php } 
	}?></div>