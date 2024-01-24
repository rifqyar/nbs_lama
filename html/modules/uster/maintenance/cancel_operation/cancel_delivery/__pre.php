<?php 

if($_SESSION["LOGGED_STORAGE"] == NULL)
{
	header('Location: '.HOME .'login');		
}
if($_SESSION["ID_GROUP"] != 'J' && $_SESSION["ID_GROUP"] != 'P')
{
	header('Location: '.HOME .'login');		
}

?>