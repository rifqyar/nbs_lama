<?php
	
	$_conf['framework']['codename']	= 'supernova';
	$_conf['framework']['version']	= '0.5';
	$_conf['framework']['type']		= 'devel';
	
	
	//-- database..

	$_conf['db']['default']['driver'] 	= 'oci';
	$_conf['db']['default']['host']        = '10.10.32.161';
	$_conf['db']['default']['catalog']	= 'pnkdb';
	$_conf['db']['default']['user']		= 'billing';
	$_conf['db']['default']['password']	= 'billingpnkkitasemua';
	
	$_conf['db']['storage']['driver'] 	= 'oci';
	$_conf['db']['storage']['host']        = '10.10.32.161';
	$_conf['db']['storage']['catalog']	= 'pnkdb';
	$_conf['db']['storage']['user']		= 'USTER';
	$_conf['db']['storage']['password']	= 'uster';
	
	/*$_conf['db']['orafin']['driver'] 	= 'oci';
	$_conf['db']['orafin']['host'] 		= '10.10.12.214';
	$_conf['db']['orafin']['catalog']	= 'prod';
	$_conf['db']['orafin']['user']		= 'apps';
	$_conf['db']['orafin']['password']	= 'ebsapp01';*/

	$_conf['db']['orafin']['driver'] 	= 'oci';
	$_conf['db']['orafin']['host'] 		= '10.10.12.214';
	$_conf['db']['orafin']['catalog'] 	= 'prod';
	$_conf['db']['orafin']['user'] 		= 'simop';
	$_conf['db']['orafin']['password'] 	= 'simopprod';
	
	
	$_conf['db']['ora']['driver']       = 'oci';    
        $_conf['db']['ora']['host']         = '10.10.12.218';
        $_conf['db']['ora']['catalog']      = 'peldb';
        $_conf['db']['ora']['user']         = 'petikemas_cabang';
        $_conf['db']['ora']['password']     = 'peti218Cabang';
	
	$_conf['db']['lineos']['driver'] 	= 'oci';
	$_conf['db']['lineos']['host'] 		= '192.168.23.16';
	$_conf['db']['lineos']['catalog']	= 'ORCL';
	$_conf['db']['lineos']['user']		= 'LINEOS';
	$_conf['db']['lineos']['password']	= 'ligneOS';
	
	$_conf['db']['billing_obx']['driver'] 	= 'oci';
	$_conf['db']['billing_obx']['host'] 	= '192.168.23.16';
	$_conf['db']['billing_obx']['catalog']	= 'ORCL';
	$_conf['db']['billing_obx']['user']		= 'BILLING_OBX';
	$_conf['db']['billing_obx']['password']	= 'BILLING_OBX';
	
	$_conf['db']['pyma']['driver'] 		= 'oci';
	$_conf['db']['pyma']['host'] 		= '192.168.23.16';
	$_conf['db']['pyma']['catalog']		= 'ORCL';
	$_conf['db']['pyma']['user']		= 'BILLING';
	$_conf['db']['pyma']['password']	= 'BILLING';
	
	$_conf['db']['dbint']['driver'] 	= 'oci';
	//$_conf['db']['dbint']['host'] 	= 'ipc-domdb-scan.indonesiaport.co.id';
	$_conf['db']['dbint']['host']        = '10.10.32.161';
	$_conf['db']['dbint']['catalog']	= 'pnkdb';
	$_conf['db']['dbint']['user']		= 'opus_repo';
	$_conf['db']['dbint']['password']	= 'opus_repo';
	
	$_conf['db']['pnoadm']['driver'] 	= 'oci';
	//$_conf['db']['pnoadm']['host'] 	= 'ipc-domdb-scan.indonesiaport.co.id';
	$_conf['db']['pnoadm']['host']        = '10.10.32.161';
	$_conf['db']['pnoadm']['catalog']	= 'pnkdb';
	$_conf['db']['pnoadm']['user']		= 'pnoadm';
	$_conf['db']['pnoadm']['password']	= 'pnoadm';
	
	$_conf['db']['dbportal']['driver'] 	= 'oci';
	$_conf['db']['dbportal']['host'] 	= '192.168.23.15';
	$_conf['db']['dbportal']['catalog']	= 'dbpriok';
	$_conf['db']['dbportal']['user']	= 'DW_WPORTAL';
	$_conf['db']['dbportal']['password'] = 'w3bport4ldw';
	//-- framework system database..
	define('SYS_DB','default');
		
	//-- site..
	$_conf['site']['vhost'] 		= '/';
	$_conf['site']['home'] 			= 'http://'.$_SERVER['SERVER_NAME'].$_conf['site']['vhost'];
	$_conf['site']['webmaster'] 	= 'taufik@gunadarma.ac.id';
	$_conf['site']['base'] 			= realpath(str_replace('conf.php','',__FILE__));
	$_conf['site']['root'] 			= basename($_conf['site']['base'] . '../html/');
	$_conf['site']['main']			= 'main';
	$_conf['site']['doc_root']		= realpath($_conf['site']['base'] . '/../');
	$_conf['site']['enc_username'] 	= md5("USER_NAME_APLIKASI_TERENKRIPSI");
	$_conf['site']['upload']		= 'uploads/';
	$_conf['site']['uploaddir']		= realpath($_conf['site']['doc_root'] . '/' . $_conf['site']['upload']);	
	$_conf['site']['allowupload']	= true;
	$_SESSION['_siteconf']			= $_conf['site'];


	define('DEFAULT_TEMPLATE', 'shell.htm');

	//-- acl
	define('ACL_CACHEGI',true);
	define('ACL_SESSION',false);	## syarat untuk fungsi multiple login aktif 
	define('ACL_MULTI',true);		// 

		//ESB Config
	define('ESB_URL', "http://10.88.56.40:5556/restv2/accountReceivable/");
	define('ESB_USERNAME', "billing");
	define('ESB_PASSWORD', "b1Llin9");
	
//-- debugging..
	define("_DEVELOP_MODE", true);
	define("_STATIC_ERROR","static/maintenance.htm");
?>
