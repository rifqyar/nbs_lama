<?php if (!defined("XLITE_INCLUSION")) die(); ?><div id="list"><font color="red"><font size="5"><blink>Terdapat <?php echo($jumlah); ?> request yang belum di approve</blink></font></font><br><font color="blue"><font size="4">Total request yang telah di approve tanggal <?=date('d F Y')?> = <?php echo($total); ?> request (<?php echo($total_co); ?> box)</font></font><br><br><table width="100%"><thead><?php	if ($multipage): ?><tr><td align="center" style="padding-bottom:4px;" colspan="8" ><?php	if ($prevvisible): ?><a onclick="change_page(parseFloat($('#navsel_').val())-1)" style="cursor:pointer;">< Sebelumnya</a><?php	endif; ?><select id="navsel_" name="p" onchange="change_page($('#navsel_').val())" style="font-size:10px; border: solid 1px #333333; background-color:#FFFDCA;"><?php $this->renderSelectOptions(array (
),$navpage,$navlist,'0','1'); ?></select><?php	if ($nextvisible): ?><a onclick="change_page(parseFloat($('#navsel_').val())+1)" style="cursor:pointer;">Selanjutnya ></a><?php	endif; ?></td></tr><?php	endif; ?><tr><th class="grid-header" width="10px;">No.</th><th class="grid-header">No RENCANA</th><th class="grid-header">No REQUEST</th><th class="grid-header">TGL PENGAJUAN</th><th class="grid-header">PEMILIK</th><th class="grid-header">NO DO | BL</th><th class="grid-header">TYPE STRIPPING</th><th class="grid-header">&nbsp;</th></tr></thead><tbody><?php $__ctr=0;
foreach( $row_list as $rows ): 
$__ctr++; ?><?php $_ALT_V[0] = explode(',',"#f9f9f3,#ffe"); $_ALT_I[0]  = (isset($_ALT_I[0])?++$_ALT_I[0]:0) % count($_ALT_V[0]); $bg = $_ALT_V[0][$_ALT_I[0]]; ?><tr bgcolor="<?php echo($bg); ?>" style="cursor:pointer;" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor=""><td class="grid-cell" style="font-size:9px;"><?php echo($rows["__no"]); ?></td><td class="grid-cell" align="center" valign="middle" style="font-family:Arial; font-size:9pt; color:#555555"><b><?php echo($rows["NO_REQUEST"]); ?></b></td><td class="grid-cell" align="center" valign="middle" style="font-family:Arial; font-size:9pt; color:#555555"><b><?php if ($rows["STATUS_REQ"] == 'Blm di Approve') {?><blink style="color:red"><?php } ?><?php echo($rows["NO_REQUEST_APP"]); ?> <br/> <?php echo($rows["STATUS_REQ"]); ?><?php if ($rows["STATUS_REQ"] == 'Blm di Approve') {?></blink><?php } ?></b></td><td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><b><?php echo($rows["TGL_REQUEST"]); ?></b></td><td align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial; color:#0066CC;"><?php echo($rows["NAMA_PEMILIK"]); ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial; color:#0066CC;"><?php echo($rows["NO_DO"]); ?> | <?php echo($rows["NO_BL"]); ?></td><td align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial; color:#0066CC;"><?php echo($rows["TYPE_STRIPPING"]); ?></td><!-- <a href="<?php echo($HOME); ?><?php echo($APPID); ?>/view?no_req=<?php echo($rows["NO_REQUEST"]); ?>"> LIHAT </a> --><?php 
					$no_request  = $rows["NO_REQUEST"];
					$db  		= getDB("storage");
					$query_cek	= "SELECT NOTA, KOREKSI, LUNAS FROM REQUEST_STRIPPING LEFT JOIN NOTA_STRIPPING 
                    ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST WHERE REQUEST_STRIPPING.NO_REQUEST = REPLACE ('$no_request','P', 'S')";	
					$query_cek2	= "SELECT CLOSING FROM PLAN_REQUEST_STRIPPING WHERE NO_REQUEST = '$no_request'";	
					$result_cek	= $db->query($query_cek);
					$result_cek2	= $db->query($query_cek2);
					$row_cek 	= $result_cek->fetchRow();
					$row_cek2 	= $result_cek2->fetchRow();
					$nota		= $row_cek["NOTA"];
					$koreksi	= $row_cek["KOREKSI"];
					$lunas     	= $row_cek["LUNAS"];
					$closing	= $row_cek2["CLOSING"];
					$no_req_c = $no_request;
					
                    
					
					if((($rows["NO_REQUEST_APP"] != 'blm di approve')) AND ($row_cek["NOTA"] != 'Y') AND ($row_cek["KOREKSI"] != 'Y') AND ($closing == "CLOSED")){
						echo '<td class="grid-cell" align="center" style="font-size:14px;"> <a href="'.HOME.APPID.'/view?no_req='.$no_request.'" target="_blank">Request Approved</a> </td>';
					}
					else if(($rows["NO_REQUEST_APP"] == 'blm di approve') AND ($row_cek["NOTA"] != 'Y') AND ($row_cek["KOREKSI"] != 'Y') AND ($closing != "CLOSED")){
						echo '<td class="grid-cell" align="center"  style="font-size:14px;"> <a href="'.HOME.APPID.'/view?no_req='.$no_request.'" target="_blank"><blink style="color:red">EDIT</blink></a> </td>';
					}
					else if(($rows["NO_REQUEST_APP"] != 'blm di approve') AND ($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] != 'Y') AND ($closing == "CLOSED")){
                        if($lunas == 'NO'){
                            echo '<td class="grid-cell" align="center" style="font-size:14px;"> <a href="'.HOME.APPID.'/view?no_req='.$no_request.'" target="_blank">Request Approved</a> </td>';
                        } else {
                            echo '<td class="grid-cell" align="center"  style="font-size:14px;"> <a href="'.HOME.APPID.'/overview?no_req='.$no_request.'" target="_blank">Nota Sudah Cetak </a> </td>';
                        }
					}
					else if(($rows["NO_REQUEST_APP"] != 'blm di approve') AND ($row_cek["NOTA"] != 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($closing == "CLOSED")){
						echo '<td class="grid-cell" align="center"  style="font-size:14px;"> <a href="'.HOME.APPID.'/view?no_req='.$no_request.'" target="_blank">EDIT</a> </td>';
					} else if(($rows["NO_REQUEST_APP"] != 'blm di approve') AND ($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($closing == "CLOSED")){
                        if($lunas == 'NO'){
                            echo '<td class="grid-cell" align="center" style="font-size:14px;"> <a href="'.HOME.APPID.'/view?no_req='.$no_request.'" target="_blank">Request Approved</a> </td>';
                        }
                        else {
						echo '<td class="grid-cell" align="center"  style="font-size:14px;"> <a href="'.HOME.APPID.'/overview?no_req='.$no_request.'" target="_blank">Nota Sudah Cetak </a> </td>';
                        }
					}else{
						echo '<td class="grid-cell" align="center"  style="font-size:14px;"> <a href="'.HOME.APPID.'/view?no_req='.$no_request.'" target="_blank">EDIT</a> </td>';
					}

				
				?></tr><?php endforeach; ?></tbody></table></div>