<?php
/* ############################################
	XDB - HiPerformance Database Abstraction
	xdb driver for OCI8 interface 
	(c) 2009
	written by Adi Gita Saputra
*/
class OCI8Driver {

	var  	$host, $db, $user, $password;
	var		$error;
	var		$connected;
	var		$conn;
	var     $exec_mode;
	var		$affected;
	var		$trans_ctr=0;
	var 	$generated_id = false;
	var 	$inserted_rowid = false;
	var    	$FALSE = false;
		
	function OCI8Driver($host,$db,$user,$password) {
		$this->host		= $host;
		$this->db		= $db;
		$this->user		= $user;
		$this->password	= $password;

		_start('DB_CONN');	
		$this->clearError();	
		$this->connect($host,$db,$user,$password);
		$this->exec_mode = OCI_COMMIT_ON_SUCCESS;
		_stop('DB_CONN');
	}
	
	function close() {
		_start('DB_CONN');
		while ($this->trans_ctr>0) $this->rollback();
		if ($this->conn) oci_close($this->conn);	
		_stop('DB_CONN');
	}
	
	function clearError() {	$this->error = array();  }
	function hasError() {  return count($this->error)>0;  }	
	
	function connect($host,$db,$user,$password) {		
		$this->conn = oci_connect($user,$password,$host.'/'.$db);
		if ($this->conn) {
			$this->connected = true;
		} else {
			$this->connected = false;
			$e = oci_error();
			$this->error[]	 = array('ERR_CONNECT',$e['message']);	
			//TODO:write error dump..
			killApp('DBDOWN');
		}
	}
	
	function generatedId() {
		return $this->generated_id;
	}
	
	
	function insert($sql, $params=false,$id='id') {
		_start('DB_QUERY');
		$this->affected 		= 0;
		$this->generated_id 	= false;
		$sel = false;
		
		if ($this->conn) {
			$sql = $sql.' RETURN '.$id.' INTO :generatedid';
			@$st = oci_parse($this->conn, $sql );
			if (!$st) {
				$e = oci_error($this->conn);
				killApp('ERR_QUERY',$e);
			}
			
			if (is_array($params)) {
				$keys = array_keys($params);
				foreach ($keys as $key) {
					oci_bind_by_name($st,':'.$key,$params[$key]);
				}
			}
			oci_bind_by_name($st,':generatedid',$rowid,20);
				
			@$qOK 	= oci_execute($st,$this->exec_mode);	
			if ($qOK) {
				$this->generated_id = $rowid;
				$this->affected = oci_num_rows( $st );
				$res = $this->affected;
										
			} else {
				$e = oci_error($st);
				$this->error[] = array('ERR_QUERY',$e['message']);
				$res = false;
			}
			
			oci_free_statement($st);
			_stop('DB_QUERY');
			return $res;
	
		} else {
			_stop('DB_QUERY');
			$this->error[] = array('ERR_CLOSED','query on closed connection');
			killApp('DBCLOSED');
			return false;	
		}
	}

	function & selectLimit( $sql, $offset,$len,$params=false,$keyfield='id') {
		$this->affected = 0;
		$false 			= false;
		$end            = $offset + $len;
		
		if ($this->conn) {
			_start('DB_QUERY');
			$lsql = "select * from (select t.*,rownum as recordnum from ($sql) t where rownum<=$end) where recordnum>$offset";
			@$st 		= oci_parse($this->conn, $lsql);
			if (!$st) {
				$e = oci_error($this->conn);
				_stop('DB_QUERY');
				killApp('ERR_QUERY',$e);
			}

			$stat_type 	= oci_statement_type($st);
			if (is_array($params)) {
				$keys = array_keys($params);
				foreach ($keys as $key) {
					oci_bind_by_name($st,':'.$key,$params[$key],$params[$key]?-1:40);
				}
			}
						
			oci_set_prefetch($st,100);
			
			@$qOK 	= oci_execute($st,$this->exec_mode);	
			_stop('DB_QUERY');
			if ($qOK) {
				$this->affected = 0;
				$res = new OCIRecordset( $this->conn,$st,$lsql,$params,$keyfield );
				return $res;
				
			} else {
				$e = oci_error($st);
				$this->error[] = array('ERR_QUERY',$e);
				oci_free_statement($st);
				return $false;
			}
	
		} else {
			killApp('DBCLOSED');
			return $false;
		}
	}
	
	function & query( $sql, & $params=false, $keyfield='id') {
		$this->affected = 0;
		$false 			= false;
		
		if ($this->conn) {
			_start('DB_QUERY');
			@$st 		= oci_parse($this->conn, $sql);
			if (!$st) {
				$e = oci_error($this->conn);
				_stop('DB_QUERY');
				killApp('ERR_QUERY',$e);
			}
			
			$cursors 	 = array();
			$cursors_len = array();
			
			$stat_type 	 = oci_statement_type($st);
			if (is_array($params)) {
				$keys = array_keys($params);
				foreach ($keys as $key) {
					if (strpos($key,':')) {
						$parts = split(':',$key,3);
						$oldkey = $key;
						$key = $parts[0];
						$deflen = $parts[1];
						
						$params[$key] = oci_new_cursor($this->conn);
						unset($params[$oldkey]);
						
						if ($deflen=='cursor') {
							oci_bind_by_name($st,':'.$key,$params[$key],-1,OCI_B_CURSOR);

							$cursors[] 			= $key;
							$cursors_len[$key]  = (isset($parts[2]) && ctype_digit($parts[2]))?$parts[2]:-1;					
						} else {
							oci_bind_by_name($st,':'.$key,$params[$key],ctype_digit($deflen)?$deflen:40);							
						}
					} else {
						oci_bind_by_name($st,':'.$key,$params[$key],$params[$key]?-1:40);
					}
				}
			}
									
			@$qOK 	= oci_execute($st,$this->exec_mode);
			_stop('DB_QUERY');	
			if ($qOK) {								
				$this->affected = oci_num_rows( $st );
				if ($cursors) {
					_start('DB_FETCH');
					foreach($cursors as $cur) {
						$s_cur = $params[$cur];
						oci_execute($s_cur);
						oci_fetch_all($s_cur,$params[$cur],0,$cursors_len[$cur],OCI_FETCHSTATEMENT_BY_ROW);
						oci_free_statement($s_cur);
					}
					oci_free_statement($st);
					_stop('DB_FETCH');
					$res = $this->affected;
					return $res;
				}
				
				if ($stat_type=='SELECT') {
					$res = new OCIRecordset( $this->conn,$st,$sql,$params, $keyfield );
				} else {
					$res = $this->affected;
				}
				return $res;
				
			} else {
				$e = oci_error($st);
				$this->error[] = array('ERR_QUERY',$e);
				print_r($e);
				oci_free_statement($st);
				return $false;
			}
	
		} else {
			killApp('DBCLOSED');
			return $false;
		}
	}
	
	# Query Without Error Message FeedBack
	function & queryNotSound( $sql, & $params=false, $keyfield='id') {
		$this->affected = 0;
		$false 			= false;
		
		if ($this->conn) {
			_start('DB_QUERY');
			@$st 		= oci_parse($this->conn, $sql);
			if (!$st) {
				$e = oci_error($this->conn);
				_stop('DB_QUERY');
				killApp('ERR_QUERY',$e);
			}
			
			$cursors 	 = array();
			$cursors_len = array();
			
			$stat_type 	 = oci_statement_type($st);
			if (is_array($params)) {
				$keys = array_keys($params);
				foreach ($keys as $key) {
					if (strpos($key,':')) {
						$parts = split(':',$key,3);
						$oldkey = $key;
						$key = $parts[0];
						$deflen = $parts[1];
						
						$params[$key] = oci_new_cursor($this->conn);
						unset($params[$oldkey]);
						
						if ($deflen=='cursor') {
							oci_bind_by_name($st,':'.$key,$params[$key],-1,OCI_B_CURSOR);

							$cursors[] 			= $key;
							$cursors_len[$key]  = (isset($parts[2]) && ctype_digit($parts[2]))?$parts[2]:-1;					
						} else {
							oci_bind_by_name($st,':'.$key,$params[$key],ctype_digit($deflen)?$deflen:40);							
						}
					} else {
						oci_bind_by_name($st,':'.$key,$params[$key],$params[$key]?-1:40);
					}
				}
			}
									
			@$qOK 	= oci_execute($st,$this->exec_mode);
			_stop('DB_QUERY');	
			if ($qOK) {								
				$this->affected = oci_num_rows( $st );
				if ($cursors) {
					_start('DB_FETCH');
					foreach($cursors as $cur) {
						$s_cur = $params[$cur];
						oci_execute($s_cur);
						oci_fetch_all($s_cur,$params[$cur],0,$cursors_len[$cur],OCI_FETCHSTATEMENT_BY_ROW);
						oci_free_statement($s_cur);
					}
					oci_free_statement($st);
					_stop('DB_FETCH');
					$res = $this->affected;
					return $res;
				}
				
				if ($stat_type=='SELECT') {
					$res = new OCIRecordset( $this->conn,$st,$sql,$params, $keyfield );
				} else {
					$res = $this->affected;
				}
				return $res;
				
			} else {
				//$e = oci_error($st);
				//$this->error[] = array('ERR_QUERY',$e);
				//print_r($e);
				oci_free_statement($st);
				
				return $false;
			}
	
		} else {
			killApp('DBCLOSED');
			return $false;
		}
	}
	function startTransaction() {
		if ($this->trans_ctr==0) $this->clearError();
		$this->exec_mode = OCI_DEFAULT;
		$this->trans_ctr++;
	}
	
	function commit() {	
		if (!$this->conn || $this->trans_ctr==0) return false;
		$this->exec_mode = OCI_COMMIT_ON_SUCCESS;
		$this->trans_ctr--;
		return oci_commit($this->conn); 	
	}
	
	function rollback() {
		if (!$this->conn || $this->trans_ctr==0) return false; 
		$this->exec_mode = OCI_COMMIT_ON_SUCCESS;
		$this->trans_ctr--;
		return oci_rollback($this->conn);
	}

	function endTransaction() {
		if (count($this->error)>0) {
			$this->rollback();
			return false;
		} else {
			return $this->commit();
		}
	}
	
	function __destruct() {
		$this->close();
	}
	
}


class OCIRecordset {
	
	var $res;
	var $numrows=false;
	var $currow;
	var $keyfield;
	var $metadata = false;
	var $sql;
	var $rec_count = false;
	var $conn;
	var $params;
	
	function OCIRecordset($conn,$res,$sql,$params,$keyfield='id') {
		$this->res 		= $res;	
		
		$this->currow   = true;
		$this->keyfield = $keyfield;
		$this->sql		= $sql;
		$this->conn		= $conn;
		$this->params	= $params;
	}	
	
	function close() {
		$this->conn = null;
		if ($this->res) oci_free_statement($this->res);	
		$this->res = false;
	}
	
	function isEmpty() { 
		return false; 
	}
	
	function RecordCount() {
		if ($this->rec_count===false) {
			_start('DB_QUERY');
			@$st 		= oci_parse($this->conn, "SELECT count(*) jum FROM (".$this->sql.")");
			if (!$st) {
				$e = oci_error($this->conn);
				killApp('ERR_QUERY',$e);
			}
			if (is_array($this->params)) {
				$keys = array_keys($this->params);
				foreach ($keys as $key) {
					oci_bind_by_name($st,':'.$key,$params[$key],$params[$key]?-1:40);
				}
			}			
			@$qOK 	= oci_execute($st);	
			if ($qOK && $row=oci_fetch_array($st,OCI_ASSOC)) {
				$this->rec_count=$row['JUM'];
			} else {
				$this->rec_count=0;
			}
			@oci_free_statement($st);
			_stop('DB_QUERY');
		}
		
		return $this->rec_count;
	}
	
	var $__counting = false;	
	function FetchRow() {
		if (!$__counting) {
			$__counting = true;
			_start('DB_FETCH');
		}

		$this->currow = oci_fetch_array( $this->res, OCI_ASSOC|OCI_RETURN_NULLS );
		
		if (!$this->currow) {
			 _stop('DB_FETCH');
			$__counting = false;
		}
		return $this->currow;
	}
	
	function getAll() {
		#_start('DB_FETCH');
		$res = array();
		while ($row=$this->FetchRow())
			$res[] = $row;
		$this->close();
		#_stop('DB_FETCH');

		return $res;
	}
	
	function getAllFirst() {
		$res = array();
		while ($row=$this->FetchRow(OCI_NUM|OCI_RETURN_NULLS))
			$res[] = $row[0];
		
		return $res;	
	}
	
	function getMetadata() {
		if (!$this->metadata)  $this->metadata = new OCI8MetaData( $this->res );
		
		return $this->metadata;	
	}
	
	function __destruct() {
		$this->close();
	}
}


	
class OCI8MetaData {

		var	$fieldcount=0;
		var	$fieldname;
		var	$fieldsize;
		var	$fieldtype;
		
		var	$res;
		
		function OCI8MetaData($res) {
			$this->res = $res;
			
			$this->fieldcount 	= oci_num_fields($res);

			$this->fieldname 	= array();
			$this->fieldsize 	= array();	
			$this->fieldtype	= array();
			$this->fieldflag	= array();
			
			if ($this->fieldcount)
				for ($i=1;$i <= $this->fieldcount;$i++) {
					$this->fieldname[$i] = oci_field_name($res,$i);	
					$this->fieldsize[$i] = oci_field_size($res,$i);	
					$this->fieldtype[$i] = $this->unifyTypeName(
												oci_field_type($res,$i),
												oci_field_precision($res,$i));					
				}
		}
		
		function hasFlag($index,$flag) {
			return false;
		}
		
		function unifyTypeName( $type, $prec=0 ) {
			switch (strtolower($type)) {
				case 'string':
				case 'varchar2':
				case 'char':
					return TYPE_STRING;
				
				case 'number':
				case 'int':
					if ($prec>0) return TYPE_REAL;
					return TYPE_INTEGER;
				
				case 'timestamp':
				case 'time':
				case 'date':
				case 'datetime':
					return TYPE_TIMESTAMP;
					
				case 'lob':
				case 'blob':
					return TYPE_BLOB;
					
				case 'real':
				case 'float':
					return TYPE_REAL;
			}
			echo('onrecognized type:'.$type);
			return $type;
		
		} 
}
 
?>
