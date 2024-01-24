<?php

if (!stristr($_SERVER['SCRIPT_FILENAME'],'example1.php')){
	$displayHome=true;
	include ('nav_thumbs.php');
	include ('navi.php');
}

?>