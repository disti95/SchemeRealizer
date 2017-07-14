<?php

class SQLiteTest{ 

	private $FhCId;
	private $INode;
	private $Comment;
	private $Date;

	public function getFhCId() {
		return $this->FhCId; 
	}
	public function getINode() {
		return $this->INode; 
	}
	public function getComment() {
		return $this->Comment; 
	}
	public function getDate() {
		return $this->Date; 
	}

	public function setFhCId($FhCId) {
		$this->FhCId = $FhCId;
	}
	public function setINode($INode) {
		$this->INode = $INode;
	}
	public function setComment($Comment) {
		$this->Comment = $Comment;
	}
	public function setDate($Date) {
		$this->Date = $Date;
	}

	protected function FhCId() {

	}
	protected function INode() {

	}
	protected function Comment() {

	}
	protected function Date() {

	}

}

?>