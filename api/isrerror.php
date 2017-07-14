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
	 * @category Interface for class SRError
	 * @since    30.05.2017
	 */
	 namespace api;
	 interface ISRError {
	 	/**
	 	 * @category push an error to the stack
	 	 * @param    $errcode
	 	 * @param    $errmsg
	 	 */
	 	public function pushError($errcode, $errmsg);
	 	/**
	 	 * @category return error stack array
	 	 * @return   error array
	 	 */
	 	public function getErrorStack();
	 	/**
	 	 * @category print all errors from the stack
	 	 * @return   error messages as a string
	 	 */
	 	public function printErrorStack();
	 	/**
	 	 * @category clear the error stack
	 	 */
	 	public function clearErrorStack();
	 	/**
	 	 * @category check if the error stack is empty
	 	 * @return   boolean
	 	 */
	 	public function hasError();
	 }