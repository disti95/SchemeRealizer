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
	 * @since    22.05.2017
	 * @category Unit-Test for the Globals-Util
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../utils/Globals.php"
			    ,"../../utils/String.php"
			    ,"../../native/Validate.php");
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
	
	class GlobalsCase extends TestCase{
		private $testFile = './../../globals/test.txt';
		/**
		 * @category test method getValOfKey
		 */
		public function testGetValOfKey(){
			$globals = new utils\Globals($this->testFile);
			$this->assertEquals($globals->getValOfKey('str')
					           ,'foobar');
			$this->assertEquals($globals->getValOfKey('arr')
					           ,array('foo', 'bar', 'is', 'an', 'array'));
			$this->assertEquals($globals->getValOfKey('arr1')
					           ,false);
			$this->assertEquals($globals->getValOfKey('str1')
					           ,false);
		}
		/**
		 * @category test method setValOfKey
		 */
		public function testSetValOfKey() {
			$globals = new utils\Globals($this->testFile);
			$globals->setValOfKey('str', 'foo');
			$this->assertEquals('foo'
					           ,$globals->getValOfKey('str'));
			$globals->setValOfKey('str', 'foobar');
			$this->assertEquals('foobar'
					           ,$globals->getValOfKey('str'));
			$globals->setValOfKey('arr', 'arr', 4);
			$this->assertEquals('arr'
					           ,$globals->getValOfKey('arr')[4]);
			$globals->setValOfKey('arr', 'array', 4);
			$this->assertEquals('array'
					           ,$globals->getValOfKey('arr')[4]);
			$this->assertEquals(false
					           ,$globals->setValOfKey('arr', 'foo', 5));
			$this->assertEquals(false
					           ,$globals->setValOfKey('doesnotexist', 'foo'));
		}
		/**
		 * @category test method getIncrementVal and getDecrementVal
		 */
		public function testGetIncrementAndDecrementVal() {
			$globals = new utils\Globals($this->testFile);
			$this->assertEquals(2
					           ,$globals->getIncrementedVal('num'));
			$this->assertEquals(0
					           ,$globals->getdecrementedVal('num'));
			$this->assertEquals(false
					           ,$globals->getIncrementedVal('arr'));
			$this->assertEquals(false
					           ,$globals->getdecrementedVal('arr'));
		}
	}
?>