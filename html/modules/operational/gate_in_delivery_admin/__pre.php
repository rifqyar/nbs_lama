<?php
	if($_SESSION["PENGGUNA_ID"] == "")
	{
		header("location:".HOME."login");
	}

?>