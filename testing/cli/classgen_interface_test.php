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
	 * @author   Michael Watzer
	 * @version  1.0
	 * @category Test classgen concerning interfaces
	 * @since    21.04.2017
	 */
	 //Including
        include_once "../../utils/File.php";
        include_once "../../utils/Directory.php";
        $arr = array("../../constants/constants.php"
                    ,"../../uml/uml_parse.php"
                    ,"../../gen/classgen.php"
                    ,"../../utils/Parsing.php"
                    ,"../../utils/Arrays.php"
                    ,"../../native/Validate.php"
                    ,"../../utils/String.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
                die($res."\n");

	$umlToParse = "../../examples/UMLIEmployee.txt";

	try {
		echo utils\String::getCommentFrame($umlToParse);
		
		$uml = new uml\uml_parse($umlToParse);
		$uml->setArr();
		$arr = $uml->getArr();
		for($i = 0; $i < count($arr); $i++)
			$arr[$i][3] = true;
		$gen = new gen\classgen($arr, "EmployeeInterface");
		echo $gen->getPHPFile()."\n";
		
		$umlToParse = "../../examples/UMLIFace.txt";
		
		echo utils\String::getCommentFrame($umlToParse);
		
		$uml = new uml\uml_parse($umlToParse);
		$uml->setArr();
		$arr = $uml->getArr();
		for($i = 0; $i < count($arr); $i++)
			$arr[$i][3] = true;
		$gen = new gen\classgen($arr, "IFace");
		echo $gen->getPHPFile();
	}
	catch(uml\uml_parseException $e) {
		echo $e->getMessage()."\n";
		exit(constant('__ERR__'));
	}
	catch(gen\classgenException $e) {
		echo $e->getMessage()."\n";
		exit(constant('__ERR__'));
	}
	exit(constant('__NOERR__'));
