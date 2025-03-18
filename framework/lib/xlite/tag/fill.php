<?php
	$cond = array();
	
	$src   = $block['attr']['src'];
	$var   = $block['attr']['var'];
	$empty = isset($block['attr']['emptyflag'])?$block['attr']['emptyflag']:false;
			
	$block['text'] = "<"."?php \$__ctr=0;\nforeach( \$$src as \$$var ): \n\$__ctr++; ?".">";		
	if (isset($block['_child'])) 
		$block['text'] .= $this->renderArray($block['_child']);	
		
	$block['text'] .= "<"."?php endforeach;";
	if ($empty) $block['text'] .= "\n\$$empty = count(\$$src)==0; ";
	$block['text'] .= " ?".">";
	
	$block['_skip_render_child'] = 1;
?>