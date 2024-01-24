<?
// Access Control
class LoginInfo {
	var $userid;
	var $password;
	var $group;
	var $token;
	var $info;
	var $menu;

	function LoginInfo() {
		$this->group = array();
		$this->token = array();
		$this->info   = array();
		$this->menu   = array();	
	}
	
	function load( $raw , $idfield='userid' ) {	
		$this->userid    = $raw[$idfield];
		$this->password  = $raw['password'];

		$this->info   = array();
		foreach ($raw as $key=>$value) {
			$this->info[$key] = $value;
		}
	}
}


class ACL {
	var $logininfo;
	var $cacheGroupInfo;
	var $allowMultipleLogin;
	var $doCheckSession;
	var $errcode='';
			
	function ACL() {
		$this->logininfo 			= false;	
		$this->cacheGroupInfo		= true;
		$this->allowMultipleLogin	= true;
		$this->useCheckSession		= false;
	}
	
	function doLogin($userid, $password) {
		$res;
		
		$db = getDB(SYS_DB);
		
		$userid    = addslashes( $userid );
		$password  = addslashes( $password );

		$this->errcode = 'OK';
		
		
		$rs = $db->query( "SELECT * FROM acl_user WHERE userid='$userid' AND password='$password' LIMIT 1" );
		if ( $rs && $rs->RecordCount()==1 ) {
			$this->logininfo = new LoginInfo();
			$this->logininfo->load( $rs->FetchRow() , 'userid' );
			$rs->close();	
			
			if ($this->logininfo->info['enabled']==0) {
				$this->errcode = 'ERR_DISABLED';
				return false;
			}
			
			if ($this->useCheckSession && !$this->checkSession()) $this->errcode = 'WARN_SESSION';			
			$this->loadGroupInfo();	
			
			$this->save();
			return true;
			
		} else {
			$this->errcode = 'ERR_BADLOGIN';
			return false;
		}
	}

	function checkSession() {
		$res	 	= true;
		$logindata  = $this->logininfo;
		$userid  	= isset($logindata->userid)? addslashes( $logindata->userid ):'public';
		$session 	= session_id();
		$ipaddress	= $_SERVER['REMOTE_ADDR'];
		
		$hassession	= false;
		
		$db  = getDB(SYS_DB);	

		if (!$this->allowMultipleLogin) {
			$db->query("DELETE FROM acl_session WHERE userid='$userid' AND sessionid<>'$session'");
		}
		
		// check session
		$rs  = $db->query("SELECT sessionid FROM acl_session WHERE userid='$userid' AND sessionid='$session'");
		if ($rs && $rs->RecordCount()>0) {
			$rs->close();
			$hassession = true;
		}
		
		// update session..
		if ($hassession) {
			$db->query("UPDATE acl_session SET heartbeat=now() WHERE userid='$userid' AND sessionid='$session'");
		} else {			
			$db->query("INSERT INTO acl_session VALUES('$userid','$session','$ipaddress',now(),now())");
		}

		return $res;
	}

		
	function loadGroupInfo() {
		$userid    = addslashes( $this->logininfo->userid );

		// clear everything..
		$this->logininfo->group = array();			
		$this->logininfo->token = array();

		// get group..
		$db = getDB(SYS_DB);
		
		$rs2 = $db->query( "SELECT a.groupname,b.groupmenu FROM acl_group_member a,acl_menu_rights b WHERE a.groupname=b.groupname and a.userid='$userid' ORDER BY a.groupname" );	
		if ($rs2 && $rs2->RecordCount()>0) {
			$groups = array();

			$tokens = $rs2->getAll();
			foreach ($tokens as $v) {
				$this->logininfo->group[] = $v['groupname'];
				$this->logininfo->menu = $v['groupmenu'];
				$groups[] = "'".addslashes($v['groupname'])."'";
			}
			$rs2->close();
			
			// get uri
			$rs2 = $db->query( 
						"SELECT w.name,w.uri 
								FROM acl_wo w, acl_group_rights a, acl_group g
								WHERE w.uri=a.uri AND w.enabled=1 AND 
								      a.groupname=g.groupname AND g.enabled=1 AND
									  a.groupname in (".implode(',',$groups).")"
						);
			if ($rs2 && $rs2->RecordCount()>0) {
				while ($row=$rs2->FetchRow()) {
					$this->logininfo->token[$row['name']] = $row['uri'];				
				}
				$rs2->close();
			} 
		}	
	}

	function getGroupTokens($groupname) {
		$groupname = addslashes($groupname);
		$res = array();
							  
		$db  = getDB(SYS_DB);
		$rs2 = $db->query( 
					"SELECT w.name,w.uri 
						FROM acl_wo w, acl_group_rights a, acl_group g
						WHERE w.uri=a.uri AND w.enabled=1 AND 
							  a.groupname=g.groupname AND g.enabled=1 AND
							  a.groupname='$groupname'"	
					);
		if ($rs2) {
			while ($row=$rs2->FetchRow()) {
				$res[$row['name']] = $row['uri'];
			}
			$rs2->close();
		} 
		
		return $res;
	}
	
	function doLogout() {
		if ($this->useCheckSession) {
			$logindata  = $this->logininfo;
			$userid  	= addslashes( $logindata->userid );
			$session 	= session_id();
	
			$db = getDB(SYS_DB);
			$db->query("DELETE FROM acl_session WHERE userid='$userid' AND sessionid='$session'");
		}
				
		$this->logininfo = false;
		unset($_SESSION['__ACL_logininfo']);
	}

	function save() {
		$_SESSION['__ACL_logininfo'] = serialize( $this->logininfo );
	}
	
	function load() {
		$this->logininfo = isset($_SESSION['__ACL_logininfo'])? unserialize($_SESSION['__ACL_logininfo']):false;
		
		if ($this->logininfo==false) return;
		
		if ($this->useCheckSession) {
		    $logindata  = $this->logininfo;
			$userid  	= addslashes( $logindata->userid );
			$session 	= session_id();
	
			$db = getDB(SYS_DB);
			$rs = $db->query("SELECT * FROM acl_session WHERE userid='$userid' AND sessionid='$session' LIMIT 1");
			if (!$rs || $rs->RecordCount()==0) {
				$this->errcode = 'ERR_LOST';
				if ($rs) $rs->close();
				$this->logininfo = false;
				unset($_SESSION['__ACL_logininfo']);
				return;
			} 
			
			$this->checkSession();
		}
		if (!$this->cacheGroupInfo) {
			$this->loadGroupInfo();
			$this->save();
		}
	}
	
	function getLogin() {
		return $this->logininfo;
	}
	
	function isLogin() {
		return $this->logininfo!=false;
	}
}


?>