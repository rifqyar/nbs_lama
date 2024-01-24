<?php
$tl     = xliteTemplate('form.htm');
$filter = array(
    array(
        'name' => 'KEY'
    )
);
if (_map($filter)) {
    $tl->assign('formtitle', 'Form Edit Data');
    $tl->assign('formmode', 'Edit Data');
    $tl->assign('formurl', _link(array(
        'sub' => 'edit_post'
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
        $sql = "SELECT A.ID_NOTA, A.TGL_NOTA, A.ID_ORDER, TO_CHAR(A.TOTAL, '999,999,999,999') TOTALNYA, A.NO_JKM, TO_CHAR(A.TGL_JKM,'DD-MM-YYYY') TGL_JKM
				FROM OG_HNOTA2 A
				WHERE A.ID_NOTA='".$_q['KEY']."'";
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