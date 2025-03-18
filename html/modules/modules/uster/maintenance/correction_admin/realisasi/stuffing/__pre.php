<?php

if($_SESSION["LOGGED_STORAGE"] == NULL)
{
	header('Location: '.HOME .'login');		
}
if($_SESSION["ID_ROLE"] != 1 && $_SESSION["ID_ROLE"] != 41)
{
	header('Location: '.HOME .'login');		
}
?>