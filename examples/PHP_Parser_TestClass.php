<?php 
	/*
	 SchemeRealizer - A free ORM and Translator for Diagrams, Databases and PHP-Classes.
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
	class PHP_Parser_TestClass{
		/**
		 * @author   Michael Watzer
		 * @version  1.0
		 * @since    23.08.2016
		 * @category Test-Class for the PHP-Parser
		 */
		const myconst       = array('Michael', 'Watzer', 'Family' => array('Stefanie', 'Watzer', 'Mother' => 'Monika', 'Watzer'));
		const mysecond      = 'val';
		const thirdconst    = '';
		public static $var2 = "implementation";
		protected static $var3;
		
		public function publicFunction(array $var1, callable $var2, ReflectionClass $rc, $person = array('Michael Watzer', 22)){}
		protected static function protectedFunction(){}
		private static final function privateFunction($var1, $var2, callable $var3){}
	}
	class JustAnotherClass{
		/**
		 * @author   Michael Watzer
		 * @version  1.0
		 * @since    23.08.2016
		 * @category Test-Class for the PHP-Parser
		 */
		private $var1;
		public function getVar1(){}
	}
	class ParentClass{}
	interface iFace1{}
	interface iFace2{}
	interface PHP_Parser_TestInterface extends iFace1{
		function publicFunction(array $var1, callable $var2, ReflectionClass $rc, $person = array('Michael Watzer', 22));
		public static function protectedFunction(array $members = array('Family' => array('brothers and sisters' => array('Stefanie', 'Michael'))));
		function privateFunction($var1, $var2, callable $var3);
	}
	abstract class ExtendsClass extends ParentClass implements iFace2{
		/**
		 * @author   Michael Watzer
		 * @version  1.0
		 * @since    23.08.2016
		 * @category Test-Class for the PHP-Parser
		 */
		const var1 = "var1";
		public static $var2 = "implementation";
		protected static $var3;
		
		public function publicFunction(array $var1, callable $var2, ReflectionClass $rc, $person = array('Michael Watzer', 22)){}
		protected static function protectedFunction(){}
		private static final function privateFunction($var1, $var2, callable $var3){}
		public abstract function abstractFunction();
	}
?>
