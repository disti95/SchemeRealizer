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
	 * @since    18.05.2017
	 * @version  1.0
	 * @category CLI-Test for PHP-Parsing
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$fileToParse  = \utils\Directory::HOME()."/workspace/SchemeRealizer/examples/PHP_Parser_TestClass.php";
	$classToParse = "PHP_Parser_TestClass";
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
		echo utils\String::getCommentFrame("PHP_Parser_TestClass - PHP-Reflection-Parser");
		
		$rc = new ReflectionClass($classToParse);
		$php_parser_reflection = new php\php_parse($rc);
		$php_parser_reflection->setAttr();
		var_dump($php_parser_reflection->getArr());
		
		echo \utils\String::getCommentFrame("PHP_Parser_TestClass - PHP-Token-Parser");
		
		$php_parser_token = new php\php_parse_token($fileToParse, $classToParse);
		$php_parser_token->setAttr();
		var_dump($php_parser_token->getArr());
	}
	catch(php\php_parseException $e){
		echo $e->getMessage()."\n";
		exit(0);
	}
	exit(1);
