<?php
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=ReportGatePerPeriodik-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");


	$tgl_awal	= $_POST["tgl_awal"];
	$tgl_akhir	= $_POST["tgl_akhir"];
	$jenis		= $_POST["option_kegiatan"];
	$shift		= $_POST["shift"];
	$size		= $_POST["size"];
	$status		= $_POST["status"];
	$lokasi		= $_POST["lokasi"];
    $no_booking     = $_POST["NO_BOOKING"];


        if ($no_booking != NULL) {
            $q_book = "and e.no_booking = '$no_booking'";
        }
        else{
            $q_book = "";
        }


        if ($shift == 1)
		{
			$time1 = $tgl_awal." 07:00";//"remarks by Yusuf & Desta ITSD - 24-Apr-2020"
            //$time2 = $tgl_akhir." 19:00";
			$time2 = $tgl_akhir." 15:30";//"remarks by Yusuf & Desta ITSD - 24-Apr-2020"
			//$query_shift = "and to_date(substr(to_char(a.tgl_in,'dd/mm/rrrr hh24:mi:ss'),11,9),'hh24:mi:ss') between to_date('07:00:00','hh24:mi:ss') and to_date('19:00:00','hh24:mi:ss')";
			$query_shift = "and a.tgl_in between to_date('$time1', 'DD-MM-YYYY HH24:MI') and to_date('$time2', 'DD-MM-YYYY HH24:MI')";
		}
		else if ($shift == 2)
		{
            //$time1 = $tgl_awal." 19:01;"
            //$time2 = $tgl_akhir." 06:59";
			$time1 = $tgl_awal." 15:31";//"remarks by Yusuf & Desta ITSD - 24-Apr-2020"
			$time2 = $tgl_akhir." 23:00";//"remarks by Yusuf & Desta ITSD - 24-Apr-2020"
			$query_shift = "and a.tgl_in between to_date('$time1', 'DD-MM-YYYY HH24:MI') and to_date('$time2', 'DD-MM-YYYY HH24:MI')";
		}//"Start add remarks by Yusuf & Desta ITSD - 24-Apr-2020"
         else if($shift == 4)
        {
            $time1 = $tgl_awal." 23:01";
            $time2 = $tgl_akhir." 06:59";
            $query_shift = "and a.tgl_in between to_date('$time1', 'DD-MM-YYYY HH24:MI') and to_date('$time2', 'DD-MM-YYYY HH24:MI')";
        }//"End add remarks by Yusuf & Desta ITSD - 24-Apr-2020"
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
		$query_list_ 	= "SELECT
                        a.no_container,
                        a.no_request,
                        a.tgl_in,
                        a.nopol,
                        a.NO_SEAL,
                        a.username,
                        a.size_,
                        a.type_,
                        a.status,
                        a.nm_pbm,
                        a.nama_yard,
                        'GATE IN' kegiatan
                    FROM
                        (
                        SELECT
                            a.no_container,
                            a.no_request,
                            TO_CHAR(a.tgl_in, 'DD-MM-YYYY HH24:MI') tgl_in,
                            a.nopol,
                            a.NO_SEAL,
                            f.nama_lengkap username,
                            b.size_,
                            b.type_,
                            a.status,
                            c.nm_pbm,
                            'USTER' nama_yard,
                            'GATE IN' kegiatan
                        FROM
                            gate_in a,
                            master_container b,
                            mst_pelanggan c,
                            request_receiving e,
                            master_user f
                        WHERE
                            a.no_container = b.no_container
                            AND a.no_request = e.no_request
                            AND e.kd_consignee = c.kd_pbm
                            AND c.kd_cabang = '05'
                            AND a.id_user = to_char(f.id(+))
                            AND a.tgl_in BETWEEN to_date('12/10/2023 07:00', 'DD-MM-YYYY HH24:MI') AND to_date('12/10/2023 15:30', 'DD-MM-YYYY HH24:MI')
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
                        FROM
                            border_gate_in a,
                            master_container b,
                            mst_pelanggan c,
                            request_receiving e,
                            master_user f
                        WHERE
                            a.no_container = b.no_container
                            AND a.no_request = e.no_request
                            AND e.kd_consignee = c.kd_pbm
                            AND a.id_user = to_char(f.id(+))
                            AND a.tgl_in BETWEEN to_date('12/10/2023 07:00', 'DD-MM-YYYY HH24:MI') AND to_date('12/10/2023 15:30', 'DD-MM-YYYY HH24:MI')
                    UNION
                        SELECT
                            a.no_container,
                            a.no_request,
                            TO_CHAR(a.tgl_in, 'DD-MM-YYYY HH24:MI') tgl_in,
                            a.nopol,
                            a.NO_SEAL,
                            a.id_user username,
                            b.size_,
                            b.type_,
                            a.status,
                            c.nm_pbm,
                            'USTER' nama_yard,
                            'GATE IN' kegiatan
                        FROM
                                border_gate_in a
                        LEFT JOIN master_container b ON
                                a.no_container = b.no_container
                        INNER JOIN request_stripping e ON
                                a.no_request = e.no_request
                        LEFT JOIN mst_pelanggan c ON
                                e.kd_consignee = c.kd_pbm
                        LEFT JOIN (
                            SELECT
                                    to_char(id) AS id,
                                    username,
                                    nama_lengkap
                            FROM
                                    master_user ) f ON
                                f.id = a.ID_USER
                        WHERE
                                c.kd_cabang = '05'
                            AND a.tgl_in BETWEEN to_date('12/10/2023 07:00', 'DD-MM-YYYY HH24:MI') AND to_date('12/10/2023 15:30', 'DD-MM-YYYY HH24:MI')   																				
                    ) a
                    ORDER BY
                        a.tgl_in DESC;
                    ";
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
		else
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
		$query_list_ 	= "SELECT
        a.no_container,
        a.no_request,
        a.tgl_in,
        a.nopol,
        a.NO_SEAL,
        a.username,
        a.size_,
        a.type_,
        a.status,
        a.nm_pbm,
        a.nama_yard,
        'GATE OUT' kegiatan,
        a.VESSEL,
        a.VOYAGE
    FROM
        (
        SELECT
            a.no_container,
            a.no_request,
            TO_CHAR(a.tgl_in, 'DD-MM-YYYY HH24:MI') tgl_in,
            a.nopol,
            a.NO_SEAL,
            f.username,
            b.size_,
            b.type_,
            a.status,
            c.nm_pbm,
            'USTER' nama_yard,
            'GATE OUT' kegiatan,
            '' VESSEL,
            '' VOYAGE
        FROM
            gate_out a,
            master_container b,
            mst_pelanggan c,
            request_delivery e,
            master_user f
        WHERE
            a.no_container = b.no_container
            AND a.no_request = e.no_request
            AND e.kd_emkl = c.kd_pbm
            AND c.kd_cabang = '05'
            AND a.id_user = to_char(f.id(+))
            ". $query_shift.' '.$query_size.' '.$query_status.  "
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
        FROM
            border_gate_out a
        LEFT JOIN master_container b ON
            a.no_container = b.no_container
        INNER JOIN request_delivery e ON
            a.no_request = e.no_request
        LEFT JOIN mst_pelanggan c ON
            e.kd_emkl = c.kd_pbm
        LEFT JOIN (
            SELECT
                to_char(id) AS id,
                username
            FROM
                master_user
                            ) f ON
            f.id = a.ID_USER
        LEFT JOIN yard_area d ON
            a.id_yard = d.id
        WHERE
            c.kd_cabang = '05'
        " . $query_shift . ' ' . $query_size . ' ' . $query_status . ' ' . $q_book . ' ' . "
                            ) a
    ORDER BY
        a.tgl_in DESC";

		}
		else if ($lokasi == '08')
		{
		$query_list_ 	= "select a.no_container, a.no_request, TO_CHAR(a.tgl_in,'DD-MM-YYYY HH24:MI') tgl_in, a.nopol, a.NO_SEAL,
						f.username, b.size_, b.type_, a.status, c.nm_pbm, 'USTER' nama_yard, 'GATE OUT' kegiatan
                        from gate_out a, master_container b, mst_pelanggan c, request_delivery e, master_user f
                        where a.no_container = b.no_container
                        and a.no_request = e.no_request
                        and e.kd_emkl = c.kd_pbm
                        and c.kd_cabang = '05'
                        and a.id_user = to_char(f.id(+))
                         ". $query_shift.' '.$query_size.' '.$query_status.' '."
						order by a.tgl_in desc";

		}

		else if ($lokasi == '03')
		{
		$query_list_ 	= "select a.no_container, a.no_request, TO_CHAR(a.tgl_in,'DD-MM-YYYY HH24:MI') tgl_in, a.nopol, a.NO_SEAL,
						f.username, b.size_, b.type_, a.status, c.nm_pbm, 'USTER' nama_yard, 'GATE OUT' kegiatan
                        from gate_out a, master_container b, mst_pelanggan c, request_delivery e, master_user f
                        where a.no_container = b.no_container
                        and a.no_request = e.no_request
                        and e.kd_emkl = c.kd_pbm
                        and c.kd_cabang = '05'
                        and a.id_user = to_char(f.id(+))
                         ". $query_shift.' '.$query_size.' '.$query_status.' '.  "
                        order by a.tgl_in desc";
		}
		else
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
		else
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

    // echo $query_list_;die;

	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll();


?>

 <div id="list">
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
            <tr>
                <th colspan="13">REPORT GATE PER PERIODIK</th>
            </tr>
            <tr>
                <th colspan="13">PT. PELABUHAN INDONESIA II - CABANG PONTIANAK</th>
            </tr>
            <tr>
                <th colspan="13"><?=$time1?> - <?=$time2?> </th>
            </tr>
                              <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header"  style="font-size:10pt">No </th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">No.Container</th>
								   <th valign="top" class="grid-header"  style="font-size:10pt">Size</th>
								    <th valign="top" class="grid-header"  style="font-size:10pt">Type</th>
                                  <th  valign="top" class="grid-header"  style="font-size:10pt">Status</th>
                                  <th  valign="top" class="grid-header"  style="font-size:10pt">No Request</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">Tgl Gate</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">No Polisi</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">No Seal</th>
								  <th valign="top" class="grid-header"  style="font-size:10pt">Nama PBM</th>
								  <th valign="top" class="grid-header"  style="font-size:10pt">Nama Yard</th>
								  <th valign="top" class="grid-header"  style="font-size:10pt">Kegiatan</th>
								  <?php if(($jenis == 'GATO' && $lokasi == '06') || ($jenis == 'GATO' && $lokasi == 'ALL')) {?>
								  <th valign="top" class="grid-header"  style="font-size:8pt">VESSEL</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">VOYAGE</th>
								  <?php } ?>
								  <th valign="top" class="grid-header"  style="font-size:10pt">Operator Gate</th>
                              </tr>
                              <?php $i=0;
							  foreach($row_list as $rows){ $i++;?>
                              <tr>
                                  <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000;  font-size:9pt"><?php echo $i; ?></td>
                                  <td width="22%" align="center" valign="middle" class="grid-cell"   style=" font-size:11pt; color:#555555"><b><?php echo $rows["NO_CONTAINER"]; ?></b></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["SIZE_"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["TYPE_"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["STATUS"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NO_REQUEST"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["TGL_IN"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NOPOL"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NO_SEAL"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NM_PBM"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NAMA_YARD"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["KEGIATAN"]; ?></td>
								   <?php if(($jenis == 'GATO' && $lokasi == '06') || ($jenis == 'GATO' && $lokasi == 'ALL')) {?>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?php echo $rows["VESSEL"]; ?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt">="<?php echo $rows["VOYAGE"]; ?>"</font></td>
								  <?php } ?>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["USERNAME"]; ?></td>
							</tr>
							<? }?>
        </table>
 </div>
