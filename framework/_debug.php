<?php
	function _out($msg) {
		if (!_DEVELOP_MODE) return;
		
		global $_DEBUG;
		
		$back  = debug_backtrace();
		if ( count($back)>1 ) array_shift($back);
		if (isset($back[0]['file'])) {
			$loc = $back[0]['file'] . " line ". $back[0]['line'] . "&nbsp;&nbsp;";
		} else {
			$loc = "&lt;INIT&gt;&nbsp;&nbsp;";
		}
		if (isset($back[0]['class'])) $loc .= $back[0]['class'] . "::";
		if (isset($back[0]['function'])) $loc .= $back[0]['function'] . "()";

		$_DEBUG['MSG'][] = array($loc, (is_array($msg) || is_object($msg))?var_export($msg,true):$msg );
	}

	function _sql($sql,$desc="") {
		if (!_DEVELOP_MODE) return $sql;
		global $_DEBUG;

		$back  = debug_backtrace();
		if ( count($back)>1 ) array_shift($back);
		$loc = $back[0]['file'] . " line ". $back[0]['line'] . "&nbsp;&nbsp;";
		if (isset($back[0]['class'])) $loc .= $back[0]['class'] . "::";
		if (isset($back[0]['function'])) $loc .= $back[0]['function'] . "()";
		
		$_DEBUG['SQL'][] = array($loc,$sql,$desc);
		return $sql;
	}
	
	function debug_err_handler($errno, $errstr, $errfile, $errline)	{
		global $_DEBUG;
		
		switch ($errno) {
			case E_ERROR:
			case E_USER_ERROR:
			
				if (_DEVELOP_MODE) {
					$_DEBUG['ERR'][] = array($errfile,$errline,$errstr);
					 
					$back  = debug_backtrace();
					if ( count($back)>1 ) array_shift($back);
					
					$_DEBUG['TRACE'] 	= serialize( $back );
					
				} else {
					// jump to system down/maintenance static page..
					header("Location: ". _STATIC_ERROR);
				}
				die();
				break;
				
			case E_WARNING:
			case E_USER_WARNING:
				$_DEBUG['WARN'][] = array($errfile,$errline,$errstr);
				break;
				
			case E_NOTICE:
			case E_USER_NOTICE:
			default:
				// biar gak brisik..
				if ( strpos($errstr,"eprecated")===false )
					$_DEBUG['NOTE'][] = array($errfile,$errline,$errstr);
				break;			
		}
		if (!defined("_DEBUG_OUT")) define("_DEBUG_OUT",true);
	}

	function dump_debug_data() {
		if (!_DEVELOP_MODE) return;

		global $_DEBUG;
		
		$_DEBUG['POST'] 	= $_POST;
		$_DEBUG['GET']  	= $_GET;
		$_DEBUG['COOKIE'] 	= $_COOKIE;
		$_DEBUG['FILE']		= $_FILES;
		$_DEBUG['SERVER']	= $_SERVER;
		$_DEBUG['SESSION']	= $_SESSION;
							
		$f = fopen( _DEBUG_DIR."/debug_".session_id().".log","w");
		fwrite($f,serialize($_DEBUG));
		fclose($f);
	}
	
	error_reporting( E_ALL & ~E_NOTICE );
	//error_reporting( E_ERROR );
	set_error_handler("debug_err_handler");

?>