<?php if (!defined("XLITE_INCLUSION")) die(); ?><div id="list"><table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="100%" ><?php	if ($multipage): ?><tr><td align="center" style="padding-bottom:4px;" colspan="7" ><?php	if ($prevvisible): ?><a onclick="change_page(parseFloat($('#navsel_').val())-1)" style="cursor:pointer;">< Sebelumnya</a>   <?php	endif; ?><select id="navsel_" name="p" onchange="change_page($('#navsel_').val())" style="font-size:10px; border: solid 1px #333333; background-color:#FFFDCA;"><?php $this->renderSelectOptions(array (
),$navpage,$navlist,'0','1'); ?></select><?php	if ($nextvisible): ?>   <a onclick="change_page(parseFloat($('#navsel_').val())+1)" style="cursor:pointer;">Selanjutnya ></a><?php	endif; ?></td></tr><?php	endif; ?><tr style=" font-size:10pt"><th width="2%" valign="top" class="grid-header"  style="font-size:8pt">No </th><th width="110" valign="top" class="grid-header"  style="font-size:8pt">NO. REQUEST</th><th width="110" valign="top" class="grid-header"  style="font-size:8pt">PERP DARI (REQ LAMA)</th><th valign="top" class="grid-header"  style="font-size:8pt">EMKL/PEMILIK BARANG</th><th valign="top" class="grid-header"  style="font-size:8pt">VESSEL/VOYAGE</th><th valign="top" class="grid-header"  style="font-size:8pt">TOTAL BOX</th><th valign="top" class="grid-header"  style="font-size:8pt">ACTION</th></tr><? $db = getDB('storage');
			 for($i=0; $i<=$counter; $i++){
				$querys			= "select count(*) cek from m_vsb_voyage@dbint_link where id_vsb_voyage = '$o_idvsb[$i]'
                                and sysdate > to_date(clossing_time,'yyyymmddhh24miss')";
					//echo $querys;								
								$results		= $db->query($querys);
								$rows		= $results->fetchRow();
								
								if($rows['CEK'] > 0){
									//$tl->assign('close','sudah melewati closing time');
									$closing_time[$i] = 'sudah melewati closing time';
									//echo "sudah melewati closing time";
								}
								else {
									$html = "<a href=".$HOME.$APPID."/view_perp?no_req=".$no_request[$i]."><img src='images/ico_approval.gif' border='0' />&nbsp Perpanjangan</a>";
									$closing_time[$i] = $html;
									//echo $html;
								}
								
			 ?><tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor=""><td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:8pt"><?=$no[$i];?></td><td width="10%" align="center" valign="middle" class="grid-cell" style="font-family:Arial; font-size:9pt; color:#555555"><b><?=$no_request[$i]?></b><br></font></td><td width="10%" align="center" valign="middle" class="grid-cell" style="font-family:Arial; font-size:9pt; color:#555555"><b><?=$ex_req[$i]?></b><br></font></td><td width="15%" align="center" valign="middle" class="grid-cell" style="font-size:8pt"><?=$consignee[$i]?></td><td width="20%" align="center" valign="middle" class="grid-cell" style="font-size:8pt"><?=$nm_kapal[$i]?>/<?=$voyage[$i]?></td><td width="5%" align="center" valign="middle" class="grid-cell" style="font-size:8pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><b><?=$total[$i]?> BOX <?//=$ex_req[$i];?><?//=$nota[$i];?><?//=$lunas[$i];?><?//=$total[$i];?></b></font></td><td width="11%" align="center" valign="middle" class="grid-cell" style="font-size:9pt;"><?php if (($ex_req[$i] == '-') AND ($lunas_nota_baru[$i] == 'NO') ) //nota awal 
							{ 	if($stuffing_dari[$i] == 'AUTO') { ?><a href="<?php echo($HOME); ?><?php echo($APPID); ?>/view_perp?no_req=<?=$no_request[$i]?>"><img src='images/ico_approval.gif' border='0' />&nbsp Perpanjangan</a><?php } else {?><i> &nbsp Nota awal belum lunas</i></a><?php } ?></td><? 	} 
					else if (($ex_req[$i] == '-') AND ($lunas_nota_baru[$i] == 'YES') ) //nota awal
							{?><!-- <a href="<?php echo($HOME); ?><?php echo($APPID); ?>/view_perp?no_req=<?=$no_request[$i]?>"><img src='images/ico_approval.gif' border='0' />&nbsp Perpanjangan</a> --><?=$closing_time[$i];?></td><? 	}	
					else if (($ex_req[$i] == '-') AND ($lunas_nota_baru[$i] == '0') ) //nota awal
							{ if($stuffing_dari[$i] == 'AUTO') { ?><a href="<?php echo($HOME); ?><?php echo($APPID); ?>/view_perp?no_req=<?=$no_request[$i]?>"><img src='images/ico_approval.gif' border='0' />&nbsp Perpanjangan</a><?php } else {?><i> &nbsp Nota awal belum lunas</i></a><?php } ?></td><?	} 
					else if (($ex_req[$i] <> '-') AND ($lunas_nota_lama[$i] == '0') AND ($lunas_nota_baru[$i] == '0') ) //nota awal
							{?><i> &nbsp Nota lama belum cetak</i></td><?	} 
					else if (($ex_req[$i] <> '-') AND ($lunas_nota_lama[$i] == '0') AND ($lunas_nota_baru[$i] == 'NO') ) //nota awal
							{?><i> &nbsp Nota lama belum cetak</i></td><?	} 
					else if (($ex_req[$i] <> '-') AND ($lunas_nota_lama[$i] == 'NO') AND ($lunas_nota_baru[$i] == '0') ) //nota awal
							{?><i> &nbsp Nota lama belum lunas</i></td><? 	} 
					else if (($ex_req[$i] <> '-') AND ($lunas_nota_lama[$i] == 'YES') AND ($lunas_nota_baru[$i] == 'YES') )  
							{?><?=$closing_time[$i];?></td><? 	} 
					else if (($ex_req[$i] <> '-') AND ($lunas_nota_lama[$i] == 'YES') AND ($lunas_nota_baru[$i] == 'NO') ) //nota awal 
							{?><i> &nbsp Nota baru belum lunas</i></td><? 	} 
					else if (($ex_req[$i] <> '-') AND ($lunas_nota_lama[$i] == 'YES') AND ($lunas_nota_baru[$i] == '0') ) //nota awal 
							{
                                if($stuffing_dari[$i] == 'AUTO') { ?><a href="<?php echo($HOME); ?><?php echo($APPID); ?>/view_perp?no_req=<?=$no_request[$i]?>"><img src='images/ico_approval.gif' border='0' />&nbsp Perpanjangan</a><?php } else {?><i> &nbsp Nota baru belum cetak</i></a><?php } ?></td><?php } ?></tr><? }?></table></div>