<?php
    /*
    SchemeRealizer - A free ORM and Translator for Diagrams, Databases and PHP-Classes/Interfaces.
    Copyright (C) 2017  Michael Watzer

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
	 * @category Generate new program file
	 * @since    27.03.2017
	 */
    //Including
    include_once '../utils/File.php';
    include_once '../utils/Directory.php';
    
    $arr = array('../utils/String.php'
    		    ,'../error/error.php');
    if(($res = utils\File::setIncludes($arr)) !== true)
    	die($res);
    
   use utils\String;
    	
   $error   = new error\error();
   $prog    = $argv[0];
   $err     = 0;
   $licence = './LICENCE';
    
    /**
     * @category Usage
     * @return   string
     */
    function getUsage() {
    	global $prog;
    	return "usage: $prog --out=/path/to/file --author=<author> --version=<version> --category=<category> [--class=<classname>]";
    }
    foreach($argv as $elem) {
    	if(strpos($elem, "--out=") !== false) 
    		$stdout = String::getValFromArg($elem);
    	elseif(strpos($elem, "--author") !== false)
    		$author = String::getValFromArg($elem);
		elseif(strpos($elem, "--version=") !== false) 
    		$version = String::getValFromArg($elem);
    	elseif(strpos($elem, "--category=") !== false) 
    		$category = String::getValFromArg($elem);
   		elseif(strpos($elem, "--class=") !== false) 
    			$class = String::getValFromArg($elem);
    }
    if(!isset($stdout))
    	$error->addElem(true, "argument --out missing!");
    if(!isset($author))
    	$error->addElem(true, "argument --author missing!");
    if(!isset($version))
    	$error->addElem(true, "argument --version missing!");
    if(!isset($category))
    	$error->addElem(true, "argument --category missing!");
    if($error->hasError()) {
        $error->setExitcd($err | 1);
    	$error->addElem(true, getUsage());
    	$error->printErrors(true);
    }
    $fh = fopen($licence, 'r')
      or $error->addElem(true, "unable to open licence file $licence!");
    if(!$error->hasError()) {
    	$licencecontent = "\t/*".PHP_EOL;
    	while(!feof($fh)) 
    		$licencecontent .= "\t".fgets($fh);
	$licencecontent .= PHP_EOL."\t*/".PHP_EOL;
    	if(!fclose($fh)) {
    		$error->addElem(true, "unable to close licence file $licence!");
    		$error->setExitcd($err | 1);
    		$error->printErrors(true);
    	}
    }
    else {
    	$error->setExitcd($err | 1);
    	$error->printErrors(true);
    }
    $fh = fopen($stdout, 'w') 
      or $error->addElem(true, "unable to open output file $stdout!");
    if(!$error->hasError()) {
    	fputs($fh, '<?php'.PHP_EOL);
    	fputs($fh, $licencecontent);
    	if(isset($class)) 
    		fputs($fh, "\tclass $class {".PHP_EOL);
    	fputs($fh, ((isset($class)) ? "\t\t" : "\t").'/**'.PHP_EOL);
    	fputs($fh, ((isset($class)) ? "\t\t" : "\t")." * @author   $author".PHP_EOL);
    	fputs($fh, ((isset($class)) ? "\t\t" : "\t")." * @version  $version".PHP_EOL);
    	fputs($fh, ((isset($class)) ? "\t\t" : "\t")." * @category $category".PHP_EOL);
    	fputs($fh, ((isset($class)) ? "\t\t" : "\t")." * @since    ".date("d.m.Y").PHP_EOL);
    	fputs($fh, ((isset($class)) ? "\t\t" : "\t").' */'.PHP_EOL);
    	if(isset($class)) 
    		fputs($fh, "\t}".PHP_EOL);
     	if(!fclose($fh)) {
     		$error->addElem(true, "unable to close output file $stdout!");
     		$error->setExitcd($err | 1);
     		$error->printErrors(true);
     	}
     	if(!\utils\File::setPerm($stdout)) {
     		$error->addElem(true, "unable to set permission for file $stdout!");
     		$error->setExitcd($err | 1);
     		$error->printErrors(true);
     	}
    }
    else {
    	$error->setExitcd($err | 1);
    	$error->printErrors(true);
    }
    exit($err | 0);
