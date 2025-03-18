<?php
$__EDITAREA_JS_INSERT = 0;

class EditArea {
	var $config = array();
	var $id;
	var $value;
	var $path;
	var $lang = 'id';
	var $syntax = '';
	var $syntax_selection = 'css,html,js,php,python,vb,xml,c,cpp,sql,basic,pas';
	
	function EditArea($id,$value='') {
		$this->id 		= $id;
		$this->value	= $value;
		$this->path		= 'plugins/editarea/';
		
		$this->config['start_highlight'] = 'false';
		$this->config['allow_resize'] = 'y';		
		$this->config['toolbar'] = 'search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, help';
		$this->config['textarea_style'] = 'height: 300px; width: 100%;';
	}
	
	function getHtml($encodevalue=false) {
		global $__EDITAREA_JS_INSERT;
		
		$res = '';
		
		if (!isset($this->config['id'])) 
			$this->config['id'] 				= $this->id;
		if (!isset($this->config['path'])) 
			$this->config['path']				= $this->path;
		if (!isset($this->config['lang'])) 
			$this->config['lang'] 				= $this->lang;
		if (!isset($this->config['syntax'])) 
			$this->config['syntax']				= $this->syntax;
		if (!isset($this->config['syntax_selection'])) 
			$this->config['syntax_selection']	= $this->syntax_selection;
		
		if ($encodevalue)
			$value = htmlspecialchars( $this->value ) ;
		else
			$value = str_replace('<'.'?php echo','<'.'?php echo htmlspecialchars', $this->value) ;
			
		if ($__EDITAREA_JS_INSERT==0) {
			$res .= '<script language="Javascript" type="text/javascript" src="'.$this->path.'edit_area_full_with_plugins.js"></script>';
			$__EDITAREA_JS_INSERT++;
		}
		
		$res .= '<script language="Javascript" type="text/javascript">editAreaLoader.init({';
		$fields = array();
		foreach ($this->config as $k=>$v) {
			$fields[] = $k.':"'.$v.'"';
		}
		$res .= implode(',',$fields);
		$res .= '});</script>';
		
		$res .= '<textarea id="'.$this->config['id'].'" style="'.$this->config['textarea_style'].'" name="'.
				$this->config['id'].'">'.
				$value.'</textarea>';
		
		return $res;
	}
}
?>