<?php
	$cond = array();
	
	if (isset($block['attr']['visible'])) {
		$cond[] =  preg_replace_callback('/\{[\$\!]([\w\_][\w\d\_]*(\.\$?[\w\d\_]+)*)\}/',
						'xlite_replace_cb',
						'{$'.$block['attr']['visible'].'}');		
	}
		
	if (isset($block['attr']['name']))
		$cond[] = "\$this->isBlockVisible('".$block['attr']['name']."')";
		
	$block['text'] = "<"."?php\tif (".implode(' && ',$cond)."): ?".">";
	if (isset($block['_child'])) 
		$block['text'] .= $this->renderArray($block['_child']);	
		
	$block['text'] .= "<"."?php\tendif; ?".">";
	
	$block['_skip_render_child'] = 1;
?>