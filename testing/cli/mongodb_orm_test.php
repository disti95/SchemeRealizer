<?php
	/*
	 SchemeRealizer - A free ORM and Translator for Diagrams, Databases and PHP-Classes/Interfaces.
	Copyright (C) 2017  Michael Watzer/Christian Dittrich
	
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU Affero General Public License as
	published by the Free Software Foundation, either version 3 of the
	License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU Affero General Public License for more details.
	
	You should have received a copy of the GNU Affero General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
	*/
	/**
	 * @author   Christian Dittrich
	* @version  1.0
	* @category Checking if nosqlgen code is correct
	* @since    01.05.2017
	*/
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../utils/Parsing.php" ,"../../php/php_parse.php", "../../error/error.php", "../../gen/sqlgen.php", "../../gen/nosqlgen.php",
			"../../gen/sqlvalidation.php", "../../examples/Event.php", "../../utils/Arrays.php", "../../constants/constants.php", "../../orm/mongodb_orm.php", "../../engines/mongodb.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");
	try {
	
		$eng = new engine\mongo("localhost","root","Nummer22");
		$eng->openConnection();
		$mongodb = new orm\mongodb_orm($eng->getConnection());
		
		//test of all orm functions:
		foreach( $mongodb->getDatabases() as $db){
			echo $db;
		}
		$mongodb->setDatabase("SchemeTest");
		foreach( $mongodb->getCollections() as $con){
			echo $con."</br>";
		}
		$mongodb->setCollection("Perso");
		foreach($mongodb->getKeysOfCollection() as $key){
			echo $key."</br>";
		}
		
	
	}
	catch(schemaexception $e) {
		echo $e->getMessage()."\n";
	}

?>