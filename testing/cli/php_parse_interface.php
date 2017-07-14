<?php
	use php\php_parse;

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
	 * @author   Michael Watzer
	 * @version  1.0
	 * @category Testing the php parser concerning interfaces
	 * @since    10.04.2017
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$fileToParse      = \utils\Directory::HOME()."/workspace/SchemeRealizer/examples/PHP_Parser_TestClass.php";
	$interfaceToParse = "PHP_Parser_TestInterface";
	$arr = array("../../utils/String.php"
		        ,"../../utils/Parsing.php"
		        ,"../../php/php_parse.php"
		        ,"../../error/error.php"
       	        ,$fileToParse
		        ,"../../constants/constants.php"
		        ,"../../utils/Arrays.php"
		        ,"../../native/Validate.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");
	
	try {
        echo \utils\String::getCommentFrame("Reflection-based parser");

		$rc  = new ReflectionClass($interfaceToParse);
		$php = new php_parse($rc);
		$php->setMethods();
		$php->setClass();
		var_dump($php->getArr());
		$reflection_arr = $php->getArr();

        echo \utils\String::getCommentFrame("Token-based Parser");

		$php = new php\php_parse_token($fileToParse, $interfaceToParse);
		$php->setMethods();
		$php->setClass();
		var_dump($php->getArr());

        echo \utils\String::getCommentFrame("Compare reflection-based Parser and token-based Parser");

		if(utils\Arrays::compareArr($reflection_arr, $php->getArr()))
			echo "OK!\n";
		else
			echo "NOK!\n";
	}
	catch(php\php_parseException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
	catch(ReflectionException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
	exit(0);
