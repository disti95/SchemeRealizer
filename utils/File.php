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
	namespace utils;
	class File{
		const srPerms = 0775;
		/**
		 * @author   Michael Watzer
		 * @version  1.0
		 * @since    21.07.2016
		 * @category Util for File operations
		 */
		/**
		 * @category Low-Level Permission-Activities
		 * @param    $file
		 * @return   boolean
		 */
		public static function setPerm($file) {
			return chmod($file, self::srPerms);
		}
		/**
		 * @category Low-Level Permission-Activities
		 * @param    $file
		 * @return   string
		 */
		public static function getPerm($file) {
			return substr(sprintf('%o', fileperms($file)), -4);
		}
		/**
		 * @category File-Writing
		 * @param    $file
		 * @param    $content
		 * @param    $method
		 * @return   string|boolean
		 */
		public static function flushFile($file, $content, $method) {
			$pathinfo       = pathinfo($file);
			$checkListArray = array("emptyFile"     => $file
					               ,"existFile"     => array($file, 0)
					               ,"extensionCheck"=> array($file, $method)
					               ,"emptyContent"  => $content
					               ,"writeableFile" => $pathinfo["dirname"]);
			//Check the Basics -> Emptiness, Existence...
			if(($res = self::basicFileValidation($checkListArray)) !== true)
				return $res;
			//Make and Check File-Operations
			if(!($open = fopen($file, "w")))
				return "Not able to open file ".$file."!";
			if(!fwrite($open, $content))
				return "Not able to write file ".$file."!";
			if(!fclose($open))
				return "Not able to close file ".$file."!";
			//Set the permission
			if(!self::setPerm($file))
				return "Not able to set the permission to ".$file."!";
			return true;
		}
		/**
		 * @category File-Reading
		 * @param    $file
		 * @param    $method
		 * @return   string|array
		 */
		public static function readFile($file, $method){
			$checkListArray = array("emptyFile"=>$file, "existFile"=>array($file, 1), "extensionCheck"=>array($file, $method), "readableFile"=>$file);
			//Check the Basics, existence and so on
			if(($res = self::basicFileValidation($checkListArray)) !== true)
				return $res;
			//Reading-Stuff
			if(!$open = fopen($file, "r")) 
				return "Not able to open file ".$file."!";
			$arr = array();
			//Fill the Arr
			while($line = fgets($open))
				$arr[] = $line;
			if(!fclose($open))
				return "Not able to close file ".$file."!";
			if(empty($arr))
				return "Array is empty!";
			return $arr;
		}
		/**
		 * @category Replace a particular line in a file
		 * @param    $file
		 * @param    string $method
		 * @param    $oldline
		 * @param    $newline
		 * @return   bool|string
		 */
		public static function replaceLineOfFile($file, $method, $oldline, $newline) {
			$checkListArray = array("emptyFile"=>$file, "existFile"=>array($file, 1), "extensionCheck"=>array($file, $method), "readableFile"=>$file, "writeableFile"=>$file);
			//Check the Basics, existence and so on
			if(($res = self::basicFileValidation($checkListArray)) !== true)
				return $res;
			
			$output = "";
			$replaced = false;
			$lines = self::readFile($file, $method);
			$writer = fopen($file.".tmp", "w");
			
			foreach($lines as $line) {
				if(stristr($line, $oldline)) {
					$line = $newline."\n";
					$replaced = true;
				}
				fputs($writer, $line);
			}
			
			if($replaced) {
				rename($file.".tmp", $file);
			}
			else {
				unlink($file.".tmp");
			}
			
			return true;
		}
		/**
		 * @category GOLDEN-UTILS
		 * @param    array $checkList
		 * @return   string|boolean
		 */
		public static function basicFileValidation(array $checkList) {
			/**
			 * checkList - Important-Array for Validation
			 * key 				value
			 *
			 * emptyFile		file				
			 * existFile		array(file, method)	
			 * extensionCheck	array(file, method)	
			 * emptyContent		string	
			 * writeableFile	file		
			 * readableFile		file	
			 */
			//emptyFile
			if(key_exists("emptyFile", $checkList) && $checkList["emptyFile"] !== false){
				if(empty($checkList["emptyFile"]))
					return "Empty file input!";
			}
			/**
			 * existFile
			 * Whereas
			 * 0 = Already Exist
			 * 1 = Doesn't Exist
			 */
			if(key_exists("existFile", $checkList) && $checkList["existFile"] !== false){
				if(count($checkList["existFile"]) < 2)
					return "checkList-existFile requires two Array-Elements!";
				switch($checkList["existFile"][1]) {
					case 0:
						if(file_exists($checkList["existFile"][0]))
							return "File ".$checkList["existFile"][0]." already exist!";
					break;
					case 1:
						if(!file_exists($checkList["existFile"][0]))
							return "File ".$checkList["existFile"][0]." doesn't exist!";
					break;
					default:
						return "Invalid checkList-existFile method!";
					break;
				}
			}
			//extensionCheck
			if(key_exists("extensionCheck", $checkList) && $checkList["extensionCheck"] !== false){
				if(count($checkList["extensionCheck"]) < 2)
					return "checkList-ExtensionCheck requires two Array-Elements!";
				if(!self::validFileExtensions($checkList["extensionCheck"][1]))
					return "Invalid Extension-Method!";
				if(!in_array(self::getExtension($checkList["extensionCheck"][0]), self::validFileExtensions($checkList["extensionCheck"][1])))
					return "Invalid extension!";
			}
			//emptyContent
			if(key_exists("emptyContent", $checkList) && $checkList["emptyContent"] !== false){
				if(empty($checkList["emptyContent"]))
					return "Content is empty!";
			}
			//writeableFile
			if(key_exists("writeableFile", $checkList) && $checkList["writeableFile"] !== false) {
				if(!is_writeable($checkList["writeableFile"])) 
					return "File ".$checkList["writeableFile"]." is not writeable!";
			}
			//readableFile
			if(key_exists("readableFile", $checkList) && $checkList["readableFile"] !== false) {
				if(!is_readable($checkList["readableFile"]))
					return "File ".$checkList["readableFile"]." is not readable!"; 
			}
			return true;
		}
		/**
		 * @category Verify the File-Extension for Class, UML and SQL-Files
		 * @param    $method
		 * @return   array|boolean
		 */
		public static function validFileExtensions($method) {
			switch($method) {
				case "uml": 
					return array("txt", "jpg", "jpeg", "png");
				break;
				case "class": 
					return array("php", "php3", "php4", "php5", "phtml");
				break;
				case "umlTXT": 
					return array("txt");
				break;
				case "mysql":
				case "mariadb":
					return array("sql");
				break;
				case "sqlite":
					return array("db");
				break;
				default: 
					return false;
				break;
			}
		}
		/**
		 * @category Mostly used for SQLite-Flushing
		 * @param    $content
		 * @return   string
		 */
		public static function stripContent($content) {
			return str_replace(array("\n", "\t"), "", $content);
		}
		/**
		 * @category Extension-Extractor f.e /path/to/data.php -> php
		 * @param    $path
		 * @return   mixed
		 */
		public static function getExtension($path) {
			$pathinfo = pathinfo($path);
			return $pathinfo["extension"];
		}
		//PLEASE USE THIS GOLDEN-UTIL TO INCLUDE CLASSES ONLY!
		/**
		 * @category GOLDEN-UTIL which includes all files of the given Array
		 * @param    $arr array = ("file1", "file2", array("dir", "method"))
		 * @return   bool|string
		 */
		//PLEASE USE THIS GOLDEN-UTIL TO INCLUDE CLASSES ONLY!
		public static function setIncludes(array $arr) {
			foreach($arr as $elem) {
				//Array equals a Directory
				if(is_array($elem)) {
					if(count($elem) !== 2) {
						return "To include a directory you have to use two elements!";
					}
					$method = $elem[1];
					$root = $elem[0];
					try {
						$dir = new Directory($root, $method);
						//Include all given files that are matching the method
						foreach($dir->getFiles() as $file)
							include_once($file);
					}
					catch(DirectoryException $e) {
						return $e->getMessage();
					}
				}
				else {
					//Use the GOLDEN-UTIL for Validation
					$checkList = array("emptyFile"=>$elem, "existFile"=>array($elem, 1));
					if(($res = self::basicFileValidation($checkList)) !== true)
						return $res;
					include_once $elem;
				}
			}
			return true;
		}
		/**
		 * @category Returns the Classes of a PHP-File via Token
		 * @param    $file
		 * @return   array
		 */
		public static function getClassesFromFile($file) {
			$classes = array();
			$content = file_get_contents($file);
			$tokens  = token_get_all($content);
			$isclass = false;
			//Parse via Token
			foreach($tokens as $token) {
				if(is_array($token)) {
					if($token[0] == T_CLASS || $token[0] == T_INTERFACE)
						$isclass = true; 
					elseif($isclass && $token[0] == T_STRING){
						$classes[] = $token[1];
						$isclass   = false;
					}
				}
			}
			return $classes;
		}
		/**
		 * @category Method to solve Name-Conflicts f.e UMLExist.txt -> UMLExist_1.txt
		 * @param    $name
		 * @param    array $arr
		 * @return   string|boolean
		 */
		public static function nameConflict($name, array $arr) {
			if(in_array($name, $arr)) {
				$i = 1;
				do {
					$tmpname = $name."_".$i;
					$i++;
				}
				while(in_array($tmpname, $arr));
				return $tmpname;
			}
			else
				return false;
		}
		/**
		 * @category Return tmp file name 
		 * @return   string
		 */
		public static function tmpFile() {
			return tempnam(sys_get_temp_dir(), constant('__SR__'));
		}
	}
?>