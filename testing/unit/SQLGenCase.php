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
	 * @category SQLGen Unit-Test
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../native/Validate.php"
			    ,"../../gen/sqlvalidation.php"
			    ,"../../constants/constants.php"
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
	
	class SQLGenCase extends TestCase{
		public function testDBMS() {
			$this->assertTrue(checkDBMS("mysql"));
			$this->assertTrue(checkDBMS("sqlite"));
			$this->assertFalse(checkDBMS("mssql"));
		}
		/**
		 * @dataProvider regexValues
		 */
		public function testRegex($expected, $value) {
			$this->assertEquals($expected, regexName($value));
		}
		public function regexValues() {
			return [
				"myTAB: Expect OK"        => [true, "myTAB"],
				"_myTAB: Expect OK"       => [true, "_myTAB"],
				"__myTAB9: Expect OK"     => [true, "__myTAB9"],
				"_0myTAB: Expect OK"      => [true, "_0myTAB"],
				"00__myTAB__ Expect OK"   => [true, "00__myTAB__"],
				"00__myTAB__00 Expect OK" => [true, "00__myTAB__00"],
				".myTAB: Expect Failure"  => [false, ".myTAB"]
			];
		}
		/**
		 * @dataProvider mysqlAI
		 */
		public function testAI($expected, $value) {
			$this->assertEquals($expected, checkAIMySQL($value));
			$this->assertTrue(checkAISQLite("integer"));
		}
		public function mysqlAI() {
			return [
				"integer OK"   => [true, "integer"],
				"int OK"       => [true, "int"],
				"tinyint OK"   => [true, "tinyint"],
				"smallint OK"  => [true, "smallint"],
				"mediumint OK" => [true, "mediumint"],
				"bigint OK"    => [true, "bigint"],
				"float OK"     => [true, "float"],
				"double OK"    => [true, "double"]
			];
		}
	}
?>
