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
			"../../gen/sqlvalidation.php", "../../examples/Event.php", "../../utils/Arrays.php", "../../constants/constants.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");
	try {
		
		$array								 	= array("mongodb", "SchemeTest", array("sqlgencheck" => array( array("capped"=>"true"), array("name"=>array("chris", "String")))));
		$array[2]["sqlgencheck"][1]["added"] 	= array("new", "String");
		$array[2]["sqlgencheck"][1]["newone"]	= array("1", "Integer");
		$array[2]["sqlgencheck"][1]["newone22"] = array(array("objkey1" => array(array("objkey1_1" => array(array("objkey1_1_1" => array("val", "String"),"objkey1_1_2" => array("val", "String"), "objkey1_1_3" => array("val", "String")), "Object"),"objkey1_2" => array("val", "String")), "Object")), "Object");
		$array[2]["sqlgencheck"][1]["ARR1"]	 	= array(array( null => array(array( "name" =>array(array( "key" =>array("Str", "String")), "Object")), "Array"),null => array("2arrwert", "String")), "Array");
		$array[2]["sqlgencheck"][1]["ARR2"]	 	= array(array("Obj1" => array(array("inner" => array("Obj","String"),"nextinn" => array("OBJIC","String")),"Object"),"key" => array("vat","String")),"Array");
		$array[2]["sqlgencheck"][1]["ARR3"]		= array(array("Obj1" => array(array("arrinner" => array(array("" => array("innerhalb1","String"),"ds"=>array("innerhalb2","String")),"Array"),"inner" => array("Obj","String"),"nextinn" => array("OBJIC","String")),"Object"),"key" => array("vat","String")),"Array");
		$array[2]["sqlgencheck"][0]["size"]	 	="1";
		$array[2]["nextcollection"]			 	=  array( array("capped"=>"true"), array("name" => array("chris", "String")));
		
		$nosqlgen = new gen\nosqlgen($array);
		$code = $nosqlgen->getNosqlCode();
		echo $code;
	
	}
	catch(schemaexception $e) {
		echo $e->getMessage()."\n";
	}
	
?>
	