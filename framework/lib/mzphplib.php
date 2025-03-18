<?php
/*$branch = GetLoginBranch();
if ($branch == '') {
    header('Location: ' . HOME);
    exit;
}*/

function MZgetMonth($mon)
{
    static $AMon = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    return $AMon[$mon - 1];
}

function ValidMonth($sMon)
{
    static $AMon = array('jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec');
    for ($i = 0; $i < 12; $i++) {
        if (strtolower($sMon) == $AMon[$i])
            return $i + 1;
    }
    return 0;
}

// Convert array date untuk di Display ke format 01-DEC-2010
function DisplayDate_Arr($d, $dateonly = true)
{
    //echo $d;
    $res = $d['d'] . '-' . MZgetMonth($d['m']) . '-' . $d['y'];
    if (!$dateonly)
        $res .= ' ' . padd($d['hh'], 2) . ':' . padd($d['mm'], 2) . ':' . padd($d['ss'], 2);
    return $res;
}

// Convert  '2010-12-01'  ke format 01-DEC-2010
function DisplayDate_Str($sd)
{
    $d = explode("-", $sd);
    $res = $d[2] . '-' . MZgetMonth($d[1]) . '-' . $d[0];
    return $res;
}

// membaca tanggal, dan mengembalikan dalam bentuk 2010-01-02
function OraDateRead($dt)
{
    $res = new DateTime($dt);
    //return $res->format ('Y-m-d');
    $d = "0" . $res->format('d');
    $d = $res->format('Y') . '-' . $res->format('m') . '-' . substr($d, -2);
    return ($d);
}

// membaca tanggal, dan mengembalikan dalam bentuk 2010-01-02
function OraDateRead2($dt)
{
    $res = new DateTime($dt);
    //return $res->format ('Y-m-d');
    $d = "0" . $res->format('d');
    $d = $res->format('Y') . '-' . $res->format('m') . '-' . "0" . $res->format('d');
    return ($d);
}

function OraDatetimeRead($dt)
{
    $res = new DateTime($dt);
//		echo $res->format ('Y-m-d H:i');exit;
    $d = "0" . $res->format('d');
    $d = $res->format('Y') . '-' . $res->format('m') . '-' . substr($d, -2) . ' ' . $res->format('H') . ':' . $res->format('i');
    return ($d);
}

// menyingkat penulisan tanggal untuk SQL command ke Oracle
function OraDate($dt)
{
    // $dt berupa array date
    return ("to_date('" . createIsoDate($dt) . "','YYYY-MM-DD HH24:MI:SS')");
}


function day00($d)
{
    if (isset ($d)) {
        if ((int)$d < 10) {
            return substr("0$d", -2);
        } else {
            return $d;
        }
    }

    return '01';
}

function MZcreateIsoDate($v, $dateonly = false)
{
    if (!is_array($v)) $v = array();

    $res = isset($v['y']) ? $v['y'] : '0000';
    $res .= '-' . day00($v['m']);
    $res .= '-' . day00($v['d']);
    if (!$dateonly) {
        $res .= ' ' . (isset($v['hh']) ? $v['hh'] : '00');
        $res .= ':' . (isset($v['mm']) ? $v['mm'] : '00');
        $res .= ':' . (isset($v['ss']) ? $v['ss'] : '00');
    }

    return $res;
}


function __ValidDate($yy, $mm, $dd)
{
    //echo "        __Validate ( $yy, $mm, $dd )<br />";

    if ((int)$mm > 12 && (int)$dd <= 12) {
        $t = $mm;
        $mm = $dd;
        $dd = $t;        // tukar
    }

    if (ValidMonth($mm) > 0)
        $mm = ValidMonth($mm);

    if (checkdate((int)$mm, (int)$dd, (int)$yy)) {
        return ($dd . '-' . MZgetMonth($mm) . '-' . $yy);
    } else {
        if ($mm >= 1 && $mm <= 12) {
            if ($mm == 2)
                return ('28-' . MZgetMonth($mm) . '-' . $yy);
            if ($dd > 30)
                return ('30-' . MZgetMonth($mm) . '-' . $yy);
        }
        return ('1-JAN-1900');
    }
}

// Check string date,
//	Try to crate valid date and return a valid date to display "dd-mmm-yyyy"
function ValidDate($dt)
{
    //echo "($dt)<br />" ;
    if (strpos($dt, "-") > 0)
        $d = explode("-", $dt);
    else if (strpos($dt, "/") > 0)
        $d = explode("/", $dt);
    else
        $d = explode(" ", $dt);

    if (((int)$d[1] == 0) && ((int)$d[2] == 0)) {
        $d[2] = date('Y');        // def: tahun
        $d[1] = date('m');        // def: bulan
    }
    if ((int)$d[2] == 0)
        $d[2] = date('Y');        // def: tahun

    $year = (int)$d[0];
    if ($year > 1970) {
        // asumsi format yyyy-mm-dd
        return (__ValidDate($year, $d[1], $d[2]));
    }

    // coba format dd-mm-yyyy
    $year = (int)$d[2];
    //$dd  = (int) $d[0];
    //$mm = (int) $d[1];
    if ($year > 1970) {
        return (__ValidDate($year, $d[1], $d[0]));
    } else {
        if ($year > 0 && $year < 30)
            $year += 2000;
        if ($year >= 30 && $year < 100)
            $year += 1900;

        return (__ValidDate($year, $d[1], $d[0]));
    }
}


function getIConfig($confid)
{
    $db = getDB();

    $sql = "Select IVALUE from TC_CONF where CONF_ID = '" . $confid . "'";

    $rs = $db->query($sql);
    $row = $rs->FetchRow();
    $rs->close();

    $id = $row['IVALUE'];

    if ($id > 0) {
        // increment counter
        $sql = "Update TC_CONF set IVALUE = IVALUE + 1 where CONF_ID = '" . $confid . "'";
        $db->query($sql);
        return $id;
    } else {
        return -1;
    }
}

function getFConfig($confid)
{
    $db = getDB();
    $sql = "Select FVALUE from TC_CONF where CONF_ID = '" . $confid . "'";

    $rs = $db->query($sql);
    $row = $rs->FetchRow();
    $rs->close();

    $id = $row ['FVALUE'];
    if ($id > 0) {
        // increment counter
        $sql = "Update TC_CONF set FVALUE = FVALUE + 1 where CONF_ID = '" . $confid . "'";
        $db->query($sql);
        return $id;
    } else {
        return -1;
    }
}


function getValue($sql)
{

    $db = getDB();
    $rs = $db->query($sql);
    $row = $rs->FetchRow();
    $rs->close();
    return ($row['VAL']);
}

// function getValue ($sql) {
// $db 	= getDB();
// $rs     = $db->query( $sql );
// if($rs->RecordCount() >0){
// $row    = $rs->FetchRow();
// return ($row['VAL']);
// }else{
// return "";
// }
// $rs->close ();

// }


// ------------------------------------------------------------------------------------------------
//		count Vessel, Company, Port, PBM
//			ret val : 	
//				6666 	: id = blank
//				1		: id ada di database
//				0		: id tidak ada di database
// ------------------------------------------------------------------------------------------------
function countVessel($id)
{
    if ($id == '')
        return 6666;
    $sql = "SELECT count(ID_VES_VOYAGE) as VAL FROM ITOS_OP.V_TR_VESSEL_NBS where ID_VES_VOYAGE='" . $id . "'";
    return (getValue($sql));
}

function countCompany($id)
{
    if ($id == '')
        return 6666;
    $sql = "SELECT count(COMPANY_ID) as VAL FROM V_TR_COMPANY_NBS where COMPANY_ID='" . $id . "'";
    return (getValue($sql));
}

function countPort($id)
{
    if ($id == '')
        return 6666;
    $sql = "SELECT count(PORT_ID) as VAL FROM TR_PORT where PORT_ID='" . $id . "'";
    return (getValue($sql));
}

function countPBM($id)
{
    if ($id == '')
        return 6666;
    $sql = "SELECT count(PBM_ID) as VAL FROM barang_prod.TR_PBM@dbint_kapal where PBM_ID='" . $id . "'";
    return (getValue($sql));
}


function getName($sql)
{
    $db = getDB();
    $rs = $db->query($sql);
    $row = $rs->FetchRow();
    $rs->close();
    return ($row['NAME']);
}

function getAddress($sql)
{
    $db = getDB();
    $rs = $db->query($sql);
    $row = $rs->FetchRow();
    $rs->close();
    return ($row['ADDRESS']);
}

function getCName($sql)
{
    $db = getDB();
    $rs = $db->query($sql);
    $row = $rs->FetchRow();
    $rs->close();
    return ($row['CARGO_NAME']);
}

function getVslName($vslId)
{
    // $sql = "Select NAME from TR_KAPAL where VESSEL_ID = '" . $vslId . "'";
    $sql = "SELECT NAME from ITOS_OP.V_TR_VESSEL_NBS where ID_VES_VOYAGE = '" . $vslId . "'";
    $name = getName($sql);

    if ($name == "")
        return ($vslId);
    else
        return ($name);
}

function geVVD($vslid)
{

    $sql = "SELECT A.ID_VES_VOYAGE, A.NAME, A.VOY, B.ID_VSB_VOYAGE AS UKKS
            FROM ITOS_OP.V_TR_VESSEL_NBS A
            LEFT JOIN ITOS_REPO.M_VSB_VOYAGE B ON A.ID_VES_VOYAGE  = B.UKKS
            WHERE ID_VES_VOYAGE = '" . $vslid . "'";
    $db = getDB();
    $rs = $db->query($sql);
    $row = $rs->FetchRow();
    return ($row['UKKS']);
}

function getCompName($compId)
{
    // $sql = "Select NAME from TR_COMPANY where COMPANY_ID = '" . $compId . "'";
    $sql = "SELECT NAME from V_TR_COMPANY_NBS where COMPANY_ID = '" . $compId . "'";
    $name = getName($sql);
    if ($name == "")
        return ($compId);
    else
        return ($name);
}

function getNPWP($compId)
{
    // $sql = "Select TAX_ID  NAME from TR_COMPANY where COMPANY_ID = '" . $compId . "'";
    $sql = "SELECT TAX_ID  NAME from V_TR_COMPANY_NBS where COMPANY_ID = '" . $compId . "'";
    $name = getName($sql);
    if ($name == "")
        return ($compId);
    else
        return ($name);
}

function getCompAddress($compId)
{
    // $sql = "Select ADDRESS from TR_COMPANY where COMPANY_ID = '" . $compId . "'";
    $sql = "SELECT ADDRESS from V_TR_COMPANY_NBS where COMPANY_ID = '" . $compId . "'";
    $address = getAddress($sql);
    if ($address == "")
        return ($compId);
    else
        return ($address);
}

function getCargoName($cargoId)
{
    $sql = "SELECT CARGO_NAME from barang_prod.TR_CARGOTYPE@dbint_kapal WHERE CARGOTYPE_ID = '" . $cargoId . "'";
    return getCName($sql);
}

function getCargosbName($cargoId)
{
    $sql = "SELECT CARGO_NAME from barang_prod.TR_CARGOSB@dbint_kapal WHERE CARGOTYPE_ID = '" . $cargoId . "'";
    return getCName($sql);
}

function getCargoGC($cargoId)
{
    $sql = "SELECT C_TYPE AS NAME from barang_prod.TR_CARGOTYPE@dbint_kapal WHERE CARGOTYPE_ID = '" . $cargoId . "'";
    $name = getName($sql);
    if ($name == "G" || $name == "H")
        return ('G');
    else
        return ('C');
}


function getAgentName($ag_id)
{
    $sql = "SELECT NAME from barang_prod.TR_AGENT@dbint_kapal where AGENT_ID = '" . $ag_id . "'";
    return getName($sql);
}


function getPortName($PortId)
{
    $sql = "SELECT NAME from barang_prod.TR_PORT@dbint_kapal where PORT_ID = '" . $PortId . "'";
    return getName($sql);
}


function getPBMName($pbmid)
{
    $sql = "SELECT NAME from barang_prod.TR_PBM@dbint_kapal where PBM_ID = '" . $pbmid . "'";
    $name = getName($sql);
    if ($name == "")
        return ($pbmid);
    else
        return ($name);
}

function getWHName($whouseid)
{
    $sql = "SELECT DESCR from barang_prod.TR_WHOUSE@dbint_kapal where WHOUSE_ID = '" . $whouseid . "'";
    $name = getNameWH($sql);

    if ($name == "")
        return ($whouseid);
    else
        return ($name);
}

function rev_getTerminal($id)
{
    $sql = "SELECT NAME from barang_prod.TR_TERMINAL@dbint_kapal where TERMINAL = '" . $id . "'";
    $name = getName($sql);

    if ($name == "")
        return ($id);
    else
        return ($name);
}

function getNameWH($sql)
{
    $db = getDB();
    $rs = $db->query($sql);
    $row = $rs->FetchRow();
    return ($row['DESCR']);
}

function getKadeName($kd)
{
   /* $sql = "SELECT NAME from barang_prod.TR_KADE@dbint_kapal where KADE = '" . $kd . "'";
    $name = getName($sql);*/

    $sql = "SELECT KADE_NAME AS NAME FROM ITOS_OP.M_KADE WHERE ID_KADE = '$kd'";
    $name = getName($sql);

    if ($name == "")
        return ($kd);
    else
        return ($name);
}

function getDagangName($dagang)
{
    $sql = "SELECT NAME from barang_prod.TR_TRADETYPE@dbint_kapal where TRD_TYPE_ID = '" . $dagang . "'";
    $name = getName($sql);

    if ($name == "")
        return ($dagang);
    else
        return ($name);
}

function getDtlId($tbldtl, $fldkey, $pkey)
{
    $db = getDB();
    $sql = "SELECT max(DTL_ID) as DTL_MAX FROM $tbldtl WHERE $fldkey ='" . $pkey . "'";
    //echo $sql; die();
    $rs = $db->query($sql);
    if ($rs && $row = $rs->FetchRow()) {
        $dtl = $row['DTL_MAX'] + 1;
    }
    return ($dtl);
}


function TSInsert($optr)
{
    $datenow = date('Y-m-j H:i:s');
    $optr = $_SESSION["SESS_USER_ID"];
    return ("'" . addslashes($optr) . "',to_date('$datenow', 'YYYY-MM-DD HH24:MI:SS'),1)");
}

function TSUpdate($optr)
{
    $datenow = date('Y-m-j H:i:s');
    $optr = $_SESSION["SESS_USER_ID"];
    return ("OPTR='" . addslashes($optr) . "',DEDIT=to_date('$datenow', 'YYYY-MM-DD HH24:MI:SS') ,TS=TS+1 ");
}

/*	
	function TSInsert ($optr)
	{
		$datenow = date ('Y-m-j H:i:s');
		return ("'".addslashes($optr)."',to_date('$datenow', 'YYYY-MM-DD HH24:MI:SS'),1)");
	}
	
	function TSUpdate ($optr)
	{
		$datenow = date ('Y-m-j H:i:s');
		return ("OPTR='".addslashes($optr)."',DEDIT=to_date('$datenow', 'YYYY-MM-DD HH24:MI:SS') ,TS=TS+1 ");
	}
*/


function val_checkMinVal($check, $name, $minVal, &$error)
{
    if (!isset($check[$name]) || trim($check[$name]) == '') {
        $error[$name] = ':empty';
        return false;
    }

    if (preg_match("/^[0-9]+$/i", $check[$name])) {
        $num = (double)($check[$name]);
        if ($num <= $minVal) {
            $error[$name] = "Nilai terlalu kecil";
            return false;
        }
        return true;
    } else {
        $error[$name] = ':pattern';
        return false;
    }
}


function val_checkPositif($check, $name, &$error)
{
    if (!isset($check[$name]) || trim($check[$name]) == '') {
        $error[$name] = ':empty';
        return false;
    }

    if ($check[$name] == '' || is_numeric($check[$name])) {
        if ((double)($check[$name]) >= 0) {
            return true;
        }
    }

    $error[$name] = 'harus positif';
    return false;
}


function checkDateDiff($tgl1, $tgl2)
{

    $pecah1 = explode("-", $tgl1);
    $date1 = $pecah1[2];
    $month1 = $pecah1[1];
    $year1 = $pecah1[0];

    // memecah tanggal untuk mendapatkan bagian tanggal, bulan dan tahun
    // dari tanggal kedua

    $pecah2 = explode("-", $tgl2);
    $date2 = $pecah2[2];
    $month2 = $pecah2[1];
    $year2 = $pecah2[0];

    // menghitung JDN dari masing-masing tanggal

    $jd1 = GregorianToJD($month1, $date1, $year1);
    $jd2 = GregorianToJD($month2, $date2, $year2);

    // hitung selisih hari kedua tanggal
    $selisih = $jd2 - $jd1;

    return $selisih;

}

function cekStatusNota($id)
{
    //ke keuangan
    $db = getDB("keu");
    $sql = "Select count(*) as VAL from apps.xpi2_ar_trx_validate_v " .
        "where jenis_query='NOTA' and trx_number = '" . $id . "'";
    $rs = $db->query($sql);
    $row = $rs->FetchRow();
    $rs->close();
    $cnt = ($row['VAL']);
    if ($cnt <= 0) {
        return FALSE;            // Data Nota belum ada di Simkeu
    }

    $sql = "select (balance_amount) as DELTA " .
        "from apps.xpi2_ar_trx_validate_v " .
        "where jenis_query='NOTA' and trx_number = '" . $id . "'";
    $rs = $db->query($sql);

    $row = $rs->FetchRow();

    if ($row ['DELTA'] == '') {
        return FALSE;
    }

    if ((double)$row ['DELTA'] > 0) {
        return FALSE;
    }

    return TRUE;
}


function cekStatusUper($id)
{
    //ke keuangan
    $db = getDB("keu");
    $sql = "select (balance_amount) as DELTA " .
        "from apps.xpi2_ar_trx_validate_v " .
        "where  jenis_query='UPER' and trx_number = '" . $id . "'";
    $rs = $db->query($sql);
    $row = $rs->FetchRow();

    if ($row ['DELTA'] == '') {
        return FALSE;
    }

    if ((double)$row ['DELTA'] > 0) {
        return FALSE;
    }

    return TRUE;
}

	function GenerateGLAccruePTP($NotaId){
		$sqlp ="select * from TTM_NOTA_GLC where trx_number='" . $NotaId . "'";
		$db = getDB();
		$res  =  $db->query($sqlp);
		$row  =  $res->fetchRow();
		$to = "TO".$row['TERMINAL'];
		$req = $row['NO_REQUEST'];

		$conn = $db->conn;
		$stid = oci_parse($conn, "begin XPTP_GL_INTEGRATION_PKG.export_to_staging ('" . $req . "', '" . $to . "', 'SIMBARANG'); end;");
		oci_execute($stid);
	}
function GenerateInvoicePTP($NotaId, $TransactionType, $switch_spinoff = null)
{
    $info = getLoginInfo();
    $db = getDB();
    $param = array(
        "nota"  => $NotaId,
        "id"    => $info['USERID'],
        "tipe"  => $TransactionType
    );

//    if ($TransactionType != 'SEWA ALAT GLC') {
        $sqlp = "begin simkeu_staging_ptp.upload_by(:nota,:id,:tipe); end;";
        $res = $db->query($sqlp, $param);
//    }

    // MASUK STAGING SPINOFF
    $BranchId = acl()->info["KD_CABANG"];

    //ambil identitas cabang
    $sqlp = "select ORG_ID from ptp_nota_header where trx_number='" . $NotaId . "'";
    $res = $db->query($sqlp);
    $row = $res->fetchRow();
    $org_id = $row['ORG_ID'];

    $rscab = getDB()->query("SELECT KODE_CABANG as BRANCH_CODE, KD_ACCOUNT_CABANG 
                            FROM KAPAL_PROD.MST_ORG_ID@dbint_kapal 
                            WHERE ORG_ID ='" . $org_id . "'")->fetchRow();
    $branch_code    = $rscab['BRANCH_CODE'];
    $branch_account = $rscab['KD_ACCOUNT_CABANG'];

    //cek go live / tidak (branck id sudah einvoice atau belum)
    $sql_cabinvc = "SELECT COUNT(1) AS CHECK_CABINVC	
                    FROM KAPAL_PROD.XEINVC_CABANG_LIVE_EINVOICE@dbint_kapal XE 
			     WHERE KODE_CABANG='" . $BranchId . "' AND EINVOICE='Y' 
				    AND TRUNC(SYSDATE) >= XE.START_EINVOICE";

    $rs_cabinvc = $db->query($sql_cabinvc);
    $row_cabinvc = $rs_cabinvc->fetchRow();
    $branch_check = $row_cabinvc['CHECK_CABINVC'];

    if ($branch_check > 0) {
        //jika branch id sudah einvoice

        /* sementara di non aktifkan dulu 18092018 
        print_r("SELECT DISTINCT PNH.TRX_NUMBER, HOU.ORG_ID, PNH.NOTAPREV, PNH.JENIS_MODUL,
									PNH.JENIS_NOTA, PNH.TRX_DATE, PNH.CURRENCY_CODE,
									(PNH.CUSTOMER_NUMBER) KODE_PELANGGAN,
									(PNH.SIMOP_CUSTOMER_NAME) NAMA_PELANGGAN,
									(PNH.SIMOP_CUSTOMER_ADD) ALAMAT_PELANGGAN,
									(PNH.SIMOP_CUSTOMER_NPWP) NPWP_PELANGGAN,
									('BELUM TERIDENTIFIKASI') BL_NO, PNH.NAMA_KAPAL, PNH.KADE,
									(MC.KD_ACCOUNT_CABANG) BRANCH_ACCOUNT, PNH.NO_BPRP,
									PNH.JENIS_PERDAGANGAN,
									TO_CHAR (PNH.START_DATE, 'DD-MON-RR') AS START_DATE,
									TO_CHAR (PNH.END_DATE, 'DD-MON-RR') AS END_DATE,
									('NO GUD LAP XX') GUD_LAP, ('NO FAKTUR ') NO_FAKTUR,
									PNH.TERMINAL
							   FROM BARANG_PROD.PTP_NOTA_HEADER PNH,
									KAPAL_PROD.MST_CABANG MC,
									KAPAL_PROD.MST_ORG_ID HOU
							  WHERE HOU.KD_ACCOUNT_CABANG = MC.KD_ACCOUNT_CABANG
								AND MC.KD_CABANG = 01
								AND PNH.TRX_NUMBER = '$NotaId'");
        */
        $rs = getDB()->query("SELECT DISTINCT PNH.TRX_NUMBER, HOU.ORG_ID, PNH.NOTAPREV, PNH.JENIS_MODUL,
									PNH.JENIS_NOTA, PNH.TRX_DATE, PNH.CURRENCY_CODE,
									(PNH.CUSTOMER_NUMBER) KODE_PELANGGAN,
									(PNH.SIMOP_CUSTOMER_NAME) NAMA_PELANGGAN,
									(PNH.SIMOP_CUSTOMER_ADD) ALAMAT_PELANGGAN,
									(PNH.SIMOP_CUSTOMER_NPWP) NPWP_PELANGGAN,
									 BL_NO, PNH.NAMA_KAPAL, PNH.KADE,
									(MC.KD_ACCOUNT_CABANG) BRANCH_ACCOUNT, PNH.NO_BPRP,
									PNH.JENIS_PERDAGANGAN,
									TO_CHAR (PNH.START_DATE, 'DD-MON-RR') AS START_DATE,
									TO_CHAR (PNH.END_DATE, 'DD-MON-RR') AS END_DATE,
									GUD_LAP, ('NO FAKTUR ') NO_FAKTUR,
									PNH.TERMINAL, 
									PNH.SIMOP_PBM_NAME,
									PNH.NO_UPER,
									PNH.VOY,
									TO_CHAR (PNH.TANGGAL_KEDATANGAN, 'DD-MON-RR') AS CTANGGAL_KEDATANGAN  
							   FROM PTP_NOTA_HEADER PNH,
									KAPAL_PROD.MST_CABANG@dbint_kapal MC,
									KAPAL_PROD.MST_ORG_ID@dbint_kapal HOU
							  WHERE HOU.KD_ACCOUNT_CABANG = MC.KD_ACCOUNT_CABANG
								AND MC.KD_CABANG = 01
								AND PNH.TRX_NUMBER = '$NotaId'")->fetchRow();

        //ambil keterangan kade
        $rs_kade = getDB()->query("select NAMA_KADE from MST_KADE_TPK where KD_KADE='".$rs['KADE']."'")->fetchRow();

        //ambil keterangan  gudang lapangan
        $rs_gud = getDB()->query("select NAMA_WHOUSE from MST_WHOUSE_TPK where KD_WHOUSE='".$rs['GUD_LAP']."'")->fetchRow();

        //start get tax value
        $rs_tax = getDB()->query("SELECT V_TAXVALUE,V_TOBEPAID,(NULL) STATUS
						FROM TTM_NOTA TMN
						WHERE TMN.NOTA_ID = '$NotaId'
						UNION ALL
						SELECT V_TAXVALUE,V_TOBEPAID,(NULL) STATUS 
						FROM TTM_NOTAHF TMN
						WHERE TMN.NOTA_ID = '$NotaId'
						UNION ALL
						SELECT (V_JB_REAL/10) V_TAXVALUE,V_TOBEPAID,PTU.STATUS 
						FROM TTM_NOTAJB TMN, PTP_UPER PTU
                        WHERE TMN.NOTAJB_ID = PTU.NO_UPER
                          AND TMN.NO_NOTA = '$NotaId'
						union all
                        select V_PPN as V_TAXVALUE,V_TOBEPAID,(NULL) STATUS
                        from TTM_NOTA_HJB TMN
                        where  TMN.NOTAHJB_ID = '$NotaId'
                        union all
                        select PPN as V_TAXVALUE,(NULL) V_TOBEPAID,(NULL) STATUS
                        from TTM_NOTA_GLC TMN
                        where  TMN.TRX_NUMBER = '$NotaId'
                        union all
                        select V_PPN as V_TAXVALUE,V_TOBEPAID,(NULL) STATUS 
                        from TT_OVERSTAGE TMN
                        where  TMN.OVERSTAGE_ID = '$NotaId'
						")->fetchRow();

        $rs_amount_total = getDB()->query("SELECT (MAX(rownum)+1) AS LINE_NUMBER_LAST,SUM(NVL(PND.AMOUNT,0)) AS AMOUNT_TOTAL                        
                        FROM PTP_NOTA_DETAIL PND
						WHERE PND.TRX_NUMBER = '$NotaId'")->fetchRow();

        $piutang = $rs_amount_total['AMOUNT_TOTAL'] + $rs_tax['V_TAXVALUE'];
        $line_number_last = $rs_amount_total['LINE_NUMBER_LAST'];
        $amount_dasar_penghasilan = $rs_amount_total['AMOUNT_TOTAL'];

		//start uang jaminan/uper
        $uang_jaminan = $rs_tax['V_TOBEPAID'];
        if ($uang_jaminan > 0 || $rs_tax['STATUS']='S') {
            $piutang_uper = $piutang - $uang_jaminan;
        } else {
            $piutang_uper = '';
        }
		//end uang jaminan/uper

        /* sementara di non aktifkan dulu 18092018
        print_r("SELECT PND.TRX_NUMBER, NVL (PND.TAX_FLAG, 'N') TAX_FLAG,     --PND.JENIS_NOTA,
								(ROWNUM
								) LINE_NUMBER,
							   (CASE
								   WHEN TO_CHAR (PND.TIPE_LAYANAN) IN ('DMG', 'SHD', 'STV')
									  THEN DECODE (PND.TIPE_LAYANAN,
												   NULL, PND.JENIS_BARANG,
													  DECODE (PND.TIPE_LAYANAN,
															  'DMG', 'DERMAGA',
															  'SHD', 'KEBERSIHAN',
															  'STV', 'STEVEDORING'
															 )
												   || ' '
												   || PND.JENIS_BARANG
												  )
								   WHEN TO_CHAR (PND.TIPE_LAYANAN) = 'LAINNYA'
									  THEN NVL (PND.JENIS_BARANG, 'BIAYA ADMINISTRASI')
								   WHEN TO_CHAR (PND.TIPE_LAYANAN) = 'LAPANGAN'
									  THEN PND.JENIS_BARANG || ' ' || PND.QTY_TOTAL || ' ' || PND.UNIT
								   WHEN TO_CHAR (PND.TIPE_LAYANAN) = 'DERMAGA'
									  THEN PND.JENIS_BARANG || ' ' || PND.QTY_TOTAL || ' ' || PND.UNIT
								   WHEN TO_CHAR (PND.TIPE_LAYANAN) = 'GUDANG'
									  THEN PND.JENIS_BARANG || ' ' || PND.QTY_TOTAL || ' ' || PND.UNIT
								   WHEN TO_CHAR (PND.TIPE_LAYANAN) = 'SUPERVISI'
									  THEN PND.JENIS_BARANG || ' ' || PND.QTY_TOTAL || ' ' || PND.UNIT
								   WHEN TO_CHAR (PND.TIPE_LAYANAN) = 'STRIPPINGSTUFFING'
									  THEN PND.JENIS_BARANG || ' ' || PND.QTY_TOTAL || ' ' || PND.UNIT
								   ELSE PND.JENIS_BARANG
								END
							   ) LINE_DESCRIPTION,
							   (PND.TARIF) TARIF,
							   (CASE
								   WHEN PND.TIPE_LAYANAN IN ('DERMAGA', 'LAPANGAN', 'GUDANG')
									  THEN PND.TIPE_LAYANAN || ' ' || PND.TIPE_PELABUHAN
								   ELSE PND.TIPE_LAYANAN
								END
							   ) AS TIPE_LAYANAN,
							   ('belum dapat') KEMASAN,
							   (CASE
								   WHEN PND.TAX_FLAG = 'Y'
									  THEN (PND.AMOUNT * 0.1)
								   ELSE 0
								END
							   ) AS PPN_AMOUNT, PND.AMOUNT, (PND.QTY_TOTAL) QTY_TOTAL, PND.UNIT
						  FROM BARANG_PROD.PTP_NOTA_DETAIL PND
						 WHERE PND.TRX_NUMBER = '$NotaId'");
        */

        $sqldetail = "SELECT PND.TRX_NUMBER, NVL (PND.TAX_FLAG, 'N') TAX_FLAG,     --PND.JENIS_NOTA,
								(ROWNUM
								) LINE_NUMBER,
							   (CASE
								   WHEN TO_CHAR (PND.TIPE_LAYANAN) IN ('DMG', 'SHD', 'STV')
									  THEN DECODE (PND.TIPE_LAYANAN,
												   NULL, PND.JENIS_BARANG,
													  DECODE (PND.TIPE_LAYANAN,
															  'DMG', 'DERMAGA',
															  'SHD', 'KEBERSIHAN',
															  'STV', 'STEVEDORING'
															 )
												   || ' '
												   || PND.JENIS_BARANG
												  )
								   WHEN TO_CHAR (PND.TIPE_LAYANAN) = 'LAINNYA'
									  THEN NVL (PND.JENIS_BARANG, 'BIAYA ADMINISTRASI')
								   WHEN TO_CHAR (PND.TIPE_LAYANAN) = 'LAPANGAN'
									  THEN PND.JENIS_BARANG || ' ' || PND.QTY_TOTAL || ' ' || PND.UNIT
								   WHEN TO_CHAR (PND.TIPE_LAYANAN) = 'DERMAGA'
									  THEN PND.JENIS_BARANG || ' ' || PND.QTY_TOTAL || ' ' || PND.UNIT
								   WHEN TO_CHAR (PND.TIPE_LAYANAN) = 'GUDANG'
									  THEN PND.JENIS_BARANG || ' ' || PND.QTY_TOTAL || ' ' || PND.UNIT
								   WHEN TO_CHAR (PND.TIPE_LAYANAN) = 'SUPERVISI'
									  THEN PND.JENIS_BARANG || ' ' || PND.QTY_TOTAL || ' ' || PND.UNIT
								   WHEN TO_CHAR (PND.TIPE_LAYANAN) = 'STRIPPINGSTUFFING'
									  THEN PND.JENIS_BARANG || ' ' || PND.QTY_TOTAL || ' ' || PND.UNIT
								   WHEN TO_CHAR (PND.TIPE_LAYANAN) = 'OVERSTAGE'
									  THEN 'OVERSTAGE'
								   ELSE PND.JENIS_BARANG
								END
							   ) LINE_DESCRIPTION,
							   (PND.TARIF) TARIF,
							   (CASE
								   WHEN pnh.org_id = 83 and pnd.tipe_layanan IN ('DERMAGA')
									  THEN pnd.tipe_layanan || ' ' || pnd.tipe_pelabuhan
								   WHEN pnh.org_id = 83 and pnd.tipe_layanan IN ('OVERSTAGE', 'PAS', 'LAPANGAN')
									  THEN pnd.tipe_layanan || ' ' || pnd.tipe_pelabuhan || ' ' || 'VAS'
								   WHEN pnh.org_id = 83 and pnd.tipe_layanan IN ('LOLO', 'LAINNYA', 'SHD', 'KEBERSIHAN', 'DMG', 'GUD/LAP', 'STV')
									  THEN pnd.tipe_layanan || ' ' || pnd.terminal
								   WHEN pnh.org_id in (1823, 1824, 1825, 1826) and pnd.tipe_layanan IN ('KEBERSIHAN',
								      'LAPANGAN','DERMAGA','PAS','LAINNYA', 'GUDANG', 'GUD/LAP', 'SHD', 'DMG', 'STV', 'SEWA ALAT GLC',
								      'OVERSTAGE')
									  THEN pnd.tipe_layanan
								   WHEN pnh.org_id = 1827
									  THEN pnd.tipe_layanan
								   ELSE pnd.tipe_layanan || ' ' || pnd.tipe_pelabuhan || ' ' || pnd.terminal
								END
                               ) AS TIPE_LAYANAN,
							   KEMASAN,
							   (CASE
								   WHEN PND.TAX_FLAG = 'Y'
									  THEN (PND.AMOUNT * 0.1)
								   ELSE 0
								END
							   ) AS PPN_AMOUNT, PND.AMOUNT, (PND.QTY_TOTAL) QTY_TOTAL, PND.UNIT,
							   TO_CHAR(PND.TGL_MASUK, 'DD/MM/RRRR')  TGL_MASUK,
                               TO_CHAR(PND.TGL_KELUAR, 'DD/MM/RRRR') TGL_KELUAR,
                               TO_CHAR(PND.QTY_LOAD) BONGKAR,
                               TO_CHAR(PND.QTY_TOTAL) MUAT,
                               TO_CHAR(PND.TARIF) NILAI_PER_UNIT,
                               TO_CHAR(PND.TERMINAL) TERMINAL,
                               TO_CHAR(PND.MASA_1) HARI_MASA_1,
                               TO_CHAR(PND.MASA_2) HARI_MASA_2,
                               TO_CHAR(PND.MASA_3) HARI_MASA_3,
                               TO_CHAR(PND.SEWA_MASA_1) SEWA_MASA_1,
                               TO_CHAR(PND.SEWA_MASA_2) SEWA_MASA_2,
                               TO_CHAR(PND.SEWA_MASA_3) SEWA_MASA_3                               
						  FROM PTP_NOTA_DETAIL PND, ptp_nota_header pnh
                          WHERE pnd.trx_number = pnh.trx_number AND pnd.tipe_layanan IS NOT NULL AND pnd.trx_number = '$NotaId'";
        $rsdetail = $db->query($sqldetail);
        $rowdetail = $rsdetail->GetAll();

        //init web service
        $service_url = API_EINVOICE . 'SimopInvoiceHeader/';
        $curl = curl_init($service_url);

        curl_setopt($curl, CURLOPT_URL, $service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, 1);

        $curl_post_data = array(
            "SOURCE_INVOICE_TYPE" => 'BRG',
            "SOURCE_INVOICE" => 'BRG',
            "BILLER_REQUEST_ID" => substr(trim($rs['TRX_NUMBER']), 0, 64),
            "TRX_NUMBER" => substr(trim($rs['TRX_NUMBER']), 0, 50),
            "TRX_NUMBER_PREV" => substr(trim($rs['NOTAPREV']), 0, 64),
            "ORG_ID" => $org_id, //$rs['ORG_ID'],
            "CUSTOMER_NUMBER" => substr(trim($rs['KODE_PELANGGAN']), 0, 25),
            "CUSTOMER_NAME" => substr(trim($rs['NAMA_PELANGGAN']), 0, 128),
            "CUSTOMER_NPWP" => substr(trim($rs['NPWP_PELANGGAN']), 0, 1000),
            "CUSTOMER_ADDRESS" => substr(trim($rs['ALAMAT_PELANGGAN']), 0, 128),
            "TRX_DATE" => $rs['TRX_DATE'],
            "AMOUNT" => substr(trim($piutang), 0, 20),
            "TRX_CLASS" => 'INV',
            "CURRENCY_CODE" => substr(trim($rs['CURRENCY_CODE']), 0, 3),
            'STATUS' => 'P',
            'HEADER_CONTEXT' => 'BRG',
            'HEADER_SUB_CONTEXT' => substr(trim($rs['JENIS_NOTA']), 0, 100),
            'TERMINAL' => substr(trim($rs['TERMINAL']), 0, 15),
            'BRANCH_CODE' => ($org_id == '1827') ? 'TPK' : substr(trim($branch_code), 0, 16), // 1827 atau 83 = TPK
            'BRANCH_ACCOUNT' => substr(trim($branch_account), 0, 16),
            'VESSEL_NAME' => substr(trim($rs['NAMA_KAPAL']), 0, 30),
            'ATTRIBUTE1' => substr(trim($rs['JENIS_NOTA']), 0, 30),
            'ATTRIBUTE2' => substr(trim($rs['KODE_PELANGGAN']), 0, 30),//SIMOP customer number
            'ATTRIBUTE3' => substr(trim($rs['NAMA_PELANGGAN']), 0, 30),//SIMOP customer name
            'ATTRIBUTE4' => substr(trim($rs['ALAMAT_PELANGGAN']), 0, 128),//SIMOP Customer Address
            'ATTRIBUTE5' => substr(trim($rs['NPWP_PELANGGAN']), 0, 30), //SIMOP Customer NPWP
            'ATTRIBUTE6' => substr(trim($rs['SIMOP_PBM_NAME']), 0, 30),// PBM NAME
            'ATTRIBUTE7' => '', //kegiatan
            'ATTRIBUTE8' => '',
            'ATTRIBUTE9' => '',//No. Faktur Pajak Dipngut/Tidak
            'ATTRIBUTE10' => '',//No FP Diganti
            'ATTRIBUTE11' => '',//Tgl Cetak
            'ATTRIBUTE12' => '',//No JRR
            'ATTRIBUTE13' => '',//No JP
            'ATTRIBUTE14' => substr(trim($rs['NO_FAKTUR']), 0, 30), //Nomor Faktur Pajak Kena
            'ATTRIBUTE15' => '',//Nomor Faktur Pajak Bebas
            'INTERFACE_HEADER_ATTRIBUTE1' => substr(trim($rs['NO_UPER']), 0, 100), //Nomor UPER
            'INTERFACE_HEADER_ATTRIBUTE2' => substr(trim($rs['NAMA_KAPAL']), 0, 100),//nama kapal
            'INTERFACE_HEADER_ATTRIBUTE3' => substr(trim($rs['JENIS_PERDAGANGAN']), 0, 100),//jenis perdagangan
            'INTERFACE_HEADER_ATTRIBUTE4' => substr(trim($rs_gud['NAMA_WHOUSE']), 0, 100),//Gudang Lapangan
            'INTERFACE_HEADER_ATTRIBUTE5' => substr(trim($rs_kade['NAMA_KADE']), 0, 100),//kade dermaga
            'INTERFACE_HEADER_ATTRIBUTE6' => substr(trim($rs['NO_BPRP']), 0, 100),//No BPRP
            'INTERFACE_HEADER_ATTRIBUTE7' => substr(trim($rs['BL_NO']), 0, 100),//nomor BL
            'INTERFACE_HEADER_ATTRIBUTE8' => substr(trim($rs['NO_DO']), 0, 100), //nomor DO
            'INTERFACE_HEADER_ATTRIBUTE9' => substr(trim($rs['VOY']), 0, 100),// No Voyage
            'INTERFACE_HEADER_ATTRIBUTE10' => $rs['CTANGGAL_KEDATANGAN'],// Tgl ETA
            'INTERFACE_HEADER_ATTRIBUTE11' => '',
            'INTERFACE_HEADER_ATTRIBUTE12' => '',
            'INTERFACE_HEADER_ATTRIBUTE13' => '',
            'INTERFACE_HEADER_ATTRIBUTE14' => '',
            'INTERFACE_HEADER_ATTRIBUTE15' => '',
            'PER_KUNJUNGAN_FROM' => $rs['START_DATE'],
            'PER_KUNJUNGAN_TO' => $rs['END_DATE'],
            'PPN_DIPUNGUT_SENDIRI' => substr(trim($rs_tax['V_TAXVALUE']), 0, 256),
            'PIUTANG' => substr(trim($piutang_uper), 0, 256),
            'SOURCE_SYSTEM' => 'SIMOPBARANG',
            'AMOUNT_DASAR_PENGHASILAN' => $amount_dasar_penghasilan,
            'PPN_10PERSEN' => $rs_tax['V_TAXVALUE'],
            'UANG_JAMINAN' => $uang_jaminan
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
        $curl_response = curl_exec($curl);
        $get_response_hdr = json_decode($curl_response, true);

        //print_r($get_response_hdr);

        curl_close($curl);

        //start detail
        foreach ($rowdetail as $item_detail) {

            $service_url = URL_WLS_EINVOICE . 'SimopInvoiceDetail/';
            $curl = curl_init($service_url);

            if ($rs['JENIS_NOTA'] == "BRG02") { //sharing gudang lapangan
                    $INTERFACE_LINE_ATTRIBUTE2 = substr(trim($item_detail['TGL_MASUK']), 0, 240);
                    $INTERFACE_LINE_ATTRIBUTE3 = substr(trim($item_detail['TGL_KELUAR']), 0, 240);
                    $INTERFACE_LINE_ATTRIBUTE4 = substr(trim($item_detail['BONGKAR']), 0, 240);
                    $INTERFACE_LINE_ATTRIBUTE5 = substr(trim($item_detail['MUAT']), 0, 240);
                    $INTERFACE_LINE_ATTRIBUTE6 = substr(trim($item_detail['NILAI_PER_UNIT']), 0, 240);
                    $INTERFACE_LINE_ATTRIBUTE7 = '';
                    $INTERFACE_LINE_ATTRIBUTE8 = '';
                    $INTERFACE_LINE_ATTRIBUTE9 = '';
                    $INTERFACE_LINE_ATTRIBUTE10 = '';
                    $INTERFACE_LINE_ATTRIBUTE11 = '';
                    $INTERFACE_LINE_ATTRIBUTE12 = '';

            }elseif(($rs['JENIS_NOTA']== 'BRG03') || ($rs['JENIS_NOTA']== "BRG04") || $rs['JENIS_NOTA']== "BRG05") {
                    //BRG03 = penumpukan, BRG04 = angkutan langsung, BRG05 = handling fee
                    $INTERFACE_LINE_ATTRIBUTE2 = substr(trim($item_detail['TGL_MASUK']), 0, 240);
                    $INTERFACE_LINE_ATTRIBUTE3 = substr(trim($item_detail['TGL_KELUAR']), 0, 240);
                    $INTERFACE_LINE_ATTRIBUTE4 = substr(trim($item_detail['HARI_MASA_1']), 0, 240);
                    $INTERFACE_LINE_ATTRIBUTE5 = substr(trim($item_detail['HARI_MASA_2']), 0, 240);
                    $INTERFACE_LINE_ATTRIBUTE6 = substr(trim($item_detail['HARI_MASA_3']), 0, 240);
                    $INTERFACE_LINE_ATTRIBUTE7 = substr(trim($item_detail['SEWA_MASA_1']), 0, 240);
                    $INTERFACE_LINE_ATTRIBUTE8 = substr(trim($item_detail['SEWA_MASA_2']), 0, 240);
                    $INTERFACE_LINE_ATTRIBUTE9 = substr(trim($item_detail['SEWA_MASA_3']), 0, 240);
                    $INTERFACE_LINE_ATTRIBUTE10 = substr(trim($item_detail['NILAI_PER_UNIT']), 0, 240);
                    $INTERFACE_LINE_ATTRIBUTE11 = substr(trim($item_detail['TERMINAL']), 0, 240);
                    $INTERFACE_LINE_ATTRIBUTE12 = ($rs['JENIS_NOTA']== 'BRG03') ? substr(trim($item_detail['LINE_DESCRIPTION']), 0, 240) : ''; // BRG03 --> description unit

            }else{
                    // BRG10 = bongkar muat & GLC, BRG09 --> overstage
                    $INTERFACE_LINE_ATTRIBUTE2 = ($rs['JENIS_NOTA']== 'BRG10') ? substr(trim($item_detail['TIPE_LAYANAN']), 0, 240) : '';
                    $INTERFACE_LINE_ATTRIBUTE3 = '';
                    $INTERFACE_LINE_ATTRIBUTE4 = '';
                    $INTERFACE_LINE_ATTRIBUTE5 = '';
                    $INTERFACE_LINE_ATTRIBUTE6 = '';
                    $INTERFACE_LINE_ATTRIBUTE7 = '';
                    $INTERFACE_LINE_ATTRIBUTE8 = '';
                    $INTERFACE_LINE_ATTRIBUTE9 = '';
                    $INTERFACE_LINE_ATTRIBUTE10 = '';
                    $INTERFACE_LINE_ATTRIBUTE11 = '';
                    $INTERFACE_LINE_ATTRIBUTE12 = '';

            }

            $curl_post_data = array(
                "BILLER_REQUEST_ID" => substr(trim($rs['TRX_NUMBER']), 0, 64),
                "TRX_NUMBER" => substr(trim($rs['TRX_NUMBER']), 0, 25),
                "LINE_NUMBER" => $item_detail['LINE_NUMBER'],
                "DESCRIPTION" => substr(trim($item_detail['LINE_DESCRIPTION']), 0, 240),
                "LINE_CONTEXT" => substr(trim($item_detail['JENIS_NOTA']), 0, 100),
                "TAX_FLAG" => substr(trim($item_detail['TAX_FLAG']), 0, 1),
                "SERVICE_TYPE" => substr(trim($item_detail['TIPE_LAYANAN']), 0, 240),
                "AMOUNT" => $item_detail['AMOUNT'],
                'TAX_AMOUNT' => $item_detail['PPN_AMOUNT'],
                'CREATION_DATE' => date('d-M-Y'),
                'LAST_UPDATED_DATE' => date('d-M-Y'),
                'INTERFACE_LINE_ATTRIBUTE1' => substr(trim($item_detail['TRX_NUMBER']), 0, 240),
                'INTERFACE_LINE_ATTRIBUTE2' => $INTERFACE_LINE_ATTRIBUTE2,
                'INTERFACE_LINE_ATTRIBUTE3' => $INTERFACE_LINE_ATTRIBUTE3,
                'INTERFACE_LINE_ATTRIBUTE4' => $INTERFACE_LINE_ATTRIBUTE4,
                'INTERFACE_LINE_ATTRIBUTE5' => $INTERFACE_LINE_ATTRIBUTE5,
                'INTERFACE_LINE_ATTRIBUTE6' => $INTERFACE_LINE_ATTRIBUTE6,
                'INTERFACE_LINE_ATTRIBUTE7' => $INTERFACE_LINE_ATTRIBUTE7,
                'INTERFACE_LINE_ATTRIBUTE8' => $INTERFACE_LINE_ATTRIBUTE8,
                'INTERFACE_LINE_ATTRIBUTE9' => $INTERFACE_LINE_ATTRIBUTE9,
                'INTERFACE_LINE_ATTRIBUTE10' => $INTERFACE_LINE_ATTRIBUTE10,
                'INTERFACE_LINE_ATTRIBUTE11' => $INTERFACE_LINE_ATTRIBUTE11,
                'INTERFACE_LINE_ATTRIBUTE12' => $INTERFACE_LINE_ATTRIBUTE12,
                'INTERFACE_LINE_ATTRIBUTE13' => substr(trim($item_detail['QTY_TOTAL']), 0, 240),//jumlah
                'INTERFACE_LINE_ATTRIBUTE14' => substr(trim($item_detail['UNIT']), 0, 240),//satuan
                'INTERFACE_LINE_ATTRIBUTE15' => '',
                'LINE_DOC' => 'KURS_RATE|'
            );

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);

            $curl_response = curl_exec($curl);

            $get_response = json_decode($curl_response, true);
            //print_r($get_response);

        }
        curl_close($curl);
        //end detail

    } else {
        //jika branch id tidak / belum einvoice

        $retn = 0;
        $err = "";
        $dbKeu = getDB('keusimop');
        $conn = $dbKeu->conn;

        if ($switch_spinoff == 'TPK') {
            $stid = oci_parse($conn, "begin APPS.xitpk_ar_invoice_pkg.generate_invoice_billing ('" . $NotaId . "', '" . $org_id . "', 'BRG', :st, :err); end;");
        } else {
            $stid = oci_parse($conn, "begin APPS.xptp_ar_invoice_pkg.generate_invoice_billing ('" . $NotaId . "', '" . $org_id . "', 'BRG', :st, :err); end;");
        }
        oci_bind_by_name($stid, ":st", $status, 128);    // 128 is the return length
        oci_bind_by_name($stid, ":err", $err_msg, 1000);

        $retn = oci_execute($stid);

        if (!$retn) {
            $err = oci_error($stid);
            return $err[message];
        }

        if ($status == 'S') {
            $sqlupdate = "update ptp_nota_header set status='2' where trx_number='" . $NotaId . "'";
            $res = $db->query($sqlupdate);
            return "";
        } else {
            $sqlupdate = "update ptp_nota_header set status='3' where trx_number='" . $NotaId . "'";
            $res = $db->query($sqlupdate);
            return "ERROR: SIMKEU gagal generate invoice ('$NotaId', '$org_id').\n$err_msg .";
        }
    }
}

function GenerateInvoice($NotaId, $BranchId)
{

    //========================================COBA-COBA==================================
    $info = getLoginInfo();
    $db = getDB();
    $param = array(
        "nota" => $NotaId,
        "id" => $info['USERID']
    );

    $sqlp = "begin simkeu_staging.upload_by(:nota,:id); end;";
    $res = $db->query($sqlp, $param);
    //=============================================================================

    $sql = "Select NAME from TR_BRANCH where BRANCH_ID = '" . $BranchId . "'";
    $branch = getName($sql);

    $retn = 0;
    $err = "";
    $dbKeu = getDB('keu');
    $conn = $dbKeu->conn;
    $stid = oci_parse($conn, "begin APPS.XPI2_AR_API_PKG.generate_invoice_simop ('" . $NotaId . "', 'Y', '" . $branch . "', :st,:err); end;");
    oci_bind_by_name($stid, ":st", $status, 256);    // 128 is the return length
    oci_bind_by_name($stid, ":err", $err_msg, 1000);

    $retn = oci_execute($stid);
    if (!$retn) {
        $err = oci_error($stid);
        return $err[message];
    }

    if ($status == 'S') {
        $dbKeu->commit();
        return "";
    } else {
        $dbKeu->rollback();
        return "ERROR: SIMKEU gagal generate invoice ('$NotaId', '$branch').\n$err_msg .";
    }
}


function getLoginInfo()
{
    require_lib('acl.php');
    $_acl = new ACL();
    $_acl->load();
    #return $_acl->load();
    return $_acl->logininfo->info;
}


function GetLoginBranch()
{
    $branch = getLoginInfo();
    return $branch['KD_CABANG'];
}

function GetUserBranch($userid)
{
    $branch = getLoginInfo();
    return $branch['KD_CABANG'];
}

/*
function GetLoginBranch() {
    $branch = '01';
    return $branch;
}

function GetUserBranch($userid) {
    $branch = '01';
    return $branch;
}
*/
function GetUserCompany($userid)
{
    $usercompany = 'PLD';
    return $usercompany;
}

function validBranch($fld)
{
    $branch = GetLoginBranch();
    return " ($fld = '$branch' or '$branch'='00') ";
}


function valid_hewan($p_cargotype, &$p_pkg, &$p_unit)
{
    $sql = "select c_type as val from tr_cargotype " .
        " where cargotype_id = '" . $p_cargotype . "'";
    $ty = getValue($sql);
    if ($ty == 'H') {
        $p_pkg = 3;     // unitized
        $p_unit = 'EKOR';
    }
}


function valid_container($p_cargotype, &$p_pkg, &$p_unit)
{
    $sql = "select c_type as val from tr_cargotype " .
        " where cargotype_id = '" . $p_cargotype . "' " .
        " and c_type not in ('H', 'G') ";
    $ty = getValue($sql);
    if ($ty != '') {
        $p_pkg = 6;     // container
        $p_unit = 'BOX';
    }
}


function add_reason($act, $type, $notaid, $reason)
{
    $db = getDB();

    $sql = "Insert into TT_DELREASON " .
        " (ACT, TYPE, NOTA_ID, REASON, OPTR, DEDIT, TS) " .
        " VALUES ('" . $act . "', '" . $type . "', '" . $notaid . "', '" . $reason . "', " .
        TSInsert('mz');
    if ($db->query($sql))
        return 1;

    return -1;
}

function CekNota($trx)
{
    $db = getDB();
    $sql = "SELECT count(TRX_NUMBER) as COUNT_ROW FROM XPI_V_KEU_VALIDATE where TRX_NUMBER='$trx'";
    $rs = $db->query($sql);
    $row = $rs->FetchRow();
    if ($row['COUNT_ROW'] > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function SetServerOutput($c, $p)
{
    if ($p)
        $s = "BEGIN DBMS_OUTPUT.ENABLE(NULL); END;";
    else
        $s = "BEGIN DBMS_OUTPUT.DISABLE(); END;";
    $s = oci_parse($c, $s);
    $r = oci_execute($s);
    oci_free_statement($s);
    return $r;
}

// Returns an array of dbms_output lines, or false.
function GetDbmsOutput($c)
{
    $res = false;
    $s = oci_parse($c, "BEGIN DBMS_OUTPUT.GET_LINE(:LN, :ST); END;");
    if (oci_bind_by_name($s, ":LN", $ln, 255) &&
        oci_bind_by_name($s, ":ST", $st)) {
        $res = array();
        //print_r($ln);die;
        while (($succ = oci_execute($s)) && !$st)
            $res[] = $ln;
        if (!$succ)
            $res = false;
    }
    oci_free_statement($s);
    return $res;
}

function genNotaIdFaktur($params)//array parameter
{
    $br = GetUserBranch();


    $db = getDB();
    $conn = $db->conn;
    // DI SET 0 KARENA BLOM ADA PERHITUNGAN UNTUK UPDATE NO NOTA FIX NANTI SETELAH PERHITUNGAN
    // JENIS PELANGGAN DIKOSONGKAN KARENA BUKAN PBM
    /*echo "begin :res := counter_nota_barang_xp.gen_invoice_num(
                                    '".$params['SERVICE']."',
                                    '$br',
                                    '".$params['TAX_FREE']."',
                                    ".$params['TOTAL'].",
                                    '".$params['CURRENCY']."',
                                    '".$params['JENIS_PELANGGAN']."',
                                    '".$params['PELANGGAN']."',
                                    '".$params['NOTA_PREV']."');
                                end;";die;*/
    $stid = oci_parse($conn, "begin :res := counter_nota_barang_xp.gen_invoice_num(
					                    '" . $params['SERVICE'] . "',
					                    '$br',
										'" . $params['BRANCH_ACCOUNT'] . "',
					                    '" . $params['TAX_FREE'] . "',
					                    " . $params['TOTAL'] . ",
					                    '" . $params['CURRENCY'] . "',
					                    '" . $params['JENIS_PELANGGAN'] . "',  
					                    '" . $params['PELANGGAN'] . "',
					                    '" . $params['NOTA_PREV'] . "');
					                end;");
    oci_bind_by_name($stid, ":res", $rtn_id, 128);    // 128 is the return length

    $ret = oci_execute($stid);
    if (!$ret) {
        $err = oci_error($stid);
        print_r($err . '<br /> Parameter No Nota Salah , Silahkan Tekan Tombol Back dan Periksa Kembali Inputan Anda..!!');
        die;
        return -1;
    }

    //--- updated spinoff 2017 --------------------------------------------------------------------------------------------------------
    //echo '---'.$rtn_id.'---'; die;
    if ($params['BRANCH_ACCOUNT'] == '010' && strlen($rtn_id) == 16) {

        // $sql="select counter_nota_barang_xp.gen_invoice_num_spoff('".$rtn_id."') trx_number from dual";
        $result = $db->query("select counter_nota_barang_xp.gen_invoice_num_spoff('" . $rtn_id . "') TRX_NUMBER from dual");
        $row = $result->fetchRow();
        return $row['TRX_NUMBER'];
    } else {
        return $rtn_id;
    }
    //--- updated spinoff 2017 --------------------------------------------------------------------------------------------------------
}

function updNotaIdFaktur($params)
{
    $br = GetUserBranch();

    $db = getDB();
    $conn = $db->conn;
    //SetServerOutput($conn, true);
    /*echo "begin :res := counter_nota_barang_xp.upd_invoice_num(
                                    '".$params['NOTA']."',
                                    '".$params['SERVICE']."',
                                    '$br',
                                    '".$params['TAX_FREE']."',
                                    ".$params['TOTAL'].",
                                    '".$params['CURRENCY']."',
                                    '".$params['JENIS_PELANGGAN']."',
                                    '".$params['PELANGGAN']."',
                                    '".$params['NOTA_PREV']."');
                                end;";die;*/
    $stid = oci_parse($conn, "
								begin 
									:res := counter_nota_barang_xp.upd_invoice_num(
										'" . $params['NOTA'] . "',
					                    '" . $params['SERVICE'] . "',
					                    '$br',
										'" . $params['BRANCH_ACCOUNT'] . "',
					                    '" . $params['TAX_FREE'] . "',
					                    " . $params['TOTAL'] . ",
					                    '" . $params['CURRENCY'] . "',
					                    '" . $params['JENIS_PELANGGAN'] . "',  
					                    --'000000',
					                    '" . $params['PELANGGAN'] . "',
					                    '" . $params['NOTA_PREV'] . "');
					                    --dbms_output.put_line('Hi');			                     
					                end;");
    oci_bind_by_name($stid, ":res", $rtn_id, 128);    // 128 is the return length

    //print_r($params);
    $ret = oci_execute($stid);
    //$output = GetDbmsOutput($conn);

    //print_r($output);die;
    if (!$ret) {
        $err = oci_error($stid);
        print_r($err . '<br /> Parameter No Nota Salah , Silahkan Tekan Tombol Back dan Periksa Kembali Inputan Anda..!!');
        die;
        return -1;
    }
    //--- updated spinoff 2017 --------------------------------------------------------------------------------------------------------
    //echo '---'.$rtn_id.'---'; die;
    if ($params['BRANCH_ACCOUNT'] == '010' && strlen($rtn_id) == 16) {

        // $sql="select counter_nota_barang_xp.gen_invoice_num_spoff('".$rtn_id."') trx_number from dual";
        $result = $db->query("select counter_nota_barang_xp.gen_invoice_num_spoff('" . $rtn_id . "') TRX_NUMBER from dual");
        $row = $result->fetchRow();
        return $row['TRX_NUMBER'];
    } else {
        return $rtn_id;
    }
    //--- updated spinoff 2017 --------------------------------------------------------------------------------------------------------
}

function updCounterNota($params)
{
    $br = GetUserBranch();

    $db = getDB();
    $conn = $db->conn;
    $stid = oci_parse($conn, "begin 
								KAPAL_PROD.counter_nota_xp.upgrade_counter@dbint_kapal ('" . $params['SERVICE_CODE'] . "','" . $params['SERVICE'] . "','$br','" . $params['BRANCH_ACCOUNT'] . "');
					                end;");
    //oci_bind_by_name($stid, ":res", $rtn_id, 128); 	// 128 is the return length

    $ret = oci_execute($stid);
    if (!$ret) {
        $err = oci_error($stid);
        print_r($err);
        die;
        return false;
    } else {
        return true;
    }
	function genBA($params){
		set_time_limit (300);
		$db = getDB("ora");
		$conn = $db->conn;
		// echo $params; die();
		$output_ap = oci_parse($conn, "begin proc_nota_sharing_pkg.do_job('".$params."'); end;");
		//$output_ap = oci_parse($conn, "begin proc_nota_sharing_pkg.nota_sharing_ptp('".$params."', :p_stat, :p_desc); end;");

				//oci_bind_by_name($output_ap, ":p_stat", $p_stat, 128);
				//oci_bind_by_name($output_ap, ":p_desc", $p_desc, 1000);
				//ini_set('max_execution_time', 1000);

				$return_ap = oci_execute($output_ap);
				//echo $p_stat; die;
				//$db->commit();
		return "S";
	}
}

function get_data_rbm($param)
{
    switch ($params) {
        case 'uperpkt_2011':
            $sql = "SELECT M.X_NOTAJB_ID_X,
               M.DNOTA,
               M.NO_NOTA,
               M.DPAID,
               M.UKK,
               M.PBM_ID,
               M.COMPANY_ID,
               M.BRANCH_ID,
               M.ZSERVICE_ID,
               M.TRD_TYPE_ID,
               M.WHOUSE_ID,
               M.KADE,
               M.BL_NO,
               M.DO_NO,
               M.VESSEL_ID,
               M.VOYAGE_NO,
               M.VOYAGE_OUT,
               M.DTA,
               M.DTD,
               M.V_TAXBASE,
               M.V_TAXVALUE,
               M.V_TOBEPAID,
               M.V_ADM,
               M.DSTART,
               M.DFINISH,
               M.FAK_PAJAK,
               M.GC_STATUS,
               M.TERBILANG,
               M.NOTADP_ID,
               M.V_NOTADP,
               M.NOTAAM_ID,
               M.V_NOTAAM,
               M.V_TAXBASE1,
               M.V_TAXVALUE1,
               M.V_TOBEPAID1,
               M.V_JB,
               M.V_DERMAGA,
               M.V_PENUMPUKAN,
               M.V_LOLO,
               M.V_JB_REAL,
               M.ORDER_ID,
               M.V_NEGO,
               M.NEGO_BA,
               M.NEGO_NOTES,
               M.NEGO_FLAG,
               M.STATUS,
               M.OPTR,
               M.DEDIT,
               M.TS,
               M.PRICE_MODE,
               M.SPK_TYPE,
               M.TERMINAL,
               M.DUPER,
               M.NOTAJB_ID,
               M.NOTA_LANJUTAN,
               M.NOTAPREV,
               M.QTY_SHIFT_F40,
               M.FLAG_SUP,
               M.V_REDUCT,
               M.QTY_SHIFT_M40,
               M.QTY_SHIFT_F20,
               M.CMS,
               M.V_REDUCT_REAL,
               M.SELECTED,
               M.QTY_SHIFT_M20,
               M.SOF_NO,
               M.ORDERBY,
               C.NAME     AS COMPNAME,
               V.NAME     AS VSLNAME,
               P.NAME     AS PBMNAME,
               MKT.COMPANY
        FROM TTM_NOTAJB M
             LEFT OUTER JOIN V_TR_COMPANY_NBS C
                ON M.COMPANY_ID = C.COMPANY_ID
             LEFT OUTER JOIN ITOS_REPO.V_TR_VESSEL_NBS V
                ON M.VESSEL_ID = V.VESSEL_ID
             LEFT OUTER JOIN barang_prod.TR_PBM@dbint_kapal P
                ON M.PBM_ID = P.PBM_ID
             LEFT OUTER JOIN barang_prod.MST_KADE_TPK@dbint_kapal MKT
                ON (M.KADE = MKT.KD_KADE and trunc(m.duper) between mkt.tanggal_mulai and mkt.tanggal_akhir)
        WHERE M.STATUS <> 'X'
        ORDER BY CASE
                     WHEN LENGTH(M.no_nota) = 20
                     THEN
                         LPAD(
                             SUBSTR(M.no_nota, 9, 2) || SUBSTR(M.no_nota, -6),
                             20,
                             9)
                     ELSE
                         LPAD(M.no_nota, 20, 0)
                 END DESC"; 
            break;
        
        default:
            # code...
            break;
    }

    return $sql;
}

function debux($str,$die=false)
{
    echo '<pre>';
    print_r($str);
    echo '</pre>';
    ($die) ? die : '';
}

?>