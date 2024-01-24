<?
/**********************************************************************************
  XLiteHTML
  	 XLite based html-oriented template parser
     (c) 2008 adi gita saputra
     
     
  changes:
   - 20081007 22:14:
        - fixed bug: attribut evaluation
   		- implements select,option,input
   		- add block flag _skip_render_child
   		 
   - 20081006 19:53:
   		- fixed fill tag. add emptyflag optional attribute
   		
   - 20081006 01:18:
        - implements block,fill,include,alt
        - added findBlock()
        - added display()
        
           
***********************************************************************************/
 
 
class XLiteHTML extends XLiteParser {
	
	function XLiteHTML() {
		parent::XLiteParser();

		$this->__BUILDTIME = dechex(filemtime(__FILE__));
		
		## space omptimation
		$this->omit_blank 		= false;
		$this->trim_whitespace	= false;

		$this->SPECIAL_TAG = array( 
									'block','fill','capture','include','alt',
									'select','input','option','richedit','tab'
								  );

		foreach ($this->SPECIAL_TAG as $tag)
			$this->registerBlockHandler( & $this, 'TAG',$tag );
	}	

	function & findBlock( & $blocks, $name ) {
		for ($i=0;$i<count($blocks);$i++) {
			if ($blocks[$i]['type']=='TAG' &&
				$blocks[$i]['tag']=='block' &&
				$blocks[$i]['attr']['name']==$name ) {
				return  $blocks[$i];		
			}
			
			
			if (isset($blocks[$i]['_child'])) {
				$temp = & $this->findBlock( & $blocks[$i]['_child'], $name );
				if ($temp) return $temp;
			}
		}
		
		$q = false;
		return $q;
	}
	
	function display( $blockname , $visible=true) {
		$o = & $this->findBlock( & $this->_TREE, $blockname );
		if ($o) $o['attr']['visible'] = ($visible)? 1:0;
	}
		
	function processBlock( & $block ) {
	
		## resolve attribute for current session
		$__tempattr = isset( $block['attr'] )?$block['attr']:false;
		$this->resolveAttribute( & $block );
		
		
		if ($block['type']=='TAG') {
			$tag = $block['tag'];
			
			$blockprocessor = get_lib_path('xlite/tag/'.$tag.'.php');
			 
			if (file_exists( $blockprocessor ))
				require $blockprocessor;
			else 
				die('XLITE: unresolved tag processor:'.$tag);
		} 
		
		## restore..
		if ($__tempattr)
			$block['attr'] = $__tempattr;
	}

}
?>