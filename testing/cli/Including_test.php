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
	 * @category CLI-Test of a GOLDEN-UTIL
	 * @version  1.0
	 * @since    28.07.2016
	 */
	include_once "../../utils/File.php"; //Required
	include_once "../../utils/Directory.php"; //Required
	//Files to include
	$arr = array("../../orm/mysql_orm.php"
			    ,"../../utils/Arrays.php"
			    ,"../../gen/sqlvalidation.php"
			    ,"../../constants/constants.php"
			    ,array("../../examples", "class"));
	//OK Expected
	if(($res = utils\File::setIncludes($arr)) !== true)
		echo $res."\n";
	//Can we call the methods?
	$event = new Event();
	$event->setCharacter_set_client("utf-8");
	echo $event->getCharacter_set_client()."\n";
	//Can we call the functions?
	if(checkDBMS("mysql"))
		echo "DBMS mysql OK!\n";
	
	//This should clearly throw an error!
	$arr = array("../../orm/mysql_orm.php", "/clearly/forbidden");
	//Failure Expected
	if(($res = utils\File::setIncludes($arr)) !== true)
		echo $res."\n";
	
	//This should clearly throw an error!
	$arr = array("../../orm/mysql_orm.php", array("../../examples", "forbiddenmethod"));
	//Failure Expected
	if(($res = utils\File::setIncludes($arr)) !== true)
		echo $res."\n";
	
	//This should clearly throw an error!
	$arr = array("../../orm/mysql_orm.php", array("forbidden/dir", "class"));
	//Failure Expected
	if(($res = utils\File::setIncludes($arr)) !== true)
		echo $res."\n";
?>