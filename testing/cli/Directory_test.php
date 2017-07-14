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
	 * @category CLI-test for the Directory-Class
	 * @since    24.07.2016
	 */
	//Include 
	include_once "../../utils/Directory.php";
	include_once "../../utils/File.php";
	try {
		//Throws an Error cause of the method!
		//$dir = new utils\Directory(\utils\Directory::HOME(), "invalidmethod");
		//var_dump($dir->getFiles());
		
		//Throws an Error cause of the root!
		//$dir = new utils\Directory("/doesnotexist", "class");
		//var_dump($dir->getFiles());
		
		//Throws an Error cause of emptiness!
		//$dir = new utils\Directory("/", "");
		//var_dump($dir->getFiles());
		
		//Throws an Error cause of MySQL
		//$dir = new utils\Directory(\utils\Directory::HOME()."/workspace/SchemeRealizer", "mysql");
		//var_dump($dir->getFiles());
		
		//Throws an Error cause of MariaDB
		//$dir = new utils\Directory(\utils\Directory::HOME()."/workspace/SchemeRealizer", "mariadb");
		//var_dump($dir->getFiles());
		
		//OK
		$dir = new utils\Directory(\utils\Directory::HOME()."/workspace/SchemeRealizer", "sqlite");
		var_dump($dir->getFiles());
		
		//OK
		$dir = new utils\Directory(\utils\Directory::HOME()."/workspace/SchemeRealizer", "class");
		var_dump($dir->getFiles());
		
		//OK
		$dir = new utils\Directory(\utils\Directory::HOME()."/workspace/SchemeRealizer", "uml");
		var_dump($dir->getFiles());
	}
	catch(utils\DirectoryException $e){
		echo $e->getMessage()."\n";
		exit(1);
	}
	exit(0);
?>