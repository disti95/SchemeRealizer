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
	 * @author Michael Watzer
	 * @since ?
	 * @version 1.0
	 * @category Start-Page
	 */
	//Including
	include_once __DIR__."/../utils/File.php";
	include_once __DIR__."/../utils/Directory.php";
	$arr = array(__DIR__."/../error/error.php"
				,__DIR__."/../utils/String.php");
	if(($res = utils\File::setIncludes($arr)) !== true) {
		die($res);
	}
	//Declare UML, Class and SQL Paths
	$umlclass = __DIR__."/../examples/userUMLTest.php";
	$umldiagram = __DIR__."/../examples/UMLExample.txt";
	$SQLclass = __DIR__."/../examples/Event.php";
	$classSQL = __DIR__."/../examples/MySQLExample.sql";
	$sqldiagram = __DIR__."/../examples/servers.txt";
	$umlSQL = __DIR__."/../examples/servers.sql";
	
	//Output an Class-Example
	echo <<< CLASSOUTPUT
		<div id='subContentDIV'>
			<h2>SchemeRealizer - A ORM and more.</span></h2><br />
			<h3>Translate Class-Diagrams to PHP-Classes.</h3><br />
		</div>
		<br />
CLASSOUTPUT;
	//Output the Code
	if(!highlight_file($umlclass)){
		echo error::setError("Not able to highlight file: ".$umlclass."!");
	}
	
	echo "<br /><br /><b />";
	
	//Output a UML-Example
	echo <<< UML
		<div id='subContentDIV'>
			<h3>Translate PHP-Classes to Class-Diagrams.</h3>
		</div>
		<br />
UML;
	$res = utils\File::readFile($umldiagram, "uml");
	//Validation-Check
	if(!is_array($res))
		echo error::setError($res);
	else {
		foreach($res as $line){
			$line = htmlspecialchars($line, ENT_QUOTES);
			echo $line."<br />";
		}
	}
	
	echo "<br /><br /><b />";
	
	//Output an Class-Example
	echo <<< CLASSOUTPUT
		<div id='subContentDIV'>
			<h3>Translate Databases to PHP-Classes.</h3>
		</div>
		<br />
CLASSOUTPUT;
	//Output the Code
	if(!highlight_file($SQLclass)){
		echo error::setError("Not able to highlight file: ".$SQLclass."!");
	}
	
	echo "<br /><br /><br />";
	
	//Output the SQL-Example
	echo <<< SQL
	<div id='subContentDIV'>
		<h3>Translate PHP-Classes to Databases.</h3>
	</div>
	<br />
SQL;
	$res = utils\File::readFile($classSQL, "mysql");
	//Validation-Check
	if(!is_array($res))
		echo error::setError($res);
	else {
		$i = 0;
		echo "<div id='SQLExample'>";
		foreach($res as $line){
			if($line == end($res) || $i == 0)
				echo $line."<br />";
			else
				echo "<span style='padding-left:20px;'>".$line."</span><br />";
			$i++;
		}
		echo "</div>";
	}
	
	echo "<br /><br /><b />";
	
	//Output a UML-Example
	echo <<< UML
		<div id='subContentDIV'>
			<h3>Translate Databases to Class-Diagrams.</h3>
		</div>
		<br />
UML;
	$res = utils\File::readFile($sqldiagram, "uml");
	//Validation-Check
	if(!is_array($res))
		echo error::setError($res);
	else {
		foreach($res as $line){
			$line = htmlspecialchars($line, ENT_QUOTES);
			echo $line."<br />";
		}
	}
	
	echo "<br /><br /><b />";
	
	//Output the SQL-Example
	echo <<< SQL
	<div id='subContentDIV'>
		<h3>Translate Class-Diagrams to Databases.</h3>
	</div>
	<br />
SQL;
	$res = utils\File::readFile($umlSQL, "mysql");
	//Validation-Check
	if(!is_array($res))
		echo error::setError($res);
	else {
		$i = 0;
		echo "<div id='SQLExample'>";
		foreach($res as $line){
			if($line == end($res) || $i == 0)
				echo $line."<br />";
			else
				echo "<span style='padding-left:20px;'>".$line."</span><br />";
			$i++;
		}
		echo "</div>";
	}
?>