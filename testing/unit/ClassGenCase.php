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
	 * @since    23.04.2017
	 * @category Unit-Test for gen/classgen
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../constants/constants.php"
                ,"../../gen/classgen.php"
                ,"../../utils/Parsing.php"
                ,"../../utils/Arrays.php"
                ,"../../native/Validate.php"
                ,"../../utils/String.php");
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
	
	class ArraysCase extends TestCase {
		/**
		 * @category testing the new PHP interface generation
		 */
		public function testInterfaceAttr() {
			$this->expectException(\gen\classgenException::class);
			$arr = array(array("myclass", 6, false, true, false, false, false, false));
			$gen = new \gen\classgen($arr, "myclass");
			$gen->getAttributes();
		}
		public function testInterfaceClass() {
			$this->expectException(\gen\classgenException::class);
			$arr = array(array("myclass", 6, false, true, array(constant('__ABSTRACT__')), false, array("myinterface"), false));
			$gen = new \gen\classgen($arr, "myclass");
			$gen->getClass();
		}
		public function testInterfaceGetter() {
			$this->expectException(\gen\classgenException::class);
			$arr = array(array("myclass", 6, false, true, false, false, false, false)
					    ,array("mygetter", 2, constant('__PUBLIC__'), true, array(constant('__FINAL__')), false, false, false));
			$gen = new \gen\classgen($arr, "myclass");
			$gen->getGetter();
		}
		public function testInterfaceSetter() {
			$this->expectException(\gen\classgenException::class);
			$arr = array(array("myclass", 6, false, true, false, false, false, false)
					    ,array("mysetter", 3, constant('__PUBLIC__'), true, array(constant('__FINAL__')), false, false, false));
			$gen = new \gen\classgen($arr, "myclass");
			$gen->getSetter();
		}
		public function testInterfaceOthers() {
			$this->expectException(\gen\classgenException::class);
			$arr = array(array("myclass", 6, false, true, false, false, false, false)
				     	,array("myother", 4, constant('__PUBLIC__'), true, array(constant('__FINAL__')), false, false, false));
			$gen = new \gen\classgen($arr, "myclass");
			$gen->getOthers();
		}
	}
?>
