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
	 * @category Mapper(UML | Database to Class)
	 */

	//Including
	include_once "../utils/File.php";
	include_once "../utils/Directory.php";
	include_once "config.php";
	$arr = array("../utils/Parsing.php" 
			    ,"../utils/Arrays.php"
			    ,"../engines/sql.php"
				,"../engines/mongodb.php"
			    ,"../uml/uml_parse.php"
			    ,"../orm/mysql_orm.php"
				,"../orm/mongodb_orm.php"
			    ,"../orm/sqlite_orm.php"
			    ,"../constants/constants.php"
				,"../utils/String.php"
			    ,"../native/Validate.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res);
	
	//Type-Selecting
	if(isset($_POST["type"])) {
		$type = trim($_POST["type"]); //Trim
		switch($type) {
			case constant('__UML__'):
				if(isset($_POST["projectpath"])) {
					mapUML($_POST["projectpath"]);
					echo json_encode($error->getArr());
				}
			break;	
			case constant('__MYSQL__'):
				if(isset($_POST["host"]) && isset($_POST["type"]) && isset($_POST["user"]) && isset($_POST["pwd"]) && isset($_POST["db"])) {
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
				$error->addElem(true, error\error::setError("toClass: Forbidden type!"));
				echo json_encode($error->getArr());
			break;
		}
	}
	
	/**
	 * @category Mapper(UML to Class)
	 * @param    $projectpath
	 */
	function mapUML($projectpath) {
		global $error; //Error-Handling
		//Check if a relevant field is empty
		if(empty($projectpath)) {
			$error->addElem(true, error\error::setError("Please leave no field empty!"));
			return;
		}
		try {
			$projectdir = new utils\Directory($projectpath, constant('__UML__'));
		}
		catch(utils\DirectoryException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));
			return;
		}
		$values   = array();
		$nameList = array();
		foreach($projectdir->getFiles() as $file) {
			try {
				$uml = new uml\uml_parse($file);
				$uml->setArr();
				if(!\utils\Arrays::typeInUMLArr(1, $uml->getArr())
				&& !\utils\Arrays::typeInUMLArr(4, $uml->getArr()))
					continue;
			}
			catch(uml\uml_parseException $e) {
				$error->addElem(true, error\error::setError($e->getMessage()));
				return;
			}
			$pathinfo = pathinfo($file);
			$name     = $pathinfo["filename"];
			//Solve Name-Conflicts
			if(($nc = utils\File::nameConflict($name, $nameList)) !== false) {
				$name = $nc;
			}
			$nameList[] = $name;
			$values[]   = array($name, $uml->getArr());
		}
		if(empty($values)) {
			$error->addElem(true, error\error::setError("toClass: No data available in $projectpath!"));
			return;
		}
		else
			$error->addElem(false, $values);
		return;
	}
	/**
	 * @category Mapper(MySQL|MariaDB to Class)
	 * @param    $host
	 * @param    $dbms
	 * @param    $user
	 * @param    $pwd
	 * @param    $db
	 */
	function mapMySQL($host, $dbms, $user, $pwd, $db) {
		global $error; //Error-Handling
		$dbms   = strtolower($dbms);
		$elem   = array($host, $dbms, $user, $db);
		$empArr = new utils\Arrays();
		$empArr->setEmptiness($elem);
		//Check elements for emptiness
		if($empArr->emptiness) {
			$error->addElem(true, error\error::setError("Please leave no field empty!"));
			return;
		}
		//Check if the DBMS is allowed
		if($dbms != constant('__MYSQL__') && $dbms != "mariadb")
			$error->addElem(true, error\error::setError("Invalid DBMS!"));
		//Meanwhile .. Error occured?
		if($error->hasError())
			return;
		try {
			$values = array();
			//Get the Connection
			$connection = new engine\sql($dbms, $host, $user, $pwd);
			$connection->getConnection($db);
			//Get the Tables via ORM
			$mysql_orm = new orm\mysql_orm($connection);
			$mysql_orm->setClass();
			$tab       = $mysql_orm->getClass();
			//Get the Attr for each Table via ORM
			foreach($tab as $table) {
				$mysql_orm->setAttr($table);
				if(!empty($classgenArr = $mysql_orm->classGenArrNotation()))
					$values[] = array($table, $classgenArr);
			}
			//Close the Connection
			$connection->closeConnection();
			if(empty($values)) {
				$error->addElem(true, error\error::setError("toClass: No data available in "
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
	 * @category Mapper(MongoDB to Class)
	 * @param 	 $host
	 * @param 	 $user
	 * @param 	 $pw
	 * @param 	 $port
	 */
	function mapMongoDB($host, $dbms, $user, $pwd, $db, $port){
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
	 			if(!empty($mongo_orm->classGenArrNotation()))
	 				$values[] = array($col, $mongo_orm->classGenArrNotation());
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
	 * @category Mapper(SQLite to Class)
	 * @param    $dbms
	 * @param    $projectpath
	 */
	function mapSQLite($dbms, $projectpath) {
		global $error;
		$dbms = strtolower($dbms);
		$elem = array($dbms, $projectpath);
		$empArr = new utils\Arrays();
		$empArr->setEmptiness($elem);
		//Check for emptiness!
		if($empArr->emptiness) {
			$error->addElem(true, error\error::setError("Please leave no field empty!"));
			return;
		}
		//Check if the DBMS is allowed
		if($dbms != constant('__SQLITE__')) {
			$error->addElem(true, error\error::setError("Invalid DBMS!"));
			return;
		}
		//Try to get the .db-Files via utils\Directory
		try {
			$dirpath = new utils\Directory($projectpath, $dbms);
		}
		catch(utils\DirectoryException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));
			return;
		}
		try {
			$values = array();
			$nameList = array();
			$con = new engine\sql($dbms);
			//Go through every .db File
			foreach($dirpath->getFiles() as $file) {
				//Get the Filename, f.e /home/michael/MyLIB.db -> MyLIB
				$pathinfo = pathinfo($file);
				$filename = $pathinfo["filename"];
				//Get the Tables via ORM
				$con->getConnection($file);
				$sqlite = new orm\sqlite_orm($con);
				$sqlite->setClass();
				$tab    = $sqlite->getClass();
				foreach($tab as $table) {
					//Get Attr per Table via ORM
					$sqlite->setAttr($table);
					//Solve Name-Conflicts
					$name = $filename."_".$table;
					if(($nc = utils\File::nameConflict($name, $nameList)) !== false) {
						$name = $nc;
					}
					if(empty($classgenArr = $sqlite->classGenArrNotation()))
						continue;
					$values[]   = array($name, $classgenArr);
					$nameList[] = $name;
				}
				$con->closeConnection(); //Close connection
			}
			if(empty($values)) {
				$error->addElem(true, error\error::setError("toClass: No data available in $projectpath!"));
				return;
			}
			else
				$error->addElem(false, $values);
		}
		catch(engine\DatabaseException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));
		}
		catch(orm\sqlite_ormException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));
		}
	}
?>