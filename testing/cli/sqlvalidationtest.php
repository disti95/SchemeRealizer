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
	 * @category SQL-Validator Test
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../utils/Arrays.php"
			    ,"../../gen/sqlvalidation.php"
			    ,"../../error/error.php"
			    ,"../../constants/constants.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");

	/*
	 * Get some data into the test-Array
	 * Build-Up = array(name, datatype, size, index, null, autoincrement, selected, default)
	 * index: 1 = PRIMARY, 2 = UNIQUE, 3 = INDEX, -1 = false
	 * null : 1 = true,   -1 = false
	 * autoincrement: 1 = true, -1 = false
	 */
	echo "MySQL-Test:\n\n";
	//Error -> Empty-Value
	$testArray = array();
	$testArray[] = array("id", "int", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "", 255, -1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Invalid data type
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "int", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "varchar", 21845, -1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> False Regex
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "int", 11, 1, -1, 1, true, false);
	$testArray[] = array("0name", "varchar", 255, -1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Min. Size
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "int", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "varchar", -1, -1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Max. Size
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "int", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "char", 2, -1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> NULL Value
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "int", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "char", 1, -1, 2, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> AI Value
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "int", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "char", 1, -1, -1, 2, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Index Value
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "int", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "char", 1, 2, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, 3, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, 4, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> AI = No Key
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "int", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "char", 1, 2, -1, 1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> AI = No Number
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "char", 1, 1, -1, 1, true, false);
	$testArray[] = array("name", "char", 1, -1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Duplication
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "int", 11, 1, -1, 1, true, false);
	$testArray[] = array("id", "char", 1, -1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Key on no length
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "int", 11, 1, -1, 1, true, false);
	$testArray[] = array("id", "char", 1, -1, -1, -1, true, false);
	$testArray[] = array("plz", "text", -1, 1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> max m length of decimal
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "int", 11, 1, -1, 1, true, false);
	$testArray[] = array("id", "char", 1, -1, -1, -1, true, false);
	$testArray[] = array("plz", "decimal", "66,30", 1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> max d length of decimal
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "int", 11, 1, -1, 1, true, false);
	$testArray[] = array("id", "char", 1, -1, -1, -1, true, false);
	$testArray[] = array("plz", "decimal", "65,31", -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Multiple AI
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "integer", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "char", 1, 1, -1, 1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Size is not numeric
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "integer", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "char", 1, 1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 'notnumeric', -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Size is not numeric
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "integer", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "char", 1, 1, -1, -1, true, false);
	$testArray[] = array("plz", "decimal", '60,', -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Size is not numeric
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "integer", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "char", 1, 1, -1, -1, true, false);
	$testArray[] = array("plz", "decimal", ',30', -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Index size 
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "integer", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "varchar", 768, 1, -1, 1, true, false);
	$testArray[] = array("plz", "decimal", ',30', -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateMySQL($testArray);
	if($result !== true)
		echo $result."\n";
	
	echo "SQLite-Test:\n\n";
	//Error -> Emptiness
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "int", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "", 1, -1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateSQLite($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Invalid DataType
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "integer", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "time", 1, -1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateSQLite($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Regex Name
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "integer", 11, 1, -1, 1, true, false);
	$testArray[] = array("0name", "char", 1, -1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateSQLite($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Invalid NULL-Value
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "integer", 11, 1, -2, 1, true, false);
	$testArray[] = array("name", "char", 1, -1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateSQLite($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Invalid AI-Value
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "integer", 11, 1, -1, -2, true, false);
	$testArray[] = array("name", "char", 1, -1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateSQLite($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Invalid Index-Value
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "integer", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "char", 1, 3, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateSQLite($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Multiple AI
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "integer", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "char", 1, 1, -1, 1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateSQLite($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> AI no PK
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "integer", 11, 2, -1, 1, true, false);
	$testArray[] = array("name", "char", 1, 1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateSQLite($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> AI no INT
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "char", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "char", 1, 1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateSQLite($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> AI Multiple PK
	unset($testArray);
	$testArray = array();
	$testArray[] = array("id", "integer", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "char", 1, 1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateSQLite($testArray);
	if($result !== true)
		echo $result."\n";
	
	//Error -> Element Duplication
	unset($testArray);
	$testArray = array();
	$testArray[] = array("name", "integer", 11, 1, -1, 1, true, false);
	$testArray[] = array("name", "char", 1, -1, -1, -1, true, false);
	$testArray[] = array("plz", "int", 4, -1, -1, -1, true, false);
	$testArray[] = array("kunde", "tinyint", 1, -1, -1, -1, true, false);
	//validate the Array
	$result = validateSQLite($testArray);
	if($result !== true)
		echo $result."\n";
?>
