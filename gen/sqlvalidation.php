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
	/*
	 * Build-Up = array(name, datatype, size, index, null, autoincrement, selected, default)
	 * index: 1 = PRIMARY, 2 = UNIQUE, 3 = INDEX, -1 = false
	 * null:  1 = true,   -1 = false
	 * autoincrement: 1 = true, -1 = false
	 */
	/**
	 * @author   Michael Watzer
	 * @version  1.0
	 * @since    ?
	 * @category Validator
	 */
/**
 * @category Getter
 * @param    $dbms
 * @return   array
 */
function getCollectionParams($dbms) {
	//Fill the Array
	if($dbms == constant('__MONGODB__')) {
		$arr = array(constant('__MONGODB_PARAM_CAPPED__')
				,constant('__MONGODB_PARAM_MAX__'));
	}
	return $arr;
}
	/**
	 * @category Getter
	 * @param    $dbms
	 * @return   array
	 */
	function getValidDatatypes($dbms) {
		//Fill the Array
		if($dbms == constant('__MYSQL__')){
			$arr = array(constant('__SQL_INTEGER__')
					    ,constant('__SQL_INT__')
					    ,constant('__SQL_TINYINT__')
					    ,constant('__SQL_SMALLINT__')
					    ,constant('__SQL_MEDIUMINT__')
					    ,constant('__SQL_BIGINT__')
					    ,constant('__SQL_FLOAT__')
					    ,constant('__SQL_DOUBLE__')
					    ,constant('__SQL_DECIMAL__') 
				        ,constant('__SQL_DATE__')
					    ,constant('__SQL_DATETIME__')
					    ,constant('__SQL_TIMESTAMP__')
					    ,constant('__SQL_TIME__')
					    ,constant('__SQL_YEAR__')
					    ,constant('__SQL_CHAR__')
					    ,constant('__SQL_VARCHAR__')
					    ,constant('__SQL_TEXT__')
					    ,constant('__SQL_TINYTEXT__') 
				        ,constant('__SQL_MEDIUMTEXT__')
					    ,constant('__SQL_LONGTEXT__'));
		}
		elseif($dbms == constant('__SQLITE__')) {
			$arr = array(constant('__SQL_INTEGER__')
					    ,constant('__SQL_INT__')
					    ,constant('__SQL_TINYINT__')
					    ,constant('__SQL_SMALLINT__')
					    ,constant('__SQL_MEDIUMINT__')
					    ,constant('__SQL_BIGINT__')
					    ,constant('__SQL_FLOAT__')
					    ,constant('__SQL_DOUBLE__')
					    ,constant('__SQL_REAL__')
				        ,constant('__SQL_DATE__')
					    ,constant('__SQL_DATETIME__')
					    ,constant('__SQL_DECIMAL__')
					    ,constant('__SQL_CHAR__')
					    ,constant('__SQL_VARCHAR__')
					    ,constant('__SQL_TEXT__'));
		}
		elseif($dbms == constant('__MONGODB__')) {
			$arr = array(constant('__MONGODB_STRING__')
						,constant('__MONGODB_INTEGER__'));
		}
		return $arr;
	}
	/**
	 * @category Error-Checker
	 * @param    $dbms
	 * @return   boolean
	 */
	function checkDBMS($dbms) {
		switch($dbms){
			case constant('__MYSQL__'): 
				return true;
			break;
			case constant('__SQLITE__'): 
				return true;
			break;
			case constant('__MONGODB__'):
				return true;
			break;
			default: 
				return false;
			break;
		}
	}
	/*
	 * Note   = The actual data size can be constant, we talk about 
	 *          the data size that is given in a DDL statement
	 * Number = Max. Size for a particular data type
	 * Array  = (m, d) -> float, double, decimal
	 * false  = No limit for this data type
	 * Requirements: http://dev.mysql.com/doc/refman/5.7/en/storage-requirements.html
	 * Tested with:  http://sqlfiddle.com/#!9/
	 */
	/**
	 * @category Getter & Error-Checker
	 * @param    $type
	 */
	function maxSizePerTypeMySQL($type) {
		switch($type) {
			case constant('__SQL_INT__'):
			case constant('__SQL_INTEGER__'):
			case constant('__SQL_TINYINT__'):
			case constant('__SQL_SMALLINT__'):
			case constant('__SQL_MEDIUMINT__'):
			case constant('__SQL_BIGINT__'):
			case constant('__SQL_CHAR__'):
				return 255;
			break;
			//Float Notation -> M, D
			case constant('__SQL_FLOAT__'):
			case constant('__SQL_DOUBLE__'):
				return array(255, 30);
			break;
			case constant('__SQL_DECIMAL__'):
				return array(65, 30);
			break;
			case constant('__SQL_VARCHAR__'):
				return 21844;
			break;
			default: 
				return false;
			break;
		}
	}
	/**
	 * @category Error-Checker
	 * @param    $key
	 * @return   string|boolean
	 */
	function checkIndexMySQL($key) {
		switch($key) {
			case 1:
				return "PRIMARY KEY";
			break;
			case 2: 
				return "UNIQUE";
			break;
			case 3: 
				return "KEY";
			break;
			case -1: 
				return true;
			break;
			default: 
				return false;
			break;
		}
	}
 	/**
 	 * @category Error-Checker
 	 * @param    $key
 	 * @return   string|boolean
 	 */
	function checkIndexSQLite($key) {
		switch($key) {
			case 1: 
				return "PRIMARY KEY";
			break;
			case 2: 
				return "UNIQUE";
			break;
			case -1: 
				return true;
			break;
			default: 
				return false;
			break;
		}
	}
	/**
	 * @category Error-Checker
	 * @param    $type
	 * @return   boolean
	 */
	function checkAIMySQL($type) {
		if(!in_array($type, array(constant('__SQL_INTEGER__') 
				                 ,constant('__SQL_INT__')
				                 ,constant('__SQL_TINYINT__')
				                 ,constant('__SQL_SMALLINT__')
				                 ,constant('__SQL_MEDIUMINT__')
				                 ,constant('__SQL_BIGINT__')
				                 ,constant('__SQL_FLOAT__')
				                 ,constant('__SQL_DOUBLE__'))))
			return false;
		return true;
	}
	/**
	 * @category Error-Checker
	 * @param    $type
	 * @return   boolean
	 */
	function checkAISQLite($type) {
		if($type != constant('__SQL_INTEGER__'))
			return false;
		return true;
	}
	/**
	 * @category Error-Checker
	 * @param    $name
	 * @return   boolean
	 */
	function regexName($name) {
		if(!preg_match("/^[a-zA-Z0-9_][a-zA-Z0-9_]*$/", $name)) 
			return false;
		return true;
	}
	/**
	 * @category Error-Checker
	 * @param    $name
	 * @return   boolean
	 */
	function checkNullAIValue($val) {
		if($val != 1 && $val != -1)
			return false;
		return true;
	}
	/**
	 * @category Error-Checker and Getter
	 * @param    $name
	 * @return   boolean
	 */
	function checkDuplication(array $nameValues) {
		//Check if we find a duplication
		$duplicates = array_count_values($nameValues);
		foreach($duplicates as $key => $elem) {
			if($elem > 1) 
				return $key;
		}
		return true;
	}
	/**
	 * @category Error-Checker
	 * @param    $name
	 * @return   boolean
	 */
	function checkAIKeyMySQL($key) {
		if($key == 1 || $key == 2 || $key == 3) 
			return true;
		return false;
	}
	/**
	 * @category Error-Checker
	 * @param    $name
	 * @return   boolean
	 */
	function checkAIKeySQLite($key) {
		if($key == 1)
			return true;
		return false;
	}
	/**
	 * @category Error-Checker
	 * @param    $name
	 * @return   boolean
	 */
	function checkEmptiness($arr) {
		$empArr = new utils\Arrays();
		$empArr->setEmptiness($arr);
		if($empArr->emptiness)
			return false;
		return true;
	}
	/**
	 * @category Check if the column has a variable-length
	 * @param    data type
	 * @return   boolean
	 */
	function isDataTypeVariable($type) {
		switch($type) {
			case constant('__SQL_CHAR__'):
			case constant('__SQL_VARCHAR__'):
			case constant('__SQL_TEXT__'):
			case constant('__SQL_TINYTEXT__'):
			case constant('__SQL_MEDIUMTEXT__'):
			case constant('__SQL_LONGTEXT__'):
				return true;
			break;
			default:
				return false;
			break;
		}
	}
	/*
	 * Actual data type storage requirements:
	 * https://dev.mysql.com/doc/refman/5.7/en/storage-requirements.html
	 */
	/**
	 * @category Get actual data type size
	 * @param    data type
	 * @return   boolean or size
	 */
	function getDataTypeSize($type) {
		switch($type) {
			case constant('__SQL_TINYINT__'):
			case constant('__SQL_YEAR__'):
				return 1;
			break;
			case constant('__SQL_SMALLINT__'):
				return 2;
			break;
			case constant('__SQL_MEDIUMINT__'):
			case constant('__SQL_DATE__'):
				return 3;
			break;
			case constant('__SQL_INT__'):
			case constant('__SQL_INTEGER__'):
			case constant('__SQL_FLOAT__'):
				return 4;
			break;
			case constant('__SQL_TIME__'):
				return 6;
			case constant('__SQL_TIMESTAMP__'):
				return 7;
			case constant('__SQL_BIGINT__'):
			case constant('__SQL_DOUBLE__'):
				return 8;
			break;
			case constant('__SQL_DATETIME__'):
				return 11;
			case constant('__SQL_DECIMAL__'):
				return 29;
			break;
			default:
				return false;
			break;
		}
	}
	/*
	 * Calculation of actual row length:
	 * 1 + (sum of column length)
	 *   + (number of NULL tagged columns + 7) / 8
	 *   + (number of variable-length columns e.g varchar)
	 */
	/**
	 * @category Calculate the actual row length
	 * @param    array $arr
	 * @return   row length
	 */
	function checkRowLength(array $arr) {
		$length   = 1;
		$variable = $null = 0;
		foreach($arr as $elem) {
			if(count($elem) >= 7) {
				if(isDataTypeVariable($elem[1])) {
					$variable++;
					if($elem[1] == constant('__SQL_CHAR__') || $elem[1] == constant('__SQL_VARCHAR__'))
						$length += $elem[2] * 3; //utf8 uses three byte per character
				}
				else
					$length += getDataTypeSize($elem[1]);
				if($elem[4] == 1)
					$null++;
			}
		}
		return $length + (floor(($null + 7) / 8)) + $variable;
	}
	/**
	 * @category Check the max. index size
	 * @param    array $arr
	 * @return   boolean, return false on error
	 */
	function checkIndexSize(array $arr) {
		if(count($arr) >= 7) {
			if($arr[1] == constant('__SQL_CHAR__') || $arr[1] == constant('__SQL_VARCHAR__')) {
				$maxIndexSize = constant('__SQL_INDEX_LENGTH__');
				if($arr[2] > $maxIndexSize)
					return false;
			}
		}
		return true;
	}
	/**
	 * @category Check if the datetype's default value needs quotes
	 * @param    string
	 * @return   boolean
	 */
	function needQuotes($datatype) {
		switch($datatype) {
			case constant("__SQL_DATE__"):
			case constant("__SQL_DATETIME__"):
			case constant("__SQL_TIME__"):
			case constant("__SQL_CHAR__"):
			case constant("__SQL_VARCHAR__"):
			case constant("__SQL_TEXT__"):
			case constant("__SQL_TINYTEXT__"):
			case constant("__SQL_MEDIUMTEXT__"):
			case constant("__SQL_LONGTEXT__"):
				return true;
			break;
			default:
				return false;
			break;
		}
	}
	/**
	 * @category Check if the time format is correct
	 * @param    $val
	 * @return   boolean
	 * @see      https://dev.mysql.com/doc/refman/5.7/en/time.html
	 */
	function checkSQLTime($val) {
		$parts = explode(':', $val);
		if(!count($parts))
			return false;
		for($i = 0; $i < count($parts); $i++) {
			if(!is_numeric($parts[$i]))
				return false;
			$part = intval($parts[$i]);
			switch($i) {
				case 0:
					if($part > 838 || $part < -838)
						return false;
				break;
				case 1:
				case 2:
					if($part < 0 || $part > 59)
						return false;
				break;
				default:
					return false;
				break;
			}
		}
		return true;
	}
	/**
	 * @category Check if date format is correct
	 * @param    $val
	 * @return   boolean
	 * @see      https://dev.mysql.com/doc/refman/5.7/en/datetime.html
	 */
	function checkSQLDate($val) {
		$parts = explode('-', $val);
		if(count($parts) != 3)
			return false;
		for($i = 0; $i < count($parts); $i++)
			if(!is_numeric($parts[$i]))
				return false;
		/**
		 * @see http://php.net/manual/de/function.checkdate.php
		 */
		if(!checkdate($parts[1], $parts[2], $parts[0]))
			return false;
		if($parts[0] < 1000 || $parts[0] > 9999)
			return false;
		return true;
	}
	/**
	 * @category Check if the datetime format is correct
	 * @param    $val
	 * @return   boolean 
	 * @see      https://dev.mysql.com/doc/refman/5.7/en/datetime.html
	 */
	function checkSQLDateTime($val) {
		$parts = preg_split('/[\s,]+/', $val);
		if(!checkSQLDate($parts[0]))
			return false;
		if(isset($parts[1])) {
			$time_parts = explode(':', $parts[1]);
			for($i = 0; $i < count($time_parts); $i++) {
				if(!is_numeric($time_parts[$i]))
					return false;
				$time_part = intval($time_parts[$i]);
				switch($i) {
					case 0:
						if($time_part > 23 || $time_part < 0)
							return false;
					break;
					case 1:
					case 2:
						if($time_part < 0 || $time_part > 59)
							return false;
					break;
					default:
						return false;
					break;
				}
			}	
		}
		return true;
	}
	/**
	 * @category Check integer value
	 * @param    $val
	 * @param    $datatype
	 * @return   boolean
	 * @see      https://dev.mysql.com/doc/refman/5.7/en/integer-types.html
	 * @see      https://dev.mysql.com/doc/refman/5.7/en/year.html
	 */
	function checkSQLInteger($val, $datatype) {
		if(!is_numeric($val))
			return false;
		switch($datatype) {
			case constant("__SQL_INT__"):
			case constant("__SQL_INTEGER__"):
				if($val < -2147483648 || $val > 2147483647)
					return false;
			break;
			case constant("__SQL_TINYINT__"):
				if($val < -128 || $val > 127)
					return false;
			break;
			case constant("__SQL_SMALLINT__"):
				if($val < -32768 || $val > 32767)
					return false;
			break;
			case constant("__SQL_MEDIUMINT__"):
				if($val < -8388608 || $val > 8388607)
					return false;
			break;
			case constant("__SQL_BIGINT__"):
				if($val < -9223372036854775808 || $val > 9223372036854775807)
					return false;
			break;
			case constant("__SQL_YEAR__"): 
				$length = ceil(log10(abs($val) + 1));
				if($length == 1 || $length == 2) {
					if($val < 0 || $val > 99)
						return false;
				}
				elseif($length == 4) {
					if($val < 1901 || $val > 2155)
						return false;
				}
				else
					return false;
			break;
			default:
				return false;
			break;
		}
		return true;
	}
	/**
	 * @category Check character value
	 * @param    $val
	 * @param 	 $datatype
	 * @param    $size, optional default is false
	 * @return   boolean
	 * @see      https://dev.mysql.com/doc/refman/5.7/en/storage-requirements.html
	 */
	function checkSQLCharacter($val, $datatype, $size = false) {
		switch($datatype) {
			case constant("__SQL_VARCHAR__"):
				if(($size && strlen($val) > $size) || strlen($val) > maxSizePerTypeMySQL($datatype) - 1)
					return false;
			break;
			case constant('__SQL_CHAR__'):
				if(($size && strlen($val) > $size) || strlen($val) > maxSizePerTypeMySQL($datatype))
					return false;
			break;
			case constant("__SQL_TEXT__"):
				if(strlen($val) > pow(2, 16) - 2)
					return false;
			break;
			case constant("__SQL_TINYTEXT__"):
				if(strlen($val) > pow(2, 8) - 1)
					return false;
			break;
			case constant("__SQL_MEDIUMTEXT__"):
				if(strlen($val) > pow(2, 24) - 3)
					return false;
			break;
			case constant("__SQL_LONGTEXT__"):
				if(strlen($val) > pow(2, 32) - 4)
					return false;
			break;
			default:
				return false;
			break;
		}
		return true;
	}
	/**
	 * @category Check floating value
	 * @param    $val
	 * @param    $datatype
	 * @param    $size, optional default is false
	 * @return   boolean
	 */
	function checkSQLFloating($val, $datatype, $size = false) {
		if(!is_array($maxSize = maxSizePerTypeMySQL($datatype)))
			return false;
		if(!$size)
			$size = $maxSize;
		$val_parts = explode('.', $val);
		if(count($val_parts) != 2)
			return false;
		if(!isset($size[0]) || !isset($size[1]))
			return false;
		if(!is_numeric($val_parts[0]) || !is_numeric($val_parts[1])
		|| !is_numeric($size[0])      || !is_numeric($size[1]))
			return false;
		$length          = ceil(log10(abs($val_parts[0]) + 1));
		$lengthprecision = ceil(log10(abs($val_parts[1]) + 1));
		if(($length + $lengthprecision) > $size[0] || $lengthprecision > $size[1])
			return false;
		return true;
	}
	/*
	 * Explanation why we use a native validation:
	 *  	Most SQL database engines (every SQL database engine other than SQLite, as far as we know) uses static, rigid typing. 
	 *	  	With static typing, the datatype of a value is determined by its container - 
	 * 	 	the particular column in which the value is stored.
     *  	SQLite uses a more general dynamic type system. 
     * 	    In SQLite, the datatype of a value is associated with the value itself, 
     *  	not with its container. The dynamic type system of SQLite is backwards compatible with 
     *  	the more common static type systems of other database engines in the sense that 
     *  	SQL statements that work on statically typed databases should work the same way in SQLite. 
     *  	However, the dynamic typing in SQLite allows it to do things which are not possible in traditional rigidly typed databases.
	 */
	/**
	 * @category Check SQLite default value
	 * @param    $datatype
	 * @param    $val
	 * @return   boolean
	 * @see      https://sqlite.org/datatype3.html
	 */
	function checkSQLiteValue($datatype, $val) {
		try {
			$file    = \utils\File::tmpFile();
			$sql     = new engine\sql(constant('__SQLITE__'));
			$sql->getConnection($file);
			$sqlCode = <<<DB
			create table validate (
			  col $datatype default $val
			);
DB;
			$sql->prepareAndQuery($sqlCode, array());
			$sql->closeConnection();
			unlink($file);
			return true;
		}
		catch(\engine\DatabaseException $e) {
 			unlink($file);
			return false;
		}
	}
	/**
	 * @category Create value depending on the data type
	 * @param    $val
	 * @param    $datatype
	 * @param    $size, optional default is false
	 * @return   default value
	 */
	function getDefaultValue($val, $datatype, $size = false) {
		switch($datatype) {
			case constant("__SQL_INT__"):
			case constant("__SQL_INTEGER__"):
			case constant("__SQL_TINYINT__"):
			case constant("__SQL_SMALLINT__"):
			case constant("__SQL_MEDIUMINT__"):
			case constant("__SQL_BIGINT__"):
			case constant("__SQL_YEAR__"):
				if(!checkSQLInteger($val, $datatype))
					return false;
			break;
			case constant("__SQL_FLOAT__"):
			case constant("__SQL_DOUBLE__"):
			case constant("__SQL_DECIMAL__"):
				if(is_string($size)) 
					$size = explode(',', $size);
				if(!checkSQLFloating($val, $datatype, $size))
					return false;
			break;
			case constant("__SQL_DATE__"):
				if(!checkSQLDate($val))
					return false;
			break;
			case constant("__SQL_DATETIME__"):
				if(!checkSQLDateTime($val))
					return false;
			break;
			case constant("__SQL_TIME__"):
				if(!checkSQLTime($val))
					return false;
			break;
			case constant("__SQL_CHAR__"):
			case constant("__SQL_VARCHAR__"):
			case constant("__SQL_TEXT__"):
			case constant("__SQL_TINYTEXT__"):
			case constant("__SQL_MEDIUMTEXT__"):
			case constant("__SQL_LONGTEXT__"):
				if(!checkSQLCharacter($val, $datatype, $size))
					return false;
			break;
			default:
				return false;
			break;
		}
		return (needQuotes($datatype))
			     ? utils\String::addQuotes($val)
				 : $val;
	}
	/**
	 * @category Validator for MySQL/MariaDB DBMS
	 * @param    array $arr
	 */
	function validateMySQL(array $arr) {
		//Create Array for the Duplication-Check
		$nameValues = array();
		$ai         = false;
		foreach($arr as $elem) {
			//Check for Emptiness, exclude selected
			if(!checkEmptiness(array_slice($elem, 0, 6)))
				return "sqlvalidation: Array has empty elements!";
			//Check for forbidden datatype
			if(!in_array($elem[1], getValidDatatypes(constant('__MYSQL__')))) 
				return "sqlvalidation: ".$elem[1]." is a forbidden data type!";
			//Check name regex
			if(!regexName($elem[0]))
				return "sqlvalidation: ".$elem[0]." does not fit the regex!";
			//Check for max. Size
			$maxSize = maxSizePerTypeMySQL($elem[1]);
			if($maxSize != false){
				if(!is_numeric($elem[2]) && !preg_match('/^[0-9]+\,[0-9]+$/', $elem[2]))
					return "sqlvalidation: ".$elem[0].": Size is not numeric!";
				//Check for min. Size
				if($elem[2] <= 0)
					return "sqlvalidation: ".$elem[0].": Size is smaller than 1!";
				//Check if its a float
				if(in_array($elem[1], array(constant('__SQL_FLOAT__')
						                   ,constant('__SQL_DECIMAL__')
						                   ,constant('__SQL_DOUBLE__')))) {
					//Are we able to explode?
					if(!($pieces = explode(",", $elem[2])))
						return "sqlvalidation: Not able to explode floating type at ".$elem[0]."!";
					//Has pieces two elements?
					if(count($pieces) < 2) 
						return "sqlvalidation: m or d missing in floating type at ".$elem[0]."!";
					//Validate m 
					if($pieces[0] > $maxSize[0])
						return "sqlvalidation: ".$elem[0].": Overruns the max. size of ".$maxSize[0]." at m!";
					//Validate d
					if($pieces[1] > $maxSize[1])
						return "sqlvalidation: ".$elem[0].": Overruns the max. size of ".$maxSize[1]." at d!";
				}
				else {
					if($elem[2] > $maxSize)
						return "sqlvalidation: ".$elem[0].": Overruns the max. size of ".$maxSize."!";
				}
			}
			//Check the value of the NULL and AI
			if(!checkNullAIValue($elem[4]))
				return "sqlvalidation: ".$elem[0].": Invalid NULL value!";
			if(!checkNullAIValue($elem[5]))
				return "sqlvalidation: ".$elem[0].": Invalid AUTO_INCREMENT value!";
			//Check the Index
			if(!checkIndexMySQL($elem[3]))
				return "sqlvalidation: ".$elem[0].": Invalid Index value!";
			//Check if its a key and if we have a key length
			if($elem[3] != -1){
				if(!checkIndexSize($elem)) 
					return "sqlvalidation: ".$elem[0].": Overruns the max. index size of ".constant('__SQL_INDEX_LENGTH__')."!";
				if($maxSize === false)
					return "sqlvalidation: ".$elem[0].": is tagged as a key, but has no key length!";
			}
			//Check if the AI is allowed
			if($elem[5] == 1) {
				//AI = TRUE -> Duplication!
				if(!$ai) {
					//Check if AI is for a Key
					if(!checkAIKeyMySQL($elem[3]))
						return "sglvalidation: ".$elem[0].": AUTO_INCREMENT has to be a Key!";
					if(!checkAIMySQL($elem[1]))
						return "sglvalidation: ".$elem[0].": AUTO_INCREMENT tagged field isn't a number/float!";
					$ai = true;
				}
				else 
					return "sqlvalidation: Multiple AUTOINCREMENT-Tagged Columns!";
			}
			if($elem[7] != false && !getDefaultValue($elem[7], $elem[1], $elem[2]))
				return "sqlvalidation: ".$elem[0].": forbidden default value!";
			//Get the nameValues into the Array
			$nameValues[] = $elem[0];
		}
		$duplResult = checkDuplication($nameValues);
		//Check the Array for Duplication
		if($duplResult !== true) 
			return "sqlvalidation: ".$duplResult.": Element duplication!";
		//Check if the max. row-length is reached
		$maxRowLength = constant('__SQL_ROW_LENGTH__');
		if(($length = checkRowLength($arr)) > $maxRowLength)
			return "sqlvalidation: $length extends max. row-length of ".$maxRowLength."!";
		return true;
	}
	/**
	 * @category Validator for SQLite DBMS
	 * @param    array $arr
	 * @return   string|boolean
	 */
	function validateSQLite(array $arr) {
		//Create Array for Duplication-Check
		$nameValues = array();
		//PK-Duplication Counter
		$PKCounter = 0;
		$ai        = false;
		foreach($arr as $elem) {
			//Check for Emptiness, exclude selected
			if(!checkEmptiness(array_slice($elem, 0, 6)))
				return "sqlvalidation: Array has empty elements!";
			//Check for forbidden Datatypes
			if(!in_array($elem[1], getValidDatatypes(constant('__SQLITE__'))))
				return "sqlvalidation: ".$elem[1]." is a forbidden data type!";
			//Check if the Column-Name is valid
			if(!regexName($elem[0]))
				return "sqlvalidation: ".$elem[0]." does not fit the regex!";
			//Check the value of the NULL and AI
			if(!checkNullAIValue($elem[4]))
				return "sqlvalidation: ".$elem[0].": Invalid NULL value!";
			if(!checkNullAIValue($elem[5]))
				return "sqlvalidation: ".$elem[0].": Invalid AUTO_INCREMENT value!";
			//Check if the Index is valid
			if(!checkIndexSQLite($elem[3]))
				return "sqlvalidation: ".$elem[0].": Invalid Index value!";
			//Increment PKCounter
			if($elem[3] == 1)
				$PKCounter++;
			/*
			   Check for AI:
			   SQLite has just one field with AI(AUTOINCREMENT) and this has to be a PRIMARY KEY and INTEGER.
			   This means that if there is an AI-tagged Field that there is just one PK.
			 */
			if($elem[5] == 1) {
				if($ai)
					return "sqlvalidation: Multiple AUTOINCREMENT-Tagged Columns!";
				//Check if its a Primary-Key
				if(!checkAIKeySQLite($elem[3]))
					return "sqlvalidation: AUTOINCREMENT-Column ".$elem[0]." has to be tagged as Primary-Key!";
				//Check if its a INTEGER
				if(!checkAISQLite($elem[1]))
					return "sqlvalidation: AUTOINCREMENT-Column ".$elem[0]." has to be an INTEGER!";
				$ai = true;
			}
			//Check if AI isset and PKCounter bigger than 1
			if($PKCounter > 1 && $ai)
 				return "sqlvalidation: AUTOINCREMENT: Primary-Key duplication!";
			if($elem[7] != false && !checkSQLiteValue($elem[1], $elem[7]))
				return "sqlvalidation: ".$elem[0].": forbidden default value!";
			//Get the name into the Array
			$nameValues[] = $elem[0];
		}
		//Duplication check
		$duplResult = checkDuplication($nameValues);
		if($duplResult !== true) 
			return "sqlvalidation: ".$duplResult.": Element duplication!";
		return true; 
	}
?>
