<?php
	use uml\uml_parse;

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
	 * @category Testing uml and token-based params
	 * @since    01.04.2017
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../constants/constants.php"
		    	,"../../uml/uml_parse.php"
			    ,"../../php/php_parse.php"
		    	,"../../utils/Parsing.php"
		    	,"../../utils/Arrays.php"
                ,"../../native/Validate.php"
			    ,"../../utils/String.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");
	
	echo \utils\String::getCommentFrame("UML");
	$uml = "../../examples/UMLEmployee.txt";
	try {
		$uml = new uml\uml_parse($uml);
		$uml->setArr();
		$evalarr = array();
		foreach($uml->getArr() as $elem)
			if($elem[7] && $elem[1] == 4) 
				$evalarr = array_merge($evalarr, $elem[7]);
		var_dump($evalarr);
	}
	catch(\uml\uml_parseException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
	
	echo \utils\String::getCommentFrame("PHP - Token-based");
	$php_file = "../../examples/PHP_Parser_TestClass.php";
	try {
		$php = new php\php_parse_token($php_file, "ExtendsClass");
		$php->setClass();
		$php->setAttr();
		$php->setMethods();
		$evalarr = array();
		foreach($php->getArr() as $elem)
			if($elem[7] && $elem[1] == 2)
				$evalarr = array_merge($evalarr, $elem[7]);
		var_dump($evalarr);
	}
	catch(\php\php_parseException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
	echo \utils\String::getCommentFrame("PHP - Reflection-based");
	include_once $php_file; #needed for the reflection-based parser
	try {
		$rc = new ReflectionClass("ExtendsClass");
		$php = new php\php_parse($rc);
		$php->setClass();
		$php->setAttr();
		$php->setMethods();
		$params = array();
		foreach($php->getArr() as $elem)
			if($elem[7] && $elem[1] == 2) 
				$params = array_merge($params, $elem[7]);
		var_dump($params);
	}
	catch(php\php_parseException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
	echo \utils\String::getCommentFrame("Compare PHP - Reflection-based and PHP - Token-based");
	if(!utils\Arrays::compareArr($evalarr, $params)) {
		echo "NOK!\n";
		exit(1);
	}
	else
		echo "OK!\n";
	exit(0);
