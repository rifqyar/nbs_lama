<?php

$_conf['framework']['codename']	= 'supernova';
$_conf['framework']['version']	= '0.5';
$_conf['framework']['type']		= 'devel';


//-- database..

$_conf['db']['default']['driver'] 	= 'oci';
$_conf['db']['default']['host'] 	= '10.10.32.27';//'10.88.48.53';
//$_conf['db']['default']['host'] 	= '192.168.29.88';
$_conf['db']['default']['catalog']	= 'ESERVICEDB';//'pnkdbt';
$_conf['db']['default']['user']		= 'billing';
$_conf['db']['default']['password']	= 'billingpass';//'billing';

$_conf['db']['storage']['driver'] 	= 'oci';
//	$_conf['db']['storage']['host'] 	= '192.168.29.88';
$_conf['db']['storage']['host'] 	= '10.10.32.27';//'10.88.48.53';
$_conf['db']['storage']['catalog']	= 'ESERVICEDB';//'pnkdbt';
$_conf['db']['storage']['user']		= 'uster';//'USTER';
$_conf['db']['storage']['password']	= 'usterpass';//'uster';

/*$_conf['db']['invoice_dev']['driver']   = 'oci';
$_conf['db']['invoice_dev']['host']     = '10.10.33.85:1521';
$_conf['db']['invoice_dev']['catalog']  = 'ESERVICEDB';
$_conf['db']['invoice_dev']['user']     = 'INVOICE';
$_conf['db']['invoice_dev']['password'] = 'invoiceIPC';*/

$_conf['db']['sgbillingdev']['driver'] 	= 'oci';
$_conf['db']['sgbillingdev']['host'] 	= 'otm-dev.c0suqoyh0m8f.ap-southeast-1.rds.amazonaws.com';
$_conf['db']['sgbillingdev']['catalog']	= 'OTMdev';
$_conf['db']['sgbillingdev']['user']		= 'sgbillingdev';
$_conf['db']['sgbillingdev']['password']	= 'Ilcs1234';

/*
$_conf['db']['ora']['driver']       = 'oci';
$_conf['db']['ora']['host']         = '10.10.12.218';
$_conf['db']['ora']['catalog']      = 'peldb';
$_conf['db']['ora']['user']         = 'petikemas_cabang';
$_conf['db']['ora']['password']     = 'peti218Cabang';

$_conf['db']['pyma']['driver'] 		= 'oci';
$_conf['db']['pyma']['host'] 		= '192.168.23.16';
$_conf['db']['pyma']['catalog']		= 'ORCL';
$_conf['db']['pyma']['user']		= 'BILLING';
$_conf['db']['pyma']['password']	= 'BILLING';
*/

$_conf['db']['dbint']['driver'] 	= 'oci';
$_conf['db']['dbint']['host'] 	= '10.10.32.27';//'10.88.48.53';
//$_conf['db']['dbint']['host'] 	    = '192.168.29.88';
$_conf['db']['dbint']['catalog']	= 'ESERVICEDB';//'pnkdbt';
$_conf['db']['dbint']['user']		= 'opus_repo';//'opus_repo';
$_conf['db']['dbint']['password']	= 'opus_repopass';//'opus_repo';

//ADD ESB Config /*Fauzan*/ 19092019
///define('ESB_URL', "http://10.88.48.57:5555/restv2/accountReceivable/");
//define('ESB_URL_MATERAI', "http://10.88.48.57:5555/restv2/inquiryData/materai");
define('ESB_URL', "http://10.88.48.57:5555/restv2/");
define('ESB_USERNAME', "billing");
define('ESB_PASSWORD', "b1Llin9");
//end modif ESB

//-- framework system database..
define('SYS_DB','default');

//-- site..
//$_conf['site']['vhost'] 		= '/nbs_pnk_dev/';
$_conf['site']['vhost'] 		= '/ipc-nbs-opus-pontianak/';
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

//-- debugging..
define("_DEVELOP_MODE", true);
define("_STATIC_ERROR","static/maintenance.htm");


/* API PRAYA */
define('PRAYA_HOST', "https://billingdev.ilcs.co.id");
define('PRAYA_API_LOGIN', PRAYA_HOST.":8016");
define('PRAYA_API_MASTER', PRAYA_HOST.":8001");
define('PRAYA_API_RECEIVING', PRAYA_HOST.":8003");
define('PRAYA_API_DELIVERY', PRAYA_HOST.":8004");
define('PRAYA_API_PROFORMA', PRAYA_HOST.":8007");
define('PRAYA_API_LOADINGCANCEL', PRAYA_HOST.":8008");
define('PRAYA_API_PAYMENT', PRAYA_HOST.":8011");
define('PRAYA_API_TOS', PRAYA_HOST.":8013");
define('PRAYA_API_INTEGRATION', PRAYA_HOST.":8020");

define('PRAYA_ITPK_PNK_TERMINAL_ID', 622);
define('PRAYA_ITPK_PNK_PORT_CODE', 'IDPNK');
define('PRAYA_ITPK_PNK_ORG_ID', 2);
define('PRAYA_ITPK_PNK_ORG_CODE', 'ITPK');
define('PRAYA_ITPK_PNK_BRANCH_ID', 63);
define('PRAYA_ITPK_PNK_BRANCH_CODE', "05");
define('PRAYA_ITPK_PNK_TERMINAL_CODE', 'PNK');
define('PRAYA_ITPK_PNK_AREA_CODE', 1827);
define('PRAYA_ITPK_PNK_PASS_PRINT', "4c733");


?>