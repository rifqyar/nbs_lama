<?
	
	define('FDIR',$_conf['site']['base']);
	define('FDIR_LIB',FDIR . "/lib/");
	
	define('DOC_ROOT',$_conf['site']['doc_root']);
	define('SITE_DIR',$_conf['site']['root']);
	define('HOME',$_conf['site']['home']);
	define('SITE_LIB',SITE_DIR . "/lib/");
	define('SITE_TEMPLATE',SITE_DIR . "/template/");
	define('SITE_MODULE',SITE_DIR . "/modules/");
	define('ENC_USERNAME',$_conf['site']['enc_username']);

	function get_lib_path( $libname ) 	{		return FDIR_LIB . $libname;		}
	function get_site_path( $libname ) 	{		return SITE_LIB . $libname;		}

	function require_lib( $libname )		{		require_once( FDIR_LIB . $libname );	}
	function require_site_lib( $libname ) 	{		require_once( SITE_LIB . $libname );	}


	####################################################
	##
	##  compatibility for PHP4
	##
	if ( !function_exists('file_put_contents') && !defined('FILE_APPEND') ) {
		define('FILE_APPEND', 1);

		function file_put_contents($n, $d, $flag = false) {
			$mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a' : 'w';
			$f = @fopen($n, $mode);
			if ($f === false) {
				return false;
			} else {
				if (is_array($d)) $d = implode($d);
				$bytes_written = fwrite($f, $d);
				fclose($f);

				return $bytes_written;
			}
		}
	}

	
	#####################################################
	## 
	##   Database library & helper functions..
	##
	function & poolDB($id_koneksi='default',$driver='mysql',$dbname=false,
							 $host=false,$user=false,$pwd=false,$debug = false) {
		global $_conf;

		require_lib('xdb/xdb.php');
		
		if (!$dbname) $dbname 	= $_conf['db'][$id_koneksi]['catalog'];
		if (!$host) $host 		= $_conf['db'][$id_koneksi]['host']; 
		if (!$user) $user 		= $_conf['db'][$id_koneksi]['user']; 
		if (!$pwd) $pwd 		= $_conf['db'][$id_koneksi]['password'];
		if (!$driver) $driver 	= $_conf['db'][$id_koneksi]['driver'];
		 
		$koneksi = ConnectDB($driver,$host,$dbname,$user,$pwd);
		
		// daftarkan for later use (english apa indo seh??)..
		$GLOBALS['DBCONN'][$id_koneksi] = $koneksi;
		$GLOBALS['DBHIT'][$id_koneksi]	= 0;
		return $koneksi;
	}
	
	function & getDB($id_koneksi='default',$driver=false,$dbname=false,
						  $host=false,$user=false,$pwd=false,$debug = false) {
	
		if (isset($GLOBALS['DBCONN'][$id_koneksi])) {
			if (!isset($GLOBALS['DBHIT'][$id_koneksi])) $GLOBALS['DBHIT'][$id_koneksi]=1;
				else $GLOBALS['DBHIT'][$id_koneksi]++;
				 
			return $GLOBALS['DBCONN'][$id_koneksi];
		}
		
		return poolDB($id_koneksi,$driver,$dbname,$host,$user,$pwd,$debug);
	}	
	
	function closeDB() {
		unset($GLOBALS['DBCONN']);
	}
	
	
	###########################################################
	##
	##  Template Engine & Output..
	##
	function & xliteTemplate( $fname, $tipe='_app' ) {
		require_lib('xlite/xtemplate.php');
		switch ($tipe) {
			case '_main':
				$path = SITE_TEMPLATE . $fname;
				break;
			case '_app':
				$path = MODULE_TEMPLATE_DIR . $fname;
				break;
			default:
				$path = $fname;
		}
		
		$xl = new XTemplate( $path );
		return $xl;	
	}	
	
	function executeScript( $scripts ) {		
		global $_q;
		global $_out;
		global $_ajax;		
		
		if (!is_array($scripts)) $scripts = array($scripts);
		
		ob_start();
		foreach($scripts as $fname) {
			if ( file_exists($fname) ) include $fname;
		}
		$content = ob_get_clean();				
		return $content;
	}
	
	
	function killApp($type='MAINTENANCE') {
		header('KILLED: '.$type);
		switch ($type) {
			case 'FORBIDDEN':
				header('Location: '.HOME . 'static/forbidden.htm');
				break;
			case 'FORCED':
				header('Location: '.HOME . 'static/forced.htm');
				break;
			case 'MAINTENANCE':
			default:
				die('ERROR: '.$type);
				ob_end_flush();
				header('Location: '.HOME . 'static/maintenance.htm');
				break;			
		}
		ob_end_clean();
		die('killed');
	}
	
	// OUTPUT HANDLING 
	function _xss($input, $filter='mini') {
		require_lib('antixss/antixss.php');		
		return XSS_cleanxss($input, $filter='mini');
	}

	function outputRaw() {
		define('RAW_OUTPUT',1);
	}
	
	function outputAjax() {
		define('RAW_OUTPUT',1);
		define('AJAX_OUTPUT',1);
		
		require_lib('ajax.php');
		global $_ajax;
		$_ajax = new AJAX();
	}
		
	function jsencode( $s ) {
		return rawurlencode($s);
	}
	
	### month
	$MON = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Okt','Nop','Des');
	function getMonth($mon) {
		global $MON;
		
		return $MON[$mon-1];
	}
	
	function padd($x,$n,$p='0') {
		while (strlen($x)<$n)
			$x = $p . $x;
		return $x;
	}	
	

	#########
	##  sanitize magic quotes..
	##	
	function stripslashes_arrays(&$array) {
		if ( is_array($array) ) {
			$keys = array_keys($array);
			foreach ( $keys as $key ) {
				if ( is_array($array[$key]) ) {
					stripslashes_arrays($array[$key]);
				} else {
					$array[$key] = stripslashes($array[$key]);
				}
			}
		}
	}
	
	set_magic_quotes_runtime(0);
	if( get_magic_quotes_gpc() ) {
		stripslashes_arrays($_GET);
		stripslashes_arrays($_POST);
	}
	
	
	#####################
	##  link
	##
	function _link($args,$initval=false,$rawlink=false) {
		$tmp = array('appid'=>APPID,'sub'=>SUBID);
		if ($initval && is_array($initval)) {
			foreach($initval as $k=>$v)
				if (!ctype_digit($k)) $tmp[$k] = $v;
		}
			
		if (is_array($args)) {
			foreach($args as $k=>$v)
				$tmp[$k]=urldecode($v);
		}
		
		// clear numeric..
		if (is_array($initval))
			foreach($initval as $k=>$v)
				if (is_numeric($k)) unset($tmp[$k]);
		
		$res = HOME;
		if ($rawlink) {
			$res.= 'index.php?'.http_build_query($tmp);
		} else {
			foreach($tmp as $arg) {
				$arg = str_replace('/',':slash:',$arg);
				$res.= urlencode($arg).'/';
			}
		}
		return $res;
	}
	
	function _map($map) {
		global $_q;
		if (!is_array($map)) return;
		
		for ($i=0;$i<count($map);$i++) {
			if ( !isset($_q[$i]) ) {
				if ( !isset($map[$i]['optional']) ) return false;					// required argumen..
				if (!isset($_q[$map[$i]['name']]))
					$_q[$map[$i]['name']] = isset($map[$i]['default'])?$map[$i]['default']:'';
				
			} else {
			
				if (isset($map[$i]['type'])) {
					switch($map[$i]['type']) {
						case 'I':						// integer
							$_q[$i] = (int) $_q[$i];
							break;
							
						case 'S':						// string
							break;
							
						case 'E':						// enum
							if (isset($map[$i]['enum'])) {
								if (!in_array($_q[$i],explode(',',$map[$i]['enum']))) {
									if (isset($map[$i]['default'])) $_q[$i]=$map[$i]['default'];
										else return false;
								}
							}
							break;
					} //-- end switch
				} //-- end if
			
				if (!isset($_q[$map[$i]['name']]))
					$_q[$map[$i]['name']] = & $_q[$i];
			}
		}
							
		return true;
	}

	function getModuleStack() {
		global $_heads;
		$res = array(
				'appid'=>APPID,
				'subid'=>SUBID,
				'leaf'=>$_heads[count($_heads)-1],
				'parent'=>count($_heads)==1?'':implode('.',array_slice($_heads,0,count($_heads)-1))
			);
		return $res;
	} 

	function acl(){
		require_lib('acl.php');	
		$_acl = new ACL();
		$_acl->load();
		$res = $_acl->getLogin();
		return $res;
	}
	
	function debug($var){
	  echo "<pre>" ;
	  print_r($var)	;
	  echo "</pre>";
	}
	
	//==function added by tofan==//
	function desimal($angka, $val)
	{
		if($val==1)	$t1=".";
		else		$t1=",";
		
		$koma = "";
		if(stristr($angka,".")) {
			$pos = strpos($angka,".")+1;
			$len = strlen($angka);
			$koma = substr($angka,$pos,$len-$pos);
			$angka = substr($angka,0,$pos-1);
			if($val==1)	$t2=",";
			else		$t2=".";
			$koma = str_pad($koma, 2, "0", STR_PAD_RIGHT);		//penyisipan angka 0 untuk 2 digit terakhir di belakang koma
		}
		
		if($koma=="" && $val==2) {	// utk dollar, 2 angka dibelakang koma tetep dikasih walaupun 00
			if($val==1)	$t2=",";
			else		$t2=".";
			$koma = "00";
		}
		
		if ($angka == "") $angka = 0;
		$desimal = "";
		$p = strlen($angka);
		while($p > 3)
		{
			$desimal = $t1 . substr($angka,-3) . $desimal;
			$l = strlen($angka) - 3;
			$angka = substr($angka,0,$l);
			$p = strlen($angka);
		}
		$desimal = $angka . $desimal . $t2 . $koma;
		return $desimal;
	}
	
	/*
	 * FUNGSI NUMERIK KE TERHITUNG
	 * (c) 2008-2010 by amarullz@yahoo.com
	 *
	 */
	//==== FUNGSI TERBILANG ====//
	function terbilang_get_valid($str,$from,$to,$min=1,$max=9){
		$val=false;
		$from=($from<0)?0:$from;
		for ($i=$from;$i<$to;$i++){
			if (((int) $str{$i}>=$min)&&((int) $str{$i}<=$max)) $val=true;
		}
		return $val;
	}
	function terbilang_get_str($i,$str,$len){
		$numA=array("","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan");
		$numB=array("","se","dua ","tiga ","empat ","lima ","enam ","tujuh ","delapan ","sembilan ");
		$numC=array("","satu ","dua ","tiga ","empat ","lima ","enam ","tujuh ","delapan ","sembilan ");
		$numD=array(0=>"puluh",1=>"belas",2=>"ratus",4=>"ribu", 7=>"juta", 10=>"milyar", 13=>"triliun");
		$buf="";
		$pos=$len-$i;
		switch($pos){
			case 1:
					if (!terbilang_get_valid($str,$i-1,$i,1,1))
						$buf=$numA[(int) $str{$i}];
				break;
			case 2:	case 5: case 8: case 11: case 14:
					if ((int) $str{$i}==1){
						if ((int) $str{$i+1}==0)
							$buf=($numB[(int) $str{$i}]).($numD[0]);
						else
							$buf=($numB[(int) $str{$i+1}]).($numD[1]);
					}
					else if ((int) $str{$i}>1){
							$buf=($numB[(int) $str{$i}]).($numD[0]);
					}				
				break;
			case 3: case 6: case 9: case 12: case 15:
					if ((int) $str{$i}>0){
							$buf=($numB[(int) $str{$i}]).($numD[2]);
					}
				break;
			case 4: case 7: case 10: case 13:
					if (terbilang_get_valid($str,$i-2,$i)){
						if (!terbilang_get_valid($str,$i-1,$i,1,1))
							$buf=$numC[(int) $str{$i}].($numD[$pos]);
						else
							$buf=$numD[$pos];
					}
					else if((int) $str{$i}>0){
						if ($pos==4)
							$buf=($numB[(int) $str{$i}]).($numD[$pos]);
						else
							$buf=($numC[(int) $str{$i}]).($numD[$pos]);
					}
				break;
		}
		return $buf;
	}
	
	function toTerbilang($nominal){
		$buf="";
		$str=$nominal."";
		$len=strlen($str);
		for ($i=0;$i<$len;$i++){
			$buf=trim($buf)." ".terbilang_get_str($i,$str,$len);
		}
		return trim($buf);
	}
	
	//==== end of FUNGSI TERBILANG ====//
	//==end of function added by tofan==//
	
	// ====== dendoy function ======== //
	
	function Romawi($n)
	{
		$hasil = "";
		$iromawi = array("","I","II","III","IV","V","VI","VII","VIII","IX","X",20=>"XX",30=>"XXX",40=>"XL",50=>"L",
		60=>"LX",70=>"LXX",80=>"LXXX",90=>"XC",100=>"C",200=>"CC",300=>"CCC",400=>"CD",500=>"D",600=>"DC",700=>"DCC",
		800=>"DCCC",900=>"CM",1000=>"M",2000=>"MM",3000=>"MMM");
		if(array_key_exists($n,$iromawi)){
		$hasil = $iromawi[$n];
		}elseif($n >= 11 && $n <= 99){
		$i = $n % 10;
		$hasil = $iromawi[$n-$i] . Romawi($n % 10);
		}elseif($n >= 101 && $n <= 999){
		$i = $n % 100;
		$hasil = $iromawi[$n-$i] . Romawi($n % 100);
		}else{
		$i = $n % 1000;
		$hasil = $iromawi[$n-$i] . Romawi($n % 1000);
		}
		return $hasil;
	}
	
	function bulan_indonesia($stringbulan)
	{
		$bulan_angka=array('01','02','03','04','05','06','07','08','09','10','11','12');
		$bulankite=array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus',
						  'September','Oktober','November','Desember');
		$konversi=str_ireplace($bulan_angka,$bulankite,$stringbulan);
		return $konversi;
	}

	function hari_indonesia($stringhari)
	{
		$hari_angka=array('1','2','3','4','5','6','7');
		$harikite=array('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu');
		$konversi=str_ireplace($hari_angka,$harikite,$stringhari);
		return $konversi;
	}

	function hr_ina($stringhari)
	{
		$hari_angka=array('MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY');
		$harikite=array('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu');
		$konversi=str_ireplace($hari_angka,$harikite,$stringhari);
		return $konversi;
	}
	
	// ====== dendoy function ======== //
	
	
	
?>
