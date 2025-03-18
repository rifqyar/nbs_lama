<?php
/* ############################################
	XDB - HiPerformance Database Abstraction
	MySQL Driver for XDB
	(c) 2009
	written by Adi Gita Saputra
*/

class MySQLDriver {

	var  	$host, $db, $user, $password;
	var		$error;
	var		$connected;
	var		$conn;
	
	function MySQLDriver($host,$db,$user,$password) {
		$this->host		= $host;
		$this->db		= $db;
		$this->user		= $user;
		$this->password	= $password;
	
		$this->clearError();	
		$this->connect($host,$db,$user,$password);
	}
	
	function close() {
		if ($this->conn) mysql_close($this->conn);	
	}
	
	function clearError() {	$this->error = array();  }
	function hasError() {  return count($this->error)>0;  }	
	
	function connect($host,$db,$user,$password) {		
		$this->conn = mysql_pconnect($host,$user,$password);
		if ($this->conn) {
			if (!mysql_select_db($db,$this->conn)) {
				$this->connected = false;
				$this->error[]	 = array('ERR_SCHEMA',mysql_error($this->conn));
			} else {				
				$this->connected = true;
			}	
		} else {
			$this->connected = false;
			$this->error[]	 = array('ERR_CONNECT',mysql_error());	
			//TODO:write error dump..
			killApp('DBDOWN');
		}
	}
	
	function generatedId() {
		if ($this->conn) return mysql_insert_id( $this->conn );
		return false;
	}
	
	function & query( $sql, $keyfield='id') {
		$false = false;
		
		if ($this->conn) {
			$rs = mysql_query($sql,$this->conn);		
			if ($rs) {
				$res = new MySQLRecordset( $rs, $keyfield );
				return $res;
			} else {
				$this->error[] = array('ERR_QUERY',mysql_error($this->conn));
				//TODO:write error dump..
				killApp('ERR_QUERY:'.mysql_error($this->conn)."\nQUERY:".$sql );
				return $false;
			}
	
		} else {
			$this->error[] = array('ERR_CLOSED','query on closed connection');
			//TODO:write error dump..
			killApp('DBCLOSED');
			return $false;	
		}
	}
	
	function & selectLimit( $sql, $offset,$len,$params=false,$keyfield='id') {
		if ($this->conn) {
			$sql = " $sql limit $offset,$len ";
			$rs = mysql_query($sql,$this->conn);		
			if ($rs) {
				$res = new MySQLRecordset( $rs, $keyfield );
				return $res;
			} else {
				$this->error[] = array('ERR_QUERY',mysql_error($this->conn));
				//TODO:write error dump..
				killApp('ERR_QUERY:'.mysql_error($this->conn));
				return $false;
			}				
		} else {
			killApp('DBCLOSED');
			return $false;
		}
	}
	
	
	function startTransaction() {
		$this->clearError();
		$this->query("START TRANSACTION");
	}
	
	function commit() {	$this->query("COMMIT"); }
	function rollback() {	$this->query("ROLLBACK"); }

	function endTransaction() {
		if (count($this->error)>0) {
			$this->query("ROLLBACK");
			return false;
		} else {
			$this->query("COMMIT");
			return true;
		}
	}
	
}


class MySQLRecordset {
	
	var $res;
	var $numrows;
	var $currow;
	var $keyfield;
	var $metadata = false;
	
	
	function MySQLRecordset($res,$keyfield='id') {
		$this->res 		= $res;	
		$this->numrows	= @mysql_num_rows($res);
		if ($this->numrows==false) $this->numrows = 0;
		
		$this->currow   = true;
		$this->keyfield = $keyfield;
	}	
	
	function close() {
		if ($this->res) mysql_free_result($this->res);	
	}
	
	function isEmpty() { return $this->numrows==0; }
	function RecordCount() { return $this->numrows; }
		
	function FetchRow() {
		$this->currow = mysql_fetch_array( $this->res );
		if ($this->currow) $this->counter++;
		return $this->currow;
	}
	
	function getAll() {
		$res = array();
		while ($row=$this->FetchRow())
			$res[] = $row;
		
		return $res;
	}
	
	function getAllFirst() {
		$res = array();
		while ($row=$this->FetchRow())
			$res[] = $row[0];
		
		return $res;	
	}
	
	function getOne() {
		$res = array();
		while ($row=$this->FetchRow())
			$res[] = $row[0];
		
		return $res;	
	}
	
	function getMetadata() {
		if (!$this->metadata)  $this->metadata = new MySQLMetaData( $this->res );
		
		return $this->metadata;	
	}
}


	
class MySQLMetaData {

		var	$fieldcount=0;
		var	$fieldname;
		var	$fieldsize;
		var	$fieldtype;
		var	$fieldflag;
		
		var	$res;
		
		function MySQLMetaData($res) {
			$this->res = $res;
			
			$this->fieldcount 	= mysql_num_fields($res);

			$this->fieldname 	= array();
			$this->fieldsize 	= array();	
			$this->fieldtype	= array();
			$this->fieldflag	= array();
			
			if ($this->fieldcount)
				for ($i=0;$i < $this->fieldcount;$i++) {
					$this->fieldname[$i] = mysql_field_name($res,$i);	
					$this->fieldsize[$i] = mysql_field_len($res,$i);	
					$this->fieldtype[$i] = $this->unifyTypeName(mysql_field_type($res,$i));	
					$this->fieldflag[$i] = explode(' ',mysql_field_flags($res,$i));					
				}
		}
		
		function hasFlag($index,$flag) {
			if (!isset($this->fieldflag[$index])) return false;
			return in_array($flag,$this->fieldflag[$index]);
		}
		
		function unifyTypeName( $type ) {
			switch (strtolower($type)) {
				case 'string':
				case 'varchar':
				case 'char':
					return TYPE_STRING;
				
				case 'int':
					return TYPE_INTEGER;
				
				case 'timestamp':
				case 'time':
				case 'date':
				case 'datetime':
					return TYPE_TIMESTAMP;
					
				case 'blob':
					return TYPE_BLOB;
					
				case 'real':
				case 'float':
					return TYPE_REAL;
			}
			return $type;
		
		} 
}
 
?>