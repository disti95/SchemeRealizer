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
	 * @category File to fetch|flush the PHPCode out of an Array
	 */
	//Including
	include_once "../utils/File.php";
	include_once "../utils/Directory.php";
	include_once  "config.php";
	$arr = array("../gen/classgen.php"
			    ,array("../utils", "class")
		      	,"../constants/constants.php"
			    ,array("../native", "class"));
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res);	
	
	/**
	 * @category RoundTrip-Handling
	 */
	if(isset($_POST["arr"]) && isset($_POST["cl"]) && isset($_POST["flush"])) {
		getPHP(json_decode($_POST["arr"], true), $_POST["cl"], $_POST["flush"]);
		echo json_encode($error->getArr());
	}
	
	/**
	 * @category Flushing, Fetching(Class)
	 * @param    $arr
	 * @param    $cl
	 * @param    $flush
	 */
	function getPHP(array $arr, $cl, $flush) {
		global $error; //Error-Handling
		try {
			//Set the Object for Generating
			$classgen = new gen\classgen($arr, $cl);
			$output   = "";
			if($flush == 1) {
				//Meanwhile .. Error occured?
				if($error->hasError())
					return;
				$classpath = getDefaultPath(0);
				$classgen->flushFile($classpath."/".$cl.".php");
				$output .= "<br />";
				$output .= "<div id='successDIV'>";
				$output .= "<h3>Flushed file to ".$classpath."/".$cl.".php!</h3>";
				$output .= "</div>";
			}
			//Return the HTML-Code
			$output .= "<br />";
			$output .= "<div id='subContentDIV'>";
			$output .= "<h3>Preview PHP-Class.</h3>";
			$output .= "</div>";
			$output .= "<br />";
			$output .= highlight_string($classgen->getPHPFile(), true);
			$error->addElem(false, $output);
		}
		catch(gen\classgenException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));
		}
	}
?>