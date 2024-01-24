<?
/**********************************************************************************
  XLiteCompiler
        compile xlite template to native PHP code
           
***********************************************************************************/
class XLiteCompiler extends XLiteParser {
	
	function XLiteCompiler() {
		parent::XLiteParser();

		$this->__BUILDTIME = dechex(filemtime(__FILE__));
		
		## space omptimation
		$this->omit_blank 		= true;
		$this->trim_whitespace	= true;

		$this->SPECIAL_TAG = array( 
									'tab','param'
								  );

		foreach ($this->SPECIAL_TAG as $tag)
			$this->registerBlockHandler( $this, 'TAG',$tag );
		//	$this->registerBlockHandler( &$this, 'TAG',$tag );
	}	

	function isSpecialTag($tag) {
		if (in_array($tag,$this->SPECIAL_TAG)) return true;
		if (file_exists( FDIR_LIB.'xlite/tag/'.$tag.'.php' )) {
			// echo "will using ".FDIR_LIB.'xlite/tag/'.$tag.".php \n";
			return true;
		} else if (file_exists( SITE_LIB.'xlite/tag/'.$tag.'.php' )) {
			return true;
		}

		// echo "not exist! $tag\n";
		return false;
	}
		
	function processBlock( & $block ) {
		## resolve attribute for current session
		$__tempattr = isset( $block['attr'] )?$block['attr']:false;
		//$this->resolveAttribute( & $block );
		
		$type = strtolower($block['type']);
		$tag  = $block['tag'];
		$pppath = get_site_path('xlite/'.$type.'/'.$tag.'.php');
		if (file_exists( $pppath )) {
			//echo "#incluiding $pppath \n";
			include ( $pppath );
		} else {

			$pppath = get_lib_path('xlite/'.$type.'/'.$tag.'.php');
			if (file_exists( $pppath )) {
				//echo "#incluiding $pppath \n";
				include ( $pppath );
			}
			else if (!in_array($tag,$this->SPECIAL_TAG))
			{
				$block['text'] = "\n\n<"."?  die('compiler not exist: $type/$tag!'); ?". ">\n\n"; 
				$block['_skip_render_child'] = 1;
			}
		}		
		## restore..
		if ($__tempattr)
			$block['attr'] = $__tempattr;
	}
	
	function replace_value_cb($raw) {	
		//echo "==vcb==".var_export($raw,true)."<br />";
		return '<'.'?php echo('.$this->replace_cb($raw).'); ?'.'>';	
	}
	 
	 
	function replace_cb($raw) {					
		//echo "==cb===".var_export($raw,true)."<br />";	
		
		$varpath = $raw[1];
		$_VARS    = explode('.',$varpath);
		for ($i=0; $i<count($_VARS); $i++) {
			if ($i==0) {
				$res = '$'.$_VARS[$i];
			} else {
				$index = $_VARS[$i];							// associative array					
				
				if (!isset($res[$index])) {
					$res = false;
					break;
				}
				$res .= '["'.$index.'"]';
			}
		}
					
		return ($res===false)?BLANK_VALUE:$res; 
	}
	
	function generateAttribute( $block , $skip=false ) {
		if (!$skip) $skip = array();
		if (!is_array($skip)) $skip = array( $skip );
		
		$res = array();
		if (isset($block['attr'])) {
			xlite_cb_start( $this );
			foreach($block['attr'] as $attr=>$value) 
				if (!in_array($attr,$skip)) {
					$replaced = '';
					//echo "=f======". $value . "<br />";
					$replaced = preg_replace_callback(
									'/\{[\$\!]([\w\_][\w\d\_]*(\.\$?[\w\d\_]+)*)\}/',
									'xlite_replace_value_cb',
									$value);				
					//echo "=t=========". $replaced . "<br />";
					$res[] = $attr . '="' . $replaced . '"';
				}  		
				
			xlite_cb_end();
		}
		
		return implode(' ',$res);
	}
}
?>