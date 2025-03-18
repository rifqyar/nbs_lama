<?php
	$tl 		= xliteTemplate("cont_liist.htm");
	$db 		= getDB("storage");
	$nm_kapal 	= $_POST["NM_KAPAL"];
	$voyage_in 	= $_POST["VOYAGE"];
	$kegiatan 	= $_POST["KEGIATAN"];
	// $no_booking = $_POST["NO_BOOKING"];
	$status		= $_POST["STATUS"];
//	$delivery	= $_POST["DELIVERY_KE"];
	
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
	
/*	if ($delivery == 'TPK')
	{	
	   $query = "select  b.no_container, mc.size_, mc.type_, a.no_request, b.status, b.via, c.nopol, TO_CHAR(c.tgl_in,'dd/mm/rrrr hh:ii:ss') tgl_in,  
                nvl((select username from master_user where to_char(id)=to_char(c.id_user)),c.id_user) username, n.no_faktur no_nota, n.lunas
                from request_delivery a, container_delivery b, master_container mc, border_gate_out c, nota_delivery n
                where a.no_request = b.no_request
                and b.no_request = c.no_request(+)
                and b.no_container = c.no_container(+)
				and b.no_container = mc.no_container
                --and C.VIA is null
                and a.no_request = n.no_request(+)
				and n.status not in ('BATAL')
				$query_status
                --and a.no_booking = '$no_booking'". $query_status. "
                order by c.tgl_in desc";
	
	} */

    //echo $query; die();

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
	
	
	$tl->assign('row_q', $row_q);
	$tl->assign('kapal', $nm_kapal);
	$tl->assign('voyage', $voyage_in);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
?>