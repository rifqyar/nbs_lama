<?php
define('BLANK_VALUE','');

/**********************************************************************************
  XLite
     powerful pluggable template parser
     (c) 2008 adi gita saputra
     
  todo:
     - dynamic block creation			* not needed!
     - active template generator        <-- beat smarty!
  

  changes:
   - 20081007 22:17:
        - fixed bug: attribute evaluation. added resolveAttribute
   		- add renderArray() utilized by render() and custom tag processing
   		
   - 20081006 01:12:
        - tag attribute evaluated
        - implements block handler
        - implements __hidden property for block
        
   - 20081005 18:55:
   		- fixed cache timestamp. added subclass modification time as factor
   		
   - 20081004 11:46:
        - advanced variable replacement. support simple and multidimensional array
        - cache mark based on template path & modification time, library modification time
        - changed to crc32 for template path hash                   
                
   - 20081002 23:06:   
   		- tested on big file (700+ KB)
   		- cached parsing using single directory
   		- very fast cached parsing
   		- XLiteHTML as subclass of XLiteParser
   		- parse_2() for block optimation (mainly, TEXT and non-special TAG)
   		- reduced result by strip whitespaces on TEXT and blank block
        - hierarchical test
        
***********************************************************************************/
   		

define('XLITE_READ_AHEAD',15);


######################################
#  
#  XLite replace callback
#
function xlite_replace_cb($match) {
	$n = count($GLOBALS['xlite']['cb']);
	if ($n==0) die('no callback');
		
	return $GLOBALS['xlite']['cb'][$n-1]->replace_cb($match);
}

function xlite_replace_value_cb($match) {
	$n = count($GLOBALS['xlite']['cbv']);
	if ($n==0) die('no callback');
		
	return $GLOBALS['xlite']['cbv'][$n-1]->replace_value_cb($match);
}

function xlite_cb_start( & $obj ) {
	$GLOBALS['xlite']['cb'][] = & $obj;	
	$GLOBALS['xlite']['cbv'][] = & $obj;	
	$GLOBALS['xlite']['var'][] = array();	
}

function xlite_cb_end() {
	array_pop( $GLOBALS['xlite']['cb'] );
	array_pop( $GLOBALS['xlite']['cbv'] );
	array_pop( $GLOBALS['xlite']['var'] );
}
#
######################################


class XLiteParser {
	var $__BUILDTIME;

	var $_RAW;
	var $_HEAD		= 0;

	var $_FILENAME  = '';
	
	var $_PARSER;
	var $_TAGS;
	var $_MARK;
	
	var $SPECIAL_TAG	= array();
	
	var $_TAGLIST;
	var $_TREE;
	
	var	$_HEAD_TREE;
	var	$_TAG_STACK;
	
	var $omit_blank 		= false;
	var $trim_whitespace	= false;

	var $BLOCK_CUSTOM_HANDLER;	
	var $_VARS;

		
	function XLiteParser() {
		$this->__BUILDTIME = dechex(filemtime(__FILE__));
		
		$this->_PARSER = array();
		$this->__initTags();	

		$this->registerParser('TAG','parseTagList','XLiteParser');
		$this->registerParser('CTAG','parseTagList','XLiteParser');
		$this->registerParser('TEXT','parseTagList','XLiteParser');
	}
	
	function __initTags() {							
		$this->_MARK	= array(
							'CTAG'		=>	array(
												'START'	=> '/^<\//',
												'END'	=> '/^\>/',
												),
											
							'COMMENT'	=>	array(
												'START'	=>'/^<!--/',
												'END'	=>'/^-->/'
											),
											
							'PHP'		=>	array(
												'START'	=> array(
																	'/^\<\?php\s/',
																	'/^\<\?\s/'
																),
												'END'	=> '/^\?\>/'
											),

							'SCRIPT'	=>	array(
												'START'	=> '/^<script/i',
												'END'	=> '/^<\/script\>/i'
											),

							'STYLE'		=>	array(
												'START'	=> '/^<style/i',
												'END'	=> '/^<\/style\>/i'
											),

							'TAG'		=>	array(
												'START'	=> '/^</',
												'END'	=> array(
																	'/^\>/',
																	'/^\/\>/'
																)
											),
							);
	}
	
	/*
	function isSpecialTag($tag) {
		if (in_array($tag,$this->SPECIAL_TAG)) return true;
		return false;
	}
	*/
	
	function registerParser($type,$method,$class=false) {
		if ($class) { 
			$this->_PARSER[$type][] = array($class,$method);
		} else {
			$this->_PARSER[$type][] = $method;
		}	
	}
	
	function load( $data, $fromfile=false ) {
		if ($fromfile) {
			$this->_FILENAME = $data;
			$data = file_get_contents($data);
		}	
		
		$this->_RAW 	= $data;
		$this->_HEAD	= 0;
	}

	function loadSerialized( $data, $fromfile=false ) {
		if ($fromfile) {
			$this->_FILENAME = $data;
			$data = file_get_contents($data);
		}	
		
		$this->_TREE 	= unserialize( $data );
	}
	
	function saveSerialized( $fname ) {
		file_put_contents( $fname, serialize( $this->_TREE ) );	
	}
	
	function hasNext() {
		return 	($this->_HEAD < strlen($this->_RAW));
	}
	
	function readNext() {
		if ($this->hasNext()) {
			return $this->_RAW[ $this->_HEAD++ ];
			$this->_HEAD++;
			return true;
		}	
		
		return false;
	}		
	
	function match($pattern,$text,$offset) {
		if (!is_array($pattern)) 
			{
				$pattern = array( $pattern );
			}	

		$test_case = substr($text,$offset, XLITE_READ_AHEAD);		
		foreach($pattern as $pat) 
			{
				$found = preg_match($pat,$test_case,$matches,PREG_OFFSET_CAPTURE);
				if ($found==1) 
					{
						return $matches[0][0];
					}
			}

		return false;
	}
	
	function readUntil($mark) {
		if (!is_array($mark)) 
			{
				$mark = array( $mark );
			}
		
		$buff = '';
		while ( 
				$this->hasNext() &&
				$this->match($mark, $this->_RAW, $this->_HEAD)==false 
			  ) 
			{
				$buff .= $this->readNext();	
			}
		
		return $buff;
	} 
	
	function parse_0() {

		## init
		$this->_TAGLIST	= array();
		$textbuff  		= '';
			
		## populate start tag..
		$start_tag = array();
		foreach($this->_MARK as $mark) 
			{
				if (is_array($mark['START']))
					$start_tag 	 = array_merge($start_tag,$mark['START']);
				else
					$start_tag[] = $mark['START'];			
			}

		## scan tag..
		while ($this->hasNext()) 
			{
				$textbuff = $this->readUntil($start_tag);
				if ($textbuff!='') 	
					{				// got text node..
						if (!(trim($textbuff)=='' && $this->omit_blank))
							{								
								$this->_TAGLIST[] = array(
														'type'=> 'TEXT',
														'text'=> $textbuff
														);
							}
							
						$textbuff = '';	// clear..
					}
							
					
				## check tag..
				foreach($this->_MARK as $type=>$mark) 
					{			
						## check start tag..
						if ( $prefix=$this->match($mark['START'],$this->_RAW,$this->_HEAD) ) 
							{
								$this->_HEAD += strlen($prefix);
								$body  = $this->readUntil($mark['END']);									
								$sufix = $this->match($mark['END'],$this->_RAW,$this->_HEAD);
								$this->_HEAD += strlen($sufix);
								
								switch($type) {
									case 'TAG':
										$parts = preg_split('/\s+/',$body,2);
										$short = ($sufix=='/>')?1:0;
										$tag   = $parts[0];
										
										if ($this->isSpecialTag($tag)) 
										{
											// echo "setting $tag as special\n";
											$attrs = isset($parts[1])?$parts[1]:0;
	
											## parsing attributes..
											if ($attrs) {
												$num = preg_match_all(
															'/\s*([\w\d]+)\s*(=\s*((\'([^\']*)\')|(\"([^\"]*)\")|([^\'\"\s]+)))?/',
															$attrs,
															$matches,
															PREG_SET_ORDER);
												$attrs = array();
												if ($num) {
													foreach($matches as $match) {
														switch (count($match)) {
															case 2:	// name only..
																$attrs[$match[1]] = '1';
																break;
															case 6:	// single quote..
																$attrs[$match[1]] = $match[5];
																break;
															case 8:	// double quote..
																$attrs[$match[1]] = $match[7];
																break;
															case 9:	// no quote..
																$attrs[$match[1]] = $match[8];
																break;
														}
													}
												}
											}
											
											$this->_TAGLIST[] = array(
																	'type'  => 'TAG',
																	'tag'   => $tag,
																	'short' => $short,
																	'attr'	=> $attrs,
																	'_raw'  => $prefix.$body.$sufix
																);
										} else {
											$this->_TAGLIST[] = array(
																	'type'  => 'TAG',
																	'tag'   => $tag,
																	'short' => $short,
																	'_raw'  => $prefix.$body.$sufix
																);											
										}
										break;
										
									case 'CTAG':
										$parts = preg_split('/\s+/',$body,2);
										$tag   = $parts[0];

										$this->_TAGLIST[] = array(
																'type'  => 'CTAG',
																'tag'   => $tag,
																'_raw'  => $prefix.$body.$sufix
															);
										break;
										
									case 'COMMENT':
										$this->_TAGLIST[] = array(
																'type'  => $type,
																'body'  => $body,
																'_raw'  => $prefix.$body.$sufix
															);
										break;
										
									default:
										$this->_TAGLIST[] = array(
																'type'  => $type,
																'_raw'  => $prefix.$body.$sufix
															);
										
								} // switch
								
								break;	// break for
							} // if	
					} // foreach	
			} //-- while
			
		
		$_RAW = null;  // clear;
	} //-- end parse
	

	###############
	#  parse_1:
	#
	#  creating tree..
	#	
	function parse_1() {
		$this->_TAG_STACK = array();
		$this->_HEAD_TREE = 0;
		$this->_TREE = array();
		
		while($this->hasNextTag()) 
			{
				$skip 	 = false;
				$tag 	 = & $this->readNextTag();
				$tagtype = $tag['type'];
				
				if (isset($this->_PARSER[$tagtype])) 
					{
						foreach ($this->_PARSER[$tagtype] as $parser) 
							{
								$skip = call_user_func( $parser, 
												array( &$this, $tagtype, &$tag ) 
											   );		
							}
					} 
					
				if (!$skip) 
					{
						if ($this->getTagStackSize()==0) { 
							$this->_TREE[] = & $tag;
						} else {
							$this->_TAG_STACK[$this->getTagStackSize()-1]['_child'][] = & $tag;
						}

					} 
			}
							
			if ($this->getTagStackSize()>0) {
				print_r( $this->_TAG_STACK );
				die( "WARNING: tag stack not empty!\n" );
			}
				
		$this->_TAGLIST = null;	// clear;
	}
	
	function hasNextTag() {
		return ($this->_HEAD_TREE < count($this->_TAGLIST));
	}
	
	function &readNextTag() {
		if (!$this->hasNextTag()) return false;	
		
		return $this->_TAGLIST[$this->_HEAD_TREE++];
	}

	function pushTag( &$tag ) {
		$this->_TAG_STACK[] = & $tag;
	}
	
	function &popTag() {
		if ($this->getTagStackSize()==0) {
			$res = false;
		} else {
			$res = &$this->_TAG_STACK[count($this->_TAG_STACK)-1];
			array_pop( $this->_TAG_STACK );
		}
		
		return $res;	
	}

	function &peekTag() {
		if ($this->getTagStackSize()==0) {
			$res = false;
		} else {
			$res = &$this->_TAG_STACK[count($this->_TAG_STACK)-1];
		}
		
		return $res;	
	}
	
	function getTagStackSize() {
		return count($this->_TAG_STACK);
	}
		
	function parseTagList( $arg ) {
		$xl   = &$arg[0];
		$type = $arg[1];
		$tag  = &$arg[2];
		
		switch ($type) {
			case 'TEXT':
				return XLiteParser::parseTEXT( $xl, $type, $tag );
			case 'TAG':
				return XLiteParser::parseTAG( $xl, $type, $tag );
			case 'CTAG':
				return XLiteParser::parseCTAG( $xl, $type, $tag );
			case 'COMMENT':
				return XLiteParser::parseCOMMENT( $xl, $type, $tag );
			default:
				return XLiteParser::parseCUSTOM( $xl, $type, $tag );
		}
	}

	function parseCOMMENT( &$xl, $type, &$taginfo ) {
		return false;
	}
	
	function parseCUSTOM( &$xl, $type, &$taginfo ) {		
		$taginfo['_special'] = 1;		// marker
		return false;
	}
	
	function parseTEXT( &$xl, $type, &$taginfo ) {
		if ($xl->trim_whitespace) {
			$taginfo['text'] = preg_replace('/\s\s+/', ' ', $taginfo['text']);
		}
		return false;
	}

	function parseTAG( &$xl, $type, &$taginfo ) {
		$tag = $taginfo['tag'];			
		if ( $xl->isSpecialTag($tag) ) 
			{
				$taginfo['_special'] = 1;		// marker
				unset($taginfo['_raw']);        // free up..
				
				if ( $taginfo['short']==0 ) 
					{
						$xl->pushTag( &$taginfo );
						return true;	
					}
			}
		return false;		
	}
	
	function parseCTAG( &$xl, $type, &$taginfo ) {

		$tag = $taginfo['tag'];
		if ( $xl->isSpecialTag($tag) ) {
			$temp = $xl->peekTag();
			if ($temp) {
				if ( $temp['tag']==$tag) {
					$last = & $xl->popTag( );

					if ($xl->getTagStackSize()==0) { 
						$xl->_TREE[] = & $last;
					} else {
						$xl->_TAG_STACK[$xl->getTagStackSize()-1]['_child'][] = & $last;
					}
					
					return true;
				} else {
					die( "WARNING: mismatched closing tag: ${temp['tag']} => $tag!\n" );
				}	
			} else {
				die( "WARNING: closing tag without opening: $tag!\n" );
			}
		}

		return false;		
	}


	###############
	#  parse_2:
	#
	#  optimize.. merging text & non-special tag..
	#
	function parse_2() {
		for ($i=0;$i<count($this->_TREE);$i++) 
			{
				if (isset($this->_TREE[$i]['_child'])) {
					$this->merge( & $this->_TREE[$i]['_child'] );
					$this->_TREE[$i]['_child'] = array_merge($this->_TREE[$i]['_child'],array());
				}
			}

		# last..		
		$this->merge( & $this->_TREE );		
		$this->_TREE = array_merge( $this->_TREE, array() );
	}
	
	function merge( &$tags) {
		$n = count($tags);
		for ($i=$n-1;$i>0;$i--) {
			$ctags = & $tags[$i];
			$ptags = & $tags[$i-1];
			
			if ( !isset($ctags['_special']) && 
				 !isset($ptags['_special']) && 
				 !isset($ctags['_child'])   && 
				 !isset($ptags['_child'])
			   ) 
			{
				## merge to text
				$ptags['type'] 	= 'TEXT';
				$mergestr 		= (isset($ptags['text'])? $ptags['text']:$ptags['_raw']) . 
								  (isset($ctags['text'])? $ctags['text']:$ctags['_raw']);
				$ptags['text'] 	= $mergestr;
				
				## delete unused key..
				$keys = array_keys($ptags);
				foreach($keys as $key) 
					if ($key!='type' && $key!='text')
						unset($ptags[$key]);
				
				## delete current tag..
				unset($tags[$i]);
			} else {
				// recursive..

				if (isset($ctags['_child'])) {
					$this->merge( &	$ctags['_child'] );
					$ctags['_child'] = array_merge( $ctags['_child'], array() );
				}
				
			}
		}	
		
	}
	
	function parse() {
		$this->parse_0();	// populate tags...
		$this->parse_1();	// normalize..
		$this->parse_2();	// optimize..
	}	
	
	function loadCache($fname) {
		if (!defined('XLITE_CACHE_DIR')) 
			die ("XLITE_CACHE_DIR not defined!");
		
		$this->_FNAME = $fname;
		
		$rpath = realpath( $fname );
		$path  = pathinfo( $rpath );
		$mtime = filemtime( $rpath );
		if ($mtime) {
			$hex     		= $this->__BUILDTIME .'_'. dechex( $mtime );
			$fcache	 		= XLITE_CACHE_DIR.'/xl'.dechex(crc32($path['basename'])).'_'.$hex;
			
			$create_cache 	= (!file_exists($fcache));
			
			if ($create_cache) {
				$this->load($rpath,true);
				$this->parse();
				$this->saveSerialized( $fcache );	
			} else {
				$this->loadSerialized( $fcache, true );	
			}
		}
	}

	########################################
	#
	#  expression evaluator	
	#
	function assign($key, $val=false) {
		if (is_array($key)) {
			foreach ($key as $k=> $v)
				$this->_VARS[$k] = $v;
		} else {
			$this->_VARS[$key] = $val;
		}
	}
	
	function & getValue($key) {
		if (ctype_digit($key)) return $key;
		
		if (isset($this->_VARS[$key]))
			return $this->_VARS[$key];

		$q = BLANK_VALUE;
		return $q;
	}
	
	function replace_value_cb($raw) {	
		return $this->replace_cb($raw);
	}
	
	function replace_cb($raw) {						
		$varpath = $raw[1];
		$_VARS    = explode('.',$varpath);
		for ($i=0; $i<count($_VARS); $i++) {
			if ($i==0) {
				$res = $this->getValue($_VARS[$i]);	
			} else {
				
				if ($_VARS[$i][0]=='$')
					$index = $this->getValue(substr($_VARS[$i],1));	// variable index		
				else
					$index = $_VARS[$i];							// associative array					
				
				if (!isset($res[$index])) {
					$res = false;
					break;
				}
				$res = $res[$index];
			}
		}
					
		return ($res===false)?BLANK_VALUE:$res; 
	}

	function registerBlockHandler(& $handler,$type,$tag=false) {
		$this->BLOCK_CUSTOM_HANDLER[$type][$tag] = & $handler;
	}
	
	function transformBlock( &$block ) {
		if (isset($block['_special'])) {
			$type = $block['type'];
			$tag  = isset($block['tag'])?$block['tag']:false;
			
			//if (isset($this->BLOCK_CUSTOM_HANDLER[$type][$tag])) 
			//	$this->BLOCK_CUSTOM_HANDLER[$type][$tag]->processBlock( & $block );
			$this->processBlock( & $block );
		}
		
		switch ($block['type']) {
			case 'TEXT':
				break;
			
			case 'TAG':			
				if (!isset($block['text']))
					if (isset($block['_raw'])) 
						$block['text'] = & $block['_raw'];
					else
						$block['text'] = '';
				break;
				
			case 'CTAG':
				$block['text'] = & $block['_raw'];
				break;
				
			default:
				$block['text'] = & $block['_raw'];			
		}
	}

	function generateAttribute( $block , $skip=false ) {
		if (!$skip) $skip = array();
		if (!is_array($skip)) $skip = array( $skip );
		
		$res = array();
		if (isset($block['attr'])) {
			foreach($block['attr'] as $attr=>$value) 
				if (!in_array($attr,$skip))
					$res[] = $attr . '="' . htmlspecialchars($value) . '"';  		
		}
		
		return implode(' ',$res);
	}
		
	function renderIndependentBlock( & $block ) {
		xlite_cb_start( & $this );			
		$res = $this->renderBlock( & $block );
		xlite_cb_end( & $this );
		return $res;	
	}

	function renderArray( & $blocks) {
		$res = '';
		xlite_cb_start( & $this );			
		for ($i=0; $i<count($blocks);$i++) 
		{
			$res .= $this->renderBlock( & $blocks[$i] );
		}			
		xlite_cb_end( & $this );
		return $res;
	}	
		
	function resolveAttribute( & $block ) {
		if ( $block['type']=='TAG' && 
			 isset($block['attr'])  ) 
		{
			foreach($block['attr'] as $k=> $v) {
				$block['attr'][$k] = preg_replace_callback(
										'/\{[\$\!]([\w\_][\w\d\_]*(\.\$?[\w\d\_]+)*)\}/',
										'xlite_replace_cb',
										$v);
			}
		}	
	}
	 
	function renderBlock(& $block) {		
		$res = '';
		do {			
			$this->transformBlock( & $block );
			
			
			if (!isset($block['__hidden'])) {
			//	$res .= $block['text'];
				
				$res .= preg_replace_callback(
							'/\{[\$\!]([\w\_][\w\d\_]*(\.\$?[\w\d\_]+)*)\}/',
							'xlite_replace_value_cb',
							$block['text']);
				
				if (!isset($block['_skip_render_child']) && isset($block['_child'])) 
					for ($i=0; $i<count($block['_child']); $i++) {
						$res.= $this->renderBlock( & $block['_child'][$i] );
					}
			}
			
		} while (isset($block['__recall']));
			
		return $res;
	}

	var $_rendered = false;
	
	function render() {
		if (!$this->_rendered) $this->_rendered = $this->renderArray( & $this->_TREE ) ;
		return $this->_rendered;
	}	
	
	function renderToScreen() {
		echo $this->render();
	}
	
	function renderToFile($fname) {
		return file_put_contents( $fname,$this->render() );	
	}

} //-- end class	

?>