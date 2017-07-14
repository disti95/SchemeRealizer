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
	 * @since    25.05.2017
	 * @category Unit-Test for the gen/sqlvalidation.php
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../constants/constants.php"
				,"../../gen/sqlvalidation.php"
				,"../../native/Validate.php"
				,"../../utils/String.php"
			    ,"../../engines/sql.php");
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

	class SQLValidationCase extends TestCase{
		/*
		 * Calculation of actual row length:
		 * 1 + (sum of column length)
		 *   + (number of NULL tagged columns + 7) / 8
		 *   + (number of variable-length columns e.g varchar)
		 */
		/**
		 * @category test function checkRowLength
		 * @see      corresponding SQL-Script examples/SQLValidationCase.sql
		 * @see      doc: gen/sqlvalidation.pdf
		 */
		public function testCheckRowLength() {			
			$sqlArr   = array();
			$sqlArr[] = array('firstname', constant('__SQL_CHAR__')   ,  240, -1,  1, -1, true);
			$sqlArr[] = array('surname'  , constant('__SQL_VARCHAR__'), 4000, -1, -1, -1, true);
			$sqlArr[] = array('age'      , constant('__SQL_INT__')    ,  255, -1,  1, -1, true);
			$sqlArr[] = array('graduated', constant('__SQL_YEAR__')   ,   -1, -1,  1, -1, true);
			
			$this->assertEquals(checkRowLength($sqlArr), 12729);
			
			$sqlArr   = array();
			$sqlArr[] = array('firstname', constant('__SQL_CHAR__')   ,  255, -1,  1, -1, true);
			$sqlArr[] = array('surname'  , constant('__SQL_VARCHAR__'), 2000, -1,  1, -1, true);
			$sqlArr[] = array('age'      , constant('__SQL_INT__')    ,  255, -1,  1, -1, true);
			$sqlArr[] = array('graduated', constant('__SQL_YEAR__')   ,   -1, -1,  1, -1, true);
			$sqlArr[] = array('socialnum', constant('__SQL_BIGINT__') ,   50, -1,  1, -1, true);
			$sqlArr[] = array('status'   , constant('__SQL_CHAR__')   ,   40, -1,  1, -1, true);
			$sqlArr[] = array('descript' , constant('__SQL_TEXT__')   ,   -1, -1,  1, -1, true);
			$sqlArr[] = array('created'  , constant('__SQL_DATE__')   ,   -1, -1,  1, -1, true);
			$sqlArr[] = array('sex'      , constant('__SQL_TINYINT__'),   30, -1,  1, -1, true);
			
			$this->assertEquals(checkRowLength($sqlArr), 6909);
			
			$sqlArr   = array();
			$sqlArr[] = array('firstname', constant('__SQL_CHAR__')   ,   241, -1,  1, -1, true);
			$sqlArr[] = array('surname'  , constant('__SQL_VARCHAR__'), 20000, -1,  1, -1, true);
			$sqlArr[] = array('descript' , constant('__SQL_VARCHAR__'),  1600, -1,  1, -1, true);
			$sqlArr[] = array('age'      , constant('__SQL_INT__')    ,   255, -1,  1, -1, true);
			$sqlArr[] = array('graduated', constant('__SQL_DATE__')   ,    -1, -1,  1, -1, true);
			
			$this->assertEquals(checkRowLength($sqlArr), constant('__SQL_ROW_LENGTH__'));
		}
		/**
		 * @category test function checkIndexSize
		 */
		public function testCheckIndexSize() {
			$sqlArr   = array();
			$sqlArr[] = array('name', constant('__SQL_VARCHAR__'), 768, -1, -1, -1, true);
			
			$this->assertEquals(false, checkIndexSize($sqlArr[0]));
			
			$sqlArr   = array();
			$sqlArr[] = array('name', constant('__SQL_VARCHAR__'), 767, -1, -1, -1, true);
			
			$this->assertEquals(true, checkIndexSize($sqlArr[0]));
		}
		/**
		 * @category test function getDefaultValue
		 */
		public function testGetDefaultValue() {		
			$this->assertEquals(2147483647, getDefaultValue(2147483647, constant('__SQL_INT__')));
			$this->assertEquals(false, getDefaultValue(2147483648, constant('__SQL_INT__')));
			$this->assertEquals(127, getDefaultValue(127, constant('__SQL_TINYINT__')));
			$this->assertEquals(false, getDefaultValue(-129, constant('__SQL_TINYINT__')));
			$this->assertEquals(-32768, getDefaultValue(-32768, constant('__SQL_SMALLINT__')));
			$this->assertEquals(false, getDefaultValue(32768, constant('__SQL_SMALLINT__')));
			$this->assertEquals(8388607, getDefaultValue(8388607, constant('__SQL_MEDIUMINT__')));
			$this->assertEquals(false, getDefaultValue(8388608, constant('__SQL_MEDIUMINT__')));
			$this->assertEquals(9223372036854775807, getDefaultValue(9223372036854775807, constant('__SQL_BIGINT__')));
			$this->assertEquals(99, getDefaultValue(99, constant('__SQL_YEAR__')));
			$this->assertEquals(false, getDefaultValue(993, constant('__SQL_YEAR__')));
			$this->assertEquals(1993, getDefaultValue(1993, constant('__SQL_YEAR__')));
			$this->assertEquals(false, getDefaultValue(2156, constant('__SQL_YEAR__')));
			$this->assertEquals(false, getDefaultValue(1900, constant('__SQL_YEAR__')));
			
			$randStr = \utils\String::getRandomString(pow(2, 16) - 1);
			$this->assertEquals(false, getDefaultValue($randStr, constant('__SQL_TEXT__')));
			$randStr = \utils\String::getRandomString(pow(2, 16) - 2);
			$this->assertEquals("'".$randStr."'", getDefaultValue($randStr, constant('__SQL_TEXT__')));
			$randStr = \utils\String::getRandomString(pow(2, 8));
			$this->assertEquals(false, getDefaultValue($randStr, constant('__SQL_TINYTEXT__')));
			$randStr = \utils\String::getRandomString(pow(2, 8) - 1);
			$this->assertEquals("'".$randStr."'", getDefaultValue($randStr, constant('__SQL_TINYTEXT__')));
			$this->assertEquals(false, getDefaultValue('Michael', constant('__SQL_CHAR__'), 6));
			$this->assertEquals("'Michael'", getDefaultValue('Michael', constant('__SQL_CHAR__'), 7));
			$randStr = \utils\String::getRandomString(maxSizePerTypeMySQL(constant('__SQL_CHAR__')));
			$this->assertEquals("'".$randStr."'", getDefaultValue($randStr, constant('__SQL_CHAR__')));
			$this->assertEquals(false, getDefaultValue('Michael', constant('__SQL_VARCHAR__'), 6));
			$this->assertEquals("'Michael'", getDefaultValue('Michael', constant('__SQL_VARCHAR__'), 7));
			$randStr = \utils\String::getRandomString(maxSizePerTypeMySQL(constant('__SQL_VARCHAR__') - 1));
			$this->assertEquals("'".$randStr."'", getDefaultValue($randStr, constant('__SQL_VARCHAR__')));
			$randStr = \utils\String::getRandomString(maxSizePerTypeMySQL(constant('__SQL_VARCHAR__')));
			$this->assertEquals(false, getDefaultValue($randStr, constant('__SQL_VARCHAR__')));
			$randStr = \utils\String::getRandomString(pow(2, 24) - 2);
			$this->assertEquals(false, getDefaultValue($randStr, constant('__SQL_MEDIUMTEXT__')));
			$randStr = substr($randStr, 0, strlen($randStr) - 2);
			$this->assertEquals("'".$randStr."'", getDefaultValue($randStr, constant('__SQL_MEDIUMTEXT__')));
			
			$this->assertEquals(10.5, getDefaultValue(10.5, constant('__SQL_FLOAT__')));
			$this->assertEquals(false, getDefaultValue(10.5, constant('__SQL_FLOAT__'), array(2, 1)));
			$this->assertEquals(false, getDefaultValue(100.555, constant('__SQL_DOUBLE__'), array(6, 2)));
			$this->assertEquals(100.555, getDefaultValue(100.555, constant('__SQL_DECIMAL__'), array(6, 3)));
			
			$this->assertEquals("'2017-01-01'", getDefaultValue('2017-01-01', constant('__SQL_DATE__')));
			$this->assertEquals("'2017-1-01'", getDefaultValue('2017-1-01', constant('__SQL_DATE__')));
			$this->assertEquals("'2017-01-1'", getDefaultValue('2017-01-1', constant('__SQL_DATE__')));
			$this->assertEquals("'1000-01-01'", getDefaultValue('1000-01-01', constant('__SQL_DATE__')));
			$this->assertEquals("'9999-12-31'", getDefaultValue('9999-12-31', constant('__SQL_DATE__')));
			$this->assertEquals(false, getDefaultValue('2017-0101', constant('__SQL_DATE__')));
			$this->assertEquals(false, getDefaultValue('2017-02-30', constant('__SQL_DATE__')));
			$this->assertEquals(false, getDefaultValue('999-01-01', constant('__SQL_DATE__')));
			$this->assertEquals(false, getDefaultValue('10000-02-01', constant('__SQL_DATE__')));
			
			$this->assertEquals("'08:00:00'", getDefaultValue('08:00:00', constant('__SQL_TIME__')));
			$this->assertEquals("'08:00:0'", getDefaultValue('08:00:0', constant('__SQL_TIME__')));
			$this->assertEquals("'08:0:00'", getDefaultValue('08:0:00', constant('__SQL_TIME__')));
			$this->assertEquals("'8:00:00'", getDefaultValue('8:00:00', constant('__SQL_TIME__')));
			$this->assertEquals("'0800:00'", getDefaultValue('0800:00', constant('__SQL_TIME__')));
			$this->assertEquals(false, getDefaultValue("839:00:00", constant('__SQL_TIME__')));
			$this->assertEquals(false, getDefaultValue("-839:00:00", constant('__SQL_TIME__')));
			$this->assertEquals(false, getDefaultValue("08:60:00", constant('__SQL_TIME__')));
			$this->assertEquals(false, getDefaultValue("08:59:-1", constant('__SQL_TIME__')));
			
			$this->assertEquals("'2017-01-01 08:00:00'", getDefaultValue('2017-01-01 08:00:00', constant('__SQL_DATETIME__')));
			$this->assertEquals("'2017-01-01 8:00:00'", getDefaultValue('2017-01-01 8:00:00', constant('__SQL_DATETIME__')));
			$this->assertEquals("'2017-01-1 08:00:00'", getDefaultValue('2017-01-1 08:00:00', constant('__SQL_DATETIME__')));
			$this->assertEquals(false, getDefaultValue('2017-01-01 0800:00', constant('__SQL_DATETIME__')));
			$this->assertEquals(false, getDefaultValue('201701-01 08:00:00', constant('__SQL_DATETIME__')));
			$this->assertEquals(false, getDefaultValue('2017-01-0108:00:00', constant('__SQL_DATETIME__')));
			$this->assertEquals(false, getDefaultValue('2017-01-01 24:00:00', constant('__SQL_DATETIME__')));
			$this->assertEquals(false, getDefaultValue('2017-01-01 08:60:00', constant('__SQL_DATETIME__')));
			$this->assertEquals(false, getDefaultValue('2017-01-01 08:00:60', constant('__SQL_DATETIME__')));
		}
		/**
		 * @category test function needQuotes
		 */
		public function testNeedQuotes() {
			$this->assertEquals(true,  needQuotes(constant("__SQL_TEXT__")));
			$this->assertEquals(true,  needQuotes(constant("__SQL_DATE__")));
			$this->assertEquals(false, needQuotes(constant('__SQL_INT__')));
			$this->assertEquals(false, needQuotes(constant('__SQL_FLOAT__')));
		}
		/**
		 * @category test function checkSQLiteValue
		 */
		public function testCheckSQLiteValue() {
			$this->assertEquals(true, checkSQLiteValue(constant('__SQL_INT__'), 10));
			$this->assertEquals(false, checkSQLiteValue(constant('__SQL_DATE__'), '2017-01-01'));
		}
	}