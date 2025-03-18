<?php
	function val_translateError( &$e ) {
		$search 	= array(':null',':empty',':size',':pattern');
		$replace	= array('data harus tersedia',
							'data tidak boleh kosong',
							'ukuran data tidak valid',
							'format tidak sesuai');
		if (is_array($e)) {
			foreach($e as $k=>$v) {
				$e[$k] = str_replace($search,$replace,$v);
			}
		}
	}
	
	function val_normalDate( &$v ) {
	
		$d  = isset($v['d'])?$v['d']:0;
		$m  = isset($v['m'])?$v['m']:0;
		$y  = isset($v['y'])?$v['y']:0;
		$hh = isset($v['hh'])?$v['hh']:0;
		$mm = isset($v['mm'])?$v['mm']:0;
	
		$time = mktime($hh,$mm,0,$m,$d,$y);
		if ($time===false || $time==-1) {
			$time = mktime();	// use current..
		}
		$d = explode(',',date('Y,m,j,G,i,s',$time));
		$v = array(
					'y' =>$d[0],
					'm' =>$d[1],
					'd' =>$d[2],
					'hh'=>$d[3],
					'mm'=>$d[4],
					'ss'=>$d[5],
				);
	}
	
	function val_checkNotNull( $check, $name, &$error ) {
		if (!isset($check[$name])) {
			$error[$name] = ':null';
			return false;
		} else {
			return true;
		}
	}

	function val_checkNotEmpty( $check, $name, &$error ) {
		if (!isset($check[$name]) || trim($check[$name])=='') {
			$error[$name] = ':empty';
			return false;
		} else {
			return true;
		}
	}

	function val_checkSize( $check, $name, $minsize, $maxsize, &$error ) {
		if (!isset($check[$name]) || strlen($check[$name])<$minsize || strlen($check[$name])>$maxsize) {
			$error[$name] = ':size';
			return false;
		} else {
			return true;
		}	
	}
	
	function val_checkPattern( $check, $name, $pattern, $regex, &$error ) {
		if (!isset($check[$name])) return false;
		switch ($pattern) {
			case 'email':
				if (preg_match("/^[a-z0-9\\._-]+@[a-z0-9\\.-]+\.[a-z]{2,6}$/i",$check[$name]))
					return true;
			case 'digit':
				if (preg_match("/^[0-9]+$/i",$check[$name]))
					return true;				
			case 'alphanumerik':
				if (preg_match("/^[0-9a-z]+$/i",$check[$name]))
					return true;				
			case 'printable':
				if (preg_match("/^\\w+$/i",$check[$name]))
					return true;				
			case 'zip':
				if (preg_match("/^\\d+{5}/i",$check[$name]))
					return true;				
			case 'regex':
				if (preg_match($regex,$check[$name]))
					return true;				
		}
		
		$error[$name] = ':pattern';
		return false;
	}



?>