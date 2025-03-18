<?php
ob_start();
session_start();

########################
## Penyertaan file pustaka
##

include "framework/conf.php";
include "framework/_debug.php";
include "framework/_viewstate.php";
include "framework/init.php";
include "framework/router.php";
#include "framework/aclcheck.php";


########################
## template and output
##
if ( file_exists( MODULE_TEMPLATE_DIR . DEFAULT_TEMPLATE ) ) {	// gunakan module template jika ada
	$base_template = MODULE_TEMPLATE_DIR . DEFAULT_TEMPLATE;
} else if ( file_exists( SITE_TEMPLATE . DEFAULT_TEMPLATE ) ) { // gunakan site template sebagai default
	$base_template = SITE_TEMPLATE . DEFAULT_TEMPLATE;
} else {	
	$base_template = false;
}
if ($base_template)  {
	$_out = xliteTemplate( $base_template, '_absolute' );
}       

####################
##  filter & execute
##
if (file_exists(MODULE_DIR."__filter.php"))
	require_once( MODULE_DIR."__filter.php" );

$scripts = array( 
				SITE_MODULE."__pre.php",
				MODULE_DIR ."__init.php",
				MODULE_DIR ."__pre.php",				
				$script_name,
				MODULE_DIR ."__post.php",
				MODULE_DIR ."__tests.php",
				SITE_MODULE."__post.php",
			);		

$main_content = executeScript( $scripts );	

if ($base_template && !defined('RAW_OUTPUT') ) {
		/*
	   	$_acl = new ACL();
		$_acl->load();
		$li = $_acl->getLogin();
		$username = $li->info["realname"];
		*/
		$_menu    = xliteTemplate( SITE_TEMPLATE.'/menu.htm', '_absolute' );
		$_updater = xliteTemplate( SITE_TEMPLATE.'/updater.htm', '_absolute' );
		
		############################
		# ASSIGN MENU AND AUTHORIZATION
		
		if($_SESSION['ATURAN'] == "ADMIN")
		{
			//$_menu->display($li->menu?$li->menu:'MENU_ADMIN');		
			$_menu->display($li->menu?$li->menu:'MENU_GLOBAL');		
			$_out->assign('username', $username);
		}
		else if($_SESSION['ATURAN'] == "BILLING")
		{
			$_menu->display($li->menu?$li->menu:'MENU_GLOBAL');
			//$_menu->display($li->menu?$li->menu:'MENU_BILLING');			
			$_out->assign('username', $username);
		}
		else if($_SESSION['ATURAN'] == "KEUANGAN")
		{
			$_menu->display($li->menu?$li->menu:'MENU_GLOBAL');
			//$_menu->display($li->menu?$li->menu:'MENU_BILLING');			
			$_out->assign('username', $username);
		}
		else if($_SESSION['ATURAN'] == "PERALATAN TO2")
		{
			$_menu->display($li->menu?$li->menu:'MENU_GLOBAL');
			//$_menu->display($li->menu?$li->menu:'MENU_BILLING');			
			$_out->assign('username', $username);
		}
		else if($_SESSION['ATURAN'] == "RENDAL TO2")
		{
			$_menu->display($li->menu?$li->menu:'MENU_GLOBAL');
			//$_menu->display($li->menu?$li->menu:'MENU_BILLING');			
			$_out->assign('username', $username);
		}
		else if($_SESSION['ATURAN'] == "PEMASARAN TO3")
		{
			$_menu->display($li->menu?$li->menu:'MENU_GLOBAL');
			//$_menu->display($li->menu?$li->menu:'MENU_BILLING');			
			$_out->assign('username', $username);
		}
		else
		{
			$_menu->display($li->menu?$li->menu:'MENU_GLOBAL');		
			//$_menu->display($li->menu?$li->menu:'MENU_PUBLIC');		
			$_out->assign('username', $username);
		}
		
		
		############################
		# ASSIGN NOTIFICATION
		if( strtoupper($li->group[0])=='ADMINISTRATOR' )
			$_out->assign('updater', $_updater->render());
		
		$_out->assign('menu', $_menu->render());
		$_out->assign('muatan', $main_content);
		$_out->renderToScreen();
} else {
	if (defined('AJAX_OUTPUT')) {
		$_ajax->setContent($main_content);
		echo $_ajax->getJSON();
	} else {
		echo $main_content;

	}
}





###########
# tambahan ini setContent



?>