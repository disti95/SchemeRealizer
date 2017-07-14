<?php

class Event{ 

	private $db;
	private $name;
	private $body;
	private $definer;
	private $execute_at;
	private $interval_value;
	private $interval_field;
	private $created;
	private $modified;
	private $last_executed;
	private $starts;
	private $ends;
	private $status;
	private $on_completion;
	private $sql_mode;
	private $comment;
	private $originator;
	private $time_zone;
	private $character_set_client;
	private $collation_connection;
	private $db_collation;
	private $body_utf8;

	public function getDb() {
		return $this->db; 
	}
	public function getName() {
		return $this->name; 
	}
	public function getBody() {
		return $this->body; 
	}
	public function getDefiner() {
		return $this->definer; 
	}
	public function getExecute_at() {
		return $this->execute_at; 
	}
	public function getInterval_value() {
		return $this->interval_value; 
	}
	public function getInterval_field() {
		return $this->interval_field; 
	}
	public function getCreated() {
		return $this->created; 
	}
	public function getModified() {
		return $this->modified; 
	}
	public function getLast_executed() {
		return $this->last_executed; 
	}
	public function getStarts() {
		return $this->starts; 
	}
	public function getEnds() {
		return $this->ends; 
	}
	public function getStatus() {
		return $this->status; 
	}
	public function getOn_completion() {
		return $this->on_completion; 
	}
	public function getSql_mode() {
		return $this->sql_mode; 
	}
	public function getComment() {
		return $this->comment; 
	}
	public function getOriginator() {
		return $this->originator; 
	}
	public function getTime_zone() {
		return $this->time_zone; 
	}
	public function getCharacter_set_client() {
		return $this->character_set_client; 
	}
	public function getCollation_connection() {
		return $this->collation_connection; 
	}
	public function getDb_collation() {
		return $this->db_collation; 
	}
	public function getBody_utf8() {
		return $this->body_utf8; 
	}

	public function setDb($db) {
		$this->db = $db;
	}
	public function setName($name) {
		$this->name = $name;
	}
	public function setBody($body) {
		$this->body = $body;
	}
	public function setDefiner($definer) {
		$this->definer = $definer;
	}
	public function setExecute_at($execute_at) {
		$this->execute_at = $execute_at;
	}
	public function setInterval_value($interval_value) {
		$this->interval_value = $interval_value;
	}
	public function setInterval_field($interval_field) {
		$this->interval_field = $interval_field;
	}
	public function setCreated($created) {
		$this->created = $created;
	}
	public function setModified($modified) {
		$this->modified = $modified;
	}
	public function setLast_executed($last_executed) {
		$this->last_executed = $last_executed;
	}
	public function setStarts($starts) {
		$this->starts = $starts;
	}
	public function setEnds($ends) {
		$this->ends = $ends;
	}
	public function setStatus($status) {
		$this->status = $status;
	}
	public function setOn_completion($on_completion) {
		$this->on_completion = $on_completion;
	}
	public function setSql_mode($sql_mode) {
		$this->sql_mode = $sql_mode;
	}
	public function setComment($comment) {
		$this->comment = $comment;
	}
	public function setOriginator($originator) {
		$this->originator = $originator;
	}
	public function setTime_zone($time_zone) {
		$this->time_zone = $time_zone;
	}
	public function setCharacter_set_client($character_set_client) {
		$this->character_set_client = $character_set_client;
	}
	public function setCollation_connection($collation_connection) {
		$this->collation_connection = $collation_connection;
	}
	public function setDb_collation($db_collation) {
		$this->db_collation = $db_collation;
	}
	public function setBody_utf8($body_utf8) {
		$this->body_utf8 = $body_utf8;
	}

}

?>