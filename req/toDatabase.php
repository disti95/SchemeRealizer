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
	 * @version  1.1
	 * @since    08.08.2016
	 * @category Mapper(UML | Class to Database)
	 */
	//Including
	include_once "../utils/File.php";
	include_once "../utils/Directory.php";
	include_once "config.php";
	$arr = array("../utils/Parsing.php"
			    ,"../utils/Arrays.php"
			    ,"../utils/String.php"
			    ,"../gen/sqlvalidation.php"
			    ,"../php/php_parse.php"
			    ,"../engines/sql.php"
			    ,"../uml/uml_parse.php"
			    ,"../utils/Arrays.php"
			    ,"../constants/constants.php"
			    ,"../native/Validate.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res);
	
	/**
	 * @category RoundTrips
	 */
	//Type-Selecting
	if(isset($_POST["type"]) && isset($_POST["dbms"]) && isset($_POST["projectpath"])) {
		$type = trim($_POST["type"]); //Trim
		switch($type) {
			case constant('__CLASS__'):
				mapClass($_POST["projectpath"], $_POST["dbms"]);
				echo json_encode($error->getArr());
			break;
			case constant('__UML__'):
				mapUML($_POST["projectpath"], $_POST["dbms"]);
				echo json_encode($error->getArr());
			break;
			default:
				//Forbidden Type
				$error->addElem(true, error\error::setError("toDatabase: Forbidden type!"));
				echo json_encode($error->getArr());
			return;
		}
	}
	//Return the maxSize of a particular datatype
	if(isset($_POST["maxSize"])) {
		echo json_encode(maxSizePerTypeMySQL($_POST["maxSize"]));
	}
	//Check AI concerning the DBMS
	if(isset($_POST["AIType"]) && isset($_POST["dbms"])) {
		switch($_POST["dbms"]){
			case constant('__MYSQL__'): 
				echo json_encode(checkAIMySQL($_POST["AIType"]));
			break;
			case constant('__SQLITE__'): 
				echo json_encode(checkAISQLite($_POST["AIType"]));
			break;
		}
	}
	//Single Element Validation-Test
	if(isset($_POST["elem"]) && isset($_POST["dbms"])) {
		validateElem(json_decode($_POST["elem"], true), $_POST["dbms"]);
		echo json_encode($error->getArr());
	}

	/**
	 * @category Mapper(Class to Database)
	 * @param    $projectpath
	 * @param    $dbms
	 * @return   array|void
	 */
	function mapClass($projectpath, $dbms) {
		global $error;
		//Check DBMS
		if(!checkDBMS($dbms))
			$error->addElem(true, error\error::setError("Forbidden DBMS!"));
		//Check for emptiness
		if(empty($projectpath))
			$error->addElem(true, error\error::setError("Empty input!"));
		//Meanwhile .. Error occured?
		if($error->hasError())
			return;
		try {
			$projectdir = new utils\Directory($projectpath, constant('__CLASS__'));
			$values     = array();
			$parser     = getDefaultParser(0); //Get the Default-Parser
			//Set values-Array
			foreach($projectdir->getFiles() as $file) {
				//Get Classes from PHP-File
				$classes         = utils\File::getClassesFromFile($file);
				$includeDetector = false;
				//Go through the Classes
				foreach($classes as $class){
					//Reflection-based
					if($parser == "reflection") {
						//BYPASS FOR THE REDECLARE ISSUE
						if(!class_exists($class) && !$includeDetector) {
							include_once $file;
							$includeDetector = true;
						}
						$rc  = new ReflectionClass($class);
						$php = new php\php_parse($rc);
					}
					//Token-based
					else {
						$php = new php\php_parse_token($file, $class);
					}
					if(isset($php) && !$php->getIsInterface()) {
						$php->setAttr();
						//Check for Emptiness
						if(!empty($php->getArr())) {
							//Set the unique filename
							$pathinfo   = pathinfo($file);
							$uniqueFile = $pathinfo["filename"]."_".$class;
							//Extract the Attributes only from the parsed PHP-Array
							$phpArr     = array();
							foreach($php->getArr() as $elem) {
								$phpArr[] = array_slice($elem, 0, 1);
							}
							$values[] = array($uniqueFile, $phpArr);
						}
					}
				}
			}
			//Check for Error
			if(!$error->hasError()) {
				//Check for Emptiness
				if(!empty($values))
					$error->addElem(false, array("datatypes"=>getValidDatatypes($dbms), "elements"=>$values));
				else
					$error->addElem(true, error\error::setError("toDatabase: No data available in $projectPath!"));
			}
		}
		catch(php\php_parseException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));
		}
		catch(ReflectionException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));
		}
		catch(utils\DirectoryException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));
		}
	}
	/**
	 * @category Mapper(UML to Database)
	 */
	function mapUML($projectpath, $dbms) {
		global $error;
		/**
		 * Basic Validations
		 */
		if(!checkDBMS($dbms))
			$error->addElem(true, error\error::setError("Forbidden DBMS!"));
		if(empty($projectpath)) 
			$error->addElem(true, error\error::setError("Empty Input!"));
		if($error->hasError())
			return;
		try {
			$projectdir = new utils\Directory($projectpath, constant('__UML__'));
		}
		catch(utils\DirectoryException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));	
			return;
		}
		try {
			$values   = array();
			$nameList = array();
			/**
			 * Go through every File with it's full Validation
			 */
			foreach($projectdir->getFiles() as $file) {
				$pathinfo = pathinfo($file);
				$name     = $pathinfo["filename"];
				/**
				 * Solve Conflicts
				 */
				if(($nc = utils\File::nameConflict($name, $nameList)) !== false)
					$name   = $nc;
				$nameList[] = $name;
				/**
				 * Parse File
				 */
				$umlParse = new uml\uml_parse($file);
				$umlParse->setArr();
				/**
				 * Extract Attr
				 */
				if(($attr = utils\Arrays::getTypeOfArr(1, $umlParse->getArr())) === false) {
					$error->addElem(true, error\error::setError("toDatabase: Not able to get Attributes from $file"));
					break;
				}
				if(!empty($attr)) {
					/**
					 * Extract Names-Only
					 */
					$umlArr = array();
					foreach($attr as $elem) {
						$umlArr[] = array_slice($elem, 0, 1);
					}
					$values[] = array($name, $umlArr);
				}
			}	
			/**
			 * Final Validation
			 */
			if(!$error->hasError()) {
				if(!empty($values)) 
					if($dbms != "mongodb") {
						$error->addElem(false, array("datatypes"=>getValidDatatypes($dbms), "elements"=>$values));
					}
					else {
						$error->addElem(false, array("datatypes"=>getValidDatatypes($dbms), "elements"=>$values, "colparams"=>getCollectionParams($dbms)));
					}
				else
					$error->addElem(true,  error\error::setError("toDatabase: No data available in $projectpath"));
			}
		}
		catch(uml\uml_parseException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));
		}
	}
	/**
	 * @category Validator
	 * @param    array $arr
	 * @param    $dbms
	 * @obsolete use req/funcReq.php instead
	 * @return   void
	 */
	function validateElem(array $arr, $dbms) {
		global $error;
		if(!checkDBMS($dbms)) {
			$error->addElem(true, error\error::setError("Invalid DBMS!"));
			return;
		}
		switch(strtolower($dbms)) {
			case constant('__MYSQL__'):
				if(($result = validateMySQL($arr)) !== true) {
					$error->addElem(true, error\error::setError($result));
					return;
				}
			break;
			case constant('__SQLITE__'):
				if(($result = validateSQLite($arr)) !== true) {
					$error->addElem(true, error\error::setError($result));
					return;
				}
			break;
		}
		$error->addElem(false, "");
	}
?>