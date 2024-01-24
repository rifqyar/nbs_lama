<?php

	$tanggal=date("dmY");
	
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=ReportGate-".$tanggal.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	/*header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=BPE_".$tanggal.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
*/
	$db 	= getDB();
	
	$tgl_awal	= $_GET["tgl_awal"]; 
	$tgl_akhir	= $_GET["tgl_akhir"]; 
	$jenis		= $_GET["jenis"];
	$shift		= $_GET["shift"]; 
	$size		= $_GET["size"]; 
	$status		= $_GET["status"];
	$type		= $_GET["type"]; 
	$id_vs		= $_GET["id_vs"];
	
if ($shift == NULL){
		$query_shift = '';
	} else {
		if ($shift == 1){
			$query_shift = "and to_date(substr(to_char(a.tgl_gate_in,'mm/dd/rrrr hh24:mi:ss'),11,9),'hh24:mi:ss')  between to_date('08:00:00','hh24:mi:ss')
and to_date('20:00:00','hh24:mi:ss')";
		} else if ($shift == 2){
			$query_shift = "and to_date(substr(to_char(a.tgl_gate_in,'mm/dd/rrrr hh24:mi:ss'),11,9),'hh24:mi:ss')  between to_date('20:00:00','hh24:mi:ss')
and to_date('08:00:00','hh24:mi:ss')";
		} else {
			$query_shift = "";
		}
	}
	
	if ($size == NULL){
		$query_size = '';
	} else {
		if ($size == 20){
			$query_size = "and a.size_ = '20'";
		} else if ($size == 40){
			$query_size = "and a.size_ = '40'";
		} else if ($size == 45){
			$query_size = "and a.size_ = '45'";
		} else {
			$query_size = '';
		}
	}
	
	if ($status == NULL){
		$query_status = '';
	} else {
		if ($status == 'FCL'){
			$query_status = "and REGEXP_REPLACE (a.status, '[[:space:]]', '') = 'FCL'";
		} else if ($status == 'MTY'){
			$query_status = "and REGEXP_REPLACE (a.status, '[[:space:]]', '') = 'MTY'";
		} else {
			$query_status = "";
		}
	}
	
	if ($id_vs == NULL){
		$query_vs = '';
	} else {
	    $query_vs = "and REGEXP_REPLACE (b.NM_KAPAL, '[[:space:]]', '') = '$id_vs'";
	}
	
	if ($type == NULL){
		$query_type = '';
	} else {
		if ($type == 'DRY'){
			$query_type = "and REGEXP_REPLACE (a.type_, '[[:space:]]', '') = 'DRY'";
		} else if ($type == 'FLT'){
			$query_type = "and REGEXP_REPLACE (a.type_, '[[:space:]]', '') = 'FLT'";
		} else if ($type == 'HQ'){
			$query_type = "and REGEXP_REPLACE (a.type_, '[[:space:]]', '') = 'HQ'";
		} else if ($type == 'OVD'){
			$query_type = "and REGEXP_REPLACE (a.type_, '[[:space:]]', '') = 'OVD'";
		} else if ($type == 'RFR'){
			$query_type = "and REGEXP_REPLACE (a.type_, '[[:space:]]', '') = 'RFR'";
		} else if ($type == 'TNK'){
			$query_type = "and REGEXP_REPLACE (a.type_, '[[:space:]]', '') = 'TNK'";
		} else {
			$query_type = "";
		}
	}
	
	//echo $tgl_awal;die;
	

	
	if ($jenis == 'GATI'){
		$query_list_ = "SELECT TO_CHAR (a.TGL_GATE_IN, 'dd Mon rrrr hh24:ii:ss') TGL_GATI,
                           a.NO_CONTAINER,
                           a.SIZE_,
                           a.TYPE_,
                           a.STATUS,
                           d.NM_KAPAL,
                           d.VOYAGE_IN,
                           d.VOYAGE_OUT,
                           a.BLOCK,
                           a.SLOT,
                           a.USER_CONFIRM,
                          (SELECT c.NO_POLISI FROM TB_CONT_JOBSLIP c WHERE a.NO_CONTAINER = c.NO_CONT) NO_POLISI,
                           a.BERAT,
                           (SELECT b.KLASIFIKASI FROM MASTER_TONAGE b WHERE (a.BERAT > MIN_ and a.berat < MAX_ ) and (b.SIZE_ = a.SIZE_)) KATEGORI_BERAT,
                           a.HZ,
                           a.SEAL_ID
                      FROM ISWS_LIST_CONTAINER a, RBM_H d
                      WHERE a.NO_UKK = d.NO_UKK
						    and substr(to_date(a.tgl_gate_in,'dd/mm/yyyy'),0,9) between to_date('$tgl_awal', 'dd/mm/yyyy') and to_date('$tgl_akhir', 'dd/mm/yyyy')
						   ".$query_shift." ".$query_size." ".$query_status." ".$query_type." ".$query_vs." order by a.TGL_GATE_IN desc ";
	} else if ($jenis == 'GATO'){
		$query_list_ = "SELECT TO_CHAR (a.TGL_GATE_OUT, 'dd Mon rrrr hh24:ii:ss') TGL_GATOUT,
                           a.NO_CONTAINER,
                           a.SIZE_,
                           a.TYPE_,
                           a.STATUS,
                           d.NM_KAPAL,
                           d.VOYAGE_IN,
                           d.VOYAGE_OUT,
                           a.BLOCK,
                           a.SLOT,
                           a.USER_CONFIRM,
                          (SELECT c.NO_POLISI FROM TB_CONT_JOBSLIP c WHERE a.NO_CONTAINER = c.NO_CONT) NO_POLISI,
                           a.BERAT,
                           (SELECT b.KLASIFIKASI FROM MASTER_TONAGE b WHERE (a.BERAT > MIN_ and a.berat < MAX_ ) and (b.SIZE_ = a.SIZE_)) KATEGORI_BERAT,
                           a.HZ,
                           a.SEAL_ID
                      FROM ISWS_LIST_CONTAINER a, RBM_H d
                      WHERE a.NO_UKK = d.NO_UKK
						    and substr(to_date(a.tgl_gate_out,'dd/mm/yyyy'),0,9) between to_date('$tgl_awal', 'dd/mm/yyyy') and to_date('$tgl_akhir', 'dd/mm/yyyy')
						   ".$query_shift." ".$query_size." ".$query_status." ".$query_type." ".$query_vs." order by a.TGL_GATE_IN desc  ";
	} else {
		$query_list_ = " SELECT TO_CHAR (a.TGL_GATE_IN, 'dd Mon rrrr hh24:ii:ss') TGL_GATIN,
							TO_CHAR (a.TGL_GATE_OUT, 'dd Mon rrrr hh24:ii:ss') TGL_GATOUT,
                           a.NO_CONTAINER,
                           a.SIZE_,
                           a.TYPE_,
                           a.STATUS,
                           d.NM_KAPAL,
                           d.VOYAGE_IN,
                           d.VOYAGE_OUT,
                           a.BLOCK,
                           a.SLOT,
                           a.USER_CONFIRM,
                          (SELECT c.NO_POLISI FROM TB_CONT_JOBSLIP c WHERE a.NO_CONTAINER = c.NO_CONT) NO_POLISI,
                           a.BERAT,
                           (SELECT b.KLASIFIKASI FROM MASTER_TONAGE b WHERE (a.BERAT > MIN_ and a.berat < MAX_ ) and (b.SIZE_ = a.SIZE_)) KATEGORI_BERAT,
                           a.HZ,
                           a.SEAL_ID
                      FROM ISWS_LIST_CONTAINER a, RBM_H d
                      WHERE a.NO_UKK = d.NO_UKK
						    and substr(to_date(a.tgl_gate_out,'dd/mm/yyyy'),0,9) between to_date('$tgl_awal', 'dd/mm/yyyy') and to_date('$tgl_akhir', 'dd/mm/yyyy')
						   ".$query_shift." ".$query_size." ".$query_status." ".$query_type." ".$query_vs." order by a.TGL_GATE_IN desc   ";
	}
	
	$result 	= $db->query($query_list_);
	$row_list 	= $result->getAll();

?>

 <div id="list">
	
	 <table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="100%" >
	 <tr>
		<td colspan="16" align="center"><b>REPORT GATE EX.TBB PER PERIODIK </b></td>
	 </tr>
	  <tr>
		<td colspan="16"  align="center"><b>PERIODE <?=$tgl_awal?> s/d <?=$tgl_akhir?></b></td>
	 </tr>
	   <tr>
		<td colspan="16"  align="center"><b>TERMINAL OPERASI III CABANG TANJUNG PRIOK </b></td>
	 </tr>
	  <tr>
		<td colspan="16"></td>
	 </tr>
	</table> 
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
                              <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header"  style="font-size:10pt">No </th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">No.Container</th>
								   <th valign="top" class="grid-header"  style="font-size:10pt">Size</th>
								    <th valign="top" class="grid-header"  style="font-size:10pt">Type</th>
                                  <th  valign="top" class="grid-header"  style="font-size:10pt">Status</th>	  
								  <th valign="top" class="grid-header"  style="font-size:10pt">Vessel/Voyage</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">Tgl Gate</th>
								   <th valign="top" class="grid-header"  style="font-size:10pt">Alokasi Yard</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">No Polisi</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">No Seal</th> 
								  <th valign="top" class="grid-header"  style="font-size:10pt">Berat</th>
								   <th valign="top" class="grid-header"  style="font-size:10pt">Kategori Berat</th>
								  <th valign="top" class="grid-header"  style="font-size:10pt">Operator Gate</th>
                              </tr>
                              <?php $i=0; 
							  foreach($row_list as $rows){ $i++;?>
                              <tr bgcolor="#f9f9f3">
                                  <td width="4%" align="center" valign="middle" style="color:#000;  font-size:9pt"><?php echo $i; ?></td>
                                  <td width="22%" align="center" valign="middle"   style=" font-size:11pt; color:#555555"><b><?php echo $rows["NO_CONTAINER"]; ?></b></td>
                                  <td width="15%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows["SIZE_"]; ?></td>
                                  <td width="15%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows["TYPE_"]; ?></td>
                                  <td width="15%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows["STATUS"]; ?></td>
                                  <td width="30%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows["NM_KAPAL"]; ?> / <?php echo $rows["VOYAGE_IN"]; ?></td>
                                  <td width="15%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows["TGL_GATI"]; ?></td>
                                 <td width="15%" align="center" valign="middle"  style="font-size:9pt">Block <?php echo $rows["BLOCK"]; ?> / SLot <?php echo $rows["SLOT"]; ?></td>
                                  <td width="15%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows["NO_POLISI"]; ?></td>
                                  <td width="15%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows["SEAL_ID"]; ?></td>
								  <td width="15%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows["BERAT"]; ?></td>
								  <td width="15%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows["KATEGORI_BERAT"]; ?></td>
                                  <td width="15%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows["USER_CONFIRM"]; ?></td>
							</tr>
							<? }?>
        </table>
 </div>