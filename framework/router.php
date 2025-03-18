<?php
	// 2009-02-15 : url request string compatible..
	define('REQ_SEPARATOR','/');
	define('REQ_SUFFIX','htm');
	
	
	
	if ($_conf['site']['vhost']!=REQ_SEPARATOR)
		$uri = str_replace($_conf['site']['vhost'],'',$_SERVER['REQUEST_URI']);
	else
		$uri = substr($_SERVER['REQUEST_URI'],1);
	
	
	if ($uri=='index.php') $uri = '';			
	define('REQ_URI',$uri);	
	$qpos = strpos($uri,'?');
	$q    = array();
		
	if ($qpos===false || $qpos>0) {	
		$_q = explode(REQ_SEPARATOR,substr($uri,0,($qpos===false)?strlen($uri):$qpos));
	
		if (isset($_q[0])) $_q['appid'] = $_q[0];
		if (isset($_q[1])) $_q['sub']   = $_q[1]; else {
			$_q['sub']='';
		}
	}
	
	if ($qpos!==false) {
		$args = substr($uri,$qpos+1);
		parse_str($args,$temp);
		$_q = array_merge($_q,$temp);
	}
		
	foreach ($_q as $k=>$v) {
		if (!is_array($v)) $v = str_replace(':slash:','/',urldecode($v));
		$_q[$k] = $v;
		if (!is_numeric($k))
			$_GET[$k] = $v;
	}
	
	foreach ($_GET as $k=>$v) 
		$_q[$k] = $v;

	function & __q($key) {
		global $_q;
		return  $_q[$key];
	}
		
	####################################
	##
	##  portal style..
	##
	$_APPID = isset($_q['appid'])?$_q['appid']:$_conf['site']['main'];
	$_SUB   = isset($_q['sub'])?$_q['sub']:'';
	
	if ( preg_match("/^[A-Za-z]([A-Za-z0-9\._\-]*)$/", $_APPID)==0 ) {
		$_APPID = $_conf['site']['main'];
	} 
	if ( preg_match("/^[A-Za-z0-9]([A-Za-z0-9\._\-]*)$/", $_SUB)==0 ) $_SUB = 'index'; 
	
	$_q['appid'] = $_APPID;
	$_q['sub'] 	 = $_SUB;
	array_shift($_q); array_shift($_q);
	
	$_heads = explode('.',$_APPID);
	
	$P_APPID = str_replace(".","/",$_APPID);


	################################
	##  path calculation
	##
	if ( !file_exists( SITE_MODULE.$P_APPID.'/' ) ) {
		if (_DEVELOP_MODE)
			#die('Module Not Implemented! '. $_APPID);	
			header('Location: '.HOME .'static/error.htm');			
		else {
			$_APPID 	= $_conf['site']['main'];
			$_SUB   	= '';
			$P_APPID 	= str_replace(".","/",$_APPID);
		}
	}

	define('APPID',$_APPID);
	define('SUBID',$_SUB);

	define('MODULE_DIR', SITE_MODULE . $P_APPID.'/' );
	define('MODULE_TEMPLATE_DIR', MODULE_DIR . "template/" );
	
	$script_name = MODULE_DIR . $_SUB . ".php";
	
	if ( !file_exists($script_name) ) {	
		$script_name = MODULE_DIR . (($_SUB=='')?'index':$_SUB) . REQ_SUFFIX;
	
		// default..
		if ( !file_exists($script_name) ) {
			$script_name = MODULE_DIR . "index.php";
		}
	}

?>