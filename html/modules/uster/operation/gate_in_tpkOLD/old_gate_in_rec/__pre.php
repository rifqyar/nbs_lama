<?php

if($_SESSION["LOGGED_STORAGE"] == NULL)
{
	header('Location: '.HOME .'login');		
}

?>