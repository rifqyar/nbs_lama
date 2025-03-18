<?php
	$type = isset($block['attr']['type'])?$block['attr']['type']:'text';
	$name = isset($block['attr']['name'])?$block['attr']['name']:'';
	
	switch ($type) 
	{
	case 'date':
	case 'time':
	case 'datetime':
	case 'month':
	
		$vv = (isset($block['attr']['value']))?$block['attr']['value']:''; 
		$vv = preg_replace_callback('/\{[\$\!]([\w\_][\w\d\_]*(\.\$?[\w\d\_]+)*)\}/',
				'xlite_replace_cb',$vv);
		if (strpos($vv,'$')===0) {
			$block['text'] = '<'."?php \$this->renderDateTime('$type','$name',$vv ); ?".'>';
		} else
			$block['text'] = '<'."?php \$this->renderDateTime('$type','$name',\"$vv\"); ?".'>';
		$block['_skip_render_child'] = 1;			
	
		break;
		
	case 'checkbox':
		if (isset($block['attr']['flag'])) {
			$block['text'] = '<input ';
			foreach($block['attr'] as $k=>$v)
				if ($k=='flag') {
					$v = preg_replace_callback('/\{[\$\!]([\w\_][\w\d\_]*(\.\$?[\w\d\_]+)*)\}/',
							'xlite_replace_cb',
							'{$'.$v.'}');
					$block['text'] .= '<'."?php if (isset($v) && $v) echo(' checked=\"checked\" '); ?".'>';				
				} else {
					$block['text'] .= $k.'="'.htmlspecialchars($v).'" ';
				}	
			
			$block['text'] .= ' />';	
			$block['_skip_render_child'] = 1;			
			break;

		}
		
	default:
		
		$block['text'] = 
				'<input '.
				$this->generateAttribute($block) . 
				' />';	
		
		$block['_skip_render_child'] = 1;			
	}

?>