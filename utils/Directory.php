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
	namespace utils; //Work-Around for name-conflict
	class Directory{ 
		/**
		 * @author   Michael Watzer
		 * @version  1.0
		 * @since    20.07.2016
		 * @category Util for Directory operations(Scanning and so on)
		 */
		private $files  = array();
		private $root;
		private $method = array();
		/**
		 * @category construct with Validation
		 * @param    $root
		 * @param    $method
		 * @throws   DirectoryException
		 */
		public function __construct($root, $method) {
			try {
				$this->setRoot($root);
				$this->setMethod($method);
				$this->setFiles();
			}
			catch(DirectoryException $e) {
				throw new DirectoryException($e->getMessage());
			}
		}
		/**
		 * @category getter
		 * @return   array
		 */
		public function getFiles() {
			return $this->files;
		}
		/**
		 * @category setter with Validation
		 * @throws   DirectoryException
		 */
		public function setFiles() {
			//Clear Array
			$this->files = array();
			//Scanning...
			$this->scanRecursively($this->getRoot());
			//Check for Emptiness
			if(empty($this->files)){
				throw new DirectoryException("Directory: No Files available!");
			}
		}
		/**
		 * @category GOLDEN-UTILS which scans a directory recursively
		 * @param    $root
		 */
		public function scanRecursively($root) {
			$scanPackage = scandir($root);
			$scanPackage = array_slice($scanPackage, 2); //Get rid of . ..
			foreach($scanPackage as $elem) {
				$fullyQualifiedFileName = $root.DIRECTORY_SEPARATOR.$elem;
				if(!is_dir($fullyQualifiedFileName)) {
					$pathparts = pathinfo($fullyQualifiedFileName);
					if(!empty($pathparts) && array_key_exists("extension", $pathparts) && in_array($pathparts["extension"], $this->getMethod()))
						$this->files[] = $fullyQualifiedFileName;
					else
						continue;
				}
				else {
					$this->scanRecursively($root.DIRECTORY_SEPARATOR.$elem);
				}
			}
		}
		/**
		 * @category getter
		 */
		public function getRoot() {
			return $this->root;
		}
		/**
		 * @category setter with Validation
		 * @param    $root
		 * @throws   DirectoryException
		 */
		public function setRoot($root) {
			//Check for Emptiness
			if(empty($root))
				throw new DirectoryException("Directory: Root-Directory is empty!");
			//Check for Directory
			if(!is_dir($root))
				throw new DirectoryException("Directory: ".$root." isn't a directory!");
			//Get rid of last directory separator
			while(substr($root, -1) == DIRECTORY_SEPARATOR)
				$root = substr($root, 0, strlen($root) - 1);
			$this->root = $root;
		}
		/**
		 * @category getter
		 */
		public function getMethod() {
			return $this->method;
		}
		/**
		 * @category setter with Validation
		 * @param    $method
		 * @throws   DirectoryException
		 */
		public function setMethod($method) {
			//Check for Emptiness
			if(empty($method)) {
				throw new DirectoryException("Directory: Method is empty!");
			}
			//MySQL and MariaDB are forbidden, cause they use the ORM
			if(strtolower($method) == "mariadb" || strtolower($method) == "mysql")
				throw new DirectoryException("Directory: Forbidden Method!");
			//Check Method
			if(($res = File::validFileExtensions($method)) === false) 
				throw new DirectoryException("Directory: Forbidden Method!");
			//Strip the inappropriate Methods like jpg, png,..
			if(strtolower($method) == "uml")
				$res = array("txt");
			$this->method = $res;
		}
		/**
		 * @category GOLDEN-UTILS
		 * @param    array $chkList
		 * @return   boolean|string
		 */
		public static function basicDirectoryValidation(array $chkList) {
			/**
			 * chkList - Array containing the validation settings
			 * key:      value:                   example:
			 * 
			 * directory string                   /home/michael
			 * exist     boolean(default = true)  true
			 * readable  boolean(default = true)  true
			 * writeable boolean(default = false) false
			 * empty     boolean(default = false) true
			 */
			if(!isset($chkList['directory']))
				return 'No directory given!';
			else
				$directory = $chkList['directory'];
			$checkList['directory'] = $directory;
			$checkList['exist']     = (isset($chkList['exist'])) 
			                            ? $chkList['exist']
			                            : true;
			$checkList['readable']  = (isset($chkList['readable']))
			                            ? $chkList['readable']
			                            : true;
			$checkList['writeable'] = (isset($chkList['writeable']))
			                            ? $chkList['writeable']
			                            : false;
			$checkList['empty']     = (isset($chkList['empty']))
                                        ? $chkList['empty']
                                        : false;
			if($checkList['exist'] && !is_dir($directory))
				return 'Directory '.$directory.' not found!';
			if($checkList['writeable'] && !is_writeable($directory))
				return 'Directory '.$directory.' is not writeable!';
			if($checkList['readable'] && !is_readable($directory))
				return 'Directory '.$directory.' is not readable!';
			if($checkList['empty'] && count(scandir($directory)) == 2) 
				return 'Directory '.$directory.' is empty!';
			return true;
		}
		/**
		 * @category Getter for environment variable $HOME
		 * @return   $HOME
		 */
		public static function HOME() {
			return getenv('HOME');
		}
	}
	/**
	 * @author   Michael Watzer
	 * @category Exception-Derivat
	 * @version  1.0
	 * @since    20.07.2016
	 */
	class DirectoryException extends \Exception{}
?>