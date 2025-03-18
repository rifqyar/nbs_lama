<?php
	if($_SESSION["NAMA_PENGGUNA"] == "")
	{
		header("location:".HOME."login/");
	}

?>