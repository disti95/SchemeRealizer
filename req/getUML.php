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
	 * @category File to fetch|flush the UML-Diagram out of an Array
	 */
	//Including
	include_once "../utils/File.php";
	include_once "../utils/Directory.php";
	include_once "config.php";
	$arr = array("../gen/umlgen.php"
			    ,array("../utils", "class")
			    ,"../constants/constants.php"
			    ,array("../native", "class"));
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res);

	/**
	 * @category RoundTrip-Handling
	 */
	if(isset($_POST["arr"]) && isset($_POST["uml"]) && isset($_POST["type"]) && isset($_POST["flush"])) {
		getUML(json_decode($_POST["arr"], true), $_POST["uml"], $_POST["type"], $_POST["flush"]);
		echo json_encode($error->getArr());
	}
	
	/**
	 * @category Flushing, Fetching(UML)
	 * @param    $arr
	 * @param    $uml
	 * @param    $type
	 * @param    $flush
	 */
	function getUML(array $arr, $uml, $type, $flush) {
		global $error; //Error-Handling
		//Check for emptiness
		$data   = array($uml, $type);
		$empArr = new utils\Arrays();
		$empArr->setEmptiness($data);
		if($empArr->emptiness) {
			$error->addElem(true, error\error::setError("Please leave no field empty!"));
			return;
		}
		/*
		* Check if the type is allowed
		* 1: txt
		* 2: jpg
		* 3: png
		*/
		switch($type) {
			case 1: $file = $uml.".txt";
			break;
			case 2: $file = $uml.".jpg";
			break;
			case 3: $file = $uml.".png";
			break;
			default:
				$error->addElem(true, error\error::setError("Illegal type!"));
				return;
				break;
		}
		try {
			//Generate UML
			$umlgen = new gen\umlgen($arr);
			$output = "";
			//Check for flush
			if($flush == 1) {
				//Meanwhile .. an Error occured?
				if($error->hasError())
					return;
				$umlpath = getDefaultPath(1);
				$umlgen->flushFile($umlpath."/".$file);
				$output .= "<br />";
				$output .= "<div id='successDIV'>";
				$output .= "<h3>Flushed file to ".$umlpath."/".$file."!</h3>";
				$output .= "</div>";
			}
			//Return the UML Content
			$output .= "<br />";
			$output .= "<div id='subContentDIV'>";
			$output .= "<h3>Preview the Diagram.</h3>";
			$output .= "</div>";
			$output .= "<br />";
			$output .= $umlgen->getUMLHTMLContent();
			$error->addElem(false, $output);
		}
		catch(gen\umlgenException $e) {
			$error->addElem(true, error\error::setError($e->getMessage()));
		}
	}
?>