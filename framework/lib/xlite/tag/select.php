<?php
	$list 		= isset($block['attr']['list'])?$block['attr']['list']:'false';
	$key		= isset($block['attr']['key'])? $block['attr']['key']:0;
	$label		= isset($block['attr']['label'])? $block['attr']['label']:1;
	$sel		= isset($block['attr']['selected'])? $block['attr']['selected']:'false';

	$block['text'] = '<select '.
					 $this->generateAttribute($block,array('list','key','label','selected')) .
					 '>';
	
	## insert list..
	$options  = array();
	
	if (isset($block['_child'])) {
		foreach($block['_child'] as $opt)
			if ($opt['type']=='TAG' && $opt['tag']=='option') {
				//var_export($opt);
				if (isset($opt['_child'])) $text = $this->renderArray( $opt['_child'] ); else $text='';
				$options[] = array(
								'value'=>isset($opt['attr']['value'])?$opt['attr']['value']:$text,
								'label'=>$text
								);
			}
	}
		
	$list = preg_replace_callback('/\{[\$\!]([\w\_][\w\d\_]*(\.\$?[\w\d\_]+)*)\}/',
								 'xlite_replace_cb',
								 '{$'.$list.'}');
	$sel = preg_replace_callback('/\{[\$\!]([\w\_][\w\d\_]*(\.\$?[\w\d\_]+)*)\}/',
								 'xlite_replace_cb',
								 '{$'.$sel.'}');	
	
								 
	$block['text'] .= '<'."?php \$this->renderSelectOptions(".var_export($options,true).",$sel,$list,'$key','$label'); ?".'></select>';
	$block['_skip_render_child'] = 1;	
?>