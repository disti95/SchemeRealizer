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
	 * @since    05.06.2017
	 * @version  1.0
	 * @category CLI-Test for the token-based parser concerning error detection
	 */
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$fileToParse  = \utils\Directory::HOME()."/workspace/SchemeRealizer/examples/PHP_Parser_ErrorClass.php";
	$classToParse = "PHP_Parser_ErrorClass";
	$arr = array("../../utils/String.php"
				,"../../utils/Parsing.php"
				,"../../php/php_parse.php"
				,"../../error/error.php"
				,"../../constants/constants.php"
				,"../../utils/Arrays.php"
				,"../../native/Validate.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");
	
	try {
		$php = new \php\php_parse_token($fileToParse, $classToParse);
		$php->setClass();
		var_dump($php->getArr());
	}
	catch(\php\php_parseException $e) {
		echo $e->getMessage()."\n";
		exit(0);
	}
	exit(1);