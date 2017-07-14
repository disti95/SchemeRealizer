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
	 * @category UML-Parser CLI-Test for const parsing
	 * @since    19.05.2017
	 */

	//Including
	include_once '../../utils/File.php';
	include_once '../../utils/Directory.php';
	$arr = array("../../utils/Parsing.php"
			    ,"../../uml/uml_parse.php"
			    ,"../../constants/constants.php"
			    ,"../../utils/Arrays.php"
			    ,"../../native/Validate.php"
			    ,"../../utils/String.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");

	$UMLToParse = "../../examples/UMLEmployee.txt";
	
	try {
		echo \utils\String::getCommentFrame("Parsing $UMLToParse");
		$umlparser = new uml\uml_parse($UMLToParse);
		$umlparser->setArr();
		var_dump($umlparser->getArr());
	}
	catch(uml\uml_parseException $e){
		echo $e->getMessage()."\n";
		exit(0);
	}
	exit(1);