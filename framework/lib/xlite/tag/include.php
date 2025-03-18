<?php
	$url 	= $block['attr']['href'];
	$fail   = isset($block['attr']['fail'])?$block['attr']['fail']:'err_include';	
	$iframe = isset($block['attr']['iframe'])?$block['attr']['iframe']:false;
	$fattr  = isset($block['attr']['attr'])?$block['attr']['attr']:'';
	
	if (strpos($url,'$')>0)
		$url = preg_replace_callback('/\{[\$\!]([\w\_][\w\d\_]*(\.\$?[\w\d\_]+)*)\}/',
				'xlite_replace_cb',$url);
	
	if ($iframe) {
		if (strpos($url,'$')==0) $url = '<'.'? echo '.$url.'; ?'.'>';

		$block['text'] = "<iframe $fattr src='$url'><a href='$url'>iframe not supported</a></iframe>";
	} else	
		$block['text'] = "<"."?php \$$fail = \$this->includeURL(\"$url\"); ?".">";

	$block['_skip_render_child'] = 1;
?>