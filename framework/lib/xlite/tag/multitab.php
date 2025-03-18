<?php
	$name 		   	= isset($block['attr']['name'])?$block['attr']['name']:'multitab_'.rand(1000,9999);
	$width			= isset($block['attr']['width'])?$block['attr']['width']:'100%';
	$height			= isset($block['attr']['height'])?$block['attr']['height']:'';	
	$src 			= isset($block['attr']['src'])?$block['attr']['src']:false;
	$style			= isset($block['attr']['style'])?$block['attr']['style']:'';
	$panelstyle		= isset($block['attr']['panelstyle'])?$block['attr']['panelstyle']:'border: solid 1px #cca;';

	## select child..
	if (isset($block['_child'])) {
		$tabs	= array();
		
		foreach ($block['_child'] as $bc) {
			if (   $bc['type']=='TAG' 
				&& $bc['tag']=='tab' ) 
			{
				$tname  = isset($bc['attr']['name'])?$bc['attr']['name']:'Tab_'.count($tabs);
				$label  = isset($bc['attr']['label'])?$bc['attr']['label']:$name;
				$text   = isset($bc['_child'])?stripslashes($this->renderArray($bc['_child'])):'';
				
				if (isset($bc['attr']['visible'])) {
					$cond =  preg_replace_callback('/\{[\$\!]([\w\_][\w\d\_]*(\.\$?[\w\d\_]+)*)\}/',
								'xlite_replace_cb',
								'$'.$bc['attr']['visible']);		
				} else {
					$cond = true;
				}	
								
				$tabs[] = array($tname, $label, $text, $cond);
			}
		}
				
		$res = 	'<'."?php require_lib('ctabbed.php');\n".
				'$__ct = new TabbedPane("'.$name.'","'.$width.'","'.$height.'","'.$style.'","'.$panelstyle.'");';
		
		$html='<'.'?php $__panename = "temppane_".rand(10000,99999)."_"; ?'.">\n";
		foreach ($tabs as $tab) {
			$res  .= "if (".$tab[3].") ".'$__ct->addReff("'.$tab[0].'","'.$tab[1].'",$__panename."'.$tab[0].'");'."\n";
			$html .= "<"."?php\tif (".$tab[3]."): ?"."><div id='<".'?php echo($__panename."'.$tab[0].'"); ?'.">' style='visibility:hidden; display:none; ' >".$tab[2]."</div>\n<"."?php\tendif; ?".">";
		}
		
		if ($src) {
			$src = preg_replace_callback('/\{[\$\!]([\w\_][\w\d\_]*(\.\$?[\w\d\_]+)*)\}/',
										 'xlite_replace_cb',
										 '{$'.$src.'}');
										 
			$res .= 'foreach('.$src.' as $_tabdata) '.
					'  $__ct->add($_tabdata[0],$_tabdata[1],$_tabdata[2]);';
		}	
		
		$res .= ' echo $__ct->render();  ?'.'>';
									
		$block['text'] = $html.$res;	
	}
	
	$block['_skip_render_child'] = 1;	
?>