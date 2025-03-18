<?
	/*
		ViewState
		---------
		Handling viewstate untuk transaksi PHP yang bersih
	============================================================= */ 
	$_script_start_time = microtime(true);
	$_timer_ctr	    = array();
	$_timer_proc	    = array('DB_CONN'=>0,'DB_QUERY'=>0,'DB_FETCH'=>0);

	function _start($key) {
		global $_timer_ctr;
		$_timer_ctr[$key] = microtime(true);
	}

	function _stop($key) {
		global $_timer_ctr;
		global $_timer_proc;
		$_timer_proc[$key] += (microtime(true)-$_timer_ctr[$key]);
	}

	function _script_startup() {
		if (defined("_DEVELOP_MODE") && _DEVELOP_MODE ) _out("##script startup");
	}
	
	function _script_shutdown() {
		global $_script_start_time;
		global $_timer_proc;
		closeDB();
	
		$time = number_format((microtime(true)-$_script_start_time),8);
		header('X_PROC_TIME: '.$time);					
		foreach($_timer_proc as $k=>$v) {
			header('X_PROC_'.$k.': '.number_format($v,8));
		}	
		
	}
	
	register_shutdown_function("_script_shutdown");
	_script_startup();
?>
