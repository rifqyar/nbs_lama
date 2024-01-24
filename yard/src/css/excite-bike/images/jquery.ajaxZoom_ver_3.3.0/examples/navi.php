<?php

	$pathNumber = str_replace('example','',basename($_SERVER['PHP_SELF'],'.php'));
	$prevFile = file_exists('example'.($pathNumber-1).'.php');
	$nextFile = file_exists('example'.($pathNumber+1).'.php');

	if ($prevFile){
		$prevButton = "<a href='".'example'.($pathNumber-1).'.php'."'><img src='../axZm/icons/previous-icon-48.png' border='0' width='48' height='48' alt='previous example' title='previous example'></a>";
	}else{
		$prevButton = "<img src='../axZm/icons/previous-icon-48-disabled.png' border='0' width='48' height='48'>";
	}
	
	if ($nextFile){
		$nextButton = "<a href='".'example'.($pathNumber+1).'.php'."'><img src='../axZm/icons/next-icon-48.png' border='0' width='48' height='48' alt='next example' title='next example'></a>";
	}else{
		$nextButton = "<img src='../axZm/icons/next-icon-48-disabled.png' border='0' width='48' height='48'>";
	}
	
	if (!isset($displayHome)){
		$homeButton = "<a href='http://www.ajax-zoom.com/index.php?cid=examples'><img src='../axZm/icons/home-icon.png' border='0' width='48' height='48' style='margin-left:5px' alt='Home' title='Home'></a>";
	}else{
		$homeButton = '';
	}
	
	echo "<DIV style='clear: both; height: 58px; background-color: #808080; width: 100%;'><DIV style='float: left; width: 106px; padding: 5px 0px 0px 5px'>$prevButton$homeButton</DIV><DIV style='float: right; width: 58px; padding: 5px 5px 0px 5px'>$nextButton</DIV></DIV>";

?>