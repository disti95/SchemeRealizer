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
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../utils/Parsing.php" 
			    ,"../../php/php_parse.php"
			    ,"../../error/error.php"
			    ,"../../gen/sqlgen.php"
			    ,"../../gen/sqlvalidation.php"
			    ,"../../examples/Event.php"
			    ,"../../utils/Arrays.php"
			    ,"../../constants/constants.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");
	
	//Check if the engine-Parameter is set and if its a valid one
	if(!isset($argv[1])) {
		echo "Engine missing [mysql, sqlite]!\n";
		exit(1);
	}
	if($argv[1] != "mysql" && $argv[1] != "sqlite"){
		echo "Forbidden engine: [mysql, sqlite]!\n";
		exit(1);
	}
	
	try {
		$rc  = new ReflectionClass("event");
		$php = new php\php_parse($rc);
		$php->setAttr();
		$phpArr = $php->getArr();
		$sqlArr = array();
		//Values for rand-Generation
		$nullvalues  = array(-1, 1);
		$indexvalues = array(-1, 2, 1, 3);
		/*
		 * Build-Up = array(name, datatype, size, index, null, autoincrement, selected, default)
		 * index: 1 = PRIMARY, 2 = UNIQUE, 3 = INDEX, -1 = false
		 * null:  1 = true,   -1 = false
		 * autoincrement: 1 = true, -1 = false
		 */
		foreach($phpArr as $elem) {
			//Array-Set for MySQL
			if($argv[1] == "mysql") 
				if($elem == $phpArr[0])
					$sqlArr[] = array($elem[0], "int", 11, 1, 1, 1, true, false);
				else
					$sqlArr[] = array($elem[0], "int", 11, $indexvalues[rand(0,3)], $nullvalues[rand(0,1)], -1, true, false);
			//Array-Set for SQLite
			elseif($argv[1] == "sqlite") 
				if($elem == $phpArr[0])
					$sqlArr[] = array($elem[0], "integer", -1, 1, -1, 1, true, false);
				else
					$sqlArr[] = array($elem[0], "int", -1, $indexvalues[rand(0,1)], $nullvalues[rand(0,1)], -1, true, false);
		}
		//Get the SQL-Code
		$sqlgen = new gen\sqlgen($sqlArr, $argv[1], "Event");
		echo $sqlgen->getSQLCode()."\n";
	}
	catch(gen\sqlgenException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
	catch(php\php_parseException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
	exit(0);
