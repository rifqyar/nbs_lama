<?php
$tanggal=date("dmY");
$noreq = $_GET["noreq"];
$jenis = $_GET["kegiatan"];
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=ReportPenumpukan-".$noreq."-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

	//echo $tgl_awal;die;
	$db 	= getDB("storage");

	if ($jenis == 'REPO')
	{
		$query_list_ 	= "SELECT 
                                X.*,
                                ROUND(lift_off_tpk * 0.1, 0) lift_off_tpk_ppn,
                                CASE 
                                    WHEN
                                        TOTMASA_TPK > 0
                                    THEN
                                        CASE
                                            WHEN
                                                TGL_OUT < BATAS_MASA12 AND TGL_OUT >= AWAL_MASA12
                                            THEN					
                                                (BATAS_MASA12 - TGL_OUT)
                                            WHEN
                                                TGL_OUT > BATAS_MASA12
                                            THEN
                                                0
                                            ELSE
                                                totmasa12
                                        END
                                    ELSE
                                        0
                                END masa12_tpk,
                                CASE
                                    WHEN 
                                        HZ = 'N'
                                    THEN
                                        (TARIF * (
                                            CASE 
                                                WHEN
                                                    TOTMASA_TPK > 0
                                                THEN
                                                    CASE
                                                        WHEN
                                                            TGL_OUT < BATAS_MASA12 AND TGL_OUT >= AWAL_MASA12
                                                        THEN					
                                                            (BATAS_MASA12 - TGL_OUT)
                                                        WHEN
                                                            TGL_OUT > BATAS_MASA12
                                                        THEN
                                                            0
                                                        ELSE
                                                            totmasa12
                                                    END
                                                ELSE
                                                    0
                                            END
                                        ) * 2)
                                    ELSE
                                        (TARIF * (
                                            CASE 
                                                WHEN
                                                    TOTMASA_TPK > 0
                                                THEN
                                                    CASE
                                                        WHEN
                                                            TGL_OUT < BATAS_MASA12 AND TGL_OUT >= AWAL_MASA12
                                                        THEN					
                                                            (BATAS_MASA12 - TGL_OUT)
                                                        WHEN
                                                            TGL_OUT > BATAS_MASA12
                                                        THEN
                                                            0
                                                        ELSE
                                                            totmasa12
                                                    END
                                                ELSE
                                                    0
                                            END
                                        ) * 2) * 2
                                END biaya_masa12_tpk,	 
                                CASE 
                                    WHEN
                                        TOTMASA_TPK > 0
                                    THEN
                                        CASE
                                            WHEN
                                                TGL_OUT < BATAS_MASA2
                                            THEN
                                                TOTMASA_TPK - (
                                                    CASE
                                                        WHEN
                                                            TGL_OUT < BATAS_MASA12
                                                        THEN
                                                            (BATAS_MASA12 - TGL_OUT)
                                                        ELSE
                                                            0
                                                    END
                                                )
                                            ELSE
                                                0
                                        END
                                    ELSE
                                        0
                                END masa2_tpk,
                                CASE
                                    WHEN 
                                        HZ = 'N'
                                    THEN
                                        (TARIF * (
                                            CASE 
                                                WHEN
                                                    TOTMASA_TPK > 0
                                                THEN
                                                    CASE
                                                        WHEN
                                                            TGL_OUT < BATAS_MASA2
                                                        THEN
                                                            TOTMASA_TPK - (
                                                                CASE
                                                                    WHEN
                                                                        TGL_OUT < BATAS_MASA12
                                                                    THEN
                                                                        (BATAS_MASA12 - TGL_OUT)
                                                                    ELSE
                                                                        0
                                                                END
                                                            )
                                                        ELSE
                                                            0
                                                    END
                                                ELSE
                                                    0
                                            END
                                        ) * 3)
                                    ELSE
                                        (TARIF * (
                                            CASE 
                                                WHEN
                                                    TOTMASA_TPK > 0
                                                THEN
                                                    CASE
                                                        WHEN
                                                            TGL_OUT < BATAS_MASA2
                                                        THEN
                                                            TOTMASA_TPK - (
                                                                CASE
                                                                    WHEN
                                                                        TGL_OUT < BATAS_MASA12
                                                                    THEN
                                                                        (BATAS_MASA12 - TGL_OUT)
                                                                    ELSE
                                                                        0
                                                                END
                                                            )
                                                        ELSE
                                                            0
                                                    END
                                                ELSE
                                                    0
                                            END
                                        ) * 3) * 2
                                END biaya_masa2_tpk
                            FROM (
                                SELECT 
                                    NO_CONTAINER,
                                    HZ,
                                    SIZE_,
                                    TYPE_,
                                    STATUS,
                                    NO_REQUEST,
                                    TGL_REQUEST,
                                    START_STACK,
                                    TGL_DELIVERY,		
                                    TGL_OUT,
                                    STATUS_REQ,
                                    TARIF,
                                    selisih SELISIH_TOTAL,
                                    SELISIH_OUT,
                                    masa11 - 1 batas_masa11,
                                    totmasa11,
                                    CASE
                                        WHEN 
                                            HZ = 'N'
                                        THEN
                                            (TARIF * 1)
                                        ELSE
                                            (TARIF * 1) * 2
                                    END biaya_masa11,
                                    CASE 
                                        WHEN
                                            masa11 > TGL_DELIVERY
                                        THEN
                                            NULL
                                        ELSE
                                            masa11
                                    END	awal_masa12,
                                    CASE 
                                        WHEN
                                            masa11 > TGL_DELIVERY
                                        THEN
                                            NULL
                                        ELSE
                                            ((masa11 - 1) + totmasa12)
                                    END batas_masa12,
                                    totmasa12,
                                    CASE
                                        WHEN 
                                            HZ = 'N'
                                        THEN
                                            (TARIF * totmasa12 * 2)
                                        ELSE
                                            (TARIF * totmasa12 * 2) * 2
                                    END	 biaya_masa12,
                                    CASE
                                        WHEN
                                            (masa11 + totmasa12) > TGL_DELIVERY
                                        THEN
                                            NULL
                                        ELSE 
                                            (masa11 + totmasa12)
                                    END	awal_masa2,
                                    CASE
                                        WHEN
                                            (masa11 + totmasa12) > TGL_DELIVERY
                                        THEN
                                            NULL
                                        ELSE 
                                            ((masa11 - 1) + totmasa12 + totmasa2)
                                    END	 batas_masa2,
                                    totmasa2,
                                    CASE
                                        WHEN 
                                            HZ = 'N'
                                        THEN
                                            (TARIF * totmasa2 * 3)
                                        ELSE
                                            (TARIF * totmasa2 * 3) * 2
                                    END	 biaya_masa2, 		
                                    CASE
                                        WHEN
                                            selisih > 5
                                        THEN 
                                            CASE
                                                WHEN
                                                    (selisih - SELISIH_OUT) < 6
                                                THEN
                                                    CASE
                                                        WHEN
                                                            SELISIH_OUT <=5
                                                        THEN
                                                            CASE
                                                                WHEN
                                                                    (selisih - 5) <=0
                                                                THEN	
                                                                    0
                                                                ELSE
                                                                    (selisih - 5)
                                                            END
                                                        ELSE
                                                            (selisih - SELISIH_OUT)
                                                    END
                                                ELSE
                                                    (selisih - SELISIH_OUT)					
                                            END
                                        ELSE
                                            0
                                    END TOTMASA_TPK,
                                    (SELECT
                                        master_tarif.tarif AS tarif
                                    FROM
                                        master_container
                                    JOIN container_delivery ON
                                        (master_container.no_container = container_delivery.no_container)
                                    JOIN iso_code ON
                                        ( iso_code.status = container_delivery.status
                                        AND master_container.size_ = iso_code.size_
                                        AND master_container.type_ = iso_code.type_)
                                    JOIN master_tarif ON
                                        (master_tarif.id_iso = iso_code.id_iso)
                                    JOIN group_tarif ON
                                        (master_tarif.id_group_tarif = group_tarif.id_group_tarif)
                                    JOIN request_delivery ON
                                        (request_delivery.no_request = container_delivery.no_request)
                                    WHERE
                                        container_delivery.no_request = V.NO_REQUEST
                                        AND MASTER_CONTAINER.NO_CONTAINER = V.NO_CONTAINER
                                        AND group_tarif.kategori_tarif = 'LOLO_TPK'
                                        AND request_delivery.tgl_request BETWEEN group_tarif.start_period AND group_tarif.end_period			
                                    GROUP BY
                                        iso_code.id_iso,
                                        master_tarif.tarif,
                                        container_delivery.hz) lift_off_tpk
                                FROM(		
                                    SELECT
                                        A.NO_CONTAINER,
                                        B.SIZE_,
                                        B.TYPE_,
                                        A.STATUS,
                                        A.NO_REQUEST,
                                        C.TGL_REQUEST,
                                        A.START_STACK,
                                        A.TGL_DELIVERY,
                                        (A.TGL_DELIVERY - A.START_STACK)+1 selisih,
                                        CASE 
                                            WHEN
                                                (A.START_STACK + 5) > A.TGL_DELIVERY
                                            THEN
                                                A.TGL_DELIVERY+1
                                            ELSE
                                                (A.START_STACK + 5)
                                        END		
                                        masa11,
                                        CASE
                                            WHEN 
                                                (A.START_STACK + 5) <= A.TGL_DELIVERY
                                            THEN 
                                                5
                                            ELSE
                                                (A.TGL_DELIVERY - (A.START_STACK)) + 1
                                        END totmasa11,
                                        CASE
                                            WHEN 
                                                (A.START_STACK + 5) < A.TGL_DELIVERY
                                            THEN 
                                                CASE
                                                    WHEN
                                                        (A.TGL_DELIVERY) - (A.START_STACK + 5) > 5
                                                    THEN 
                                                        5
                                                    WHEN
                                                        (A.TGL_DELIVERY) - (A.START_STACK + 5) < 5
                                                    THEN
                                                        (A.TGL_DELIVERY) - (A.START_STACK + 5)+1
                                                    WHEN
                                                        (A.TGL_DELIVERY) - (A.START_STACK + 5) = 5
                                                    THEN
                                                        (A.TGL_DELIVERY) - (A.START_STACK + 5)
                                                END			
                                            ELSE
                                                CASE
                                                    WHEN
                                                        (A.TGL_DELIVERY - (A.START_STACK+5)) = 0
                                                    THEN
                                                        1
                                                    WHEN 
                                                        (A.TGL_DELIVERY - (A.START_STACK+5)) < 0
                                                    THEN
                                                        0
                                                    ELSE
                                                        (A.TGL_DELIVERY - (A.START_STACK+5))
                                                END
                                        END totmasa12,
                                        CASE 
                                            WHEN
                                                (A.TGL_DELIVERY - ((A.START_STACK + 5) +5)) < 0
                                            THEN
                                                0
                                            ELSE
                                                ((A.TGL_DELIVERY + 1) - ((A.START_STACK + 5) +5))
                                        END totmasa2,
                                        (SELECT 
                                            MT.TARIF
                                        FROM 
                                            master_tarif MT,
                                            ISO_CODE IC,
                                            GROUP_TARIF GT
                                        WHERE 
                                            GT.KATEGORI_TARIF = 'PENUMPUKAN_DEPO'
                                            AND C.TGL_REQUEST BETWEEN GT.start_period  AND GT.end_period
                                            AND MT.ID_GROUP_TARIF = GT.ID_GROUP_TARIF
                                            AND IC.SIZE_ = B.SIZE_
                                            AND IC.TYPE_ = B.TYPE_
                                            AND IC.STATUS = A.STATUS
                                            AND MT.ID_ISO = IC.ID_ISO) TARIF,
                                            TO_DATE(TO_CHAR(D.TGL_IN, 'YYYYMMDD'), 'YYYYMMDD') AS TGL_OUT,
                                            A.HZ,
                                            A.STATUS_REQ,
                                            CASE
                                                WHEN			
                                                    (TO_DATE(TO_CHAR(D.TGL_IN, 'YYYYMMDD'), 'YYYYMMDD') - A.START_STACK) <= 0
                                                THEN
                                                    1
                                                ELSE
                                                    ((TO_DATE(TO_CHAR(D.TGL_IN, 'YYYYMMDD'), 'YYYYMMDD') + 1) - A.START_STACK)
                                            END SELISIH_OUT
                                    FROM
                                        CONTAINER_DELIVERY A
                                        JOIN MASTER_CONTAINER B ON A.NO_CONTAINER = B.NO_CONTAINER
                                        JOIN REQUEST_DELIVERY C ON A.NO_REQUEST = C.NO_REQUEST
                                        LEFT JOIN BORDER_GATE_OUT D  ON A.NO_CONTAINER = D.NO_CONTAINER AND A.NO_REQUEST = D.NO_REQUEST
                                    WHERE			
                            			A.NO_REQUEST = '$noreq' AND 			
                                        C.DELIVERY_KE = 'TPK'
                                        AND C.NOTA = 'Y'
                                    ORDER BY A.START_STACK ASC			
                                    )V				
                            )X";
    }
    else if($jenis == 'STRIPPING'){
        $query_list_ = "SELECT 
                                X.*,
                                (ROUND(lift_on_tpk * 0.1, 0) + lift_on_tpk) lift_on_tpk_ppn,
                                CASE 
                                    WHEN
                                        TOTMASA_USTER > 0
                                    THEN
                                        CASE
                                            WHEN
                                                TGL_OUT < BATAS_MASA12
                                            THEN
                                                totmasa12
                                            ELSE
                                                0
                                        END
                                    ELSE
                                        0
                                END masa12_uster,
                                CASE
                                    WHEN 
                                        HZ = 'N'
                                    THEN
                                        (TARIF * (
                                            CASE 
                                                WHEN
                                                    TOTMASA_USTER > 0
                                                THEN
                                                    CASE
                                                        WHEN
                                                            TGL_OUT < BATAS_MASA12
                                                        THEN
                                                            totmasa12
                                                        ELSE
                                                            0
                                                    END
                                                ELSE
                                                    0
                                            END
                                        ) * 2)
                                    ELSE
                                        (TARIF * (
                                            CASE 
                                                WHEN
                                                    TOTMASA_USTER > 0
                                                THEN
                                                    CASE
                                                        WHEN
                                                            TGL_OUT < BATAS_MASA12
                                                        THEN
                                                            totmasa12
                                                        ELSE
                                                            0
                                                    END
                                                ELSE
                                                    0
                                            END
                                        ) * 2) * 2
                                END biaya_masa12_uster,	 
                                CASE 
                                    WHEN
                                        TOTMASA_USTER > 0
                                    THEN
                                        CASE
                                            WHEN
                                                TGL_OUT < BATAS_MASA2
                                            THEN
                                                TOTMASA_USTER - (
                                                    CASE
                                                        WHEN
                                                            TGL_OUT < BATAS_MASA12
                                                        THEN
                                                            CASE
                                                                WHEN
                                                                    (BATAS_MASA12 - TGL_OUT) > 5
                                                                THEN
                                                                    totmasa12
                                                                ELSE
                                                                    (BATAS_MASA12 - TGL_OUT)
                                                            END
                                                        ELSE
                                                            0
                                                    END
                                                )
                                            ELSE
                                                0
                                        END
                                    ELSE
                                        0
                                END masa2_uster,
                                CASE
                                    WHEN 
                                        HZ = 'N'
                                    THEN
                                        (TARIF * (
                                            CASE 
                                                WHEN
                                                    TOTMASA_USTER > 0
                                                THEN
                                                    CASE
                                                        WHEN
                                                            TGL_OUT < BATAS_MASA2
                                                        THEN
                                                            TOTMASA_USTER - (
                                                                CASE
                                                                    WHEN
                                                                        TGL_OUT < BATAS_MASA12
                                                                    THEN
                                                                        CASE
                                                                            WHEN
                                                                                (BATAS_MASA12 - TGL_OUT) > 5
                                                                            THEN
                                                                                totmasa12
                                                                            ELSE
                                                                                (BATAS_MASA12 - TGL_OUT)
                                                                        END
                                                                    ELSE
                                                                        0
                                                                END
                                                            )
                                                        ELSE
                                                            0
                                                    END
                                                ELSE
                                                    0
                                            END
                                        ) * 3)
                                    ELSE
                                        (TARIF * (
                                            CASE 
                                                WHEN
                                                    TOTMASA_USTER > 0
                                                THEN
                                                    CASE
                                                        WHEN
                                                            TGL_OUT < BATAS_MASA2
                                                        THEN
                                                            TOTMASA_USTER - (
                                                                CASE
                                                                    WHEN
                                                                        TGL_OUT < BATAS_MASA12
                                                                    THEN
                                                                        CASE
                                                                            WHEN
                                                                                (BATAS_MASA12 - TGL_OUT) > 5
                                                                            THEN
                                                                                totmasa12
                                                                            ELSE
                                                                                (BATAS_MASA12 - TGL_OUT)
                                                                        END
                                                                    ELSE
                                                                        0
                                                                END
                                                            )
                                                        ELSE
                                                            0
                                                    END
                                                ELSE
                                                    0
                                            END
                                        ) * 3) * 2
                                END biaya_masa2_uster
                            FROM (	
                                SELECT 
                                    NO_CONTAINER,
                                    HZ,
                                    SIZE_,
                                    TYPE_,
                                    STATUS,
                                    NO_REQUEST,
                                    TGL_REQUEST,
                                    TGL_BONGKAR,
                                    START_STACK,
                                    TGL_DELIVERY,		
                                    TGL_OUT,
                                    STATUS_REQ,
                                    TARIF,
                                    selisih SELISIH_TOTAL,
                                    SELISIH_OUT,
                                    masa11 - 1 batas_masa11,
                                    totmasa11,
                                    CASE
                                        WHEN
                                            totmasa11 > 0
                                        THEN
                                            CASE
                                                WHEN 
                                                    HZ = 'N'
                                                THEN
                                                    (TARIF * 1)
                                                ELSE
                                                    (TARIF * 1) * 2
                                            END 
                                        ELSE
                                            0
                                    END	biaya_masa11,
                                    CASE
                                        WHEN 
                                            NVL(STATUS_REQ, '0') = '0'
                                        THEN
                                            CASE 
                                                WHEN
                                                    masa11 > TGL_DELIVERY
                                                THEN
                                                    NULL
                                                ELSE
                                                    masa11
                                            END	
                                        ELSE
                                            CASE 
                                                WHEN
                                                    START_STACK > (TGL_BONGKAR+5)
                                                THEN
                                                    CASE
                                                        WHEN
                                                            ((TGL_BONGKAR+5)+5) < START_STACK
                                                        THEN
                                                            NULL
                                                        ELSE
                                                            START_STACK
                                                    END
                                                WHEN
                                                    START_STACK = (TGL_BONGKAR+5)
                                                THEN
                                                    START_STACK
                                                ELSE
                                                    NULL
                                            END
                                    END awal_masa12,
                                    CASE
                                        WHEN 
                                            NVL(STATUS_REQ, '0') = '0'
                                        THEN
                                            CASE 
                                                WHEN
                                                    masa11 > TGL_DELIVERY
                                                THEN
                                                    NULL
                                                ELSE
                                                    ((masa11 - 1) + totmasa12)
                                            END 
                                        ELSE
                                            CASE 
                                                WHEN
                                                    START_STACK > (TGL_BONGKAR+5)
                                                THEN
                                                    CASE
                                                        WHEN
                                                            ((TGL_BONGKAR+5)+5) < START_STACK
                                                        THEN
                                                            NULL
                                                        ELSE
                                                            (START_STACK + totmasa12) -1
                                                    END
                                                WHEN
                                                    START_STACK = (TGL_BONGKAR+5)
                                                THEN
                                                    (START_STACK  + totmasa12) - 1
                                                ELSE
                                                    NULL
                                            END
                                    END batas_masa12,
                                    totmasa12,
                                    CASE
                                        WHEN 
                                            HZ = 'N'
                                        THEN
                                            (TARIF * totmasa12 * 2)
                                        ELSE
                                            (TARIF * totmasa12 * 2) * 2
                                    END	 biaya_masa12,
                                    CASE
                                        WHEN 
                                            NVL(STATUS_REQ, '0') = '0'
                                        THEN
                                            CASE
                                                WHEN
                                                    (masa11 + totmasa12) > TGL_DELIVERY
                                                THEN
                                                    NULL
                                                ELSE 
                                                    (masa11 + totmasa12)
                                            END	
                                        ELSE
                                            CASE 
                                                WHEN
                                                    START_STACK > ((TGL_BONGKAR+5)+5)
                                                THEN
                                                    CASE
                                                        WHEN
                                                            ((TGL_BONGKAR+5)+5) <= START_STACK
                                                        THEN
                                                            START_STACK
                                                        ELSE
                                                            (TGL_BONGKAR+5)+ totmasa12
                                                    END
                                                WHEN
                                                    START_STACK = ((TGL_BONGKAR+5)+5)
                                                THEN
                                                    START_STACK - 1
                                                ELSE
                                                    CASE
                                                        WHEN
                                                            TGL_DELIVERY > ((TGL_BONGKAR+5)+5)
                                                        THEN
                                                            (START_STACK + totmasa12)
                                                        ELSE
                                                            NULL
                                                    END
                                            END
                                    END
                                    awal_masa2,
                                    CASE
                                        WHEN 
                                            NVL(STATUS_REQ, '0') = '0'
                                        THEN
                                            CASE
                                                WHEN
                                                    (masa11 + totmasa12) > TGL_DELIVERY
                                                THEN
                                                    NULL
                                                ELSE 
                                                    ((masa11 - 1) + totmasa12 + totmasa2)
                                            END	 
                                        ELSE
                                            CASE
                                                WHEN
                                                    TGL_DELIVERY > ((TGL_BONGKAR+5)+5)
                                                THEN
                                                    TGL_DELIVERY
                                                ELSE
                                                    NULL
                                            END
                                    END	batas_masa2,
                                    totmasa2,
                                    CASE
                                        WHEN 
                                            HZ = 'N'
                                        THEN
                                            (TARIF * totmasa2 * 3)
                                        ELSE
                                            (TARIF * totmasa2 * 3) * 2
                                    END	 biaya_masa2, 	
                                    CASE
                                        WHEN 
                                            NVL(STATUS_REQ, '0') = '0'
                                        THEN
                                            CASE
                                                WHEN
                                                    SELISIH_OUT > 5
                                                THEN 
                                                    CASE
                                                        WHEN
                                                            (selisih - SELISIH_OUT) < 6
                                                        THEN
                                                            CASE
                                                                WHEN								
                                                                    (selisih - SELISIH_OUT) < 0
                                                                THEN
                                                                    0
                                                                ELSE
                                                                    (selisih - SELISIH_OUT)
                                                            END
                                                        ELSE
                                                            5
                                                    END
                                                ELSE
                                                    0
                                            END 
                                        ELSE
                                            (totmasa12 + totmasa2)
                                    END	TOTMASA_USTER,
                                    CASE
                                        WHEN
                                            NVL(STATUS_REQ, '0') = '0'
                                        THEN
                                            (SELECT
                                                master_tarif.tarif AS tarif	
                                            FROM
                                                master_container
                                            JOIN container_stripping ON
                                                (master_container.no_container = container_stripping.no_container)
                                            JOIN iso_code ON
                                                ( iso_code.status = 'FCL'
                                                AND master_container.size_ = iso_code.size_
                                                AND master_container.type_ = iso_code.type_)
                                            JOIN master_tarif ON
                                                (master_tarif.id_iso = iso_code.id_iso)
                                            JOIN group_tarif ON
                                                (master_tarif.id_group_tarif = group_tarif.id_group_tarif)
                                            JOIN request_stripping ON
                                                (request_stripping.no_request = container_stripping.no_request)
                                            WHERE
                                                container_stripping.no_request = V.NO_REQUEST
                                                AND container_stripping.NO_CONTAINER = V.NO_CONTAINER
                                                AND group_tarif.kategori_tarif = 'LOLO_TPK'
                                                AND request_stripping.tgl_request BETWEEN group_tarif.start_period AND group_tarif.end_period
                                            GROUP BY
                                                iso_code.id_iso,
                                                master_tarif.tarif)
                                        ELSE
                                            0
                                    END lift_on_tpk
                                FROM(				
                                    SELECT
                                        A.NO_CONTAINER,
                                        B.SIZE_,
                                        B.TYPE_,
                                        'FCL' STATUS,
                                        A.NO_REQUEST,
                                        C.TGL_REQUEST,
                                        A.TGL_BONGKAR,
                                        CASE
                                            WHEN 
                                                NVL(C.STATUS_REQ, '0') = '0'
                                            THEN
                                                A.TGL_BONGKAR
                                            ELSE
                                                A.START_PERP_PNKN
                                        END START_STACK,
                                        CASE
                                            WHEN 
                                                NVL(C.STATUS_REQ, '0') = '0'
                                            THEN
                                                A.TGL_SELESAI
                                            ELSE
                                                A.END_STACK_PNKN
                                        END TGL_DELIVERY,
                                        CASE
                                            WHEN 
                                                NVL(C.STATUS_REQ, '0') = '0'
                                            THEN
                                                (A.TGL_SELESAI - A.TGL_BONGKAR)+1 
                                            ELSE
                                                (A.END_STACK_PNKN - A.START_PERP_PNKN)+1
                                        END	selisih,
                                        CASE
                                            WHEN 
                                                NVL(C.STATUS_REQ, '0') = '0'
                                            THEN
                                                (A.TGL_SELESAI - A.TGL_BONGKAR)+1
                                            ELSE
                                                (A.END_STACK_PNKN - A.START_PERP_PNKN)+1
                                        END selisih_stack,				
                                        CASE 
                                            WHEN 
                                                NVL(C.STATUS_REQ, '0') = '0'
                                            THEN
                                                CASE
                                                    WHEN
                                                        (A.TGL_BONGKAR + 5) > A.TGL_SELESAI
                                                    THEN
                                                        A.TGL_SELESAI + 1
                                                    ELSE
                                                        (A.TGL_BONGKAR + 5)
                                                END	
                                            ELSE
                                                CASE
                                                    WHEN
                                                        (A.TGL_BONGKAR + 5) > A.END_STACK_PNKN
                                                    THEN
                                                        A.END_STACK_PNKN
                                                    ELSE
                                                        NULL
                                                END
                                        END
                                        masa11,
                                        CASE 
                                            WHEN 
                                                NVL(C.STATUS_REQ, '0') = '0'
                                            THEN
                                                CASE
                                                    WHEN 
                                                        (A.TGL_BONGKAR + 5) < A.TGL_SELESAI
                                                    THEN 
                                                        5
                                                    ELSE
                                                        A.TGL_SELESAI - (A.TGL_BONGKAR)+1
                                                END
                                            ELSE
                                                CASE
                                                    WHEN 
                                                        (A.TGL_BONGKAR + 5) < A.END_STACK_PNKN
                                                    THEN 
                                                        0
                                                    ELSE
                                                        A.END_STACK_PNKN - (A.TGL_BONGKAR + 5)
                                                END
                                        END	totmasa11,
                                        CASE 
                                            WHEN 
                                                NVL(C.STATUS_REQ, '0') = '0'
                                            THEN
                                                CASE
                                                    WHEN 
                                                        (A.TGL_BONGKAR + 5) < A.TGL_SELESAI
                                                    THEN 
                                                        CASE
                                                            WHEN
                                                                (A.TGL_SELESAI) - (A.TGL_BONGKAR + 5) > 5
                                                            THEN 
                                                                5
                                                            WHEN
                                                                (A.TGL_SELESAI) - (A.TGL_BONGKAR + 5) < 5
                                                            THEN
                                                                (A.TGL_SELESAI) - (A.TGL_BONGKAR + 5)+1
                                                            WHEN
                                                                (A.TGL_SELESAI) - (A.TGL_BONGKAR + 5) = 5
                                                            THEN
                                                                (A.TGL_SELESAI) - (A.TGL_BONGKAR + 5)
                                                        END			
                                                    ELSE
                                                        CASE
                                                            WHEN
                                                                (A.TGL_SELESAI - (A.TGL_BONGKAR+5)) = 0
                                                            THEN
                                                                1
                                                            WHEN 
                                                                (A.TGL_SELESAI - (A.TGL_BONGKAR+5)) < 0
                                                            THEN
                                                                0
                                                            ELSE
                                                                (A.TGL_SELESAI - (A.TGL_BONGKAR+5))
                                                        END
                                                END
                                            ELSE 				
                                                CASE
                                                    WHEN
                                                        A.START_PERP_PNKN >= ((A.TGL_BONGKAR + 5)+5)
                                                    THEN
                                                        0
                                                    WHEN 
                                                        A.START_PERP_PNKN <= ((A.TGL_BONGKAR + 5))
                                                    THEN
                                                        CASE
                                                            WHEN
                                                                (((A.TGL_BONGKAR + 5)+5)-1) > A.END_STACK_PNKN
                                                            THEN
                                                                (A.END_STACK_PNKN - A.START_PERP_PNKN) + 1
                                                            ELSE
                                                            5
                                                        END
                                                    WHEN
                                                        A.START_PERP_PNKN > (A.TGL_BONGKAR + 5)
                                                    THEN
                                                        (A.END_STACK_PNKN - A.START_PERP_PNKN)+1
                                                    ELSE
                                                        0 --?
                                                END
                                        END	totmasa12,
                                        CASE 
                                            WHEN 
                                                NVL(C.STATUS_REQ, '0') = '0'
                                            THEN
                                                CASE 
                                                    WHEN
                                                        (A.TGL_SELESAI - ((A.TGL_BONGKAR + 5) +5)) < 0
                                                    THEN
                                                        0
                                                    ELSE
                                                        ((A.TGL_SELESAI + 1) - ((A.TGL_BONGKAR + 5) +5))
                                                END 
                                            ELSE
                                                CASE
                                                    WHEN
                                                        A.START_PERP_PNKN > ((A.TGL_BONGKAR + 5)+5)
                                                    THEN
                                                        (A.END_STACK_PNKN - (A.START_PERP_PNKN ))+1
                                                    ELSE
                                                        CASE
                                                            WHEN
                                                                ((A.TGL_BONGKAR + 5)+5) < A.END_STACK_PNKN
                                                            THEN
                                                                (A.END_STACK_PNKN - ((A.TGL_BONGKAR + 5)+5))+1
                                                            ELSE
                                                                0
                                                        END
                                                END
                                        END	totmasa2,
                                        (SELECT 
                                            MT.TARIF
                                        FROM 
                                            master_tarif MT,
                                            ISO_CODE IC,
                                            GROUP_TARIF GT
                                        WHERE 
                                            GT.KATEGORI_TARIF = 'PENUMPUKAN_DEPO'
                                            AND C.TGL_REQUEST BETWEEN GT.start_period  AND GT.end_period
                                            AND MT.ID_GROUP_TARIF = GT.ID_GROUP_TARIF
                                            AND IC.SIZE_ = B.SIZE_
                                            AND IC.TYPE_ = B.TYPE_
                                            AND IC.STATUS = 'FCL'
                                            AND MT.ID_ISO = IC.ID_ISO) TARIF,
                                            TO_DATE(TO_CHAR(D.TGL_IN, 'YYYYMMDD'), 'YYYYMMDD') AS TGL_OUT,
                                            A.HZ,
                                            C.STATUS_REQ,
                                            CASE 
                                                WHEN 
                                                    NVL(C.STATUS_REQ, '0') = '0'
                                                THEN
                                                    CASE
                                                        WHEN			
                                                            (TO_DATE(TO_CHAR(D.TGL_IN, 'YYYYMMDD'), 'YYYYMMDD') - A.TGL_BONGKAR) <= 0
                                                        THEN
                                                            1
                                                        ELSE
                                                            (TO_DATE(TO_CHAR(D.TGL_IN, 'YYYYMMDD'), 'YYYYMMDD')  - A.TGL_BONGKAR)+1 ---?
                                                    END 
                                                ELSE
                                                    CASE
                                                        WHEN			
                                                            (TO_DATE(TO_CHAR(D.TGL_IN, 'YYYYMMDD'), 'YYYYMMDD') - A.START_PERP_PNKN) <= 0
                                                        THEN
                                                            ((TO_DATE(TO_CHAR(D.TGL_IN, 'YYYYMMDD'), 'YYYYMMDD') + 1) - A.TGL_BONGKAR)
                                                        ELSE
                                                            ((TO_DATE(TO_CHAR(D.TGL_IN, 'YYYYMMDD'), 'YYYYMMDD') + 1) - A.START_PERP_PNKN)
                                                    END
                                            END SELISIH_OUT
                                    FROM
                                        CONTAINER_STRIPPING A
                                        JOIN MASTER_CONTAINER B ON A.NO_CONTAINER = B.NO_CONTAINER
                                        JOIN REQUEST_STRIPPING C ON A.NO_REQUEST = C.NO_REQUEST
                                        LEFT JOIN BORDER_GATE_IN D  ON A.NO_CONTAINER = D.NO_CONTAINER AND C.NO_REQUEST_RECEIVING = D.NO_REQUEST
                                    WHERE
                                        A.NO_REQUEST = '$noreq' AND 
                                        C.NOTA = 'Y'
                                    ORDER BY A.no_request ASC
                                    )V
                            )X	ORDER BY no_request";
    }
    else {
        $query_list_ = "SELECT 
                            X.*,
                            (ROUND(lift_on_tpk * 0.1, 0) + lift_on_tpk) lift_on_tpk_ppn,
                            CASE 
                                WHEN
                                    TOTMASA_USTER > 0
                                THEN
                                    CASE
                                        WHEN
                                            TGL_OUT < BATAS_MASA12
                                        THEN
                                            totmasa12
                                        ELSE
                                            0
                                    END
                                ELSE
                                    0
                            END masa12_uster,
                            CASE
                                WHEN 
                                    HZ = 'N'
                                THEN
                                    (TARIF * (
                                        CASE 
                                            WHEN
                                                TOTMASA_USTER > 0
                                            THEN
                                                CASE
                                                    WHEN
                                                        TGL_OUT < BATAS_MASA12
                                                    THEN
                                                        totmasa12
                                                    ELSE
                                                        0
                                                END
                                            ELSE
                                                0
                                        END
                                    ) * 2)
                                ELSE
                                    (TARIF * (
                                        CASE 
                                            WHEN
                                                TOTMASA_USTER > 0
                                            THEN
                                                CASE
                                                    WHEN
                                                        TGL_OUT < BATAS_MASA12
                                                    THEN
                                                        totmasa12
                                                    ELSE
                                                        0
                                                END
                                            ELSE
                                                0
                                        END
                                    ) * 2) * 2
                            END biaya_masa12_uster,	 
                            CASE 
                                WHEN
                                    TOTMASA_USTER > 0
                                THEN
                                    CASE
                                        WHEN
                                            TGL_OUT < BATAS_MASA2
                                        THEN
                                            TOTMASA_USTER - (
                                                CASE
                                                    WHEN
                                                        TGL_OUT < BATAS_MASA12
                                                    THEN
                                                        CASE
                                                            WHEN
                                                                (BATAS_MASA12 - TGL_OUT) > 5
                                                            THEN
                                                                totmasa12
                                                            ELSE
                                                                (BATAS_MASA12 - TGL_OUT)
                                                        END
                                                    ELSE
                                                        0
                                                END
                                            )
                                        ELSE
                                            0
                                    END
                                ELSE
                                    0
                            END masa2_uster,
                            CASE
                                WHEN 
                                    HZ = 'N'
                                THEN
                                    (TARIF * (
                                        CASE 
                                            WHEN
                                                TOTMASA_USTER > 0
                                            THEN
                                                CASE
                                                    WHEN
                                                        TGL_OUT < BATAS_MASA2
                                                    THEN
                                                        TOTMASA_USTER - (
                                                            CASE
                                                                WHEN
                                                                    TGL_OUT < BATAS_MASA12
                                                                THEN
                                                                    CASE
                                                                        WHEN
                                                                            (BATAS_MASA12 - TGL_OUT) > 5
                                                                        THEN
                                                                            totmasa12
                                                                        ELSE
                                                                            (BATAS_MASA12 - TGL_OUT)
                                                                    END
                                                                ELSE
                                                                    0
                                                            END
                                                        )
                                                    ELSE
                                                        0
                                                END
                                            ELSE
                                                0
                                        END
                                    ) * 3)
                                ELSE
                                    (TARIF * (
                                        CASE 
                                            WHEN
                                                TOTMASA_USTER > 0
                                            THEN
                                                CASE
                                                    WHEN
                                                        TGL_OUT < BATAS_MASA2
                                                    THEN
                                                        TOTMASA_USTER - (
                                                            CASE
                                                                WHEN
                                                                    TGL_OUT < BATAS_MASA12
                                                                THEN
                                                                    CASE
                                                                        WHEN
                                                                            (BATAS_MASA12 - TGL_OUT) > 5
                                                                        THEN
                                                                            totmasa12
                                                                        ELSE
                                                                            (BATAS_MASA12 - TGL_OUT)
                                                                    END
                                                                ELSE
                                                                    0
                                                            END
                                                        )
                                                    ELSE
                                                        0
                                                END
                                            ELSE
                                                0
                                        END
                                    ) * 3) * 2
                            END biaya_masa2_uster
                        FROM (	
                            SELECT 
                                NO_CONTAINER,
                                HZ,
                                SIZE_,
                                TYPE_,
                                STATUS,
                                NO_REQUEST,
                                TGL_REQUEST,
                                TGL_BONGKAR,
                                START_STACK,
                                TGL_DELIVERY,		
                                TGL_OUT,
                                STATUS_REQ,
                                TARIF,
                                selisih SELISIH_TOTAL,
                                SELISIH_OUT,
                                masa11 - 1 batas_masa11,
                                totmasa11,
                                CASE
                                    WHEN
                                        totmasa11 > 0
                                    THEN
                                        CASE
                                            WHEN 
                                                HZ = 'N'
                                            THEN
                                                (TARIF * 1)
                                            ELSE
                                                (TARIF * 1) * 2
                                        END 
                                    ELSE
                                        0
                                END	biaya_masa11,
                                CASE
                                    WHEN 
                                        NVL(STATUS_REQ, '0') = '0'
                                    THEN
                                        CASE 
                                            WHEN
                                                masa11 > TGL_DELIVERY
                                            THEN
                                                NULL
                                            ELSE
                                                masa11
                                        END	
                                    ELSE
                                        CASE 
                                            WHEN
                                                START_STACK > (TGL_BONGKAR+5)
                                            THEN
                                                CASE
                                                    WHEN
                                                        ((TGL_BONGKAR+5)+5) < START_STACK
                                                    THEN
                                                        NULL
                                                    ELSE
                                                        START_STACK
                                                END
                                            WHEN
                                                START_STACK = (TGL_BONGKAR+5)
                                            THEN
                                                START_STACK
                                            ELSE
                                                NULL
                                        END
                                END awal_masa12,
                                CASE
                                    WHEN 
                                        NVL(STATUS_REQ, '0') = '0'
                                    THEN
                                        CASE 
                                            WHEN
                                                masa11 > TGL_DELIVERY
                                            THEN
                                                NULL
                                            ELSE
                                                ((masa11 - 1) + totmasa12)
                                        END 
                                    ELSE
                                        CASE 
                                            WHEN
                                                START_STACK > (TGL_BONGKAR+5)
                                            THEN
                                                CASE
                                                    WHEN
                                                        ((TGL_BONGKAR+5)+5) < START_STACK
                                                    THEN
                                                        NULL
                                                    ELSE
                                                        (START_STACK + totmasa12) -1
                                                END
                                            WHEN
                                                START_STACK = (TGL_BONGKAR+5)
                                            THEN
                                                (START_STACK  + totmasa12) - 1
                                            ELSE
                                                NULL
                                        END
                                END batas_masa12,
                                totmasa12,
                                CASE
                                    WHEN 
                                        HZ = 'N'
                                    THEN
                                        (TARIF * totmasa12 * 2)
                                    ELSE
                                        (TARIF * totmasa12 * 2) * 2
                                END	 biaya_masa12,
                                CASE
                                    WHEN 
                                        NVL(STATUS_REQ, '0') = '0'
                                    THEN
                                        CASE
                                            WHEN
                                                (masa11 + totmasa12) > TGL_DELIVERY
                                            THEN
                                                NULL
                                            ELSE 
                                                (masa11 + totmasa12)
                                        END	
                                    ELSE
                                        CASE 
                                            WHEN
                                                START_STACK > ((TGL_BONGKAR+5)+5)
                                            THEN
                                                CASE
                                                    WHEN
                                                        ((TGL_BONGKAR+5)+5) <= START_STACK
                                                    THEN
                                                        START_STACK
                                                    ELSE
                                                        (TGL_BONGKAR+5)+ totmasa12
                                                END
                                            WHEN
                                                START_STACK = ((TGL_BONGKAR+5)+5)
                                            THEN
                                                START_STACK - 1
                                            ELSE
                                                CASE
                                                    WHEN
                                                        TGL_DELIVERY > ((TGL_BONGKAR+5)+5)
                                                    THEN
                                                        (START_STACK + totmasa12)
                                                    ELSE
                                                        NULL
                                                END
                                        END
                                END
                                awal_masa2,
                                CASE
                                    WHEN 
                                        NVL(STATUS_REQ, '0') = '0'
                                    THEN
                                        CASE
                                            WHEN
                                                (masa11 + totmasa12) > TGL_DELIVERY
                                            THEN
                                                NULL
                                            ELSE 
                                                ((masa11 - 1) + totmasa12 + totmasa2)
                                        END	 
                                    ELSE
                                        CASE
                                            WHEN
                                                TGL_DELIVERY > ((TGL_BONGKAR+5)+5)
                                            THEN
                                                TGL_DELIVERY
                                            ELSE
                                                NULL
                                        END
                                END	batas_masa2,
                                totmasa2,
                                CASE
                                    WHEN 
                                        HZ = 'N'
                                    THEN
                                        (TARIF * totmasa2 * 3)
                                    ELSE
                                        (TARIF * totmasa2 * 3) * 2
                                END	 biaya_masa2, 	
                                CASE
                                    WHEN 
                                        NVL(STATUS_REQ, '0') = '0'
                                    THEN
                                        CASE
                                            WHEN
                                                SELISIH_OUT > 5
                                            THEN 
                                                CASE
                                                    WHEN
                                                        (selisih - SELISIH_OUT) < 6
                                                    THEN
                                                        CASE
                                                            WHEN								
                                                                (selisih - SELISIH_OUT) < 0
                                                            THEN
                                                                0
                                                            ELSE
                                                                (selisih - SELISIH_OUT)
                                                        END
                                                    ELSE
                                                        5
                                                END
                                            ELSE
                                                0
                                        END 
                                    ELSE
                                        (totmasa12 + totmasa2)
                                END	TOTMASA_USTER,
                                CASE
                                    WHEN
                                        NVL(STATUS_REQ, '0') = '0'
                                    THEN
                                        (SELECT
                                            master_tarif.tarif AS tarif	
                                        FROM
                                            master_container
                                        JOIN CONTAINER_STUFFING ON
                                            (master_container.no_container = CONTAINER_STUFFING.NO_CONTAINER)
                                        JOIN iso_code ON
                                            ( iso_code.status = 'MTY'
                                            AND master_container.size_ = iso_code.size_
                                            AND master_container.type_ = iso_code.type_)
                                        JOIN master_tarif ON
                                            (master_tarif.id_iso = iso_code.id_iso)
                                        JOIN group_tarif ON
                                            (master_tarif.id_group_tarif = group_tarif.id_group_tarif)
                                        JOIN REQUEST_STUFFING ON
                                            (request_stuffing.NO_REQUEST = container_stuffing.NO_REQUEST)
                                        WHERE
                                            container_stuffing.NO_REQUEST = V.NO_REQUEST
                                            AND container_stuffing.NO_CONTAINER = V.NO_CONTAINER
                                            AND group_tarif.kategori_tarif = 'LOLO_TPK'
                                            AND request_stuffing.tgl_request BETWEEN group_tarif.start_period AND group_tarif.end_period
                                        GROUP BY
                                            iso_code.id_iso,
                                            master_tarif.tarif)
                                    ELSE
                                        0
                                END lift_on_tpk
                            FROM(			
                                SELECT
                                    A.NO_CONTAINER,
                                    B.SIZE_,
                                    B.TYPE_,
                                    'FCL' STATUS,
                                    A.NO_REQUEST,
                                    C.TGL_REQUEST,
                                    A.START_STACK TGL_BONGKAR,
                                    CASE
                                        WHEN 
                                            NVL(C.STATUS_REQ, '0') = '0'
                                        THEN
                                            A.START_STACK
                                        ELSE
                                            A.START_PERP_PNKN
                                    END START_STACK,
                                    CASE
                                        WHEN 
                                            NVL(C.STATUS_REQ, '0') = '0'
                                        THEN
                                            A.START_PERP_PNKN
                                        ELSE
                                            A.END_STACK_PNKN
                                    END TGL_DELIVERY,
                                    CASE
                                        WHEN 
                                            NVL(C.STATUS_REQ, '0') = '0'
                                        THEN
                                            (A.START_PERP_PNKN - A.START_STACK)+1 
                                        ELSE
                                            (A.END_STACK_PNKN - A.START_PERP_PNKN)+1
                                    END	selisih,
                                    CASE
                                        WHEN 
                                            NVL(C.STATUS_REQ, '0') = '0'
                                        THEN
                                            (A.START_PERP_PNKN - A.START_STACK)+1
                                        ELSE
                                            (A.END_STACK_PNKN - A.START_PERP_PNKN)+1
                                    END selisih_stack,				
                                    CASE 
                                        WHEN 
                                            NVL(C.STATUS_REQ, '0') = '0'
                                        THEN
                                            CASE
                                                WHEN
                                                    (A.START_STACK + 5) > A.START_PERP_PNKN
                                                THEN
                                                    A.START_PERP_PNKN + 1
                                                ELSE
                                                    (A.START_STACK + 5)
                                            END	
                                        ELSE
                                            CASE
                                                WHEN
                                                    (A.START_STACK + 5) > A.END_STACK_PNKN
                                                THEN
                                                    A.END_STACK_PNKN
                                                ELSE
                                                    NULL
                                            END
                                    END
                                    masa11,
                                    CASE 
                                        WHEN 
                                            NVL(C.STATUS_REQ, '0') = '0'
                                        THEN
                                            CASE
                                                WHEN 
                                                    (A.START_STACK + 5) < A.START_PERP_PNKN
                                                THEN 
                                                    5
                                                ELSE
                                                    A.START_PERP_PNKN - (A.START_STACK)+1
                                            END
                                        ELSE
                                            CASE
                                                WHEN 
                                                    (A.START_STACK + 5) < A.END_STACK_PNKN
                                                THEN 
                                                    0
                                                ELSE
                                                    A.END_STACK_PNKN - (A.START_STACK + 5)
                                            END
                                    END	totmasa11,
                                    CASE 
                                        WHEN 
                                            NVL(C.STATUS_REQ, '0') = '0'
                                        THEN
                                            CASE
                                                WHEN 
                                                    (A.START_STACK + 5) < A.START_PERP_PNKN
                                                THEN 
                                                    CASE
                                                        WHEN
                                                            (A.START_PERP_PNKN) - (A.START_STACK + 5) > 5
                                                        THEN 
                                                            5
                                                        WHEN
                                                            (A.START_PERP_PNKN) - (A.START_STACK + 5) < 5
                                                        THEN
                                                            (A.START_PERP_PNKN) - (A.START_STACK + 5)+1
                                                        WHEN
                                                            (A.START_PERP_PNKN) - (A.START_STACK + 5) = 5
                                                        THEN
                                                            (A.START_PERP_PNKN) - (A.START_STACK + 5)
                                                    END			
                                                ELSE
                                                    CASE
                                                        WHEN
                                                            (A.START_PERP_PNKN - (A.START_STACK+5)) = 0
                                                        THEN
                                                            1
                                                        WHEN 
                                                            (A.START_PERP_PNKN - (A.START_STACK+5)) < 0
                                                        THEN
                                                            0
                                                        ELSE
                                                            (A.START_PERP_PNKN - (A.START_STACK+5))
                                                    END
                                            END
                                        ELSE 				
                                            CASE
                                                WHEN
                                                    A.START_PERP_PNKN >= ((A.START_STACK + 5)+5)
                                                THEN
                                                    0
                                                WHEN 
                                                    A.START_PERP_PNKN <= ((A.START_STACK + 5))
                                                THEN
                                                    CASE
                                                        WHEN
                                                            (((A.START_STACK + 5)+5)-1) > A.END_STACK_PNKN
                                                        THEN
                                                            (A.END_STACK_PNKN - A.START_PERP_PNKN) + 1
                                                        ELSE
                                                        5
                                                    END
                                                WHEN
                                                    A.START_PERP_PNKN > (A.START_STACK + 5)
                                                THEN
                                                    (A.END_STACK_PNKN - A.START_PERP_PNKN)+1
                                                ELSE
                                                    0 --?
                                            END
                                    END	totmasa12,
                                    ((A.START_STACK + 5)+5),
                                    CASE 
                                        WHEN 
                                            NVL(C.STATUS_REQ, '0') = '0'
                                        THEN
                                            CASE 
                                                WHEN
                                                    (A.START_PERP_PNKN - ((A.START_STACK + 5) +5)) < 0
                                                THEN
                                                    0
                                                ELSE
                                                    ((A.START_PERP_PNKN + 1) - ((A.START_STACK + 5) +5))
                                            END 
                                        ELSE
                                            CASE
                                                WHEN
                                                    A.START_PERP_PNKN > ((A.START_STACK + 5)+5)
                                                THEN
                                                    (A.END_STACK_PNKN - (A.START_PERP_PNKN ))+1
                                                ELSE
                                                    CASE
                                                        WHEN
                                                            ((A.START_STACK + 5)+5) < A.END_STACK_PNKN
                                                        THEN
                                                            (A.END_STACK_PNKN - ((A.START_STACK + 5)+5))+1
                                                        ELSE
                                                            0
                                                    END
                                            END
                                    END	totmasa2,
                                    (SELECT 
                                        MT.TARIF
                                    FROM 
                                        master_tarif MT,
                                        ISO_CODE IC,
                                        GROUP_TARIF GT
                                    WHERE 
                                        GT.KATEGORI_TARIF = 'PENUMPUKAN_DEPO'
                                        AND C.TGL_REQUEST BETWEEN GT.start_period  AND GT.end_period
                                        AND MT.ID_GROUP_TARIF = GT.ID_GROUP_TARIF
                                        AND IC.SIZE_ = B.SIZE_
                                        AND IC.TYPE_ = B.TYPE_
                                        AND IC.STATUS = 'MTY'
                                        AND MT.ID_ISO = IC.ID_ISO) TARIF,
                                        TO_DATE(TO_CHAR(D.TGL_IN, 'YYYYMMDD'), 'YYYYMMDD') AS TGL_OUT,
                                        A.HZ,
                                        C.STATUS_REQ,
                                        CASE 
                                            WHEN 
                                                NVL(C.STATUS_REQ, '0') = '0'
                                            THEN
                                                CASE
                                                    WHEN			
                                                        (TO_DATE(TO_CHAR(D.TGL_IN, 'YYYYMMDD'), 'YYYYMMDD') - A.START_STACK) <= 0
                                                    THEN
                                                        1
                                                    ELSE
                                                        (TO_DATE(TO_CHAR(D.TGL_IN, 'YYYYMMDD'), 'YYYYMMDD')  - A.START_STACK)+1 ---?
                                                END 
                                            ELSE
                                                CASE
                                                    WHEN			
                                                        (TO_DATE(TO_CHAR(D.TGL_IN, 'YYYYMMDD'), 'YYYYMMDD') - A.START_PERP_PNKN) <= 0
                                                    THEN
                                                        ((TO_DATE(TO_CHAR(D.TGL_IN, 'YYYYMMDD'), 'YYYYMMDD') + 1) - A.START_STACK)
                                                    ELSE
                                                        ((TO_DATE(TO_CHAR(D.TGL_IN, 'YYYYMMDD'), 'YYYYMMDD') + 1) - A.START_PERP_PNKN)
                                                END
                                        END SELISIH_OUT
                                FROM
                                    CONTAINER_STUFFING A
                                    JOIN MASTER_CONTAINER B ON A.NO_CONTAINER = B.NO_CONTAINER
                                    JOIN REQUEST_STUFFING C ON A.NO_REQUEST = C.NO_REQUEST
                                    LEFT JOIN BORDER_GATE_IN D  ON A.NO_CONTAINER = D.NO_CONTAINER AND C.NO_REQUEST_RECEIVING = D.NO_REQUEST
                                WHERE
                                    C.NOTA = 'Y'
                                    AND A.ASAL_CONT = 'TPK'
                                    AND A.NO_REQUEST = '$noreq'
                                ORDER BY A.no_request ASC			
                                )V								
                        )X	ORDER BY no_request";
    }

	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll();


?>

 <div id="list">
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
                <?php 
                    if($jenis == 'REPO') { ?>
                        <tr>
                            <th colspan="33">REPORT PENUMPUKAN <?php echo $jenis; ?></th>
                        </tr>
                        <tr>
                            <th colspan="33">PT. PELABUHAN INDONESIA II - CABANG PONTIANAK</th>
                        </tr>
                              <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header" style="font-size:8pt">No</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">No. Request</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Tgl. Request</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">No. Container </th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Size </th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Type</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Status</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt; width:100px;">Hz</th> 
                                  <th valign="top" class="grid-header"  style="font-size:8pt;">Start Stack</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Tgl. Delivery</th> 
								  <th valign="top" class="grid-header"  style="font-size:8pt">Tgl. Out</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">Status Req</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">Tarif</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">Selisih Total</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Selisih Out</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Batas Masa 1.1</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Total Masa 1.1</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Biaya Masa 1.1</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Awal Masa 1.2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Batas Masa 1.2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Total Masa 1.2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Biaya Masa 1.2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Awal Masa 2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Batas Masa 2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Total Masa 2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Biaya Masa 2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Total Masa TPK</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Lift Of TPK</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Lift Of TPK + PPN</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Masa 1.2 TPK</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Biaya Masa 1.2 TPK</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Masa 2 TPK</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Biaya Masa 2 TPK</th>
                              </tr>
                              <?php $i=0;
							  foreach($row_list as $data){ $i++;?>
                              <tr>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$i?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['NO_REQUEST'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TGL_REQUEST'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['NO_CONTAINER'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['SIZE_'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TYPE_'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['STATUS'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['HZ'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['START_STACK'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TGL_DELIVERY'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TGL_OUT'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['STATUS_REQ'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TARIF'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['SELISIH_TOTAL'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['SELISIH_OUT'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BATAS_MASA11'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TOTMASA11'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BIAYA_MASA11'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['AWAL_MASA12'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BATAS_MASA12'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TOTMASA12'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BIAYA_MASA12'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['AWAL_MASA2'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BATAS_MASA2'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TOTMASA2'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BIAYA_MASA2'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TOTMASA_TPK'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['LIFT_OFF_TPK'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['LIFT_OFF_TPK_PPN'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['MASA12_TPK'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BIAYA_MASA12_TPK'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['MASA2_TPK'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BIAYA_MASA2_TPK'] ?></td>
							</tr>
                            <?php }
                        }
                        else { ?>
                            <tr>
                                <th colspan="34">REPORT PENUMPUKAN <?php echo $jenis; ?></th>
                            </tr>
                            <tr>
                                <th colspan="34">PT. PELABUHAN INDONESIA II - CABANG PONTIANAK</th>
                            </tr>
                            <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header" style="font-size:8pt">No</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">No. Request</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Tgl. Request</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">No. Container </th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Size </th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Type</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Status</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt;">Hz</th> 
                                  <th valign="top" class="grid-header"  style="font-size:8pt;">Start Stack</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt;">Tanggal Bongkar</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Tgl. Delivery</th> 
								  <th valign="top" class="grid-header"  style="font-size:8pt">Tgl. Out</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">Status Req</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">Tarif</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">Selisih Total</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Selisih Out</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Batas Masa 1.1</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Total Masa 1.1</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Biaya Masa 1.1</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Awal Masa 1.2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Batas Masa 1.2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Total Masa 1.2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Biaya Masa 1.2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Awal Masa 2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Batas Masa 2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Total Masa 2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Biaya Masa 2</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Total Masa USTER</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Lift On TPK</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Lift On TPK + PPN</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Masa 1.2 USTER</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Biaya Masa 1.2 USTER</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Masa 2 USTER</th>
                                  <th valign="top" class="grid-header" style="font-size:8pt">Biaya Masa 2 USTER</th>
                              </tr>
                              <?php $i=0;
							  foreach($row_list as $data){ $i++;?>
                              <tr>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$i?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['NO_REQUEST'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TGL_REQUEST'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['NO_CONTAINER'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['SIZE_'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TYPE_'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['STATUS'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['HZ'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['START_STACK'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TGL_BONGKAR'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TGL_DELIVERY'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TGL_OUT'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['STATUS_REQ'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TARIF'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['SELISIH_TOTAL'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['SELISIH_OUT'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BATAS_MASA11'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TOTMASA11'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BIAYA_MASA11'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['AWAL_MASA12'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BATAS_MASA12'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TOTMASA12'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BIAYA_MASA12'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['AWAL_MASA2'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BATAS_MASA2'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TOTMASA2'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BIAYA_MASA2'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['TOTMASA_USTER'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['LIFT_ON_TPK'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['LIFT_ON_TPK_PPN'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['MASA12_USTER'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BIAYA_MASA12_USTER'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['MASA2_USTER'] ?></td>
                                <td align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$data['BIAYA_MASA2_USTER'] ?></td>
                        <?php }
                    } ?>
        </table>
 </div>