<?php
	/* ############################################
		XDB - HiPerformance Database Abstraction
		(c) 2009
		written by Adi Gita Saputra
	*/
	define('TYPE_STRING','S');
	define('TYPE_TIMESTAMP','T');
	define('TYPE_INTEGER','I');
	define('TYPE_REAL','R');
	define('TYPE_BLOB','B');

	function ConnectDB($driver,$host,$db,$user,$password) {
		$driver = strtolower($driver);
		switch($driver) {
			case 'mysql':
				require_once('mysql.php');
				return new MySQLDriver($host,$db,$user,$password);
			case 'oci':
				require_once('oci8.php');
				return new OCI8Driver($host,$db,$user,$password);
			default:
				die('unsupported driver: '.$driver);
		}
	}
	
	function readIsoDate( $v ) {
		$res = array();
		list($y,$m,$d,$hh,$mm,$ss) = sscanf( $v, '%d-%d-%d %d:%d:%d' );
		$res['y'] 	= $y;
		$res['m'] 	= padd($m,2);
		$res['d'] 	= padd($d,2);
		$res['hh']	= padd($hh,2);
		$res['mm']	= padd($mm,2);
		$res['ss']	= padd($ss,2);
		return $res;
	}
	
	function readIndoDate( $v ) {
		$res = array();
		list($d,$m,$y,$hh,$mm,$ss) = sscanf( $v, '%d-%d-%d %d:%d:%d' );
		$res['y'] 	= $y;
		$res['m'] 	= padd($m,2);
		$res['d'] 	= padd($d,2);
		$res['hh']	= padd($hh,2);
		$res['mm']	= padd($mm,2);
		$res['ss']	= padd($ss,2);
		return $res;
	
	}
	
	function createIsoDate( $v ,$dateonly=false) {
		
		if (!is_array($v)) $v = array();	
		$res  = isset($v['y'])?$v['y']:'0000';
		$res .= '-'. (isset($v['m'])?padd($v['m'],2):'01');
		$res .= '-'. (isset($v['d'])?padd($v['d'],2):'01');
		if (!$dateonly) {
			$res .= ' '. (isset($v['hh'])?$v['hh']:'00');
			$res .= ':'. (isset($v['mm'])?$v['mm']:'00');
			$res .= ':'. (isset($v['ss'])?$v['ss']:'00');
		}		
		
		return $res;
	}
	
	
	function formatIsoDate( $v, $dateonly=true ) {
		$d = readIsoDate( $v );
		$res = $d['d'].'&nbsp;'.getMonth($d['m']).'&nbsp;'.$d['y'];
		if (!$dateonly) 
			$res.=' '.padd($d['hh'],2).':'.padd($d['mm'],2).':'.padd($d['ss'],2);
		return $res;
	}
	
	function formatIsoMonth( $v ) {
		$res = getMonth($v['m']).'&nbsp;'.$v['y'];
		return $res;
	}

	function formatIndoDate($v){
	  
	   if (!is_array($v)) $v = array();		
		$res  = isset($v['d'])?$v['d']:'01';
		$res .= '-'. (isset($v['m'])?$v['m']:'01');
		$res .= '-'.isset($v['y'])?$v['y']:'0000';
		if (!$dateonly) {
			$res .= ' '. (isset($v['hh'])?$v['hh']:'00');
			$res .= ':'. (isset($v['mm'])?$v['mm']:'00');
			$res .= ':'. (isset($v['ss'])?$v['ss']:'00');
		}		
		return $res;	
	}
	
	
	function IndoToIsoDate($v){
	 $v = readIndoDate($v);
	 if (!is_array($v)) $v = array();		
		$res  = isset($v['y'])?$v['y']:'0000';
		$res .= '-'. (isset($v['m'])?$v['m']:'01');
		$res .= '-'. (isset($v['d'])?$v['d']:'01');
		if (!$dateonly) {
			$res .= ' '. (isset($v['hh'])?$v['hh']:'00');
			$res .= ':'. (isset($v['mm'])?$v['mm']:'00');
			$res .= ':'. (isset($v['ss'])?$v['ss']:'00');
		}		
		return $res;	
	}
	
	
  ?>