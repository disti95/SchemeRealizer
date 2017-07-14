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
	 * @category Testing the uml parser concerning interfaces
	 * @since    11.04.2017
	 */
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$fileToParse = \utils\Directory::HOME()."/workspace/SchemeRealizer/examples/UMLIEmployee.txt";
	$arr = array("../../utils/Parsing.php"
			    ,"../../uml/uml_parse.php"
			    ,"../../constants/constants.php"
			    ,"../../utils/Arrays.php"
			    ,"../../native/Validate.php"
			    ,"../../utils/String.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");
	
	try {
		$uml = new uml\uml_parse($fileToParse);
		$uml->setArr();
		var_dump($uml->getArr());
		exit(0);
	}
	catch(\uml\uml_parseException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
	exit(0);