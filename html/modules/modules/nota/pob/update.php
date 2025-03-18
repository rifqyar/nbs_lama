<?php
$tl     = xliteTemplate('update_jkm.htm');
$filter = array(
    array(
        'name' => 'KEY'
    )
);
if (_map($filter)) {
    $tl->assign('formtitle', 'Entry JKM');
    $tl->assign('formmode', 'Edit Data');
    $tl->assign('formurl', _link(array(
        'sub' => 'update_post'
    ), $_q));
    if (isset($_SESSION['__postback'][APPID])) {
        $tl->display('form');
        $_POST = $_SESSION['__postback'][APPID];
        $error = array();
        validatePostData($error);
        if (isset($error) && count($error) > 0) {
            val_translateError($error);
            $tl->assign('error', $error);
        }
        normalizePostData();
        unset($_SESSION['__postback'][APPID]);
        $row = $_POST;
        $tl->assign('row', $row);
    } else {
        $db  = getDB();
        $sql = "SELECT NO_NOTA,
               TO_CHAR(PENDAPATAN,'999,999,999,999') PENDAPATAN
               FROM POB_PENDAPATAN 
               WHERE NO_NOTA='".$_q['KEY']."'";
		$rs  = $db->query($sql);
        if ($rs && $row = $rs->FetchRow()) {
			//debug($row);die;
            $tl->display('form');
            $tl->assign('row', $row);
        } else {
            $tl->display('nodata');
        }
    }
} else {
    $tl->display('nodata');
}
$tl->renderToScreen();
?>
