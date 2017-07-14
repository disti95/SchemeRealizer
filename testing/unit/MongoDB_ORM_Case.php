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
	 * @author   Christian Dittrich
	 * @version  1.0
	 * @category unit test for Mongodb_orm
	 * @since    11.05.2017
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../orm/mongodb_orm.php" ,
				"../../engines/mongodb.php",
				"../../utils/Arrays.php",
				"../../utils/String.php",
				"../../native/Validate.php",
				"../../constants/constants.php");
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
	class MongoDB_ORM_Case extends TestCase{
		
		private $orm;
		private $arr;
		/**
		 * @category setUp to initiate the connection
		 */
		function setUp() {
			try {
				$eng = new engine\mongo("localhost","root","Nummer22");
				$eng->openConnection();
				$this->orm = new orm\mongodb_orm($eng->getConnection());
				$this->assertEquals(get_class($this->orm), "orm\mongodb_orm");
			}
			catch(\Exception $e) {
				throw new MongoDB_ORM_Case_Exception("MongoDB_ORM_Case_Exception: ".$e);
			}
		}
		/**
		 * @category test setters
		 */
		function testSettersCase() {
			try {
				$this->orm->setDatabase("SchemeTest");
				$this->orm->setCollection("UnitTestCollection");
			} catch(\Exception $e) {
				throw new MongoDB_ORM_Case_Exception("MongoDB_ORM_Case_Exception: ".$e);
			}
			$this->assertEquals($this->orm->getCollection(), "UnitTestCollection");
		}
		/**
		 * @category Test of getting out keys of a specific Collection
		 */
		function testGetKeysOfCollectionCase() {
			try {
				$this->orm->setDatabase("SchemeTest");
				$this->orm->setCollection("UnitCollection");
				$this->orm->InsertData(json_decode('{ "name":"chris", "added":"new", "newone":1, "newone":{ "objkey1":{ "objkey1_1":{ "objkey1_1_1":"val", "objkey1_1_2":"val", "objkey1_1_3":"val" } , "objkey1_2":"val" } } , "ARR1":[{ "key":"Str" }, "2arrwert"], "ARR2":[{ "inner":"Obj", "nextinn":"OBJIC" }, "vat"], "ARR3":[{ "arrinner":["innerhalb1", "innerhalb2"], "inner":"Obj", "nextinn":"OBJIC" }, "vat"] }'));
				$arr = $this->orm->getKeysOfCollection();
				$checkarr = array("ARR1", "ARR2", "ARR3", "_id", "added", "arrinner", "inner", "key", "name", "newone", "nextinn", "objkey1", "objkey1_1", "objkey1_1_1", "objkey1_1_2", "objkey1_1_3", "objkey1_2");
				asort($checkarr);
				$this->assertEquals($arr,$checkarr, "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
				$this->arr = $arr;
			}
			catch(\Exception $e) {
				throw new MongoDB_ORM_Case_Exception("MongoDB_ORM_Case_Exception: ".$e);
			}
		}
		/**
		 * @category test the "gen" function
		 */
		function testGenCase() {
			try {
				$this->orm->setDatabase("SchemeTest");
				$this->orm->setCollection("UnitCollection");
				$this->orm->classGenArrNotation();
				$this->orm->umlGenArrNotation();
			}
			catch(\Exception $e) {
				throw new MongoDB_ORM_Case_Exception("MongoDB_ORM_CASE_Exception: ".$e);
			}
		}
		
	}
	
	class MongoDB_ORM_Case_Exception extends \Exception {}
