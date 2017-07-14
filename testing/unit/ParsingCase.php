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
	 * @version  1.1
	 * @since    23.08.2016
	 * @category Unit-Test for the utils\Parsing.php Class
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../utils/Parsing.php"
			    ,"../../native/Validate.php"
			    ,"../../constants/constants.php"
			    ,"../../utils/String.php"
			    ,"../../utils/Arrays.php");
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
	
	class ParsingCase extends TestCase{
		/**
		 * @category test method validateClassName 
		 */
		public function testValidateClassName(){
			$this->assertEquals(true, \utils\Parsing::validateClassName("__This_is_Valid"));
			$this->assertEquals(true, \utils\Parsing::validateClassName("_0123__This_is_Valid"));
			$this->assertEquals(true, \utils\Parsing::validateClassName("---12__This_is_Valid"));
			$this->assertEquals(true, \utils\Parsing::validateClassName("\1__This_is_Valid--2"));
		}
		/**
		 * @category test method classIsInterface
		 */
		public function testClassIsInterface() {
			$fileWithInterface    = "../../examples/PHP_Parser_TestClass.php";
			$fileWithoutInterface = "../../examples/userUMLTest.php";
			$this->assertEquals(true,  \utils\Parsing::classIsInterface($fileWithInterface, "iFace2"));
			$this->assertEquals(false, \utils\Parsing::classIsInterface($fileWithoutInterface, "userUMLTest"));
		}
		/**
		 * @category test method parseConstVal
		 */
		public function testParseConstVal() {
			$str    = "myconst = array('Michael', 'Watzer', 'Family' => array('Monika', 'Stefanie')) {const}";
			$expect = array(0 => 'Michael', 1 => 'Watzer', 'Family' => array('Monika', 'Stefanie'));
			$this->assertEquals($expect, \utils\Parsing::parseConstVal($str));
			$str    = "myconst = 'Michael' {const}";
			$expect = "Michael";
			$this->assertEquals($expect, \utils\Parsing::parseConstVal($str));
			$str    = '# firstname';
			$expect = false;
			$this->assertEquals($expect, \utils\Parsing::parseConstVal($str));
			
			$str       = "myconst = array('Michael', 'Watzer', 'Family' => array('Monika', 'Stefanie')) {const}";
			$name      = \utils\Parsing::DiagramHasName($str, 0);
			$attr      = \utils\Parsing::DiagramHasAttribute($str);
			$val       = \utils\Parsing::parseConstVal($str);
			$expectVal = array(0 => 'Michael', 1 => 'Watzer', 'Family' => array('Monika', 'Stefanie'));
			$this->assertEquals('myconst', $name);
			$this->assertEquals(array('const'), $attr);
			$this->assertEquals($expectVal, $val);
		}
	}
?>