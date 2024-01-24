<?php
if ($_SESSION["LOGGED_STORAGE"] == NULL) {
     exit();
}
outputRaw();



$tl = xliteTemplate("pbm.htm");

$tl->renderToScreen();



?>