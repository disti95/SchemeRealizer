<?php
	/*
	 SchemeRealizer - A free ORM and Translator for Diagrams, Databases and PHP-Classes/Interfaces.
	 Copyright (C) 2017  Michael Watzer/Christian Dittrich
	
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
	 * @category Error class for class SchemeRealizer
	 * @since    30.05.2017
	 * @see      api/doc/schemerealizer.pdf
	 */
	namespace api;
	class SRError implements ISRError {
		private $errorstack = array();
		/**
	 	 * @category push an error to the stack
	 	 * @param    $errcode
	 	 * @param    $errmsg
	 	 */
	 	public function pushError($errcode, $errmsg) {
	 		array_push($this->errorstack, array($errcode, $errmsg));
	 	}
	 	/**
	 	 * @category return error stack array
	 	 * @return   error array
	 	 */
	 	public function getErrorStack() {
	 		return $this->errorstack;
	 	}
	 	/**
	 	 * @category print all errors from the stack
	 	 * @return   error messages as a string
	 	 */
	 	public function printErrorStack($type = 'cli') {
	 		$output = '';
	 		$break  = ($type == 'cli') 
	 			        ? '\n'
	 			        : '<br />';
	 		foreach($this->getErrorStack() as $error) 
	 			$output .= $error[0].': '.$error[1].$break;
	 		return $output;
	 	}
	 	/**
	 	 * @category clear the error stack
	 	 */
	 	public function clearErrorStack() {
	 		$this->errorstack = array();
	 	}
	 	/**
	 	 * @category check if the error stack is empty
	 	 * @return   boolean
	 	 */
	 	public function hasError() {
	 		return count($this->getErrorStack()) 
	 		         ? true
	 		         : false;
	 	}
	}