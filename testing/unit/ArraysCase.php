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
	 * @since    12.08.2016
	 * @category Unit-Test for utils/Arrays.php
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../utils/Arrays.php"
			    ,"../../native/Validate.php"
				,"../../constants/constants.php");
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
	use function constants\classAndInterface;
	use function constants\noClassOrInterface;
	
	class ArraysCase extends TestCase {
		/**
		 * @category testing recursive emptiness search
		 */
		public function testEmptiness(){
			$arr = new utils\Arrays();
			
			$arr->setEmptiness(array("elem1", "elem2", "elem3"));
			$this->assertEquals(false, $arr->emptiness);
			$arr->emptiness = false;
			
			$arr->setEmptiness(array("", "elem2", "elem3"));
			$this->assertEquals(true, $arr->emptiness);
			$arr->emptiness = false;
			
			$arr->setEmptiness(array(array(array("elem1", "elem2", "elem3"), array("elem1", "elem2"))));
			$this->assertEquals(false, $arr->emptiness);
			$arr->emptiness = false;
			
			$arr->setEmptiness(array(array("elem1", "elem2", "elem3"), array(array("", "elem2"))));
			$this->assertEquals(true, $arr->emptiness);
			$arr->emptiness = false;
		}
		/**
		 * @category testing param array to str 
		 */
		public function testGetParamStr(){
			$arr = new utils\Arrays();
			
			$str = $arr->getParamStr(array('$name' => "Michael", '$age' => 22, '$foo' => "bar", '$city' => array("Vienna", array("Stammersdorf" => 7, "Guertel", "NewYork" => array("Big", "City"), "Seattle"), "Berlin", "Rome"), '$language' => "C"));
			$expect = '$name = \'Michael\', $age = \'22\', $foo = \'bar\', $city = array(\'Vienna\', array(\'Stammersdorf\' => \'7\', \'0\' => \'Guertel\', \'NewYork\' => array(\'Big\', \'City\'), \'1\' => \'Seattle\'), \'Berlin\', \'Rome\'), $language = \'C\'';
			$this->assertEquals($expect, $str);
		}
		/**
		 * @category test if an array is associative
		 */
		public function testIsAssoc() {
			$arr = new utils\Arrays();
			
			$noassoc = array("0"    => "Michael", "1"   => "Watzer", "2" => 22);
			$isassoc = array("name" => "Michael", "age" => 22, "city" => array("Vienna", "Berlin", "Rome"));
			$this->assertEquals(false, $arr->isAssoc($noassoc));
			$this->assertEquals(true,  $arr->isAssoc($isassoc));
		}
		/**
		 * @category test isInterface
		 */ 
		public function testIsInterface() {
			$name = 'MyClass';
			$msg1 = constant('__CLASS_NAME_MISSING__');
			$msg2 = constant('__SRC_ERROR__');
			$msg3 = classAndInterface($name);
			$msg4 = noClassOrInterface($name);
			
			$ret = utils\Arrays::isInterface(array(), "", __CLASS__);
			$this->assertEquals($msg1, $ret);
			$ret = utils\Arrays::isInterface(array(), $name, "sql");
			$this->assertEquals($msg2, $ret);
			$ret = utils\Arrays::isInterface(array(array(0, 5), array(0, 6)), $name, constant('__CLASS__'));
			$this->assertEquals($msg3, $ret);
			$ret = utils\Arrays::isInterface(array(array(0, 1)), $name, constant('__CLASS__'));
			$this->assertEquals($msg4, $ret);
			$ret = utils\Arrays::isInterface(array(array(0, 6)), $name, constant('__CLASS__'));
			$this->assertEquals(true, $ret);
			$ret = utils\Arrays::isInterface(array(array(0, 4)), $name, constant('__UML__'));
			$this->assertEquals(true, $ret);
			$ret = utils\Arrays::isInterface(array(array(0, 5)), $name, constant('__CLASS__'));
			$this->assertEquals(false, $ret);
			$ret = utils\Arrays::isInterface(array(array(0, 3)), $name, constant('__UML__'));
			$this->assertEquals(false, $ret);
		}
		/**
		 * @category test extractClassName
		 */
		public function testExtractClassName() {
			$classname     = 'myclass';
			$interfacename = 'myinterface';
			
			$ret = utils\Arrays::extractClassName(array(array(0, 1), array($classname, 5)), constant('__CLASS__'));
			$this->assertEquals(array(false, $classname), $ret);
			$ret = utils\Arrays::extractClassName(array(array(0, 1), array($classname, 3)), constant('__UML__'));
			$this->assertEquals(array(false,$classname), $ret);
			$ret = utils\Arrays::extractClassName(array(array(0, 1), array($interfacename, 6)), constant('__CLASS__'));
			$this->assertEquals(array(false,$interfacename), $ret);
			$ret = utils\Arrays::extractClassName(array(array(0, 1), array($interfacename, 4)), constant('__UML__'));
			$this->assertEquals(array(false,$interfacename), $ret);
			$ret = utils\Arrays::extractClassName(array(array(0, 1), array($classname, 5)), "sql");
			$this->assertEquals(array(true, constant('__SRC_ERROR__')), $ret);
			$ret = utils\Arrays::extractClassName(array(array(0, 1), array($classname, 4)), constant('__CLASS__'));
			$this->assertEquals(array(true, constant('__CLASS_NAME_MISSING__')), $ret);
			$ret = utils\Arrays::extractClassName(array(array(0, 1), array($classname, 5), array($interfacename, 6)), constant('__CLASS__'));
			$this->assertEquals(array(true, classAndInterface($classname)), $ret);
		}
		/**
		 * @category test typeInUMLArr
		 */
		public function testTypeInUMLArr() {
			$arr = array(array('attribute', 1));
			$this->assertEquals(true, utils\Arrays::typeInUMLArr(1, $arr));
			$this->assertEquals(false, utils\Arrays::typeInUMLArr(2, $arr));
		}
	}
?>
