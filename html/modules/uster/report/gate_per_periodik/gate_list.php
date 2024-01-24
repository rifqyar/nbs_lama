<?php
	//header('Location: '.HOME .'static/error.htm');
	$tl 	=  xliteTemplate('gate_list.htm');

	$tgl_awal	= $_POST["tgl_awal"];
	$tgl_akhir	= $_POST["tgl_akhir"];
	$jenis		= $_POST["jenis"];
	$shift		= $_POST["shift"];
	$size		= $_POST["size"];
	$status		= $_POST["status"];
    $lokasi     = $_POST["lokasi"];
	$no_booking		= $_POST["no_booking"];


        if ($no_booking != NULL) {
            $q_book = "and e.no_booking = '$no_booking'";
        }
        else{
            $q_book = "";
        }

		if ($shift == 1)
		{
			$time1 = $tgl_awal." 07:00";
			$time2 = $tgl_akhir." 15:30";
			//$query_shift = "and to_date(substr(to_char(a.tgl_in,'dd/mm/rrrr hh24:mi:ss'),11,9),'hh24:mi:ss') between to_date('07:00:00','hh24:mi:ss') and to_date('19:00:00','hh24:mi:ss')";
			$query_shift = "and a.tgl_in between to_date('$time1', 'DD-MM-YYYY HH24:MI') and to_date('$time2', 'DD-MM-YYYY HH24:MI')";
		}
		else if ($shift == 2)
		{
			$time1 = $tgl_awal." 15:31";
			$time2 = $tgl_akhir." 23:00";
			$query_shift = "and a.tgl_in between to_date('$time1', 'DD-MM-YYYY HH24:MI') and to_date('$time2', 'DD-MM-YYYY HH24:MI')";
		}
        else if($shift == 4)
        {
            $time1 = $tgl_awal." 23:01";
            $time2 = $tgl_akhir." 06:59";
            $query_shift = "and a.tgl_in between to_date('$time1', 'DD-MM-YYYY HH24:MI') and to_date('$time2', 'DD-MM-YYYY HH24:MI')";
        }
		else if($shift == 3)
		{
			$time1 = $tgl_awal." 00:00";
			$time2 = $tgl_akhir." 23:59";
			$query_shift = "and a.tgl_in between to_date('$time1', 'DD-MM-YYYY HH24:MI') and to_date('$time2', 'DD-MM-YYYY HH24:MI')";
		}
        else if ($shift == 'ALL') {
            $time1 = $tgl_awal;
            $time2 = $tgl_akhir;
            $query_shift = "and a.tgl_in between to_date('$time1', 'DD-MM-YYYY') and to_date('$time2', 'DD-MM-YYYY')";
        }
		else
		{
			$time1 = $tgl_awal." 07:00";
			$time2 = $tgl_akhir." 07:00";
			$query_shift = "and a.tgl_in between to_date('$time1', 'DD-MM-YYYY HH24:MI') and to_date('$time2', 'DD-MM-YYYY HH24:MI')";
		}

	if ($size == NULL)
	{
		$query_size = '';
	}
	else
	{
		if ($size == 20)
		{
			$query_size = "and b.size_ = '20'";
		} else if ($size == 40)
		{
			$query_size = "and b.size_ = '40'";
		} else if ($size == 45)
		{
			$query_size = "and b.size_ = '45'";
		} else
		{
			$query_size = '';
		}
	}


	if ($status == NULL)
	{
		$query_status = '';
	}
	else
	{
		if ($status == 'FCL')
		{
			$query_status = "and a.status = 'FCL'";
		} else if ($status == 'MTY')
		{
			$query_status = "and a.status = 'MTY'";
		} else if ($status == 'LCL')
		{
			$query_status = "and a.status = 'LCL'";
		} else
		{
			$query_status = "";
		}
	}
	//echo $tgl_awal;die;
	$db 	= getDB("storage");

	if ($jenis == 'GATI')
	{
		if ($lokasi == 'ALL')
		{
		$query_list_ 	= "select * from (select a.no_container, a.no_request, a.tgl_in, a.nopol, a.NO_SEAL, a.username,
                        a.size_, a.type_, a.status, a.nm_pbm, a.nama_yard, 'GATE IN' kegiatan
                        FROM (select a.no_container, a.no_request, TO_CHAR(a.tgl_in,'DD-MM-YYYY HH24:MI') tgl_in, a.nopol, a.NO_SEAL, f.nama_lengkap username,
                        b.size_, b.type_, a.status, c.nm_pbm, 'USTER' nama_yard, 'GATE IN' kegiatan
                        from gate_in a, master_container b, mst_pelanggan c, request_receiving e, master_user f
                        where a.no_container = b.no_container
                        and a.no_request = e.no_request
                        and e.kd_consignee = c.kd_pbm
                        and c.kd_cabang = '05'
                        and a.id_user = to_char(f.id(+))
                        ". $query_shift.' '.$query_size.' '.$query_status.' '.  "
                        UNION
                        SELECT
                            a.no_container,
                            a.no_request,
                            TO_CHAR(a.tgl_in, 'DD-MM-YYYY HH24:MI') tgl_in,
                            a.nopol,
                            a.NO_SEAL,
                            nvl(f.nama_lengkap, a.id_user) username,
                            b.size_,
                            b.type_,
                            a.status,
                            c.nm_pbm,
                            'USTER' nama_yard,
                            'GATE IN' kegiatan
                        FROM border_gate_in a
                        LEFT JOIN master_container b ON a.no_container = b.no_container
                        INNER JOIN request_stripping e ON a.no_request = e.no_request
                        LEFT JOIN mst_pelanggan c ON e.kd_consignee = c.kd_pbm
                        LEFT JOIN (
                            SELECT
                                to_char(id) AS id,
                                username,
                                nama_lengkap
                            FROM
                                master_user
                        ) f ON f.id = a.ID_USER
                        WHERE 
                            c.kd_cabang = '05'
                         ". $query_shift.' '.$query_size.' '.$query_status.' '.  "
						 ) a
                         order by a.tgl_in desc)";
		}
		else if ($lokasi == '03' || $lokasi == '08')
		{
		$query_list_ 	= "select a.no_container, a.no_request, TO_CHAR(a.tgl_in,'DD-MM-YYYY HH24:MI') tgl_in, a.nopol, a.NO_SEAL,
						f.username, b.size_, b.type_, a.status, c.nm_pbm, 'USTER' nama_yard, 'GATE IN' kegiatan
                        from gate_in a, master_container b, mst_pelanggan c, request_receiving e, master_user f
                        where a.no_container = b.no_container
                        and a.no_request = e.no_request
                        and e.kd_consignee = c.kd_pbm
                        and c.kd_cabang = '05'
                        and a.id_user = to_char(f.id(+))
                         ". $query_shift.' '.$query_size.' '.$query_status.' '.  "
                        order by a.tgl_in desc";
		}
		else //lokasi 06
		{
		$query_list_ 	= "SELECT
                                a.no_container,
                                a.no_request,
                                TO_CHAR(a.tgl_in, 'DD-MM-YYYY HH24:MI') tgl_in,
                                a.nopol,
                                a.NO_SEAL,
                                nvl(f.nama_lengkap, a.id_user) username,
                                b.size_,
                                b.type_,
                                a.status,
                                c.nm_pbm,
                                'USTER' nama_yard,
                                'GATE IN' kegiatan
                            FROM border_gate_in a
                            LEFT JOIN master_container b ON a.no_container = b.no_container
                            INNER JOIN request_stripping e ON a.no_request = e.no_request
                            LEFT JOIN mst_pelanggan c ON e.kd_consignee = c.kd_pbm
                            LEFT JOIN (
                                SELECT
                                    to_char(id) AS id,
                                    username,
                                    nama_lengkap
                                FROM
                                    master_user
                            ) f ON f.id = a.ID_USER
                            WHERE 
                                c.kd_cabang = '05'
                         ". $query_shift.' '.$query_size.' '.$query_status.' '.  "
                        order by a.tgl_in desc";
		}
	}
	else if ($jenis == 'GATO')
	{
		if ($lokasi == 'ALL')
		{
		$query_list_ 	= "select a.no_container, a.no_request, a.tgl_in, a.nopol, a.NO_SEAL, a.username,
                        a.size_, a.type_, a.status, a.nm_pbm, a.nama_yard, 'GATE OUT' kegiatan, a.VESSEL, a.VOYAGE
                        FROM (select a.no_container, a.no_request, TO_CHAR(a.tgl_in,'DD-MM-YYYY HH24:MI') tgl_in, a.nopol, a.NO_SEAL, f.username,
                        b.size_, b.type_, a.status, c.nm_pbm, 'USTER' nama_yard, 'GATE OUT' kegiatan, '' VESSEL, '' VOYAGE
                        from gate_out a, master_container b, mst_pelanggan c, request_delivery e, master_user f
                        where a.no_container = b.no_container
                        and a.no_request = e.no_request
                        and e.kd_emkl = c.kd_pbm
                        and c.kd_cabang = '05'
                        and a.id_user = to_char(f.id(+))
                        ". $query_shift.' '.$query_size.' '.$query_status.' '.  "
                        UNION
                        SELECT
                            a.no_container,
                            a.no_request,
                            TO_CHAR(a.tgl_in, 'DD-MM-YYYY HH24:MI') tgl_in,
                            a.nopol,
                            a.NO_SEAL,
                            NVL(f.username, a.id_user) username,
                            b.size_,
                            b.type_,
                            a.status,
                            c.nm_pbm,
                            d.nama_yard,
                            'GATE OUT' kegiatan,
                            e.VESSEL,
                            e.VOYAGE
                        FROM border_gate_out a
                        LEFT JOIN master_container b ON a.no_container = b.no_container
                        INNER JOIN request_delivery e ON a.no_request = e.no_request
                        LEFT JOIN mst_pelanggan c ON e.kd_emkl = c.kd_pbm
                        LEFT JOIN (
                            SELECT
                                to_char(id) AS id,
                                username
                            FROM master_user
                        ) f ON f.id = a.ID_USER
                        LEFT JOIN yard_area d ON a.id_yard = d.id
                        WHERE
                            c.kd_cabang = '05'
                            ". $query_shift.' '.$query_size.' '.$query_status.' '.$q_book.' '.  "
                        ) a
                        order by a.tgl_in desc";

		}
		else if ($lokasi == '08' || $lokasi == '03')
		{
		$query_list_ 	= "select a.no_container, a.no_request, TO_CHAR(a.tgl_in,'DD-MM-YYYY HH24:MI') tgl_in, a.nopol, a.NO_SEAL,
						f.username, b.size_, b.type_, a.status, c.nm_pbm, 'USTER' nama_yard, 'GATE OUT' kegiatan, '' VESSEL, '' VOYAGE
                        from gate_out a, master_container b, mst_pelanggan c, request_delivery e, master_user f
                        where a.no_container = b.no_container
                        and a.no_request = e.no_request
                        and e.kd_emkl = c.kd_pbm
                        and c.kd_cabang = '05'
                        and a.id_user = to_char(f.id(+))
                         ". $query_shift.' '.$query_size.' '.$query_status.' '."
						order by a.tgl_in desc";

		}
		else // lokasi 06
		{
		$query_list_ 	= "SELECT
                            a.no_container,
                            a.no_request,
                            TO_CHAR(a.tgl_in, 'DD-MM-YYYY HH24:MI') tgl_in,
                            a.nopol,
                            a.NO_SEAL,
                            NVL(f.username, a.id_user) username,
                            b.size_,
                            b.type_,
                            a.status,
                            c.nm_pbm,
                            d.nama_yard,
                            'GATE OUT' kegiatan,
                            e.VESSEL,
                            e.VOYAGE
                        FROM border_gate_out a
                        LEFT JOIN master_container b ON a.no_container = b.no_container
                        INNER JOIN request_delivery e ON a.no_request = e.no_request
                        LEFT JOIN mst_pelanggan c ON e.kd_emkl = c.kd_pbm
                        LEFT JOIN (
                            SELECT
                                to_char(id) AS id,
                                username
                            FROM master_user
                        ) f ON f.id = a.ID_USER
                        LEFT JOIN yard_area d ON a.id_yard = d.id
                        WHERE
                            c.kd_cabang = '05'
                            ". $query_shift.' '.$query_size.' '.$query_status.' '.$q_book.' '.  "
                        order by tgl_in desc";
		}

	}
	else
	{
		if ($lokasi == 'ALL')
		{
			$query_list_ 	= "select * from (select a.no_container, a.no_request, a.tgl_in, a.nopol, a.NO_SEAL, a.username,
                                    a.size_, a.type_, a.status, a.nm_pbm, a.nama_yard, 'GATE IN' kegiatan, a.VESSEL, a.VOYAGE
                                    FROM (select a.no_container, a.no_request, TO_CHAR(a.tgl_in,'DD-MM-YYYY HH24:MI') tgl_in, a.nopol, a.NO_SEAL, f.nama_lengkap username,
                                    b.size_, b.type_, a.status, c.nm_pbm, 'USTER' nama_yard, 'GATE IN' kegiatan, '' VESSEL, '' VOYAGE
                                    from gate_in a, master_container b, mst_pelanggan c, request_receiving e, master_user f
                                    where a.no_container = b.no_container
                                    and a.no_request = e.no_request
                                    and e.kd_consignee = c.kd_pbm
                                    and c.kd_cabang = '05'
                                    and a.id_user = to_char(f.id(+))
                                    ". $query_shift.' '.$query_size.' '.$query_status.' '.  "
                                    UNION
                                    SELECT
                                        a.no_container,
                                        a.no_request,
                                        TO_CHAR(a.tgl_in, 'DD-MM-YYYY HH24:MI') tgl_in,
                                        a.nopol,
                                        a.NO_SEAL,
                                        nvl(f.nama_lengkap, a.id_user) username,
                                        b.size_,
                                        b.type_,
                                        a.status,
                                        c.nm_pbm,
                                        'USTER' nama_yard,
                                        'GATE IN' kegiatan,
                                        '' VESSEL,
                                        '' VOYAGE
                                    FROM border_gate_in a
                                    LEFT JOIN master_container b ON a.no_container = b.no_container
                                    INNER JOIN request_stripping e ON a.no_request = e.no_request
                                    LEFT JOIN mst_pelanggan c ON e.kd_consignee = c.kd_pbm
                                    LEFT JOIN (
                                        SELECT
                                            to_char(id) AS id,
                                            username,
                                            nama_lengkap
                                        FROM
                                            master_user
                                    ) f ON f.id = a.ID_USER
                                    WHERE 
                                        c.kd_cabang = '05'
                                    ". $query_shift.' '.$query_size.' '.$query_status.' '.  "
                                    ) a
                                UNION
                                select b.no_container, b.no_request, b.tgl_in, b.nopol, b.NO_SEAL, b.username,
                                    b.size_, b.type_, b.status, b.nm_pbm, b.nama_yard, 'GATE OUT' kegiatan, b.VESSEL, b.VOYAGE
                                    FROM (select a.no_container, a.no_request, TO_CHAR(a.tgl_in,'DD-MM-YYYY HH24:MI') tgl_in, a.nopol, a.NO_SEAL, f.username,
                                    b.size_, b.type_, a.status, c.nm_pbm, 'USTER' nama_yard, 'GATE OUT' kegiatan, '' VESSEL, '' VOYAGE
                                    from gate_out a, master_container b, mst_pelanggan c, request_delivery e, master_user f
                                    where a.no_container = b.no_container
                                    and a.no_request = e.no_request
                                    and e.kd_emkl = c.kd_pbm
                                    and c.kd_cabang = '05'
                                    and a.id_user = to_char(f.id(+))
                                    ". $query_shift.' '.$query_size.' '.$query_status.' '.  "
                                    UNION
                                    SELECT
                                        a.no_container,
                                        a.no_request,
                                        TO_CHAR(a.tgl_in, 'DD-MM-YYYY HH24:MI') tgl_in,
                                        a.nopol,
                                        a.NO_SEAL,
                                        NVL(f.username, a.id_user) username,
                                        b.size_,
                                        b.type_,
                                        a.status,
                                        c.nm_pbm,
                                        d.nama_yard,
                                        'GATE OUT' kegiatan,
                                        e.VESSEL,
                                        e.VOYAGE
                                    FROM border_gate_out a
                                    LEFT JOIN master_container b ON a.no_container = b.no_container
                                    INNER JOIN request_delivery e ON a.no_request = e.no_request
                                    LEFT JOIN mst_pelanggan c ON e.kd_emkl = c.kd_pbm
                                    LEFT JOIN (
                                        SELECT
                                            to_char(id) AS id,
                                            username
                                        FROM master_user
                                    ) f ON f.id = a.ID_USER
                                    LEFT JOIN yard_area d ON a.id_yard = d.id
                                    WHERE
                                        c.kd_cabang = '05'
                                        ". $query_shift.' '.$query_size.' '.$query_status.' '.$q_book.' '.  ") b
                                ) c
                                order by c.tgl_in DESC";
		}
		else if ($lokasi == '03' || $lokasi == '08')
		{
						$query_list_ 	= "SELECT a.no_container,a.no_request, a.tgl_in, a.nopol, a.NO_SEAL, a.username,
                       a.size_, a.type_, a.status,a.nm_pbm, a.nama_yard, a.kegiatan FROM (select a.no_container,a.no_request, TO_CHAR(a.tgl_in,'DD-MM-YYYY HH24:MI') tgl_in, a.nopol, a.NO_SEAL, f.username,
                        b.size_, b.type_, a.status, c.nm_pbm, 'USTER' nama_yard, 'GATE IN' kegiatan
                        from gate_in a, master_container b, mst_pelanggan c, request_receiving e, master_user f
                        where a.no_container = b.no_container
                        and a.no_request = e.no_request
                        and e.kd_consignee = c.kd_pbm
                        and c.kd_cabang = '05'
                        and a.id_user = to_char(f.id(+))
                         ". $query_shift.' '.$query_size.' '.$query_status.' '.  " UNION
                        select a.no_container,a.no_request, TO_CHAR(a.tgl_in,'DD-MM-YYYY HH24:MI') tgl_in, a.nopol, a.NO_SEAL, f.username,
                        b.size_, b.type_, a.status, c.nm_pbm, 'USTER' nama_yard, 'GATE OUT' kegiatan
                        from gate_out a, master_container b, mst_pelanggan c, request_delivery e, master_user f
                        where a.no_container = b.no_container
                        and a.no_request = e.no_request
                        and e.kd_emkl = c.kd_pbm
                        and c.kd_cabang = '05'
                        and a.id_user = to_char(f.id(+))
                         ". $query_shift.' '.$query_size.' '.$query_status.' '.") a
                         order by a.tgl_in desc";
		}
		else //lokasi 06
		{   //print_r($query_shift);
						$query_list_ 	= "SELECT a.no_container,a.no_request, a.tgl_in, a.nopol, a.NO_SEAL, a.username,
                       a.size_, a.type_, a.status,a.nm_pbm, a.nama_yard, a.kegiatan 
                       FROM (
                        SELECT
                            a.no_container,
                            a.no_request,
                            TO_CHAR(a.tgl_in, 'DD-MM-YYYY HH24:MI') tgl_in,
                            a.nopol,
                            a.NO_SEAL,
                            nvl(f.nama_lengkap, a.id_user) username,
                            b.size_,
                            b.type_,
                            a.status,
                            c.nm_pbm,
                            'USTER' nama_yard,
                            'GATE IN' kegiatan,
                            '' VESSEL,
                            '' VOYAGE
                        FROM border_gate_in a
                        LEFT JOIN master_container b ON a.no_container = b.no_container
                        INNER JOIN request_stripping e ON a.no_request = e.no_request
                        LEFT JOIN mst_pelanggan c ON e.kd_consignee = c.kd_pbm
                        LEFT JOIN (
                            SELECT
                                to_char(id) AS id,
                                username,
                                nama_lengkap
                            FROM
                                master_user
                        ) f ON f.id = a.ID_USER
                        WHERE 
                            c.kd_cabang = '05'
                        ". $query_shift.' '.$query_size.' '.$query_status.' '. "
						UNION
                        SELECT
                            a.no_container,
                            a.no_request,
                            TO_CHAR(a.tgl_in, 'DD-MM-YYYY HH24:MI') tgl_in,
                            a.nopol,
                            a.NO_SEAL,
                            NVL(f.username, a.id_user) username,
                            b.size_,
                            b.type_,
                            a.status,
                            c.nm_pbm,
                            d.nama_yard,
                            'GATE OUT' kegiatan,
                            e.VESSEL,
                            e.VOYAGE
                        FROM border_gate_out a
                        LEFT JOIN master_container b ON a.no_container = b.no_container
                        INNER JOIN request_delivery e ON a.no_request = e.no_request
                        LEFT JOIN mst_pelanggan c ON e.kd_emkl = c.kd_pbm
                        LEFT JOIN (
                            SELECT
                                to_char(id) AS id,
                                username
                            FROM master_user
                        ) f ON f.id = a.ID_USER
                        LEFT JOIN yard_area d ON a.id_yard = d.id
                        WHERE
                            c.kd_cabang = '05'
                            ". $query_shift.' '.$query_size.' '.$query_status.' '.$q_book.' '.  ") a
                         order by a.tgl_in desc";
		}
	}


//	echo $query_list_;
//	die();

	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll();


	$tl->assign("row_list",$row_list);
	$tl->assign("tgl_awal",$tgl_awal);
	$tl->assign("tgl_akhir",$tgl_akhir);
	$tl->assign("jenis",$jenis);
    $tl->assign("lokasi",$lokasi);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);

	$tl->renderToScreen();
?>
