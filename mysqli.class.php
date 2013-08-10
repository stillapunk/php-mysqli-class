<?php

  		
class Mysqli_Database {


//number of rows affected by SQL query
public $affected_rows = 0;

//query exectime
public  $query_time ;
private $query_start_time;

private $link_id = 0;
private $query_id = 0;



private $server   = "localhost";	 
private $user     = ""; 			 
private $pass     = ""; 			 
private $database = ""; 			 
private $pre      = ""; 			 

private $error = "";
private $errno = 0;


////////////////////////////
//query time functions
private function query_start_time () {
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$this->query_start_time = $mtime;
}
private function query_end_time () {
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$endtime = $mtime; 
	$totaltime = ($endtime - $this->query_start_time);
	$this->query_time = $totaltime;
}
//-----------------------------------------------------
// The constructor
public function Mysqli_Database($server, $user, $pass, $database, $pre=''){
	$this->server=$server;
	$this->user=$user;
	$this->pass=$pass;
	$this->database=$database;
	$this->pre=$pre;
	$this->connect();
	
}


//-----------------------------------------------------
// Connecting to database using above

private function connect($new_link=false) {
	$this->query_start_time();
	$this->link_id=@mysqli_connect($this->server,$this->user,$this->pass);

	if (!$this->link_id) {
		$this->display_error("Server connection Could Not Be Established : <b>{$this->server}</b>.");
		}

	if(!@mysqli_select_db($this->link_id,$this->database)) {//no database
		$this->display_error("Server connection Could Not Be Established : <b>{$this->database}</b>.");
		}

	unset($this->server,$this->user,$this->pass,$this->database);

}


//-------------------------------------------------
// Closing the connection
private function close() {
	if(!@mysqli_close($this->link_id)){
		$this->display_error("Connection close failed.");
	}
		$this->query_end_time();

}


// Escapes characters to be mysqli ready

private function escape($string) {
	if(get_magic_quotes_runtime()) $string = stripslashes($string);
	return @mysqli_real_escape_string($string,$this->link_id);
}

//-------------------------------------------------------

public function raw_query($sql) {
	
	$this->query_start_time();
	
	$this->query_id = mysqli_query($this->link_id,$sql);

	if (!$this->query_id) {
		$this->display_error("MySQLi Query failed");
	}
	
	$this->affected_rows = mysqli_affected_rows($this->link_id);
	$this->query_end_time();
	return $this->query_id;
}

public function fetch_assoc($query_id) {


	if (isset($this->query_id->num_rows) && ($this->query_id->num_rows >= 0) && (empty($this->query_id->errno))) {
		$record = @mysqli_fetch_assoc($this->query_id);
	}else{
		$this->display_error("Invalid MySqli query id: <br/>{$this->query_id}<br/>Records could not be fetched for the above query id.");
	}

	return $record;
}


//------------------------------------------------------------

public function fetch_all_assoc($sql) {
	$query_id = $this->query($sql);
	$out = array();

	while ($row = $this->fetch_assoc($query_id)){
		$out[] = $row;
	}
	$this->free_result($query_id);
	return $out;
}

private function free_result($query_id=-1) {
	if ($query_id!=-1) {
		$this->query_id=$query_id;
	}
	if($this->query_id!=0 && !@mysqli_free_result($this->query_id)) {
		$this->display_error("The Result With The ID: <b>$this->query_id</b> could not be freed.");
	}
}


/*
- Info       : EXECUTES the query sent , fetches the first row only
- Parameters : (MySQL query) the query to run on server
- Return     : array of results obtained
*/
public function get_first_row($the_query_string) {
	$query_id = $this->raw_query($the_query_string);
	$out = $this->fetch_assoc($query_id);
	$this->free_result($query_id);
	return $out;
}


/*
- desc: does an insert query with an array
*/

public function table_insert($table, $data) {
	$q="INSERT INTO `".$this->pre.$table."` ";
	$v=''; $n='';

	foreach($data as $key=>$val) {
		$n.="`$key`, ";
		if(strtolower($val)=='null') $v.="NULL, ";
		elseif(strtolower($val)=='now()') $v.="NOW(), ";
		else $v.= "'".$this->escape($val)."', ";
	}

	$q .= "(". rtrim($n, ', ') .") VALUES (". rtrim($v, ', ') .");";

	if($this->raw_query($q)){
		$this->free_result();
		return mysqli_insert_id($this->link_id);
	}
	else return false;

}

private function display_error($msg='') {

	if($this->link_id->errno > 0){
		$this->error=mysqli_error($this->link_id);
		$this->errno=mysqli_errno($this->link_id);
		die($msg."<br/>Error No. : ".$this->errno.'<br/>'.$this->error);
	}
	else{
		$this->error=mysqli_error();
		$this->errno=mysqli_errno();
		die($msg." ".$this->errno.' '.$this->error);
	}
	

}


}

?>
