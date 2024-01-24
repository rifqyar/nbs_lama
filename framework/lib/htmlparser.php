<?php

function array_top($arr) {
	if (!is_array($arr) || count($arr)==0) return '';
	return $arr[count($arr)-1];
}

class HtmlParser {
	var $tags;
	var $state;
	var $data;
	var $ptr;
	var $len;
	var $tagopen;
	var $expected;
	var $last,$ch;
	
	var $tag_filter  = array( 'LIMIT'=>array(), 'STRIP'=>array() );
	var $attr_filter = array( 'LIMIT'=>array(), 'STRIP'=>array() );
	

	
	function stripTags($tags) {
		if (!is_array($tags)) $tags = array( $tags );
		
		foreach ($tags as $tag) {
			if (!in_array($tag,$this->tag_filter['STRIP']))
				$this->tag_filter['STRIP'][] = $tag;
		}
	}
	
	function stripAttributes($attrs) {
		if (!is_array($attrs)) $attrs = array( $attrs );
		
		foreach ($attrs as $attr) {
			if (!in_array($attr,$this->attr_filter['STRIP']))
				$this->attr_filter['STRIP'][] = $attr;
		}
	}
	
	function init($data){
		$this->data 	= $data;
		$this->len		= strlen($data);
		$this->ptr  	= 0;
		$this->tags	 	= array();
		$this->tagopen  = false;
		$this->expected	= false;
		$this->line		= 1;
		$this->col		= 1;
	}
	
	function parse($data) {
		$this->init($data);
		if (!$this->parseText()) return false;
		return true;
	}
	
	function parseText() {
		$buffer    = '';
		$startcol  = $this->col;
		$startline = $this->line;

		while ($this->ptr < $this->len) {
			$this->last = $this->ch;
			$this->ch   = $this->data[ $this->ptr++ ];
			$this->col++;
			
			switch ($this->ch) {
				case '<':
					if ($buffer!='') {
						$this->tags[] 	= array('TEXT',$buffer);
						$buffer	= '';
					}
					if (!$this->parseTag()) return false;

					$startcol  = $this->col;
					$startline = $this->line;
					break;
			
				case '>':
					$buffer .= '&gt;';
					break;
					
				case "\r":
					$this->line++;			
					$this->col=1;
					$buffer .= $this->ch;
					break;
					
				case "\n":
					if ($this->last!="\r") {
						$this->line++;			
						$this->col=1;
					} else {
						$this->col--;
					}
					$buffer .= $this->ch;
					break;
				
				default:
					$buffer .= $this->ch;
			}
		}
		
		// end..
		if ($buffer!='') {
			$this->tags[] 	= array('TEXT',$buffer);
			$buffer	= '';		
		}
		
		return true;
	}
	
	
	function parseTag() {
		$closetag  = false;
		$shorttag  = false;
		$tagname   = '';
		$expecting = false;
		$attrmode  = false;
		$attr	   = array();
				
		$startptr  = $this->ptr; 
		$startcol  = $this->col;
		$startline = $this->line;
		
		
		while ($this->ptr < $this->len ) {
			$this->last = $this->ch;
			$this->ch   = $this->data[ $this->ptr++ ];
			$this->col++;

			//echo 'tag '.$this->ch . "\n";
			if ($expecting && $expecting!=$this->ch) return false;
					
			switch ($this->ch) {		
				case '!':
					if ($this->ptr-$startptr==1) {
						return $this->parseComment($startline,$startcol);
					} else {
						return false;
					}
					break;
				
				case '/':
					if ($this->ptr-$startptr==1) {
						$closetag = true;
					} else {
						$shorttag  = true; 
						$expecting = '>';	// short close tag..
					}
					break;
				
				case '>':
					if ($closetag)
						$this->tags[] 	= array('CTAG',$tagname);
					else
						$this->tags[] 	= array('OTAG',$tagname,$shorttag,'attr'=>$attr);
					return true;
					
				case ' ':
				case "\t":
					$attrmode = true;
					break;
					
				case "\r":
					$this->line++;			
					$this->col=1;
					$attrmode = true;
					break;

				case "\n":
					if ($this->last!="\r") {
						$this->line++;			
						$this->col=1;
					} else {
						$this->col--;
					}
					$attrmode = true;
					break;
					
				default:
					if (!ctype_alnum($this->ch) && $this->ch!=':' && $this->ch!='-' && $this->ch!='_') 
						return false;
						
					if ($attrmode) {
						if ($closetag) return false;	// close tag doesnt have any attribute..
						$newattr = $this->parseAttribute($tagname,$this->ch,$startline,$startcol);
						if (!$newattr) return false;
						$stripped = false;
						foreach($this->attr_filter['STRIP'] as $attpat) {
							$attpat = str_replace('*','(.)*',$attpat);
							if (preg_match('/^'.$attpat.'$/',$newattr[0])) {
								$stripped = true;
								break;
							}
						}
						
						if ($stripped) {
							echo "stripping '$newattr[0]' attribute..\n";
						} else {
							$attr[] = $newattr;
						}
						
					} else {
						$tagname .= $this->ch;
					}		
			}			
		}		
		
		return false;	
	}
	
	function parseAttribute($tagname,$ch,$startline,$startcol) {
		$attrname  = $ch;
		$expecting = false;
		$valmode   = false;
		$value	   = '';
		$cquote    = '';
		
		$startptr  = $this->ptr; 
		$startcol  = $this->col;
		$startline = $this->line;
		
		while ($this->ptr < $this->len ) {
			$this->last = $this->ch;
			$this->ch   = $this->data[ $this->ptr++ ];
			$this->col++;

			//echo 'attr '.$this->ch . "\n";
			if ($expecting && strpos($expecting,$this->ch)===false) return false;
					
			switch ($this->ch) {		
				
				case '>':
					if ($valmode) 
						$value .= '&gt;';
					else {
						$this->ptr--;
						$this->col--;
						return array( $this->removeInvisible($attrname),$this->removeInvisible($value));
					}
					break;
										
				case '=':
					if ($valmode) $value .= $this->ch;
						else
					$expecting = " \t\r\n'\"";
					break;
					
				case '\'':
				case '"':
					if ($valmode && $cquote==$this->ch) {
						return array($this->removeInvisible($attrname),$this->removeInvisible($value));

					} elseif ($cquote=='') {
						$valmode   = true;
						$value	   = '';
						$cquote    = $this->ch;
						$expecting = false;
					} else {
						$value .= $this->ch;					
					}
					break;
					
				case ' ':
				case "\t":
					if ($valmode) $value .= $this->ch;
					break;
					
				case "\r":
					$this->line++;			
					$this->col=1;
					if ($valmode) $value .= '&#13;';
					break;

				case "\n":
					if ($this->last!="\r") {
						$this->line++;			
						$this->col=1;
					} else {
						$this->col--;
					}
					if ($valmode) $value .= '&#10;';
					break;
					
				default:
					if ($valmode) {
						$value .= $this->ch;
					} else {
						if (!ctype_alnum($this->ch) && $this->ch!=':' && $this->ch!='-' && $this->ch!='_') 	return false;		
						$attrname .= strtolower($this->ch);
					}
			}			
		}		
		
		return false;		
	}

	function parseComment($startline,$startcol) {
		$buffer = '';
		while ($this->ptr < $this->len) {
			$this->last = $this->ch;
			$this->ch   = $this->data[ $this->ptr++ ];
			$this->col++;
					
			switch ($this->ch) {		
				case "\r":
					$this->line++;			
					$this->col=1;
					break;

				case "\n":
					if ($this->last!="\r") {
						$this->line++;			
						$this->col=1;
					} else {
						$this->col--;
					}
					break;
					
				default:
					$buffer .= $this->ch;
					if (strlen($buffer)<3) break;
					
					if (strpos($buffer,'--')===0 && strpos(strrev($buffer),'>--')===0) {
						$this->tags[] 	= array('COMMENT',substr($buffer,2,strlen($buffer)-5));
						return true;
					} else if (strpos(strrev($buffer),'>')===0) {
						$this->tags[] 	= array('PROLOG',substr($buffer,0,strlen($buffer)-1));
						return true;
					}
			}			
		}		
		
	}
	
	
	function renderAttribute($a) {
		if (!is_array($a)) return '';
		
		$pairs = '';
		foreach ($a as $attr) {
			if (strpos($attr[1],'"')!==false) $attr[1]="'$attr[1]'"; else $attr[1]='"'.$attr[1].'"';
			$pairs .= ' '.$attr[0].'='.$attr[1];
		}
		return $pairs;
	}
	
	#############
	#  close open tag..
	#  try to be xml well formed
	function normalize() {
		$autoclose = array('br','input','hr','img','base','link');
		$tagstack  = array();
		$skipstack = array();
		$res = '';
		
		foreach($this->tags as $tag) {
			switch ($tag[0]) {
				case 'OTAG':
					if (count($skipstack)==0) {
						if (in_array($tag[1],$this->tag_filter['STRIP'])) {
							$skipstack[] = $tag[1];
						} else {
							if (!$tag[2] && !in_array($tag[1],$autoclose)) {
								$res .= '<'.$tag[1].$this->renderAttribute($tag['attr']).'>';
								$tagstack[] = $tag[1];
							} else {
								$res .= '<'.$tag[1].$this->renderAttribute($tag['attr']).' />';
							}
						}
					}
					break;
					
				case 'CTAG':
					if (count($skipstack)>0) {
						if ($tag[1]==array_top($skipstack)) array_pop($skipstack);
					} else {
						if ($tag[1]!=array_top($tagstack) && !in_array($tag[1],$autoclose)) {
							
							$recovered = false;
							while (count($tagstack)>0 && !$recovered) {
								$ltag = array_pop($tagstack);
								$res .= '</'.$ltag.'>';
								$recovered = ($ltag==$tag[1]);
							}
							
							if (!$recovered) {
								//echo 'IMPROPER CLOSING TAG: '.$tag[1];
								return false;
							} else {
							}
						} else {
							$res .= '</'.$tag[1].'>';
							array_pop($tagstack);
						}
					}
					break;
				
				case 'COMMENT':
					if (count($skipstack)==0) $res .= '<!--'.$tag[1].'-->';
					break;					
				case 'PROLOG':
					if (count($skipstack)==0) $res .= '<?'.$tag[1].'?>';
					break;	
				case 'TEXT':
				default:
					if (count($skipstack)==0) $res .= $tag[1];
			}
			//echo "$tag[0]:$tag[1]\n"; flush();
		}

		// recover remaining open tag..
		while (count($tagstack)>0) {
			$ltag = array_pop($tagstack);
			$res .= '</'.$ltag.'>';
		}
		
		
		return $res;
	}


	#############
	#  get numchar rendered character text..
	#  try to be xml well formed
	function getLimit($numchar) {
		$autoclose = array('br','input','hr','img','base','link');
		$nontext   = array('head','meta','script','applet','video','embed','style','link','img');
		$skip      = array_merge($this->tag_filter['STRIP'],$nontext);
		$tagstack  = array();
		$skipstack = array();
		$ctr = 0;
		$res = '';
		
		foreach($this->tags as $tag) {
			switch ($tag[0]) {
				case 'OTAG':
					if (count($skipstack)==0) {
						if (in_array($tag[1],$skip)) {
							if (!$tag[2] && !in_array($tag[1],$autoclose)) $skipstack[] = $tag[1];
						} else {
							if (!$tag[2] && !in_array($tag[1],$autoclose)) {
								$res .= '<'.$tag[1].$this->renderAttribute($tag['attr']).'>';
								$tagstack[] = $tag[1];
							} else {
								$res .= '<'.$tag[1].$this->renderAttribute($tag['attr']).' />';
							}
						}
					}
					break;
					
				case 'CTAG':
					if (count($skipstack)>0) {
						if ($tag[1]==array_top($skipstack)) array_pop($skipstack);
					} else {
						if ($tag[1]!=array_top($tagstack) && !in_array($tag[1],$autoclose)) {
							
							$recovered = false;
							while (count($tagstack)>0 && !$recovered) {
								$ltag = array_pop($tagstack);
								$res .= '</'.$ltag.'>';
								$recovered = ($ltag==$tag[1]);
							}
							
							if (!$recovered) {
								//echo 'IMPROPER CLOSING TAG: '.$tag[1];
								return false;
							} else {
							}
						} else {
							$res .= '</'.$tag[1].'>';
							array_pop($tagstack);
						}
					}
					break;
				
				case 'COMMENT':
					if (count($skipstack)==0) $res .= '<!--'.$tag[1].'-->';
					break;					
				case 'PROLOG':
					if (count($skipstack)==0) $res .= '<?'.$tag[1].'?>';
					break;	
				case 'TEXT':
				default:
					if (count($skipstack)==0) {
						$newtext = substr(html_entity_decode($tag[1]),0,$numchar-$ctr);
						//echo "ADD $tag[0]:".(strlen($newtext)).": $newtext\n";
						$ctr += strlen($newtext);
						$res .= htmlentities($newtext);
					}
			}
			//echo "$tag[0]:$tag[1]\n"; flush();
			if ($ctr>=$numchar) break;
		}

		// recover remaining open tag..
		while (count($tagstack)>0) {
			$ltag = array_pop($tagstack);
			$res .= '</'.$ltag.'>';
		}
		
		return $res;
	}
	
	##############################################
	## removeInvisible(): modified from codeIgniter	
	function removeInvisible($str)
	{
		static $non_displayables;
		
		if ( ! isset($non_displayables))
		{
			// every control character except newline (dec 10), carriage return (dec 13), and horizontal tab (dec 09),
			$non_displayables = array(
										'/%0[0-8bcef]/',			// url encoded 00-08, 11, 12, 14, 15
										'/%1[0-9a-f]/',				// url encoded 16-31
										'/[\x00-\x08]/',			// 00-08
										'/\x0b/', '/\x0c/',			// 11, 12
										'/[\x0e-\x1f]/'				// 14-31
									);
		}

		do
		{
			$cleaned = $str;
			$str = preg_replace($non_displayables, '', $str);
		}
		while ($cleaned != $str);

		return $str;
	}
}
?>