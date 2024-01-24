<?php

switch (SUBID) {

   case 'edit_ok':
		if (isset($_SESSION['__uriback'][APPID])) {

           $newuri = $_SESSION['__uriback'][APPID];

           unset($_SESSION['__uriback'][APPID]);

           header('Location: '.$newuri.'&_editok=1');

           ob_end_flush();

           die();

           break;

       }
   case 'add_ok':

       if (isset($_SESSION['__uriback'][APPID])) {

           $newuri = $_SESSION['__uriback'][APPID];

           unset($_SESSION['__uriback'][APPID]);

           header('Location: '.$newuri.'&_saveok=1');

           ob_end_flush();

           die();

           break;

       }
	 case 'delete_ok':

       if (isset($_SESSION['__uriback'][APPID])) {

           $newuri = $_SESSION['__uriback'][APPID];

           unset($_SESSION['__uriback'][APPID]);

           header('Location: '.$newuri.'&_deleteok=1');

           ob_end_flush();

           die();

           break;

       }




   default:
       $tl = xliteTemplate('grid.htm');
       $tl->renderToScreen();

}

?>
