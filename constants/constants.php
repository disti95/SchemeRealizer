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
	 * @since    28.08.2016
	 * @category Define constants 
	 */
	namespace constants;
	/*
	 * General const
	 */
	define("__STATIC__", "static");
	define("__ABSTRACT__", "abstract");
	define("__EXTENDS__", "extends");
	define("__IMPLEMENTS__", "implements");
	define("__FINAL__", "final");
	define("__CONST__", "const");
	define("__PUBLIC__", "public");
	define("__PRIVATE__", "private");
	define("__PROTECTED__", "protected");
	define("__UML_PRIVATE__", "-");
	define("__UML_PUBLIC__", "+");
	define("__UML_PROTECTED__", "#");
	define("__VERSION__", 1.1);
	define("__RELEASE__", 1);
	define("__CLASS__", "class");
	define("__INTERFACE__", "interface");
	define("__INCLUDE__", "include");
	define("__REQUIRE__", "require");
	define("__INCLUDE_ONCE__", "include_once");
	define("__FUNC_TMP__", "function tmp");
	define("__UML__", "uml");
	define("__NOERR__", 0);
	define("__ERR__", 1);
	define("__MONGODB_ARR_SIZE__", 5);
	define("__OBJECT__", "Object");
	define("__ARRAY__", "Array");
	define("__BOOLEAN__", "Boolean");
	define("__STRING__", "String");
	define("__INTEGER__", "Integer");
	define("__OBJECTID__", "ObjectID");
	define("__JSONDATE__", "JSONDate");
	define("__TIMESTAMP__", "Timestamp");
	define("__ISODATE__", "ISODate");
	define("__NUMBERLONG__", "NumberLong");
	define("__NUMBERINT__", "NumberInt");
	define("__NUMBERDECIMAL__", "NumberDecimal");
	define("__SQL_ROW_LENGTH__", 65535);
	define("__SQL_INDEX_LENGTH__", 767);
	define("__SQL_TINYINT__", "tinyint");
	define("__SQL_SMALLINT__", "smallint");
	define("__SQL_INT__", "int");
	define("__SQL_INTEGER__", "integer");
	define("__SQL_BIGINT__", "bigint");
	define("__SQL_MEDIUMINT__", "mediumint");
	define("__SQL_FLOAT__", "float");
	define("__SQL_DOUBLE__", "double");
	define("__SQL_DECIMAL__", "decimal");
	define("__SQL_DATE__", "date");
	define("__SQL_DATETIME__", "datetime");
	define("__SQL_TIME__", "time");
	define("__SQL_TIMESTAMP__", "timestamp");
	define("__SQL_YEAR__", "year");
	define("__SQL_VARCHAR__", "varchar");
	define("__SQL_TEXT__", "text");
	define("__SQL_TINYTEXT__", "tinytext");
	define("__SQL_MEDIUMTEXT__", "mediumtext");
	define("__SQL_CHAR__", "char");
	define("__SQL_LONGTEXT__", "longtext");
	define("__SQL_REAL__", "real");
	define("__MYSQL__", "mysql");
	define("__SQLITE__", "sqlite");
	define("__MONGODB__", "mongodb");
	define("__MONGODB_STRING__", "string");
	define("__MONGODB_INTEGER__", "integer");
	define("__MONGODB_PARAM_CAPPED__", "capped");
	define("__MONGODB_PARAM_MAX__", "max");
	define("__SR__", "sr");
	/*
	 * Error const
	 */
	define("__N_PROPERTIES_ERR__", "Properties are limited to one attribute!");
	define("__N_CLASS_ERR__", "Classes are limited to one attribute!");
	define("__N_METHODS_ERR__", "Methods are limited to two attributes!");
	define("__FILTER_ARRAY_ERR__", "Error occured while filtering Array!");
	define("__FORBIDDEN_MODIFIER__", "Forbidden modifier!");
	define("__FORBIDDEN_KEY__", "Forbidden key!");
	define("__NOTATION_ERR__", "Notation Error!");
	define("__EXTENSION_ERR__", "Forbidden extension!");
	define("__ELEMENT_MISSING_ERR__", "Array is missing some elements!");
	define("__FORBIDDEN_FAMILY__", "Forbidden family: Neither an class nor an interface!");
	define("__NO_CLASS_NAME__", "No class name given!");
	define("__NO_INTERFACE_NAME__", "No interface name given!");
	define("__CLASS_NOT_ABSTRACT__", "Apparently a method is declared as abstract, therefore the class has to be abstract too!");
	define("__CLASS_NAME_MISSING__", "class name missing!");
	define("__SRC_ERROR__", "invalid source, allowed values are class and uml!");
	define("__NO_DB_CON__", "no database connection available!");
	define("__CONST_ASSIGNMENT__", "constant need an assignment!");
	define("__EXTENDS_AFTER_IMPLEMENTS__", "found keyword extends after keyword implements!");
	define("__MULTIPLE_SUPERCLASSES__", "found multiple superclasses!");
	/**
	 * Parametic error messages
	 */
	function noModifier($name) {
		return $name." requires a modifier!";
	}	
	function constModifier($name) {
		return "Constant ".$name." can not have a modifier!";
	}
	function classModifier($name) {
		return "Class $name can not have a modifier!";
	}
	function forbiddenName($name) {
		return $name." is a forbidden name!";
	}
	function emptyArguments(array $args) {
		$ret = (count($args) > 1) ? "Arguments" : "Argument";
		foreach($args as $arg) {
			$ret .= " '".$arg."'";
			if($arg != end($args))
				$ret .= ",";
		}
		$ret .= (count($args) > 1) ? " are" : " is";
		$ret .= " empty!";
		return $ret;
	}
	function validClassName($name) {
		return "$name is not a valid Class name!";
	}
	function isNULL($name) {
		return "$name is NULL!";
	}
	function forbiddenAttributes($name) {
		return "$name has forbidden attributes!";
	}
	function umlValidName($name) {
		return "$name has an invalid name, check out NORM.txt!";
	}
	function parseParameter($name) {
		return "unable to parse parameters from method prototype $name!";
	}
	function FinalAndAbstract($name) {
		return "$name can not be final and abstract!";
	}
	function setPermission($name) {
		return "can not set permission of $name!";
	}
	function forbiddenModifier($name) {
		return "$name is not a valid modifier!";
	}
	function umlParentParse($name) {
		return "Not able to parse parent of $name!";
	}
	function umlInterfaceParse($name) {
		return "Not able to parse interfaces of $name!";
	}
	function NParents($name) {
		return "Class $name has more than one parent!";
	}
	function redundantError($name, $type) {
		return "$type $name is already declared!";
	}
	function interfaceAccessType($name) {
		return "Access type is not allowed in interface method $name!";
	}
	function interfaceAttributes($name) {
		return "$name is an interface and may not have member variables!";
	}
	function interfacePrototypeHasAccessType($name) {
		return "Access type not allowed in prototype of interface $name!";
	}
	function interfaceImplements($name) {
		return "Interface implementation not allowed in interface $name!";
	}
	function classAndInterface($name) {
		return "found class and interface in array of class $name!";
	}
	function noClassOrInterface($name) {
		return "found no class or interface in array of class $name!";
	}
	function noConstVal($name) {
		return "constant $name has no value!";
	}
	function forbiddenDBMS($dbms) {
		return "DBMS $dbms is not allowed!";
	}
	function attrParseErr($type) {
		return "unable to finish $type parsing!";
	}
