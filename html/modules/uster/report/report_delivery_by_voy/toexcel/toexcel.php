<?php

$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=LAP_Repo_Perkapal_tanggal-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

	$nm_kapal 	= $_GET["nm_kapal"];
	$voyage_in	 	= $_GET["voyage"];
	// $no_booking = $_GET["no_booking"];
	$status		= $_GET["status"];
	$db 		= getDB("storage");
	
	if ($status == NULL)
	{
		$query_status = '';
	} 
	else 
	{
		if ($status == 'FCL')
		{
			$query_status = "and b.status = 'FCL'";
		} else if ($status == 'MTY')
		{
			$query_status = "and b.status = 'MTY'";
		} else if ($status == 'LCL')
		{
			$query_status = "and b.status = 'LCL'";
		} else 
		{
			$query_status = "";
		}
	}
	
	   // $query = "select  b.no_container, mc.size_, mc.type_, a.no_request, b.status, b.via, c.nopol, TO_CHAR(c.tgl_in,'dd/mm/rrrr hh:ii:ss') tgl_in,  
                // d.username, n.no_faktur no_nota, n.lunas
                // from request_delivery a, container_delivery b, master_container mc, border_gate_out c, master_user d, nota_delivery n
                // where a.no_request = b.no_request
                // and b.no_request = c.no_request(+)
                // and b.no_container = c.no_container(+)
                // and c.id_user = d.id(+)
				// and b.no_container = mc.no_container
                // and C.VIA is null
                // and a.no_request = n.no_request(+)
                // and a.no_booking = '$no_booking'". $query_status. "
                // order by c.tgl_in desc";
				
		// $query = "select  b.no_container, mc.size_, mc.type_, a.no_request, b.status, b.via, c.nopol, TO_CHAR(c.tgl_in,'dd/mm/rrrr hh:ii:ss') tgl_in,  
        //         nvl((select username from master_user where to_char(id)=to_char(c.id_user)),c.id_user) username, no_faktur no_nota, n.lunas
        //         from request_delivery a, container_delivery b, master_container mc, border_gate_out c, nota_delivery n
        //         where a.no_request = b.no_request
        //         and b.no_request = c.no_request(+)
        //         and b.no_container = c.no_container(+)
		// 		and b.no_container = mc.no_container
        //         --and C.VIA is null
        //         and a.no_request = n.no_request(+)
		// 		and n.status not in ('BATAL')
        //         and a.no_booking = '$no_booking'". $query_status. "
        //         order by c.tgl_in desc";	
				
			$query = "SELECT
				b.no_container,
				mc.size_,
				mc.type_,
				a.no_request,
				b.status,
				b.via,
				c.nopol,
				TO_CHAR(c.tgl_in, 'dd/mm/rrrr hh:ii:ss') tgl_in,
				nvl((SELECT username FROM master_user WHERE to_char(id)= to_char(c.id_user)), c.id_user) username,
				n.no_faktur no_nota,
				n.lunas
			FROM
				request_delivery a,
				container_delivery b,
				master_container mc,
				border_gate_out c,
				nota_delivery n
			WHERE
				a.no_request = b.no_request
				AND b.no_request = c.no_request(+)
				AND b.no_container = c.no_container(+)
				AND b.no_container = mc.no_container
				AND a.no_request = n.no_request(+)
				AND n.status NOT IN ('BATAL')
				$query_status
				AND a.VESSEL = '$nm_kapal'
				AND a.O_VOYIN = '$voyage_in'
			ORDER BY
				c.tgl_in DESC";
	
	$r_query = $db->query($query); 
	$row_q = $r_query->getAll();
	
	
?>

  <div id="list">
	<center><h2>KAPAL <?=$nm_kapal?> (<?=$voyage?>)</h2></center>
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
        <tr style=" font-size:10pt">
            <th valign="top" class="grid-header"  style="font-size:8pt">NO </th>
            <th valign="top" class="grid-header"  style="font-size:8pt">NO CONTAINER</th>
            <th valign="top" class="grid-header"  style="font-size:8pt">SIZE</th>
            <th valign="top" class="grid-header"  style="font-size:8pt">TYPE</th>
            <th valign="top" class="grid-header"  style="font-size:8pt">STATUS</th> 
            <th valign="top" class="grid-header"  style="font-size:8pt">NO REQUEST</th> 
            <th valign="top" class="grid-header"  style="font-size:8pt">VIA</th>
            <th valign="top" class="grid-header"  style="font-size:8pt">NOPOL</th>
            <th valign="top" class="grid-header"  style="font-size:8pt">TGL GATE OUT</th>
            <th valign="top" class="grid-header"  style="font-size:8pt">USER</th>
            <th valign="top" class="grid-header"  style="font-size:8pt">NO NOTA</th>
            <th valign="top" class="grid-header"  style="font-size:8pt">LUNAS</th>
        </tr>
		
		<?php $i=0;?>
        <?php foreach ($row_q as $rows) { ?>
		<?php  $i++;	?>
        <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
			<td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$i?> </td>								
			<td width="22%" align="center" valign="middle" class="grid-cell"   style="font-family:Arial; font-size:11pt; color:#555555"><b><?=$rows[NO_CONTAINER]?></b></td>
			<td width="22%" align="center" valign="middle" class="grid-cell"   style="font-family:Arial; font-size:11pt; color:#555555"><b><?=$rows[SIZE_]?></b></td>
			<td width="22%" align="center" valign="middle" class="grid-cell"   style="font-family:Arial; font-size:11pt; color:#555555"><b><?=$rows[TYPE_]?></b></td>
			<td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[STATUS]?></font></td>
            <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[NO_REQUEST]?></font></td>
			<td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[VIA]?></font></td>
			<td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[NOPOL]?></font></td>
			<td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[TGL_IN]?></font></td>
			<td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[USERNAME]?></font></td>
			<td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[NO_NOTA]?></font></td>
			<td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[LUNAS]?></font></td>
		</tr>							
		<?php } //if(count($rows) > 0) {?>							
        </table>
		<center><h2>Total Jumlah Container = <?=$i?> Box</h2></center>
 </div>