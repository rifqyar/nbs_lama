<?php
	if($_SESSION["ID_USER"] == "")
	{
		header("location:".HOME."login");
	}

?>