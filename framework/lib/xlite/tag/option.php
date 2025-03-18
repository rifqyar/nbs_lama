<?php
	$block['text'] = '<option '.
					 $this->generateAttribute($block) .
					 '>';
	if (isset($block['_child'])) 			
		$block['text'] .= $this->renderArray( & $block['_child'] );
	$block['text'] .= '</option>';
	$block['_skip_render_child'] = 1;			

?>