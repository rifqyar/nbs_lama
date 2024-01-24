<?php
	if (isset($_ALT_INDEX)) $_ALT_INDEX++; else $_ALT_INDEX = 0;
	 
	$list 		= $block['attr']['list'];
	$var		= $block['attr']['var'];
	$delimiter	= isset($block['attr']['delimiter'])?$block['attr']['delimiter']:',';
			
	$block['text'] = '<'."?php \$_ALT_V[$_ALT_INDEX] = explode('$delimiter',\"$list\"); \$_ALT_I[$_ALT_INDEX]  = (isset(\$_ALT_I[$_ALT_INDEX])?++\$_ALT_I[$_ALT_INDEX]:0) % count(\$_ALT_V[$_ALT_INDEX]); \$$var = \$_ALT_V[$_ALT_INDEX][\$_ALT_I[$_ALT_INDEX]]; ?".'>';		
	$block['_skip_render_child'] = 1;
?>