<?php
	/*if($_SESSION["PENGGUNA_ID"] == "")
	{
		header("location:".HOME."login");
	}*/

require_lib('validation.php');

function validatePostData($error) {
    //val_checkNotEmpty($_POST, "NO_JKM", $error);
	
	//debug($error); die;
	return (count($error) == 0);
}

function normalizePostData() {
    //$_POST["NO_JKM"]	= substr($_POST["NO_JKM"], 0, 35);
}
?>
