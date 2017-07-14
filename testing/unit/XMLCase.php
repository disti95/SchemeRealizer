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
	 * @since    ?
	 * @category Unit-Test for the XML-Util
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../native/Validate.php"
			    ,"../../utils/XML.php");
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
	
	class XMLCase extends TestCase{
		/**
		 * @category Construct-Testing
		 */
		public function testConstruct(){
			try {
				new utils\XML("../this/file/is/invalid");
			}
			catch(utils\XMLException $e) {
				$this->assertEquals("XML: File ../this/file/is/invalid doesn't exist!", $e->getMessage());
			}
			try {
				new utils\XML("../../config/config.xml", "/now/this/is/invalid");
			}
			catch(utils\XMLException $e) {
				$this->assertEquals("XML: File /now/this/is/invalid doesn't exist!", $e->getMessage());
			}
			try {
				new utils\XML("../../config/config.xml", "../../config/config.xsd", "hastobeabool");
			}
			catch(utils\XMLException $e) {
				$this->assertEquals("XML: CLI has to be a boolean!", $e->getMessage());
			}
			try {
				new utils\XML("../../config/config.xml", "", true);
			}
			catch(utils\XMLException $e) {
				$this->assertEquals("XML: Empty file input!", $e->getMessage());
			}
			try {
				new utils\XML("", "../../config/config.xsd", true);
			} 
			catch(utils\XMLException $e) {
				$this->assertEquals("XML: Empty file input!", $e->getMessage());
			}
		}
		/**
		 * @category Schema-Validator Test
		 */
		public function testSchema() {
			//True expected
			$xmlOK = new utils\XML("../../config/config.xml", "../../config/config.xsd");
			$res   = $xmlOK->validateXML();
			$this->assertEquals($res, true);
			//Failure expected
			$xmlFailure = new utils\XML("../../config/invalidconfig.xml", "../../config/config.xsd");
			$res        = $xmlFailure->validateXML();
			$this->assertNotEquals($res, true);
		}
		/**
		 * @category test method nodeExist
		 */
		public function testNodeExist() {
			$xml = new utils\XML("../../config/config.xml", "../../config/config.xsd");
			$res = $xml->nodeExist("class");
			$this->assertEquals($res, true);
			
			$res = $xml->nodeExist("didnotexist");
			$this->assertEquals(gettype($res), "string");
		}
		/**
		 * @category test method getNodeVal
		 */
		public function testGetNodeVal()  {
			$xml = new utils\XML("../../config/config.xml", "../../config/config.xsd");
			$res = $xml->getNodeVal("class");
			$this->assertEquals($res, "../clflush");
			
			$this->expectException(\utils\XMLException::class);
			$res = $xml->getNodeVal("didnotexist");
		}
		/**
		 * @category test method setNodeVal
		 */
		public function testSetNodeVal() {
			$newpath = \utils\Directory::HOME();
			$oldpath = "../clflush";
			$xml     = new utils\XML("../../config/config.xml", "../../config/config.xsd");
			$res     = $xml->setNodeVal("class", $newpath);
			$this->assertEquals($res, true);
			
			$res = $xml->getNodeVal("class");
			$this->assertEquals($res, $newpath);
			
			$res = $xml->setNodeVal("class",$oldpath);
			$this->assertEquals($res, true);
			
			$res = $xml->getNodeVal("class");
			$this->assertEquals($res, $oldpath);
		}
	}
?>