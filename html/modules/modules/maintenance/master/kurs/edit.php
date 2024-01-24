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
		$arr_key = explode('|',$_q['KEY']);
        $db  = getDB();
        $sql = "SELECT TO_CHAR(START_DATE,'DD-MM-YYYY') START_DATE, TO_CHAR(END_DATE,'DD-MM-YYYY') END_DATE, KURS FROM TR_KURS WHERE START_DATE='$arr_key[0]' AND END_DATE='$arr_key[1]'";
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