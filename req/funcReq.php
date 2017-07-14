<?php
	use function constants\forbiddenDBMS;

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
	 * @author   Michael Watzer/Christian Dittrich
	 * @version  1.0
	 * @category Response to requests with function calls
	 * @since    04.04.2017
	 */
	//Including
	include_once "../utils/File.php";
	include_once "../utils/Directory.php";
	include_once  "config.php";
	$arr = array("../utils/Arrays.php"
			    ,"../native/Validate.php"
			    ,"../constants/constants.php"
			    ,"../gen/sqlvalidation.php"
			    ,"../engines/sql.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res);
	
	/**
	 * @RoundTrip-Handling
	 */
	if(isset($_POST["getParamStr"])) {
		$paramarr = (array) $_POST["getParamStr"];
		if(is_array($paramarr)) 
			echo json_encode(utils\Arrays::getParamStr($paramarr));
	}
	/**
	 * @RoundTrip-Handling
	 */
	if(isset($_POST["isInterface"]) && isset($_POST["src"])) {
		$prepArr = (array) $_POST["isInterface"];
		classIsInterface($prepArr, $_POST["src"]);
		echo json_encode($error->getArr());
	}
	/**
	 * @RoundTrip-Handling
	 */
	if(isset($_POST["getAttrValStr"])) {
		$valArr['$const'] = $_POST["getAttrValStr"];
		$valArr           = utils\Arrays::getParamStr($valArr);
		$valArr           = ltrim(rtrim(substr($valArr
										      ,strpos($valArr, '=') + 1)));
		echo json_encode($valArr);
	}
	/**
	 * @RountTrip-Handling
	 */
	if(isset($_POST["sqlArr"]) && isset($_POST["dbms"])) {
		validateSQLArr(json_decode($_POST["sqlArr"], true), $_POST["dbms"]);
		echo json_encode($error->getArr());
	}
	/**
	 * @category decide if the given class is an interface or a class
	 * @param    $prepArr
	 * @param    $src
	 */
	function classIsInterface($prepArr, $src) {
		global $error;
		if(is_array($prepArr)) {
			$classname = utils\Arrays::extractClassName($prepArr, $src);
			if($classname[0]) {
				$error->addElem(true, error\error::setError($classname[1]));
				return;
			}
			else {
				$isInterface = utils\Arrays::isInterface($prepArr, $classname[1], $src);
				if(!is_bool($isInterface))
					$error->addElem(true, error\error::setError($isInterface));
				else 
					$error->addElem(false
							       ,($isInterface) 
							          ? constant('__INTERFACE__')
							          : constant('__CLASS__'));
			}
		}
	}
	/**
	 * @category sqlvalidation
	 * @param    $sqlArr
	 * @param    $dbms
	 */
	function validateSQLArr(array $sqlArr, $dbms) {
		global $error;
		switch(strtolower($dbms)) {
			case constant('__MYSQL__'):
				$ret = validateMySQL($sqlArr);
			break;
			case constant('__SQLITE__'):
				$ret = validateSQLite($sqlArr);
			break;
			default:
				$ret = forbiddenDBMS($dbms);
			break;
		}
		$error->addElem(($ret === true) 
				          ? false 
				          : true
				       ,($ret === true)
				          ? $ret
				          : error\error::setError($ret));
	}