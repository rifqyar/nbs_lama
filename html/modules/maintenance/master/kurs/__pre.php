<?php
if($_SESSION["NAMA_PENGGUNA"] == "")
{
	header("location:".HOME."login");
}

require_lib('validation.php');

function validatePostData($error) {
    val_checkNotEmpty($_POST, "START_DATE", $error);
    val_checkNotEmpty($_POST, "END_DATE", $error);	
	val_checkNotEmpty($_POST, "KURS", $error);
	
	//debug($error); die;
	return (count($error) == 0);
}

function normalizePostData() {
    /*$_POST["START_DATE"]	= substr($_POST["START_DATE"], 0, 35);
    $_POST["END_DATE"]	= substr($_POST["END_DATE"], 0, 2);
    $_POST["KURS"]	= substr($_POST["KURS"], 0, 35);*/
}

?> 
