<?php

class MySQLTest{ 

	private $name;
	private $ret;
	private $dl;
	private $type;

	public function getName() {
		return $this->name; 
	}
	public function getRet() {
		return $this->ret; 
	}
	public function getDl() {
		return $this->dl; 
	}
	public function getType() {
		return $this->type; 
	}

	public function setName($name) {
		$this->name = $name;
	}
	public function setRet($ret) {
		$this->ret = $ret;
	}
	public function setDl($dl) {
		$this->dl = $dl;
	}
	public function setType($type) {
		$this->type = $type;
	}

}

?>