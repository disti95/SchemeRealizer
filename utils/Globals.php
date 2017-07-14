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
	class Globals {
		/**
		 * @author   Michael Watzer
		 * @since    01.10.2016
		 * @category Functions to cope with the Globals-System
		 * @version  1.0
		 */	
		private $file;
		public function __construct($file){
			$checkList = array("emptyFile"    => $file
					          ,"existFile"    => array($file, 1)
					          ,"readableFile" => $file);
			if(($res = \utils\File::basicFileValidation($checkList)) !== true) 
				throw new GlobalsException("Globals:$res");
			if(!$this->isGlobalFile($file))
				throw new GlobalsException("Globals:The file $file is not registered in the Global-System!");
			$this->file = $file;
		}
		/**
		 * @category Check if the given value is an array
		 * @param    value
		 * @return   boolean
		 */
		public function isValArray($val) {
			if(substr($val, 0, 1)             == '[' 
			&& substr($val, strlen($val) - 1) == ']')
				return true;
			else
				return false;
		}
		/**
		 * @category Returns the value of a global variable
		 * @param    $key
		 * @return   boolean|string|array
		 */
		public function getValOfKey($key) {
			$val   = false;
			$lines = \utils\File::readFile($this->file, "uml");
			foreach($lines as $line) {
				if(($pos = strpos($line, $key."=")) !== false) {
					if(!empty($val = substr($line, strpos($line, "=") + 1))) {
						$val = str_replace("\n", "", $val);
						if($this->isValArray($val))
							if(($val = explode(',', substr($val, 1, strlen($val) - 2))) == array(''))
									$val = false;
					}
				}
			}
			return $val;
		}
		/**
		 * @category Replace the value of a particular key
		 * @param    $key
		 * @param    $newval
		 * @param    $idx   (index of array)
		 * @return   bool|string
		 */
		public function setValOfKey($key, $newval, $idx = false) {
			$old_val = $this->getValOfKey($key);
			if(!$old_val)
				return $old_val;
			if(is_numeric($idx) && is_array($old_val) && isset($old_val[$idx])) {
				$new_val       = $old_val;
				$new_val[$idx] = $newval;
				return \utils\File::replaceLineOfFile($this->file
					                           		 ,"uml"
				                            		 ,$key."=[".implode($old_val, ',').']'
					                           		 ,$key."=[".implode($new_val, ',').']');
			}
			elseif(!$idx && is_string($old_val))
				return \utils\File::replaceLineOfFile($this->file
					                      	   	     ,"uml"
					                         	     ,$key."=".$old_val
					                          	  	 ,$key."=".$newval);
			else
				return false;
		}
		/**
		 * @category Check if the file is assigned to the Globals-System
		 * @param    $file
		 */
		public function isGlobalFile($file) {
			//Work around, because UML-Files have be .txt files in SchemeRealizer
			$fileList = new \utils\Directory(\utils\String::getDeepthOfPath($file)."globals", "uml"); 
			if(in_array($file, $fileList->getFiles()))
				return true;
			else 
				return false;
		}
		/**
		 * @category Returns the incremented value of a key
		 * @param    $key
		 * @return   bool|int
		 */
		public function getIncrementedVal($key) {
			$val = $this->getValOfKey($key);
			if(is_string($val))
				return $val + 1;
			else
				return false;
		}
		/**
		 * @category Returns the decremented value of a key
		 * @param    $key
		 * @return   bool|int
		 */
		public function getdecrementedVal($key) {
			$val = $this->getValOfKey($key);
			if(is_string($val))
				return $val - 1;
			else
				return false;
		}
	}
	class GlobalsException extends \Exception{}
?>