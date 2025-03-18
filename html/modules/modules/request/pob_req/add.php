<?/*php

 $tl = xliteTemplate('add_edit.htm');
 $stat = "tambah" ;
 $tl->assign('status',$stat);
 $tl->renderToScreen();
*/
?>
<?php
$tl = xliteTemplate('add_edit.htm');
$tl->assign('formtitle', 'Tambah Request');
$tl->assign('formmode', 'Insert Data');
$tl->assign('formurl', _link(array(
    'sub' => 'add_post'
)));
$tl->display('form');

if (isset($_SESSION['__postback'][APPID])) {
    $_POST = $_SESSION['__postback'][APPID];
    $error = array();
	
	debug($_POST);
	
    validatePostData($error);
    if (isset($error) && count($error) > 0) {
        val_translateError($error);
        $tl->assign('error', $error);
    }
	debug($error);
	
	normalizePostData();
    unset($_SESSION['__postback'][APPID]);
    $row = $_POST;
	$tl->assign('row', $row);
	
} else {
	
    ##########################
    #  init default
    #
	/*$db = getDB();
	$sql ="select nvl(max(id_dummy),0)+1 AS IDMAX from DUMMY";
	$res = $db->query($sql)->fetchRow();
    $row["ID_DUMMY"]    = $res[IDMAX];*/
    $row["ID_DUMMY"]    = '--AUTO--';
}
$tl->assign('row', $row);
$tl->renderToScreen();
?>
