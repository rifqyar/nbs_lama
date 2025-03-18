<?php
########################
## ACL
##

require_lib('acl.php');
$_acl = new ACL();
$_acl->cacheGroupInfo		= ACL_CACHEGI;
$_acl->allowMultipleLogin	= ACL_MULTI;
$_acl->useCheckSession		= ACL_SESSION;
$_acl->load();
#$_acl->checkSession();


//$_acl->doLogin('admin','admin');
//$_acl->doLogout('admin','admin');

if ($_acl->errcode=='ERR_LOST') {
	killApp('FORCED');
	
} else {
	$li = $_acl->getLogin();
	if ($li) {
		$tokens = $li->token;		
	} else {
		$tokens = $_acl->getGroupTokens('public'); 
	}
	#echo "<pre>";var_export($li);var_export($tokens);echo "</pre>";
	
	## check uri pattern.. pentiiingg
	$rooturi = '/'.APPID.'/'.SUBID;
	$uri_ok = false;	
	foreach ($tokens as $token=>$uripat) {		
		$uripat = str_replace('/','\/',$uripat);
		$uripat = str_replace('.','\.',$uripat);
		$uripat = str_replace('*','([A-Za-z0-9\._\-]*)',$uripat);
		#echo "MATCHING $uripat AGAINST $rooturi <br />";
		if (preg_match('/^'.$uripat.'$/',$rooturi)) {
			#echo 'OK!';
			$uri_ok=true; break;
		}
	}
}

if (strstr(APPID,'main')) $uri_ok=true;	
if (strstr(APPID,'home')) $uri_ok=true;	

###########
# khusus 
$li = $_acl->getLogin();

if (strstr(APPID,'penulisan.verifikasi')) {
	if( strtoupper($li->group[0]) =='PETUGAS_VALIDASI' ) 
	$uri_ok=true;	
}

if (strstr(APPID,'penulisan.pengiriman')) {
	#echo strtoupper($li->group[0]);
	if( strtoupper($li->group[0]) =='MAHASISWA_S1') $uri_ok=true;	
}

if (!$uri_ok) killApp('FORBIDDEN');


?>