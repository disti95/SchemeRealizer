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
	 * @author Michael Watzer
	 * @version 1.0
	 * @since ?
	 * @category prepArr Unit-Test
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../native/Validate.php"
			    ,"../../php/php_parse.php"
			    ,"../../uml/uml_parse.php"
			    ,"../../utils/Parsing.php"
				,"../../utils/Arrays.php"
			    ,"../../engines/sql.php"
			    ,"../../orm/mysql_orm.php"
			    ,"../../orm/sqlite_orm.php"
			    ,"../../utils/String.php"
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
	
	class prepArrCase extends TestCase{
		/**
		 * @dataProvider countElements
		 */
		public function testCountElements($expected, $value){
			$this->assertEquals($expected, $value);
		}
		public function countElements() {
			$php = $this->getPHPArr();
			$uml = $this->getUMLArr();
			$mysql = $this->getMySQLArr();
			$sqlite = $this->getSQLiteArr();
			return [
				"PHP: Expect OK" => [8, count($php[0])],
				"UML Expect OK" => [8, count($uml[0])],
				"MySQL Expect OK" => [8, count($mysql[0])],
				"SQLite Expect OK" => [8, count($sqlite[0])]
			];
		}
		public function getMySQLArr() {
			$mysql = new engine\sql("mysql", "localhost", "root", "");
			$mysql->getConnection("mysql");
			$mysql_orm = new orm\mysql_orm($mysql);
			$mysql_orm->setAttr("func");
			return $mysql_orm->classGenArrNotation();
		}
		public function getSQLiteArr() {
			$sqlite = new engine\sql("sqlite");
			$sqlite->getConnection("../../examples/MyLIB.db");
			$sqlite_orm = new orm\sqlite_orm($sqlite);
			$sqlite_orm->setAttr("Comments");
			return $sqlite_orm->classGenArrNotation();
		}
		public function getPHPArr() {
			include_once "../../examples/Event.php";
			$rc = new ReflectionClass("Event");
			$php = new php\php_parse($rc);
			$php->setAttr();
			$php->setMethods();
			return $php->getArr();
		}
		public function getUMLArr() {
		 	$uml = new uml\uml_parse("../../examples/UMLExample.txt");
		 	$uml->setArr();
		 	return $uml->getArr();
		}
	}
?>
