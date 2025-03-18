<?php

	$tanggal=date("dFY");
	
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=ReportBMRealization-".$tanggal.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	/*header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=BPE_".$tanggal.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
*/
	$db 	= getDB();
	
	$date_  = date('d F Y h:i:s');
	
	$jenis		= $_GET["keg"];
	$id_vs		= $_GET["id_vs"];
	
	
	
	if ($jenis == 'disc'){
		$query_list_ = "/* Formatted on 3/20/2013 3:40:00 AM (QP5 v5.163.1008.3004) */
SELECT DISTINCT * FROM (SELECT b.KODE_STATUS, CONCAT (CONCAT (LPAD (a.BAY_, 3, 0), LPAD (a.ROW_, 2, 0)),
               LPAD (a.TIER_, 2, 0))
          POSISI,
       a.NO_CONTAINER,
       b.SIZE_,
       b.TYPE_,
       b.STATUS,
       B.BERAT,
       'I' ACT,
       a.ALAT,
       b.POD,
       b.POL,
       A.USER_CONFIRM,
       b.IMO,
       b.HZ,
       TO_CHAR (a.DATE_CONFIRM, 'dd Mon RRRR hh24:mi:ss') DATE_CONFIRM,
       'DISC' KEG
  FROM confirm_disc a, isws_list_container b
 WHERE     A.NO_CONTAINER = b.No_container
       AND a.no_ukk = b.No_ukk
       AND TRIM (a.no_ukk) = '$id_vs'
     )   ORDER BY DATE_CONFIRM DESC";
		$kegiatan 	 = 'DISCHARGE REALIZATION';
		
	
		$query = "select size_, type_, status_, hz, count(1) BOX, case when size_ = 20 then count(1) else count(1)*2 end TEUS, 'Disc' tipe from confirm_disc where no_ukk = '$id_vs' group by size_, type_, status_, hz order by size_, type_, status_, hz asc ";
	
	} else if ($jenis == 'load'){
		$query_list_ = "/* Formatted on 3/20/2013 3:40:00 AM (QP5 v5.163.1008.3004) */
SELECT DISTINCT * FROM (SELECT b.KODE_STATUS, CONCAT (CONCAT (LPAD (a.BAY_, 3, 0), LPAD (a.ROW_, 2, 0)),
               LPAD (a.TIER_, 2, 0))
          POSISI,
       a.NO_CONTAINER,
       b.SIZE_,
       b.TYPE_,
       b.STATUS,
       B.BERAT,
       'I' ACT,
       a.ALAT,
       b.POD,
       b.POL,
       A.USER_CONFIRM,
       b.IMO,
       b.HZ,
       TO_CHAR (a.DATE_CONFIRM, 'dd Mon RRRR hh24:mi:ss') DATE_CONFIRM,
	   'LOAD' KEG
  FROM confirm_load a, isws_list_container b
 WHERE     A.NO_CONTAINER = b.No_container
       AND a.no_ukk = b.No_ukk
       AND TRIM (a.no_ukk) = '$id_vs'
	 )   ORDER BY DATE_CONFIRM DESC";
		$kegiatan 	 = 'LOADING REALIZATION';
		
		$query = "select size_, type_, status_, hz, count(1) BOX, case when size_ = 20 then count(1) else count(1)*2 end TEUS, 'Load' tipe from confirm_load where no_ukk = '$id_vs' group by size_, type_, status_, hz order by size_, type_, status_, hz asc";
		
	} else {
		$query_list_ = "/* Formatted on 3/20/2013 3:40:00 AM (QP5 v5.163.1008.3004) */
SELECT DISTINCT * FROM (SELECT b.KODE_STATUS,CONCAT (CONCAT (LPAD (a.BAY_, 3, 0), LPAD (a.ROW_, 2, 0)),
               LPAD (a.TIER_, 2, 0))
          POSISI,
       a.NO_CONTAINER,
       b.SIZE_,
       b.TYPE_,
       b.STATUS,
       B.BERAT,
       'I' ACT,
       a.ALAT,
       b.POD,
       b.POL,
       A.USER_CONFIRM,
       b.IMO,
       b.HZ,
       TO_CHAR (a.DATE_CONFIRM, 'dd Mon RRRR hh24:mi:ss') DATE_CONFIRM,
	   'LOAD' KEG
  FROM confirm_load a, isws_list_container b
 WHERE     A.NO_CONTAINER = b.No_container
       AND a.no_ukk = b.No_ukk
       AND TRIM (a.no_ukk) = '$id_vs'
	   UNION
SELECT b.KODE_STATUS, CONCAT (CONCAT (LPAD (a.BAY_, 3, 0), LPAD (a.ROW_, 2, 0)),
               LPAD (a.TIER_, 2, 0))
          POSISI,
       a.NO_CONTAINER,
       b.SIZE_,
       b.TYPE_,
       b.STATUS,
       B.BERAT,
       'I' ACT,
       a.ALAT,
       b.POD,
       b.POL,
       A.USER_CONFIRM,
       b.IMO,
       b.HZ,
       TO_CHAR (a.DATE_CONFIRM, 'dd Mon RRRR hh24:mi:ss') DATE_CONFIRM,
	   'DISC' KEG
  FROM confirm_disc a, isws_list_container b
 WHERE     A.NO_CONTAINER = b.No_container
       AND a.no_ukk = b.No_ukk
       AND TRIM (a.no_ukk) = '$id_vs') ORDER BY DATE_CONFIRM DESC
	   ";
		$kegiatan 	 = 'LOAD AND DISCHARGE REALIZATION';
		
		$query = "select * from (select size_, type_, status_, hz, count(1) BOX, case when size_ = 20 then count(1) else count(1)*2 end TEUS, 'Disc' tipe from confirm_disc where no_ukk = '$id_vs' group by size_, type_, status_, hz
UNION
select size_, type_, status_,hz, count(1) BOX,case when size_ = 20 then count(1) else count(1)*2 end TEUS,'Load' tipe from confirm_load where no_ukk = '$id_vs' group by size_, type_, status_, hz)
order by size_, type_, status_, tipe asc";
	}
	
	$query_list = "SELECT NM_KAPAL, VOYAGE_IN, VOYAGE_OUT FROM RBM_H WHERE NO_UKK = '$id_vs'";
	$result1 	= $db->query($query_list);
	$row_list1 	= $result1->fetchRow();
	$nm_kapal	= $row_list1['NM_KAPAL'];
	$voyin		= $row_list1['VOYAGE_IN'];
	$voyout		= $row_list1['VOYAGE_OUT'];
	
	$hasil      = $db->query($query);
	$hasil1     = $hasil->getAll();
?>

<div style="font-family:Tahoma;font-size:8pt;" > 
<table>
<thead style="display: table-header-group;">
<tr> 
<td>
<div style="font-size:8pt" align="left">PT. PELABUHAN INDONESIA II (PERSERO)</div>
<div style="font-size:8pt" align="left">CABANG TANJUNG PRIOK</div>
<div style="font-size:8pt" align="right">&nbsp;</div>
<div style="font-size:8pt" align="right">Tanggal Cetak : <?=$date_;?></div>
<div style="font-size:8pt;" align="right;"></div>

<table align="center" border="1"  style="font-size:14pt" ><tr><td><i>VESSEL : <?=$nm_kapal?> - <?=$voyin?> / <?=$voyout?></i></td></tr></table>
<table align="left"   border="1"  style="font-size:8pt" ><tr>
  <td><b>&nbsp;&nbsp;&nbsp;ACTIVITY : <?=$kegiatan?></b></td>
</tr></table>
<table align="left"   border="1"  style="font-size:8pt; font-weight:bold" ><tr><td>
<table width="900"    border="0" cellspacing="0" cellpadding="0">
<tr >
<th  width="20"   align="center">&nbsp;No</th>
<th  width="60"   align="center">&nbsp;No Container</th>
<th  width="32"   align="center">&nbsp;Posisi</th>
<th  width="20"   align="center" >&nbsp;Size</th>
<th  width="25"   align="center">&nbsp;Type</th>
<th  width="27"   align="center" >&nbsp;Status</th>
<th  width="27"   align="center">&nbsp;Gross</th>
<th  width="15"   align="center">&nbsp;HZ</th>
<th  width="15"   align="center">&nbsp;E/I</th>
<th  width="20"   align="center">&nbsp;IMO</th>
<th  width="25"   align="center">&nbsp;CC</th>
<th  width="28"   align="center">&nbsp;FDISC</th>
<th  width="28"   align="center" >&nbsp;POL</th>
<th  width="60"   align="center" >&nbsp;ACT</th>
<th  width="60"   align="center" >&nbsp;Kode Status</th>
<th  width="60"   align="center" >&nbsp;User ID</th>
<th  width="106"   align="center" >&nbsp;DateTime</th>
</tr> 
</table>
</td>
</tr>
</table>

<table align="left"   border="0"  style="font-size:8pt; " >
<tr><td>
<table width="900"  border="0" cellspacing="0" cellpadding="0">
<? 	
	$result 	= $db->query($query_list_);
	$row_list 	= $result->getAll();
	$i = 1;
	foreach($row_list as $row){

?>
<tr >  
<td  width="20"  align="center"><?=$i?></td> 
<td  width="60"  align="center"><?=$row['NO_CONTAINER'];?></td>
<td  width="32"  align="center"><?=$row['POSISI'];?></td>
<td  width="20"  align="center"><?=$row['SIZE_'];?></td>
<td  width="25"  align="center"><?=$row['TYPE_'];?></td>
<td  width="27"  align="center"><?=$row['STATUS'];?></td> 
<td  width="27"  align="right"><?=$row['BERAT'];?></td>
<td  width="15"  align="center"><?=$row['HZ'];?></td>
<td  width="15"  align="center">I</td>
<td  width="20"  align="center"><?=$row['IMO'];?></td>
<td  width="25"  align="center"><?=$row['ALAT'];?></td>
<td  width="28"  align="center"><?=$row['POD'];?></td>
<td  width="28"  align="center"><?=$row['POL'];?></td>
<td  width="28"  align="center"><?=$row['KEG'];?></td>
<td  width="28"  align="center"><?=$row['KODE_STATUS'];?></td>
<td  width="60"  align="center" style="text-transform:uppercase"><?=$row['USER_CONFIRM'];?></td>
<td  width="106"  align="center"><?=$row['DATE_CONFIRM'];?></td>
</tr>
<?$i++;}?>
</table>
</td></tr>
</table>


</td>
</tr>
</thead>
</table>
<br>
<br>
<h3>Resume :</h3>
<br>
<table border="1">
	<tr>
		<td align="center"><b>No</b></td>
		<td align="center"><b>Size</b></td>
		<td align="center"><b>Type</b></td>
		<td align="center"><b>Status</b></td>
		<td align="center"><b>Hz</b></td>
		<td align="center"><b>Box</b></td>
		<td align="center"><b>Teus</b></td>
		<td align="center"><b>Activity</b></td>
	</tr>
<?
	$i = 1;
	$box =0;
	$teus = 0;
	foreach($hasil1 as $rew){
?>
	<tr>
		<td align="center"><?=$i;?></td>
		<td align="center"><?=$rew['SIZE_'];?></td>
		<td align="center"><?=$rew['TYPE_'];?></td>
		<td align="center"><?=$rew['STATUS_'];?></td>
		<td align="center"><?=$rew['HZ'];?></td>
		<td align="center"><?=$rew['BOX'];?></td>
		<td align="center"><?=$rew['TEUS'];?></td>
		<td align="center"><?=$rew['TIPE'];?></td>
	</tr>
<?  $i++;
	$box  = $box + $rew['BOX'];
	$teus = $teus + $rew['TEUS'];
}?>
	<tr>
		<td align="center" colspan="5"><b> TOTAL </b></td>
		<td align="center"><b><?=$box;?></b></td>
		<td align="center"><b><?=$teus;?></b></td>
	</tr>


</div> 
