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
	namespace error;
	class error{
		/**
		 * @author  Michael Watzer
		 * @version 1.0
		 * @since   ?
		 * @since   DBUS for Error-Messages
		 */
		/**
		 * @description array(error, val)
		 */
		private $arr    = array();
		private $exitcd = 0;
		/**
		 * @category View-Supporting
		 * @param    $message
		 * @return   string
		 */
		public static function setError($message) {
			return "<br /><div id='errorDIV'><h3>".$message."</h3></div><br />";
		}
		/**
		 * @category   Emptiness Validation
		 * @deprecated Use utils/Arrays.php instead
		 * @param      $val
		 * @return     boolean
		 */
		public static function checkEmptiness($val) {
			$empty = false;
			if(is_array($val)) {
				foreach($val as $elem) {
					if(empty($elem)) {
						$empty = true;
					}
				}
			}
			else {
				if(empty($val))
					$empty = true;
			}
			return $empty;
		}
		/**
		 * @category Array-Handling
		 * @param    $error
		 * @param    $val
		 */
		public function addElem($error, $val) {
			if($error == true || $error == false) 
				$this->arr[] = array("error"=>$error,"val"=>$val);
		}
		/**
		 * @category Getter
		 * @return   multitype:
		 */
		public function getArr() {
			return $this->arr;
		}
		/**
		 * @category Error-Checking
		 * @return   boolean
		 */
		public function hasError() {
			foreach($this->arr as $elem) {
				if($elem["error"] == true) 
					return true;
			}
			return false;
		}
		/**
		 * @category Setter
		 * @param    $exitcd
		 */
		public function setExitcd($exitcd) {
			if(!is_int($exitcd))
				$this->exitcd = 0;
			else 
				$this->exitcd = $exitcd;
		}
		public function getExitcd() {
			return $this->exitcd;
		}
		/**
		 * @category Error-Printing
		 * @param    crash(true|false)(optional)
		 */
		public function printErrors($crash = false) {
			foreach($this->arr as $elem) 
				if($elem["error"] == true)
					echo $elem["val"]."\n";
			if($crash)
				exit($this->getExitcd());
		}
	}
?>
