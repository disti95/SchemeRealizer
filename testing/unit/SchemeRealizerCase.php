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
	 * @category Unit-Test for api/schemerealizer.php
	 * @since    30.05.2017
	 */
	//Including
	include_once '../../utils/File.php';
	include_once '../../utils/Directory.php';
	$INC = array(array("../../gen"      , "class")
			    ,array("../../orm"      , "class")
				,array("../../constants", "class")
				,array("../../engines"  , "class")
				,array("../../php"      , "class")
				,array("../../uml"      , "class")
				,array("../../native"   , "class")
			    ,array("../../api"      , "class")
				,"../../utils/Arrays.php"
				,"../../utils/Parsing.php"
				,"../../utils/String.php");
	if(($res = \utils\File::setIncludes($INC)) !== true)
		die($res);

	if(!native\Validate::checkOS()) {
		echo "Linux OS Required! \n";
		exit(1);
	}
	if(!native\Validate::checkPHPUnit()) {
		echo "PHPUnit is not installed! \n";
		exit(1);
	}

	use PHPUnit\Framework\TestCase;
	
	class SchemeRealizerCase extends TestCase {
		/**
		 * @category test construct
		 */
		public function testConstruct() {
			$this->expectException(\api\SchemeRealizerException::class);
			$sr = new \api\SchemeRealizer('NOTSupported', 'NOTSupported');
		}
		/**
		 * @category test setProjectPath
		 */
		public function testSetProjectPath() {
			$sr = new \api\SchemeRealizer('class', 'mysql');
			$sr->setProjectPath(\utils\Directory::HOME());
			$this->assertEquals(\utils\Directory::HOME(), $sr->getProjectPath());
			$this->expectException(\api\SchemeRealizerException::class);
			$sr->setProjectPath(\utils\Directory::HOME().'/workspace/SchemeRealizer/examples/testdir');
		}
		/**
		 * @category test setDSN
		 */
		public function testSetDSN() {
			$sr = new \api\SchemeRealizer('sqlite', 'class');
			$sr->setConId('from');
			
			$sr->setDSN();
			$this->assertEquals(get_class($sr->getDSN()), 'engine\sql');
			
			$sr->setConvertFrom('mysql');
			$sr->setDSN('localhost', 'root', '');
			$this->assertEquals(get_class($sr->getDSN()), 'engine\sql');
			/**
			 * Mongo Extension neccessary
			 * $sr->setConvertFrom('mongodb');
			 * $sr->setDSN('', '', '', 27017);
			 * $this->assertEquals(get_class($sr->getDSN()), 'engine\mongo');
			 */
			$sr->setConvertFrom('uml');
			$this->expectException(\api\SchemeRealizerException::class);
			$sr->setDSN();
		}
		/**
		 * @category test setORM
		 */
		public function testSetORM() {
			$sr = new \api\SchemeRealizer('mysql', 'class');
			$sr->setConId('from');
			
			$sr->setDSN('localhost', 'root', '');
			$sr->setORM('mysql');
			$this->assertEquals(get_class($sr->getORM()), 'orm\mysql_orm');
			
			$sr->setConvertFrom('sqlite');
			$sr->setDSN();
			$sr->setORM(\utils\Directory::HOME().'/workspace/SchemeRealizer/install/SchemeRealizer.db');
			$this->assertEquals(get_class($sr->getORM()), 'orm\sqlite_orm');
			
			$sr = new \api\SchemeRealizer('class', 'uml');
			$this->expectException(\api\SchemeRealizerException::class);
			$sr->setORM('mysql');
		}
		/**
		 * @category test listDatabaseContent
		 */
		public function testListDatabaseContent() {
			$sr = new \api\SchemeRealizer('mysql', 'class');
			$sr->setConId('from');
			
			$sr->setDSN('localhost', 'root', '');
			$sr->setORM('mysql');
			
			$this->assertGreaterThanOrEqual(24, count($sr->listDatabaseContent()));
			
			$sr->setConvertFrom('sqlite');
			$sr->setDSN();
			$sr->setORM(\utils\Directory::HOME().'/workspace/SchemeRealizer/install/SchemeRealizer.db');
			
			$this->assertEquals($sr->listDatabaseContent(), array('SchemeRealizer'));
		}
		/**
		 * @category test setConId
		 */
		public function testSetConId() {
			$sr = new api\SchemeRealizer('mysql', 'mongodb');
			
			$sr->setConId('from');
			$this->assertEquals('from', $sr->getConId());
			
			$sr->setConId('to');
			$this->assertEquals('to', $sr->getConId());
			
			$sr->setConvertFrom('class');
			$this->expectException(\api\SchemeRealizerException::class);
			$sr->setConId('from');
			
			$sr->setConvertFrom('mysql');
			$sr->setConvertTo('class');
			$this->expectException(\api\SchemeRealizerException::class);
			$sr->setConId('to');
		}
		/**
		 * @category test ClassObj construct
		 */
		public function testClassObjConstruct() {
			$prepArr  = array('name', 1, 'public', true, array('static'), false, false, array('michael'));
			$classObj = new \api\ClassObj($prepArr);
			$this->assertEquals($classObj->getName(),       $prepArr[\api\ClassObj::NAME]);
			$this->assertEquals($classObj->getKey(),        $prepArr[\api\ClassObj::KEY]);
			$this->assertEquals($classObj->getModifier(),   $prepArr[\api\ClassObj::MODIFIER]);
			$this->assertEquals($classObj->getSelected(),   $prepArr[\api\ClassObj::SELECT]);
			$this->assertEquals($classObj->getKeywords(),   $prepArr[\api\ClassObj::KEYWORDS]);
			$this->assertEquals($classObj->getParents(),    $prepArr[\api\ClassObj::PARENTS]);
			$this->assertEquals($classObj->getInterfaces(), $prepArr[\api\ClassObj::INTERFACES]);
			$this->assertEquals($classObj->getValues(),     $prepArr[\api\ClassObj::VALUES]);
			
			$prepArr  = array('name', 1, 'public', true, array('static'), false, false);
			$this->expectException(\api\ClassObjException::class);
			$classObj = new \api\ClassObj($prepArr);
		}
		/**
		 * @category test mapToClass
		 */
		public function testMapToClass() {
			$sr = new \api\SchemeRealizer('mysql', 'class');
			
			$sr->setConId('from');
			$sr->setDSN('localhost', 'root', '');
			$sr->setORM('mysql');
			
			$classObjList = $sr->mapToClass('db');
			$this->assertEquals(get_class($classObjList), 'api\ClassObjList');
			
			foreach($classObjList->getMembers() as $elem) 
				$this->assertEquals($elem->getKey(), \api\ClassObjList::ATTR);
			foreach($classObjList->getMethods() as $elem) 
				$this->assertEquals(true, in_array($elem->getKey(), array(\api\ClassObjList::SETTER, \api\ClassObjList::GETTER, \api\ClassObjList::OTHERMETH)));
			foreach($classObjList->getClass() as $elem)
				$this->assertEquals($elem->getKey(), \api\ClassObjList::CLASSES);
			$this->assertEquals($classObjList->getInterface(), array());
			
			$sr->setConvertTo('uml');
			$classObjList = $sr->mapToClass('db');
			$this->assertEquals(get_class($classObjList), 'api\ClassObjList');
			
			foreach($classObjList->getMembers() as $elem)
				$this->assertEquals($elem->getKey(), \api\ClassObjList::ATTR);
			foreach($classObjList->getMethods() as $elem)
				$this->assertEquals($elem->getKey(), \api\ClassObjList::UMLMETH);
			foreach($classObjList->getClass() as $elem)
				$this->assertEquals($elem->getKey(), \api\ClassObjList::UMLCLASS);
			$this->assertEquals($classObjList->getInterface(), array());
			
			$sr->setConvertTo('mongodb');
			$this->expectException(\api\SchemeRealizerException::class);
			$classObj = $sr->mapToClass('db');
			
			$sr->setConvertTo('class');
			$this->expectException(\api\SchemeRealizerException::class);
			$classObj = $sr->mapToClass('doesnotexist');
		}
		/**
		 * @category test getAvailableClasses
		 */
		public function testGetAvailableClasses() {
			$sr = new \api\SchemeRealizer('class', 'mysql');
			$sr->setProjectPath(\utils\Directory::HOME().'/workspace/SchemeRealizer/examples/');
			
			$arr = $sr->getAvailableClasses();
			$this->assertEquals(true, is_array($arr) && !empty($arr));
			
			$sr->setConvertFrom('uml');
			$arr = $sr->getAvailableClasses();
			$this->assertEquals(true, is_array($arr) && !empty($arr));
			
			$sr->setConvertFrom('mongodb');
			$this->expectException(\api\SchemeRealizerException::class);
			$sr->getAvailableClasses();
		}
		/**
		 * @category test SQLObj construct
		 */
		public function testSQLObjConstruct() {
			$arr    = array('col', 'int', 255, 1, -1, -1, true, 10);
			$sqlObj = new \api\SQLObj($arr);
			
			$this->assertEquals($sqlObj->getName(),     $arr[\api\SQLObj::NAME]);
			$this->assertEquals($sqlObj->getDatatype(), $arr[\api\SQLObj::DATATYPE]);
			$this->assertEquals($sqlObj->getSize(),     $arr[\api\SQLObj::SIZE]);
			$this->assertEquals($sqlObj->getIndex(),    $arr[\api\SQLObj::INDEX]);
			$this->assertEquals($sqlObj->getNull(),     $arr[\api\SQLObj::NULLABLE]);
			$this->assertEquals($sqlObj->getAI(),       $arr[\api\SQLObj::AI]);
			$this->assertEquals($sqlObj->getSelected(), $arr[\api\SQLObj::SELECTED]);
			$this->assertEquals($sqlObj->getDefault(),  $arr[\api\SQLObj::DEFVAL]);
			
			$arr    = array('col', 'int', 255, 1, -1, -1, true);
			$this->expectException(\api\SQLObjException::class);
			$sqlObj = new \api\SQLObj($arr);
		}
		/**
		 * @category test mapToSQL
		 */
		public function testMapToSQL() {
			$sr = new \api\SchemeRealizer('class', 'mysql');
			$sr->setProjectPath(\utils\Directory::HOME().'/workspace/SchemeRealizer/examples');
			$sr->setDistinctFile(\utils\Directory::HOME().'/workspace/SchemeRealizer/examples/PHP_Parser_TestClass.php');
			$sqlObjList = $sr->mapToSQL('PHP_Parser_TestClass');

			foreach($sqlObjList->getSQLObjList() as $sqlObj) 
				$this->assertEquals(get_class($sqlObj), 'api\SQLObj');
			
			$sr->setDistinctFile(0);
			$sqlObjList = $sr->mapToSQL('ExtendsClass');
			
			foreach($sqlObjList->getSQLObjList() as $sqlObj)
				$this->assertEquals(get_class($sqlObj), 'api\SQLObj');
			
			$sr->setConvertFrom('uml');
			$sqlObjList = $sr->mapToSQL('Employee');
			
			foreach($sqlObjList->getSQLObjList() as $sqlObj) 
				$this->assertEquals(get_class($sqlObj), 'api\SQLObj');
			
			$this->expectException(\api\SchemeRealizerException::class);
			$sqlObjList = $sr->mapToSQL('doesnotexist');
		}
		/**
		 * @category test getSQLCode
		 */
		public function testGetSQLCode() {
			$sr = new \api\SchemeRealizer('class', 'mysql');
			$sr->setProjectPath(\utils\Directory::HOME().'/workspace/SchemeRealizer/examples');
			
			$sqlCode = $sr->getSQLCode($sr->mapToSQL('PHP_Parser_TestClass')
					                  ,'PHP_Parser_TestClass');
			$this->assertEquals(strpos($sqlCode, 'CREATE TABLE PHP_Parser_TestClass'), 0);
			
			$sr->setConvertTo('sqlite');
			$sqlCode = $sr->getSQLCode($sr->mapToSQL('ExtendsClass')
					                 ,'ExtendsClass');
			$this->assertEquals(strpos($sqlCode, 'CREATE TABLE ExtendsClass'), 0);
			
			$this->expectException(\api\SchemeRealizerException::class);
			$sr->getSQLCode($sr->mapToSQL('ExtendsClass'), '');
		}
	}
