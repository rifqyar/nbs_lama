<?php
	$mode 		   	= isset($block['attr']['mode'])?$block['attr']['mode']:'html';
	$set 		   	= isset($block['attr']['toolbar'])?$block['attr']['toolbar']:'';
	$block['text'] 	= '';
	
	## select child..
	if (isset($block['_child'])) {			
		if ($mode=='html') {
			## use fckeditor..
			require_lib('fckeditor.php');
			
			$editor	= array();
			
			foreach ($block['_child'] as $bc) {
				if (   $bc['type']=='TAG' 
					&& $bc['tag']=='tab' ) 
				{
					$name  = $bc['attr']['name'];
					$label = isset($bc['attr']['label'])?$bc['attr']['label']:$bc['attr']['name'];
					$text  = isset($bc['_child'])?stripslashes($this->renderArray($bc['_child'])):'';
					
					$fck   = new FCKEditor($name);
					$fck->BasePath = HOME.'plugins/fckeditor/';
					$fck->Config = array( 'BaseHref'=>HOME );
					$fck->Value = $text;
					
					$labels[] = $label;
					$editor[] = $fck;
				}
			}
			
			## assign to text prop..
			$str = '';
			if (count($editor)>1) {
				$tb_id = 'tb'.rand(1000,9999);
				require_lib('ctabbed.php');

				$tp = new TabbedPane('tab_'.rand(1000,9999));
				for ($ii=0;$ii<count($editor);$ii++) {
					$tp->add($ii,$labels[$ii],$editor[$ii]->CreateHtml(strpos($editor[$ii]->Value,'<'.'?')===false));
				}
				$str .= $tp->render();
				
			} else {
				$str = $editor[0]->CreateHtml( strpos($editor[0]->Value,'<'.'?')===false );
				
			}
			unset($editor);
			
			$block['text'] = $str;
			
		} else 	if ($mode=='code') {			
			## use editarea..
			require_lib('editarea.php');
			
			$editor	= array();
			
			foreach ($block['_child'] as $bc) {
				if (   $bc['type']=='TAG' 
					&& $bc['tag']=='tab' ) 
				{
					$name  = $bc['attr']['name'];
					$label = isset($bc['attr']['label'])?$bc['attr']['label']:$bc['attr']['name'];
					$text  = isset($bc['_child'])?stripslashes($this->renderArray($bc['_child'])):'';
					
					$edit   = new EditArea($name,$text);
												
					$labels[] = $label;
					$editor[] = $edit;
				}
			}
			
			## assign to text prop..
			$str = '';
			if (count($editor)>1) {
				$tb_id = 'tb'.rand(1000,9999);
				require_lib('ctabbed.php');

				$tp = new TabbedPane('tab_'.rand(1000,9999));
				for ($ii=0;$ii<count($editor);$ii++) {
					$tp->add($ii,$labels[$ii],$editor[$ii]->getHtml(strpos($editor[$ii]->value,'<'.'?')===false));
				}
				$str .= $tp->render();	
			} else {
				$str = $editor[0]->getHtml( strpos($editor[0]->value,'<'.'?')===false );
			}
			unset($editor);			
			$block['text'] = $str;
			
		} else {
			$block['text'] = 'unknown editor: '.$mode;
		}
	}
	
	$block['_skip_render_child'] = 1;	
?>