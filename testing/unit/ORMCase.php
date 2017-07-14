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
	* @since    15.06.2017
	* @category Unit-Test for orm/*
	*/
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../engines/sql.php"
			    ,"../../orm/mysql_orm.php"
			    ,"../../orm/sqlite_orm.php"
			    ,"../../constants/constants.php"
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
	
	class ParsingCase extends TestCase{
		/**
		 * @category test MySQL's method getDefaultValue
		 */
		public function testMySQLGetDefaultValue() {
			$sql = new engine\sql(constant('__MYSQL__')
					             ,'localhost'
					             ,'root'
					             ,'');
			$sql->getConnection(constant('__MYSQL__'));
			$orm     = new orm\mysql_orm($sql);
			$default = $orm->getDefaultValue('SchemeRealizer', 'testcol');
			$this->assertEquals($default, 'SchemeRealizer');
			$default = $orm->getDefaultValue('SchemeRealizer', 'doesnotexist');
			$this->assertEquals($default, false);
		}
		/**
		 * @category test SQLite's method getDefaultValue
		 */
		public function testSQLiteGetDefaultValue() {
			$sql = new engine\sql(constant('__SQLITE__'));
			$sql->getConnection('../../install/SchemeRealizer.db');
			
			$orm     = new orm\sqlite_orm($sql);
			$default = $orm->getDefaultValue('SchemeRealizer', 'testcol');
			$this->assertEquals('SchemeRealizer', $default);
			$default = $orm->getDefaultValue('SchemeRealizer', 'doesnotexist');
			$this->assertEquals(false, $default);
		}
	}