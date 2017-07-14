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
	 * @category test uml interface generation
	 * @since    25.04.2017
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../constants/constants.php"
				,"../../utils/String.php"
				,"../../gen/umlgen.php"
				,"../../utils/Parsing.php"
				,"../../utils/Arrays.php"
			    ,"../../native/Validate.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");
	
	try {
		$preparr = array();
		/**
		 * case 1: class name missing
		 * $preparr[] = array("MyInterface", 1, false, true, false, array(array('ParentInterface', true)), false, false);
		 * $preparr[] = array("setName", 2, false, true, array(constant('__STATIC__')), false, false, array('$name' => 'Michael'));
		 * $preparr[] = array("getName", 2, false, true, false, false, false, false);
		 */
		/**
		 * case 2: interface prototype has access types
		 * $preparr[] = array("MyInterface", 4, false, true, array(constant('__ABSTRACT__')), array(array('ParentInterface', true)), false, false); 
		 * $preparr[] = array("setName", 2, false, true, array(constant('__STATIC__')), false, false, array('$name' => 'Michael'));
		 * $preparr[] = array("getName", 2, false, true, false, false, false, false);
		 */
		/**
		 * case 3: interface implements
		 * $preparr[] = array("MyInterface", 4, false, true, false, array(array('ParentInterface', true)), array(array('ParentInterface', true)), false); 
		 * $preparr[] = array("setName", 2, false, true, array(constant('__STATIC__')), false, false, array('$name' => 'Michael'));
		 * $preparr[] = array("getName", 2, false, true, false, false, false, false);
		 */
		/**
		 * case 4: interface method and modifier
		 * $preparr[] = array("MyInterface", 4, false, true, false, array(array('ParentInterface', true)), false, false);
		 * $preparr[] = array("setName", 2, constant('__PRIVATE__'), true, array(constant('__STATIC__')), false, false, array('$name' => 'Michael'));
		 * $preparr[] = array("getName", 2, false, true, false, false, false, false);
		 */
		/**
		 * case 5: interface method and access type
		 * $preparr[] = array("MyInterface", 4, false, true, array(constant('__ABSTRACT__')), array(array('ParentInterface', true)), false, false);
		 * $preparr[] = array("setName", 2, false, true, array(constant('__STATIC__'), constant('__ABSTRACT__')), false, false, array('$name' => 'Michael')); 
		 * $preparr[] = array("getName", 2, false, true, false, false, false, false);
		 */
		$preparr[] = array("MyInterface", 4, false, true, false, array(array('ParentInterface', true)), false, false);
		$preparr[] = array("setName", 2, false, true, array(constant('__STATIC__')), false, false, array('$name' => 'Michael'));
		$preparr[] = array("getName", 2, false, true, false, false, false, false);
		
		$gen = new \gen\umlgen($preparr);
		/**
		 * case 6: try to get attributes
		 * $gen->getAttr(); 
		 */
		echo $gen->getUMLFileContent();
		exit(constant('__NOERR__'));
	}
	catch(\gen\umlgenException $e) {
		echo $e->getMessage()."\n";
		exit(constant('__ERR__'));
	}
	exit(constant('__NOERR__'));