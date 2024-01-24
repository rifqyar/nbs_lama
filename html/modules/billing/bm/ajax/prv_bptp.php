<?php
$id=$_GET['id'];
$db=getDb();
$query="select cust_name, cust_tax_no, cust_addr, vessel, voy_in||'-'||voy_out as voyage,
		to_char(DPP_,'9,999,999,999,999.00') DPP_,
		to_char(PPN_,'9,999,999,999,999.00') PPN_,
		to_char(TTL_,'9,999,999,999,999.00') TTL_,
		to_char(DISC_,'9,999,999,999,999.00') DISC_,
		to_char(TO_DATE(ATA,'yyyymmddhh24miss'),'dd-mm-yyyy') as ata
		from bil_rpstv_h where id_rpstv='$id'";

$hqry=$db->query($query);
$head=$hqry->fetchRow();
$queryd="select KEG, EI,'O' AS OI, TY_CC,SIZE_CONT, CASE WHEN HGH_CONT_TERM = 'OOG'
            THEN CONCAT(TYPE_CONT,CONCAT('/',HGH_CONT_TERM))
            ELSE   TYPE_CONT END TYPE_CONT, STS_CONT, HZ_CONT,HZ_LABEL, 
		QTY_RPSTV, to_char(FARE_,'9,999,999,999,999.00') FARE_, VAL,to_char(TTL_FARE,'9,999,999,999,999.00') TTL_FARE from bil_rpstv_d where id_rpstv='$id' AND KEG<>'ADM'order by EI, SIZE_CONT, TYPE_CONT, STS_CONT";
$dqry=$db->query($queryd);
$dtl=$dqry->getAll();

$queryadm="select to_char(TTL_FARE,'9,999,999,999,999.00') TTL_FARE from bil_rpstv_d where id_rpstv='$id' AND KEG='ADM'";
$admqry=$db->query($queryadm);
$adm=$admqry->fetchRow();


?>

<div class="form-fieldset" style="margin: 5px 5px 5px 5px">
	<table border="1" width="100%">
    	<tr>
        	<td align="center">
            	<fieldset >
                	<div style="background-color:#fff; height:100%; border:thin #00F groove">
						<table>
                <tr height="25">
                    <td COLSPAN="29" align="left"></td>
                </tr>
						<tr>
                    <td COLSPAN="29" align="left"><b>PT. PELABUHAN INDONESIA II (PERSERO)</b></td>
                </tr>
                <tr>
                    <td COLSPAN="29" align="left"><b>CABANG TANJUNG PRIOK</b></td>
                </tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="4" align="right">No. Inovoice</td>
					<td colspan="1" align="right">:</td>
					<td colspan="8" align="left"><td>
				</tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="4" align="right">No. Doc</td>
					<td colspan="1" align="right">:</td>
					<td colspan="8" align="left"><?=$id;?></td>
				</tr>
				
				<tr>
					<td COLSPAN="17"></td>
					<td COLSPAN="4" align="right">Tgl.Proses</td>
					<td colspan="1" align="right">:</td>
					<td COLSPAN="8" align="left"><?=date('d-M-Y H:i')?></td>
				</tr>
				<tr>
					<td COLSPAN="17"></td>
					<td COLSPAN="4" align="right">Halaman</td>
					<td colspan="1" align="right">:</td>
					<td COLSPAN="8" align="left">1/1</td>
				</tr>

                <tr height="30">
                    <td COLSPAN="29" align="left"></td>
                </tr>
				<tr>
					<td COLSPAN="29" align="center"><b>NOTA PERHITUNGAN PELAYANAN JASA<br>
BONGKAT / MUAT</b></font></td>
				</tr>
               <tr height="30">
                    <td COLSPAN="29" align="left"><hr></td>
                </tr>
				<tr>
					<td colspan="4">Customer</td>
					<td>:</td>
					<td colspan="3" align="left"><?=$head[CUST_NAME];?></td>
					<td colspan="9"></td>
					<td colspan="3">No DO</td>
					<td colspan="0">:</td>
					<td colspan="4" align="left"></td>
				</tr> 
				<tr>
					<td colspan="4">NPWP</td>
					<td colspan="0">:</td>
					<td colspan="3" align="left"><?=$head[CUST_TAX_NO];?></td>
					<td  colspan="9"></td>
					<td colspan="3">No BL/PEB</td>
					<td colspan="0">:</td>
					<td colspan="4" align="left"></td>
				</tr>
				<tr>
					<td colspan="4">Alamat</td>
					<td colspan="0">:</td>
					<td colspan="3" align="left"><?=$head[CUST_ADDR];S?></td>
					<td  colspan="9"></td>
					<td colspan="3">Disch/Load</td>
					<td colspan="0">:</td>
					<td colspan="4" align="left">Disch/Load</td>
				</tr>
				<tr>
					<td colspan="4">Vessel/Voyage</td>
					<td colspan="0">:</td>
					<td colspan="3" align="left"><?=$head[VESSEL]?> <?=$head[VOYAGE]?></td>
					<td colspan="9" ></td>
					<td colspan="3">Tgl Tiba</td>
					<td colspan="0">:</td>
					<td colspan="4" align="left"><?=$head[ATA]?></td>
				</tr>
                   
               <tr height="30">
                    <td COLSPAN="29" align="left"><hr></td>
                </tr>
				<tr>
					<td colspan="3"></td>
					<td colspan="5" width="50"><b>KETERANGAN</b></td>
					<td align="center" width="20"><b>E/I</b></td>
					<td align="center" width="40"><b>O/I</b></td>
					<td align="center" width="60"><b>CRANE</b></td>
					<td align="center" width="20"><b>SZ</b></td>
					<td  width="40" align="center"><b>TY</b></td>
					<td  width="40" align="center"><b>ST</b></td>
					<td  width="10" align="center"><b>HZ</b></td>
					<td  align="center" width="20"><b>BOX</b></td>
					<td colspan="4" width="25" align="right"><b>TARIF</b></td>
					<td colspan="2" width="20" align="right"><b>VAL</b></td>
					<td colspan="5" align="right" width="80"><b>JUMLAH</b></td>
					<td colspan="2"></td>
				</tr>
				    <tr height="30">
                    <td COLSPAN="29" align="left"><hr></td>
                </tr>
				<? foreach ($dtl as $row){?>
				<tr>
					<td colspan="3"></td>
					<td colspan="5" width="50"><?=$row[KEG];?></td>
					<td align="center" width="20"><?=$row[EI];?></td>
					<td align="center" width="40"><?=$row[OI];?></td>
					<td align="center" width="60"><?=$row[TY_CC];?></td>
					<td align="center" width="20"><?=$row[SIZE_CONT];?></td>
					<td  width="40" align="center"><?=$row[TYPE_CONT];?></td>
					<td  width="40" align="center"><?=$row[STS_CONT];?></td>
					<td  width="10" align="center"><? echo $row[HZ_CONT]; if($row[HZ_CONT]=='Y'){ echo '('.$row[HZ_LABEL].')';}?></td>
					<td  align="center" width="20"><?=$row[QTY_RPSTV];?></td>
					<td colspan="4" width="25" align="right"><?=$row[FARE_];?></td>
					<td colspan="2" width="20" align="right"><?=$row[VAL];?></td>
					<td colspan="5" align="right" width="80"><?=$row[TTL_FARE];?></td>
					<td colspan="2"></td>
				</tr>
				<?}?>
                    <tr height="30">
                    <td COLSPAN="29" align="left"><hr></td>
                </tr>
				<tr><td></td></tr>
				<tr><td></td></tr>
				<tr><td></td></tr>
				<tr>
					<td colspan="13"></td>
					<td colspan="7"  align="right">Discount :</td>
                    <td colspan="7" align="right"><?=$head[DISC_];?></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="13"></td>
					<td colspan="7"  align="right">Administrasi :</td>
                    <td colspan="7" align="right"><?=$adm[TTL_FARE];?></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="13"></td>
					<td colspan="7"  align="right">Dasar Pengenaan Pajak :</td>
                    <td colspan="7" align="right"><?=$head[DPP_];?></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="13"></td>
					<td colspan="7"  align="right">Jumlah PPN :</td>
                    <td colspan="7" align="right"><?=$head[PPN_];?></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="13"></td>
					<td colspan="7"  align="right">Jumlah PPN Subsidi :</td>
                    <td colspan="7" align="right">0.00</td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="13"></td>
					<td colspan="7"  align="right">Jumlah Dibayar :</td>
                    <td colspan="7" align="right"><?=$head[TTL_];?></td>
					<td colspan="2"></td>
				</tr>
				<tr height="100"><td></td></tr>
				<tr>
					<td># nominal</td>
				</tr>
						</table>
                    </div>
                </fieldset>
            </td>
		</tr>
    </table>
 </div>