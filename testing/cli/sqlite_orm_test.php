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
	 * @since    ?
	 * @category ORM Testing
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../engines/sql.php"
			    ,"../../orm/sqlite_orm.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");
		
	$database = "../../examples/MyLIB.db";
	
	try{
		$con = new engine\sql("sqlite");
		$con->getConnection($database);
		$sqlite_orm = new orm\sqlite_orm($con);
		$sqlite_orm->setClass(); 

		foreach($sqlite_orm->getClass() as $classes) { 
			echo $classes.": ";
			$sqlite_orm->setAttr($classes); 
			$attr = $sqlite_orm->getAttr(); 
			foreach($attr[$classes] as $attr) 
				echo $attr." ";
			echo "\n";
		}	
		$con->closeConnection();
	}
	catch(engine\DatabaseException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
	catch(orm\sqlite_ormException $e) {
		echo $e->getMessage()."\n";
		exit(1);
	}
	exit(0);