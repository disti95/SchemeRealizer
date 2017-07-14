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
	namespace native;
	
	class Validate {
		/**
		 * @author   Michael Watzer
		 * @since    12.03.2017
		 * @version  1.0
		 * @category Validation with native OS-Calls
		 */
		/**
		 * @category Evaluate PHP-Syntax
		 * @param    $code
		 * @return   boolean
		 */
		public static function chkPHPSyntax($code) {
			$phpcode   = str_replace("'", '"', $code);
			$phpcode   = str_replace('"$', '"\$', $phpcode); //Escaping
			$chkSyntax = exec('echo \'<?php '.$phpcode. '\' | php -l > /dev/null 2>&1; echo $?');
			return(!$chkSyntax) ? true : false;
		}
		/**
		 * @category Check if the given code throws an error
		 * @param    $code
		 * @return   boolean
		 */  
		public static function codeThrowsError($code) {
			$chkErr = exec('echo \'<?php '.$code. '\' | php > /dev/null 2>&1; echo $?');
		    return(!$chkErr) ? true : false;
		}
		/**
		 * @category Check if the phpunit is installed
		 * @return   boolean
		 */
		public static function checkPHPUnit() {
			return (!exec("phpunit --version | grep $?")) ? true : false;
		}
		/**
		 * @category Check if the current environment is Linux-based
		 * @return   boolean
		 */
		public static function checkOS() {
			return (substr(php_uname(), 0, 5) == 'Linux') ? true : false;
		}
	}
?>
