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
	 * @version  1.0
	 * @since    ?
	 * @category Generator Testing
	 * @obsolete Was created at the beginning, now we rely on separated test files
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../constants/constants.php"
			    ,"../../gen/classgen.php"
				,"../../utils/String.php"
				,"../../utils/Arrays.php"
			    ,"../../utils/Parsing.php"
			    ,"../../error/error.php"
			    ,array("../../orm", "class")
			    ,array("../../uml", "class")
			    ,"../../error/error.php"
			    ,array("../../php", "class")
			    ,"../../gen/umlgen.php"
			    ,array("../../engines", "class")
			    ,"../../native/Validate.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");
		
	//Check for Shell
	if(php_sapi_name() != "cli") {
		echo "Please use the Shell!\n";
		exit(1);
	}
	//Check for Parameter
	if(!isset($argv[1])) {
		echo "Parameter missing!\n";
		echo "Usage: php tests.php <engine>\n";
		echo "Engines: [mysql], [sqlite], [uml]\n";
		exit(1);
	}
	//Execute the Engine-Tests
	switch($argv[1]) {
		case "mysql": 
			/*
			 * ORM Test
			 */
			//Get Connection
			try{
				$connection = new engine\sql("mysql", "localhost", "root", "");
				$connection->getConnection("mysql");
				//Get Data with ORM
				$mysql_orm  = new orm\mysql_orm($connection);
				$mysql_orm->setAttr("func");
				$attributes = $mysql_orm->classGenArrNotation();
				//Close Database Connection
				$connection->closeConnection();
			}
			catch(engine\DatabaseException $e) {
				echo $e->getMessage()."\n";
				exit(1);
			}		
			catch(orm\mysql_ormException $e) {
				echo $e->getMessage()."\n";
				exit(1);
			}
			/*
			 * ClassGen Test
			 */
			echo "MySQL-ClassGen Test:\n\n";
			try{
				//Test the MySQL-Engine
				for($i = 0; $i < count($attributes); $i++) //Set selected TRUE
					$attributes[$i][3] = true;
				$classgen = new gen\classgen($attributes, "MySQLTest");
				//Get File content and Flush it
				echo $classgen->getPHPFile()."\n";
				echo "Flushing file to ../../examples/MySQLTest.php\n";
				$classgen->flushFile("../../examples/MySQLTest.php");
			}
			catch(gen\classgenException $e) {
				echo $e->getMessage()."\n";
				exit(1);
			}
		break;
		case "sqlite": 
			/*
			 * ORM Test
			*/
			//Get Connection
			try{
				$connection = new engine\sql("sqlite");
				$connection->getConnection("../../examples/MyLIB.db");
				//Get Data with ORM
				$sqlite_orm = new orm\sqlite_orm($connection);
				$sqlite_orm->setAttr("Comments");
				$attributes = $sqlite_orm->classGenArrNotation();
				//Close Database Connection
				$connection->closeConnection();
			}
			catch(engine\DatabaseException $e) {
				echo $e->getMessage()."\n";
				exit(1);
			}
			catch(orm\sqlite_ormException $e) {
				echo $e->getMessage()."\n";
				exit(1);
			}
			/*
			 * ClassGen Test
			*/
			echo "SQLite-ClassGen Test:\n\n";
			try{
				//Test the SQLite-Engine
				for($i = 0; $i < count($attributes); $i++) //Set selected TRUE
					$attributes[$i][3] = true;
				$classgen = new gen\classgen($attributes, "SQLiteTest");
				//Get File content and Flush it
				echo $classgen->getPHPFile()."\n";
				echo "Flushing file to ../../examples/SQLiteTest.php\n";
				$classgen->flushFile("../../examples/SQLiteTest.php");
			}
			catch(gen\classgenException $e) {
				echo $e->getMessage()."\n";
				exit(1);
			}
		break;
		case "uml":
			/*
			 * UML-Import Test
			*/
			echo "UML-Import Test:\n\n";
			try {
				//Get uml_parse-Array
				$uml_parse = new uml\uml_parse("../../examples/UMLExample.txt");
				$uml_parse->setArr();
				$data      = $uml_parse->getArr();
				for($i = 0; $i < count($data); $i++){ //Set selected TRUE
					$data[$i][3] = true;
				}
				$classgenUML = new gen\classgen($data, "userUMLTest");
				//Get File content and Flush it
				echo $classgenUML->getPHPFile()."\n";
				echo "Flushing file to ../../examples/userUMLTest.php\n";
				$classgenUML->flushFile("../../examples/userUMLTest.php");
			}
			catch(uml\uml_parseException $e) {
				echo $e->getMessage()."\n";
				exit(1);
			}
			catch(gen\classgenException $e) {
				echo $e->getMessage()."\n";
				exit(1);
			}
			/*
			 * UML-Export Test
			 */
			echo "UML-Export Test:\n\n";
			try {
				//Get Array
				include_once "../../examples/userUMLTest.php";
				$rc        = new ReflectionClass("userUMLTest");
				$php_parse = new php\php_parse($rc);
				$php_parse->setClass();
				$php_parse->setAttr();
				$php_parse->setMethods();
				$elements = $php_parse->getArr();
				
				//Tag the elements as selected
				for($i = 0; $i < count($elements); $i++) 
					$elements[$i][3] = true;
				
				echo "TXT:\n\n";
				
				$umlgen = new gen\umlgen($elements);
				//Get the UML-Content and flush the Diagram
				echo $umlgen->getUMLFileContent();"\n";
				echo "Flushing file to ../../examples/userUMLGenTest.txt\n";
				$umlgen->flushFile("../../examples/userUMLGenTest.txt");
				
				echo "\nJPG:\n\n";

				$umlgen = new gen\umlgen($elements);
				//Get the UML-Content and flush the Diagram
				echo $umlgen->getUMLFileContent();"\n";
				echo "Flushing file to ../../examples/userUMLGenTest.jpg\n";
				$umlgen->flushFile("../../examples/userUMLGenTest.jpg");
				
				echo "\nPNG:\n\n";

				$umlgen = new gen \umlgen($elements);
				//Get the UML-Content and flush the Diagram
				echo $umlgen->getUMLFileContent();"\n";
				echo "Flushing file to ../../examples/userUMLGenTest.png\n";
				$umlgen->flushFile("../../examples/userUMLGenTest.png");
			}
			catch(php\php_parseException $e) {
				echo $e->getMessage()."\n";
				exit(1);
			}
			catch(gen\umlgenException $e) {
				echo $e->getMessage()."\n";
				exit(1);
			}
		break;
		default:
			echo "Wrong engine!\n";
			echo "Supported engines:\n\n";
			echo "1. mysql -> MySQL\n";
			echo "2. uml -> UML-Diagram\n";
		break;
	}
	exit(0);
