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
	 * @since    19.03.2017
	 * @category Unit-Test for utils/String.php
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../utils/String.php", "../../native/Validate.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");
	
	if(!native\Validate::checkOS()) {
		echo "Linux OS Required! \n";
		exit(1);
	}
	if(!native\Validate::checkPHPUnit()) {
		echo "PHPUnit is not installed! \n";
		exit(1);
	}
	
	use PHPUnit\Framework\TestCase;
	
	class StringCase extends TestCase {
		/**
		 * @category testing static method StrParamToArray
		 */
		public function testStrParamToArray() {
			$paramstr    = '$name = array(\'Michael=\', \'Watzer\', \'Family\' => array(\'Monika\', \'Stefanie\' => array(\'Caro\' => array(\'4\'), \'Miri\'))), $project = \'SR\'';
			$paramarr    = utils\String::StrParamToArray($paramstr);
			$expectedarr = array('$name' => 'array(\'Michael=\', \'Watzer\', \'Family\' => array(\'Monika\', \'Stefanie\' => array(\'Caro\' => array(\'4\'), \'Miri\')))', '$project' => '\'SR\'');
			$this->assertEquals($expectedarr, $paramarr);
		}
		/**
		 * @category testing static method cntCharStr
		 */
		public function testCntCharStr() {
			$str = "This = String = has = four = assignments";
			$cnt = utils\String::cntCharStr($str, "=");
			$this->assertEquals(4, $cnt);
		}
		/**
		 * @category testing static method getDSN
		 */
		public function testGetDSN() {
			$dsn = utils\String::getDSN("localhost", "root", "mysql");
			$this->assertEquals($dsn, "root@host=localhost;dbname=mysql");
		}
		/**
		 * @category testing static method addQuotes
		 */
		public function testAddQuotes() {
			$this->assertEquals("'Michael'", utils\String::addQuotes("Michael"));
		}
		/**
		 * @category testing static method getRandomString
		 */
		public function testGetRandomString() {
			$this->assertEquals(pow(2, 16), strlen(\utils\String::getRandomString(pow(2, 16))));
		}
		/**
		 * @category testing static method chkJavaClassName
		 */
		public function testChkJavaClassName() {
			$this->assertEquals(false, \utils\String::chkJavaClassName("/not/allowed"));
			$this->assertEquals(true,  \utils\String::chkJavaClassName("main.java.foo.bar"));
			$this->assertEquals(true,  \utils\String::chkJavaClassName("main.java.foo.bar1"));
		}
	}
?>
