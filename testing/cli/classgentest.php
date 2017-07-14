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
	 * @since    24.09.2016
	 * @category CLI-Test for the classgen-Class
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
	
	$umlToParse = "../../examples/UMLEmployee.txt";

	try {
		$umlparser = new uml\uml_parse($umlToParse);
		$umlparser->setArr();
		$tmpArr = $umlparser->getArr();
		for($i = 0; $i < count($tmpArr); $i++) {
			$tmpArr[$i][3] = true;
		}
		//Test the getter and setter too
		$tmpArr[] = array("getter", 2, "public", true, false, false, false, false);
		$tmpArr[] = array("setter", 3, "public", true, false, false, false, array('$setter' => null));
		//Test the abstract keyword
		$tmpArr[] = array("AbstractGetter", 2, "public", true, array("abstract"), false, false, false);
		$tmpArr[] = array("AbstractSetter", 3, "public", true, array("abstract"), false, false, array('$setter' => null));
		$classgen = new gen\classgen($tmpArr, "Employee");
		echo $classgen->getPHPFile();
	}
	catch(gen\classgenException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
	catch(uml\uml_parseException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
	exit(0);
?>
