<?php
if ($_SESSION["LOGGED_STORAGE"] == NULL) {
     exit();
}
outputRaw();



$tl = xliteTemplate("view.htm");

$tl->renderToScreen();



?>