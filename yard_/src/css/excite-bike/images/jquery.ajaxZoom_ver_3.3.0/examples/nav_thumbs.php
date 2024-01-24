<?php
	$arrayNavi = array();
	for ($i=1; $i<=30; $i++){
		if (file_exists('example'.$i.'.php')){
			$arrayNavi[$i]='example'.$i.'.php';
		}else{
			break;
		}
	}
	
	$current = intval(str_replace(array('example','.php'),'',basename($_SERVER['PHP_SELF'])));
	
	if ($current != 22){
		echo '<DIV style="width: 125px; clear: both; background-color: #000000; padding: 5px 5px 0px 5px; font-size: 100%; color: #FFFFFF; text-align: center">MORE EXAMPLES</DIV>';
	}
	echo "<DIV style='clear: both; height: 120px; background-color: #000000; border-top: #000000 1px solid; overflow: hidden; overflow-x: scroll; margin-bottom: 0px; '><table cellspacing='0' cellpadding='0' style='padding: 0px; margin: 0px; line-height: 0px;'><tr>";
	foreach ($arrayNavi as $k=>$v){
		echo '<td><DIV style="margin: 5px 8px 0px '.(($k==1) ? 5 : 0).'px; background-color: '.(($current == $k) ? 'red' : '#FFFFFF').'; padding: 3px;"><a href="'.$v.'"><img class="demoImage" src="http://www.ajax-zoom.com/pic/layout/image-zoom_'.$k.'.jpg" width="150" height="84" border="0" alt="javascript picture zoom"></a></DIV></td>';
	}
	echo "</tr></table></DIV>";

?>