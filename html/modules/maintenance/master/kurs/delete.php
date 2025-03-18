<?php
$tl     = xliteTemplate('form.htm');
$filter = array(
    array(
        'name' => 'ID_DUMMY'
    )
);
if (_map($filter)) {
    $tl->assign('formtitle', 'Form Delete Data');
    $tl->assign('formmode', 'Delete Data');
    $tl->assign('formurl', _link(array(
        'sub' => 'delete_post'
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
		header('Location: ' . _link(array(
            'sub' => 'delete_post'
        ), $_q));
        ob_end_flush();
        die();
    }
} else {
    $tl->display('nodata');
}
$tl->renderToScreen();
?>