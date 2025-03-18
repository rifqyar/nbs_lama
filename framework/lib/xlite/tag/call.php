<?php
	$comp 	= $block['attr']['comp'];
	$param	= 'array(';
	
	if (isset($block['_child'])) {
		foreach($block['_child'] as $opt) {
			if ($opt['type']=='TAG' && $opt['tag']=='param') 
			{
				$v = $opt['attr']['value'];
				if (strpos($v,'$')>0) {
					$v = preg_replace_callback('/\{[\$\!]([\w\_][\w\d\_]*(\.\$?[\w\d\_]+)*)\}/',
						'xlite_replace_cb',$v);
				} else {
					$v = '"'. addslashes($v) .'"';
				}
				$param .= '"'.$opt['attr']['name'].'"=>'.$v.',';
			}
		}
	}	
	$block['text'] = "<"."?php \$this->call(\"$comp\",$param)); ?".">";

	$block['_skip_render_child'] = 1;
?>