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
	 * @category SQLite test
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../gen/sqlgen.php"
			    ,"../../examples/Event.php"
			    ,"../../php/php_parse.php"
			    ,"../../utils/Parsing.php"
			    ,"../../gen/sqlvalidation.php"
			    ,"../../error/error.php"
			    ,"../../engines/sql.php"
			    ,"../../utils/Arrays.php"
			    ,"../../constants/constants.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");
	
	//Check for argument
	if(!isset($argv[1])) {
		echo "Parameter missing!\n";
		echo "Usage: php $argv[0] <SQLite-File>\n";
		exit(1);
	}
	//Get the SQL-Code with sqlgen
	try {
		$rc     = new ReflectionClass("event");
		$php    = new php\php_parse($rc);
		$php->setAttr();
		$sqlArr = array();
		$i      = 0;
		foreach($php->getArr() as $elem) 
			$sqlArr[$i++] = array($elem[0], constant('__SQL_INTEGER__'), 1, -1, 1, -1, true, false);
		$sqlgen = new gen\sqlgen($sqlArr, "sqlite", "SQLiteTest");	
	}
	catch(ReflectionException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
	catch(php\php_parseException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
	catch(gen\sqlgenException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
	$example = "../../examples/".$argv[1];
	//Create the SQLite-File
	try {
		//Strip unnecessary tabs, new lines
		$sqlCode = str_replace(array("\n", "\t"), "", $sqlgen->getSQLCode());
		$sql     = new engine\sql("sqlite");
		$sql->getConnection($example);
		$sql->prepareAndQuery($sqlCode, array());
		$sql->closeConnection();
	}
	catch(engine\DatabaseException $e){
		echo $e->getMessage()."\n";
		exit(1);
	}
	exit(0);