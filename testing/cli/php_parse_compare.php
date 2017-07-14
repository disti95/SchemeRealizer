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
	 * @since    22.08.2016
	 * @category CLI-Test for the PHP-Parser
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../utils/Arrays.php"
		    	,"../../php/php_parse.php"
		    	,"../../utils/String.php"
		    	,"../../utils/Parsing.php"
		   		,"../../constants/constants.php"
           		,"../../native/Validate.php");
	if(($res = utils\File::setIncludes($arr)) !== true) 
		die($res."\n");
	
	try {
		header("Content-Type: text/plain"); //Set Header
		
		$file = \utils\Directory::HOME()."/workspace/SchemeRealizer/examples/PHP_Parser_TestClass.php";
		
		//Set Token-Instance
		$parserToken = new php\php_parse_token($file, "PHP_Parser_TestClass");
		$parserToken->setAttr();
		$parserToken->setMethods();
		
		include_once $file;
		$rc = new ReflectionClass("PHP_Parser_TestClass");
		
		//Set Introspection-Instance
		$parserReflection = new php\php_parse($rc);	
		$parserReflection->setAttr();
		$parserReflection->setMethods();

		echo \utils\String::getCommentFrame("Compare token and reflection-based PHP-Parser");
		
		//Compare the parsed-Arrays
		if(utils\Arrays::compareArr($parserReflection->getArr(), $parserToken->getArr())) {
			echo "\nTest OK: \n\nToken and Reflection-Parser are returning the same Array!\n";
			exit(0);
		}
		else {
			echo "\nTest failed, difference: \n\n";
			var_dump(array_diff(array_map("serialize"
					                     ,$parserReflection->getArr())
				               ,array_map("serialize"
				               		     ,$parserToken->getArr())));
			exit(1);
		}
	}
	catch(php\php_parseException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
?>
