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
	 * @category Mapper(Class | Database to UML-Diagram)
	 */
	//Including
	include_once "../utils/File.php";
	include_once "../utils/Directory.php";
	include_once "config.php"; 
	$arr = array("../utils/Parsing.php" 
			    ,"../utils/String.php"
			    ,"../php/php_parse.php"
			    ,"../orm/mysql_orm.php"
			    ,"../orm/sqlite_orm.php"
				,"../orm/mongodb_orm.php"
			    ,"../engines/sql.php"
				,"../engines/mongodb.php"
			    ,"../utils/Arrays.php"
			    ,"../constants/constants.php"
			    ,"../native/Validate.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res);
	
	/**
	 * @category Routing-Handler
	 */
	if(isset($_POST["type"])) {
		$type = trim($_POST["type"]);
		switch($type) {
			case constant('__CLASS__'):
				if(isset($_POST["projectpath"])) {
					mapClass($_POST["projectpath"]);
					echo json_encode($error->getArr());
				}
			break;
			case constant('__MYSQL__'):
				if(isset($_POST["host"]) && isset($_POST["user"]) && isset($_POST["pwd"]) && isset($_POST["db"])) {
					mapMySQL($_POST["host"], $_POST["type"], $_POST["user"], $_POST["pwd"], $_POST["db"]);
					echo json_encode($error->getArr());
				}
			break;
			case constant('__SQLITE__'):
				if(isset($_POST["type"]) && isset($_POST["projectpath"])) {
					mapSQLite($_POST["type"], $_POST["projectpath"]);
					echo json_encode($error->getArr());
				}
			break;
			case constant('__MONGODB__'):
				if(isset($_POST["host"]) && isset($_POST["user"]) && isset($_POST["pwd"]) && isset($_POST["db"])) {
					mapMongoDB($_POST["host"], $_POST["type"], $_POST["user"], $_POST["pwd"], $_POST["db"], $_POST["port"]);
					echo json_encode($error->getArr());
				}
				break;
			default:
				//Forbidden Type
				$error->addElem(true, error\error::setError("toUML: Forbidden type!"));
				echo json_encode($error->getArr());
			return;
		}
	}

	/**
	 * @category Mapper(Class to UML)
	 * @param    $projectPath
	 */
	function mapClass($projectPath) {
		global $error; //Error-Handling
		//Check emptiness
		if(empty($projectPath)) {
			$error->addElem(true, error\error::setError("Please leave no field empty!"));
			return;
		}
		//Get files of the project
		try {
			$projectdir = new utils\Directory($projectPath, constant('__CLASS__'));
		}
		catch(utils\DirectoryException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));
		}
		//Meanwhile .. Error occured?
		if($error->hasError())
			return;
		try {
			$values = array();
			$parser = getDefaultParser(0); //get Default-Parser
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
					if(isset($php)) {
						if(!$php->getIsInterface())
							$php->setAttr();
						$php->setMethods();
						//Check for Emptiness
						if(!empty($php->getArr())) {
							$php->setClass();
							//Set the unique filename
							$pathinfo   = pathinfo($file);
							$uniqueFile = $pathinfo["filename"]."_".$class;
							$values[]   = array($uniqueFile, $php->getArr());
						}
					}
				}
			}
			//Check for Error
			if(!$error->hasError()) {
				//Check for Emptiness
				if(!empty($values))
					$error->addElem(false, $values);
				else
					$error->addElem(true, error\error::setError("toUML: No data available in $projectPath!"));
			}
		}
		catch(php\php_parseException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));
		}
		catch(ReflectionException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));
		}
	}
	/**
	 * @category Mapper(MySQL to UML)
	 */	
	function mapMySQL($host, $dbms, $user, $pwd, $db) {
		global $error;
		$dbms = strtolower($dbms);
		$emptyArr = array($host, $dbms, $user, $db);
		$arrays = new utils\Arrays();
		$arrays->setEmptiness($emptyArr);
		/**
		 * Basic Input-Validation
		 */
		if($arrays->emptiness) 
			$error->addElem(true, error\error::setError("Please leave no field empty!"));
		if($dbms != constant('__MYSQL__') && $dmbs != "mariadb")
			$error->addElem(true, error\error::setError("Forbidden DBMS!"));
		if($error->hasError())
			return;
		/**
		 * Parsing
		 */
		try {
			$values = array();
			//Get Connection
			$sql = new engine\sql($dbms, $host, $user, $pwd);
			$sql->getConnection($db);
			//Get 'Classes', actually a bad label for this task
			$mysql_orm = new orm\mysql_orm($sql);
			$mysql_orm->setClass();
			//Get all Attr per 'Class'
			foreach($mysql_orm->getClass() as $file) {
				$mysql_orm->setAttr($file);
				$umlArr   = array();
				if(!empty($umlgenArr = $mysql_orm->umlGenArrNotation()))
					$values[] = array($file, $mysql_orm->umlGenArrNotation());
			}
			$sql->closeConnection();
			if(empty($values)) {
				$error->addElem(true, error\error::setError("toUML: No data available in "
														   .\utils\String::getDSN($host, $user, $db)."!"));
				return;
			}
			else
				$error->addElem(false, $values);
		}
		catch(engine\DatabaseException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));
		}
		catch(orm\mysql_ormException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));	
		}
 	}
 	/**
 	 * @category Mapper(MongoDB to UML)
 	 */
 	function mapMongoDB($host, $dbms, $user, $pwd, $db, $port) {
 		global $error;
 		$dbms = strtolower($dbms);
 		$emptyArr = array($host, $dbms, $user, $db);
 		$arrays = new utils\Arrays();
 		$arrays->setEmptiness($emptyArr);
 		/**
 		 * Basic Input-Validation
 		 */
 		if($arrays->emptiness)
 			$error->addElem(true, error\error::setError("Please leave no field empty!"));
 		if($dbms != constant('__MONGODB__'))
 			$error->addElem(true, error\error::setError("Forbidden DBMS!"));
 		if($error->hasError())
 			return;
 		/**
 		 * Parsing
 		 */
 		try {
	 		$mongo = new \engine\mongo($host, $user, $pwd, $port);
	 		$mongo->openConnection();
	 		$mongo_orm= new orm\mongodb_orm($mongo->getConnection());
	 		$mongo_orm->setDatabase($db);
	 		$collectionlist=$mongo_orm->getCollections();
	 		$umlArr = array();
	 		foreach($collectionlist as $col) {
	 			//var_dump($col);
	 			$mongo_orm->setCollection($col);
	 			if(!empty($mongo_orm->umlGenArrNotation()))
	 				$values[] = array($col, $mongo_orm->umlGenArrNotation());
	 		}
	 		if(empty($values)) {
	 			$error->addElem(true, error\error::setError("toUML: No data available in "
	 					.\utils\String::getDSN($host, $user, $db)."!"));
	 			return;
	 		}
	 		else
	 			$error->addElem(false, $values);
 		}
 		catch(engine\mongodbException $e) {
 			$error->addElem(true, error\error::setError($e->getMessage()));
 		}
 		catch(orm\mongodb_ormException $e) {
 			$error->addElem(true, error\error::setError($e->getMessage()));
 		}
 	}
	/**
	 * @category Mapper(SQLite to UML)
	 */
	function mapSQLite($dbms, $projectpath) {
		global $error;
		$dbms     = strtolower($dbms);
		$emptyArr = array($dbms, $projectpath);
		$arrays   = new utils\Arrays();
		$arrays->setEmptiness($emptyArr);
		/**
		 * Basic Validations
		 */
		if($arrays->emptiness)
			$error->addElem(true, error\error::setError("Please leave no field empty!"));
		if($dbms != constant('__SQLITE__'))
			$error->addElem(true, error\error::setError("Forbidden DBMS!"));
		if($error->hasError())
			return;
		try {
			$projectdir = new utils\Directory($projectpath, $dbms);
		}
		catch(utils\DirectoryException $e){
			$error->addElem(true, error\error::setError($e->getMessage()));
			return;
		}
		/**
		 * Parsing
		 */
		try {
			$sql      = new engine\sql(constant('__SQLITE__'));
			$values   = array();
			$nameList = array();
			//Go through every File
			foreach($projectdir->getFiles() as $file) {
				$pathinfo = pathinfo($file);
				//Get Connection and set 'Classes', actually a bad label for this task
				$sql->getConnection($file);
				$sqlite = new orm\sqlite_orm($sql);
				$sqlite->setClass();
				//Go through the 'Classes'
				foreach($sqlite->getClass() as $class) {
					$sqlite->setAttr($class);
					$name = $pathinfo["filename"]."_".$class;
					//Solve Name-Conflicts
					if(($nc = utils\File::nameConflict($name, $nameList)) !== false) 
						$name = $nc;
					if(empty($umlgenArr = $sqlite->umlGenArrNotation()))
							continue;
					$values[]   = array($name, $sqlite->umlGenArrNotation());
					$nameList[] = $name;
				}
			}
			$sql->closeConnection();
			if(empty($values)) {
				$error->addElem(true, error\error::setError("toUML: No data available in $projectpath!"));
				return;
			}
			else
				$error->addElem(false, $values);
		}
		catch(orm\sqlite_ormException $e) {
			$error->setError(true, error\error::setError($e->getMessage()));
		}
		catch(engine\DatabaseException $e) {
			$error->setError(true, error\error::setError($e->getMessage()));
		}
	}
?>