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
	 * @since    01.10.2016
	 * @category Executes all given Files and redirect the output to the Logging-System.
	 */
	
	//Get some help
	if(isset($argv[1]) && $argv[1] == "help") {
		echo "Usage: \t\t php execute_all.php <interpreter> <deepth> file1 file2\n";
		echo "Interpreter: \t php, phpunit, node\n";
		echo "Min. deepth: \t 0\n";
		echo "Max. deepth: \t 2\n";
		exit(0);
	}
	
	if(count($argv) < 4) {
		echo "At least three arguments needed!\n";
		exit(1);
	}
	
	$deepth = "";
	if($argv[2] == "0")
		$deepth = "./";
	elseif($argv[2] == "1")
		$deepth = "../";
	elseif($argv[2] == "2") 
		$deepth = "../../";
	else {
		echo "Forbidden deepth!\n";
		exit(1);
	}
	
	//Including
	include_once $deepth."utils/File.php";
	include_once $deepth."utils/Directory.php";
	$arr = array($deepth."utils/Globals.php"
			    ,$deepth."utils/String.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");
	
	if(!\utils\String::chkInterpreter($argv[1])) {
		echo "Forbidden interpreter!\n";
		exit(1);
	}
	else
		$interpret = $argv[1];
	
	$filteredArr = array();
	foreach($argv as $arg) { //No need of README.md, arguments, execute_all.php and deepth
		if($arg != $argv[0] 
		&& $arg != $interpret  
		&& $arg != $argv[2] 
		&& $arg != "README.md")
			$filteredArr[] = $arg;
	}
		
	if(!count($filteredArr)) {
		echo "No files to execute!\n";
		exit(1);
	}
		
	//Get the seq-num for the file name
	$globals = new utils\Globals($deepth."globals/logging.txt");
	$seq     = $globals->getValOfKey("seq");
	
	//Increment the seq-num
	$globals->setValOfKey("seq", $globals->getIncrementedVal("seq"));
	
	$out = $deepth."logging/execute_all_".$interpret."_".$seq.".txt";
	
	//FYI
	echo "STDOUT: $out\n";
	
	$output = "";
	for($i = 0; $i < count($filteredArr); $i++) {
		$output .= "###$filteredArr[$i]###\n\n";
		$output .= shell_exec("$interpret $filteredArr[$i]");
		if($filteredArr[$i] != end($filteredArr))
			$output .= "\n-------------------------------\n";
	}
	
	//Writing
	if(($res = \utils\File::flushFile($out, $output, "uml")) !== true) {
		echo "Writing of $out failed!\n";
		echo $res."\n";
		exit(1);
	}
	exit(0);
?>
