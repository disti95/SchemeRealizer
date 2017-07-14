<?php 
	/*
	 SchemeRealizer - A free ORM and Translator for Diagrams, Databases and PHP-Classes/Interfaces.
	Copyright (C) 2016  Michael Watzer
	
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU Affero General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU Affero General Public License for more details.
	
	You should have received a copy of the GNU Affero General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
	*/
	/**
	 * @author   Michael Watzer
	 * @category AJAX-Req Handler
	 * @since    ?
	 * @version  1.1
	 */
	//Including
	include_once "../utils/File.php";
	include_once "../utils/Directory.php";
	include_once "../gen/sqlvalidation.php";
	$arr = array("../error/error.php"
			    ,"../utils/XML.php"
			    ,"../utils/String.php"
			    ,"../constants/constants.php");
	if(($res = \utils\File::setIncludes($arr)) !== true)
		die($res);
	
	$conf  = "../config/config.xml";
	$error = new \error\error();
	$nodes = array("class"
			      ,"uml"
			      ,"sql"
			      ,"classparser"
			      ,"path"
				  ,"host"
				  ,"user"
			      ,"db");
	
	/**
	 * @category Routing-Handler
	 */
	if(isset($_POST["path"]) && isset($_POST["method"])) {
		validatePath($_POST["path"]);
		validateMethod($_POST["method"]);
		if(!$error->hasError()) {
			setDefaultPath($_POST["path"], $_POST["method"]);
			if(!$error->hasError())
				$error->addElem(false
						       ,\utils\String::getSuccessMsg('config: Successfully changed path to '.$_POST["path"].'!'));
		}
		echo json_encode($error->getArr());
	}
	/**
	 * @category Routing-Handler
	 */
	if(isset($_POST["getpath"]) && isset($_POST["method"])) {
		validateMethod($_POST["method"]);
		if(!$error->hasError()) {
			$ret = getDefaultPath($_POST["method"]);
			if(!$error->hasError()) 
				$error->addElem(false, \utils\String::getSuccessMsg($ret));
		}
		echo json_encode($error->getArr());
	}
	/**
	 * @category Routing-Handler
	 */
	if(isset($_POST["reset"])) {
		resetConfig();
		echo json_encode($error->getArr());
	}
	/**
	 * @category Routing-Handler
	 */
	if(isset($_POST["method"]) && isset($_POST["parser"])) {
		validateMethod($_POST["method"]);
		validateParser($_POST["method"], $_POST["parser"]);
		if(!$error->hasError()) {
			$parser = getParser($_POST["method"], $_POST["parser"]);
			setDefaultParser($_POST["method"]
					        ,$parser);
			if(!$error->hasError())
				$error->addElem(false
						       ,\utils\String::getSuccessMsg('config: Successfully changed parser to '.$parser.'!'));
		}
		echo json_encode($error->getArr());
	}
	/**
	 * @category Routing-Handler
	 */
	if(isset($_POST["method"]) && isset($_POST["getparser"])) {
		validateMethod($_POST["method"]);
		if(!$error->hasError()) {
			$ret = getDefaultParser($_POST["method"]);
			if(!$error->hasError()) 
				$error->addElem(false, \utils\String::getSuccessMsg($ret));
		}
		echo json_encode($error->getArr());
	}
	/**
	 * @category Routing-Handler
	 */
	if(isset($_POST["setprojectpath"])) {
		validatePath($_POST["setprojectpath"]);
		if(!$error->hasError()) {
			setProjectPath($_POST["setprojectpath"]);
			if(!$error->hasError())
				$error->addElem(false
						       ,\utils\String::getSuccessMsg('config: Successfully changed project path to '.$_POST["setprojectpath"].'!'));
		}
		echo json_encode($error->getArr());
	}
	/**
	 * @category Routing-Handler
	 */
	if(isset($_POST["getprojectpath"])) {
		$ret = getProjectPath();
		if(!$error->hasError())
			$error->addElem(false, \utils\String::getSuccessMsg($ret));
		echo json_encode($error->getArr());
	}
	/**
	 * @category Routing-Handler
	 */
	if(isset($_POST["setDSN"]) && isset($_POST["host"]) && isset($_POST["user"]) && isset($_POST["db"])) {
		validateDB($_POST["db"]);
		if(!$error->hasError()) {
			setDSN($_POST["host"]
				  ,$_POST["user"]
				  ,$_POST["db"]);
			if(!$error->hasError())
				$error->addElem(false
						       ,\utils\String::getSuccessMsg('config: Successfully changed to '.\utils\String::getDSN($_POST["host"], $_POST["user"], $_POST["db"])."!"));
		}
		echo json_encode($error->getArr());
	}
	/**
	 * @category Routing-Handler
	 */
	if(isset($_POST["getDSN"])) {
		$ret = getDSN();
		if(!$error->hasError()) 
			$error->addElem(false, \utils\String::getSuccessMsg($ret));
		echo json_encode($error->getArr());
	}
	/**
	 * @category Getter
	 */
	function getMethod($method) {
		switch($method) {
			case 0:
				return "class";
			break;
			case 1:
				return "uml";
			break;
			case 2:
				return "sql";
			break;
		}
	}
	/**
	 * @category Getter
	 */
	function getParser($method, $parser) {
		switch($method) {
			case 0:
				switch($parser) {
					case 0:
						return "reflection";
					break;
					case 1:
						return "token";
					break;
				}		
			break;
		}
	}
	/**
	 * @category Validator
	 */
	function validatePath($path) {
		global $error;
		
		$path = str_replace(" ","",$path);
		if(empty($path)) 
			$error->addElem(true, \error\error::setError("config:Please leave no field empty!"));
		if(!is_dir($path)) 
			$error->addElem(true, \error\error::setError("config:Path ".$path." isn't a directory!"));
		if($path == "/") 
			$error->addElem(true, \error\error::setError("config:Path ".$path." isn't allowed!"));
	}
	/**
	 * @category Validator
	 */
	function validateMethod($method) {
		global $error;
		
		if($method == 0 || $method == 1 || $method == 2)
			return;
		$error->addElem(true, \error\error::setError("config:Forbidden Type indicator!"));
	}
	/**
	 * @category Validator
	 */
	function validateParser($method, $parser){
		global $error;
		
		switch($method) {
			case 0:
				if($parser == 0 || $parser == 1)
					return;
			break;
		}
		$error->addElem(true, \error\error::setError("config:Forbidden Parser indicator!"));
	}
	/**
	 * @category Validator
	 */
	function validateDB($db) {
		global $error;
		if(!regexName($db))
			$error->addElem(true, \error\error::setError("config:Forbidden database name $db!"));
	}
	/**
	 * @category Setter
	 */
	 function setDefaultPath($path, $method){ 
	 	global $conf, $error;
	 	
	 	try {
	 		$xml  = new \utils\XML();
	 		$type = getMethod($method);
	 		if(($res = $xml->setNodeVal($type, $path)) !== true) 
	 			$error->addElem(true, \error\error::setError($res));
	 	}
	 	catch(\utils\XMLException $e) {
	 		$error->addElem(true, \error\error::setError($e->getMessage()));
	 	}
	}
	/**
	 * @category Setter
	 */
	function setDefaultParser($method, $parser) {
		global $error, $conf;

		try {
			$xml    = new \utils\XML();
			$type   = getMethod($method)."parser";
			if(($res = $xml->setNodeVal($type, $parser)) !== true) 
				$error->addElem(true, \error\error::setError($res));
		}
		catch(\utils\XMLException $e) {
			$error->addElem(true, \error\error::setError($e->getMessage()));
		}
	}
	/**
	 * @category Getter
	 */
	function getDefaultPath($method) {
		global $conf, $error;

		try {
			$xml  = new \utils\XML();
			$type = getMethod($method);
			return $xml->getNodeVal($type);
		}
		catch(\utils\XMLException $e) {
			$error->addElem(true, \error\error::setError($e->getMessage()));
		}
	}
	/**
	 * @category Getter
	 */
	function getDefaultParser($method){
		global $conf, $error;
		
		try {
			$xml  = new \utils\XML();
			$type = getMethod($method);
			return $xml->getNodeVal($type."parser");
		}
		catch(\utils\XMLException $e) {
			$error->addElem(true, \error\error::setError($e->getMessage()));
		}
	}
	/**
	 * @category Setter(project path)
	 * @param    project path
	 */
	function setProjectPath($path) {
		global $conf, $error;
		
		try {
			$xml = new \utils\XML();
			if(($ret = $xml->setNodeVal("path", $path)) !== true) 
				$error->addElem(true, \error\error::setError($ret));
		}
		catch(\utils\XMLException $e) {
			$error->addElem(true, \error\error::setError($e->getMessage()));
		}
	}
	/**
	 * @category Getter(project path)
	 */
	function getProjectPath() {
		global $conf, $error;
		
		try {
			$xml = new \utils\XML();
			return $xml->getNodeVal("path");
		}
		catch(\utils\XMLException $e) {
			$error->addElem(true, \error\error::setError($e->getMessage()));
		}
	}
	/**
	 * @category Setter(DSN)
	 * @param    host
	 * @param    user
	 * @param    db
	 */
	function setDSN($host, $user, $db) {
		global $conf, $error;
		
		try {
			$xml = new \utils\XML();
			if(($ret = $xml->setNodeVal("host", $host)) !== true)
				$error->addElem(true, \error\error::setError($ret));
			if(($ret = $xml->setNodeVal("user", $user)) !== true)
				$error->addElem(true, \error\error::setError($ret));		
			if(($ret = $xml->setNodeVal("db", $db)) !== true)
				$error->addElem(true, \error\error::setError($ret));
		}	
		catch(\utils\XMLException $e) {
			$error->addElem(true, \error\error::setError($e->getMessage()));
		}
	}
	/**
	 * @category Getter(DSN)
	 */
	function getDSN() {
		global $conf, $error;
		
		try {
			$xml = new \utils\XML();
			return \utils\String::getDSN($xml->getNodeVal("host")
					                    ,$xml->getNodeVal("user")
					                    ,$xml->getNodeVal("db"));
		}
		catch(\utils\XMLException $e) {
			$error->addElem(true, \error\error::setError($e->getMessage()));
		}
	}
 	/**
	 * @category Setter/Reseting
	 */
	function resetConfig() { 
		global $conf, $error, $nodes;
		
		foreach($nodes as $node) {
			try {
				$xml = new \utils\XML();
				$ori = $xml->getNodeVal("origin".$node);
				if(($ret = $xml->setNodeVal($node, $ori)) !== true) 
					$error->addElem(true, \error\error::setError($ret));
			}
			catch(\utils\XMLException $e) {
				$error->addElem(true, \error\error::setError($e->getMessage()));
			}
		}
		if(!$error->hasError()) 
			$error->addElem(false
					       ,\utils\String::getSuccessMsg("config:Successfully reseted config!"));
	}
?>