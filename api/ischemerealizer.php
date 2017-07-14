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
	namespace api;
	
	/**
	 * @author   Michael Watzer
	 * @since    25.06.2017
	 * @category Interface for class SQLObj
	 * @version  1.0
	 */
	interface ISQLObj {
		/**
		 * @category setter for member $prepArr;
		 * @param    $prepArr
		 */
		public function setPrepArr(array $prepArr);
		/**
		 * @category getter for member $prepArr
		 * @return   value of member $prepArr
		 */
		public function getPrepArr();
		/**
		 * @category setter for member $name
		 * @param    $name
		 */
		public function setName($name);
		/**
		 * @category getter for member $name
		 * @return   value of member $name
		 */
		public function getName();
		/**
		 * @category setter for member $datatype
		 * @param    $datatype
		 */
		public function setDatatype($datatype);
		/**
		 * @category getter for member $datatype
		 * @param    value of member $datatype
		 */
		public function getDatatype();
		/**
		 * @category setter for member $size
		 * @param    $size
		 */
		public function setSize($size);
		/**
		 * @category getter for member $size
		 * @return   value of member $size
		 */
		public function getSize();
		/**
		 * @category setter for member $index
		 * @param    $index
		 */
		public function setIndex($index);
		/**
		 * @category getter for member $index
		 * @return   value of member $index
		 */
		public function getIndex();
		/**
		 * @category setter for member $null
		 * @param    $null
		 */
		public function setNull($null);
		/**
		 * @category getter for member $null
		 * @return   value of member $null
		 */
		public function getNull();
		/**
		 * @category setter for member $ai
		 * @param    $ai
		 */
		public function setAI($ai);
		/**
		 * @category getter for member $ai
		 * @return   value of member $ai
		 */
		public function getAI();
		/**
		 * @category setter for member $selected
		 * @param    $selected
		 */
		public function setSelected($selected);
		/**
		 * @category getter for member $selected
		 * @return   $value of member selected
		 */
		public function getSelected();
		/**
		 * @category setter for member $default
		 * @param    $default
		 */
		public function setDefault($default);
		/**
		 * @category getter for member $default
		 * @return   value of member $default
		 */
		public function getDefault();
		/**
		 * @category getter for member $srerror
		 * @return   value of member $srerror
		 */
		public function getSRError();
		/**
		 * @category get error message
		 * @param    $errcode
		 * @param    $errval (optional)
		 * @return   error message
		 */
		public function getErrorMsg($errcode, $errval = '');
	}
	/**
	 * @version  1.0
	 * @author   Michael Watzer
	 * @category Interface for class SQLObjList
	 * @since    26.06.2017
	 */
	interface ISQLObjList {
		/**
		 * @category add a column to the table
		 * @param    array $prepArr
		 */
		public function addSQLObj(array $prepArr);
		/**
		 * @category getter for member $srerror
		 * @return   value of member $srerror
		 */
		public function getSRError();
		/**
		 * @category getter for member $sqlObjList
		 * @return   value of member $sqlObjList
		 */
		public function getSQLObjList();
		/**
		 * @category getter for member $prepArr
		 * @return   value of member $prepArr
		 */
		public function getPrepArr();
		/**
		 * @category get error message
		 * @param    $errcode
		 * @param    $errval (optional)
		 * @return   error message
		 */
		public function getErrorMsg($errcode, $errval = '');
	}
	/**
	 * @version  1.0
	 * @author   Michael Watzer
	 * @category Interface for class ClassObjList
	 * @since    24.06.2017
	 */
	interface IClassObjList {
		/**
		 * @category add an member/method to the class
		 * @param    array $prepArr
		 */
		public function addClassObj(array $prepArr);
		/**
		 * @category getter for member $classObjList
		 * @return   value of member $classObjList
		 */
		public function getClassObjList();
		/**
		 * @category return ClassObj members
		 * @return   Filtered ClassObj array with members only
		 */
		public function getMembers();
		/**
		 * @category setter for member $type
		 * @param    $type
		 */
		public function setType($type);
		/**
		 * @category getter for member $type
		 * @return   value of member $type;
		 */
		public function getType();
		/**
		 * @category getter for member $srerror
		 * @return   value of member $srerror
		 */
		public function getSRError();
		/**
		 * @category return ClassObj methods
		 * @return   Filtered ClassObj array with methods only
		 */
		public function getMethods();
		/**
		 * @category return ClassObj class
		 * @return   Filtered ClassObj array with class only
		 */
		public function getClass();
		/**
		 * @category return ClassObj interface
		 * @return   Filtered ClassObj array with interface only
		 */
		public function getInterface();
		/**
		 * @category get error message
		 * @param    $errcode
		 * @param    $errval (optional)
		 * @return   error message
		 */
		public function getErrorMsg($errcode, $errval = '');
	}
	/**
	 * @version  1.0
	 * @author   Michael Watzer
	 * @category Interface for class ClassObj
	 * @since    22.06.2017
	 */
	interface IClassObj {
		/**
		 * @category setter for member $prepArr;
		 * @param    $prepArr
		 */
		public function setPrepArr(array $prepArr);
		/**
		 * @category getter for member $prepArr
		 * @return   value of member $prepArr
		 */
		public function getPrepArr();
		/**
		 * @category setter for member $name
		 * @param    $name
		 */
		public function setName($name);
		/**
		 * @category getter for member $name
		 * @return   value of member $name
		 */
		public function getName();
		/**
		 * @category setter for member $key
		 * @param    $key
		 */
		public function setKey($key);
		/**
		 * @category getter for member $key
		 * @return   value of member $key
		 */
		public function getKey();
		/**
		 * @category setter for member $modifier
		 * @param    $modifier
		 */
		public function setModifier($modifier);
		/**
		 * @category getter for member $modifier
		 * @return   value of member $modifier
		 */
		public function getModifier();
		/**
		 * @category setter for member $selected
		 * @param    $selected
		 */
		public function setSelected($selected);
		/**
		 * @category getter for member $selected
		 * @return   $selected
		 */
		public function getSelected();
		/**
		 * @category setter for member $keywords
		 * @param    $keywords
		 */
		public function setKeywords($keywords);
		/**
		 * @category getter for member $keywords
		 * @return   value of member $keywords
		 */
		public function getKeywords();
		/**
		 * @category setter for member $parents
		 * @param    $parents
		 */
		public function setParents($parents);
		/**
		 * @category getter for member $parents
		 * @return   value of member $parents
		 */
		public function getParents();
		/**
		 * @category setter for member $interfaces
		 * @param    $interfaces
		 */
		public function setInterfaces($interfaces);
		/**
		 * @category getter for member $interfaces
		 * @return   value of member $interfaces
		 */
		public function getInterfaces();
		/**
		 * @category setter for member $values
		 * @param    $values
		 */
		public function setValues($values);
		/**
		 * @category getter for member $values
		 * @return   value of member $values
		 */
		public function getValues();
		/**
		 * @category getter for member $srerror
		 * @return   value of member $srerror
		 */
		public function getSRError();
		/**
		 * @category get error message
		 * @param    $errcode
		 * @param    $errval (optional)
		 * @return   error message
		 */
		public function getErrorMsg($errcode, $errval = '');
	}
	/**
	 * @author   Michael Watzer
	 * @version  1.0
	 * @category Interface for class SchemeRealizer
	 * @since    29.05.2017
	 */
	interface ISchemeRealizer {
		/**
		 * @category Setter for member $ConvertFrom
		 * @param    $ConvertFrom
		 * @return   boolean
		 */
		public function setConvertFrom($ConvertFrom);
		/**
		 * @category Setter for member $ConvertTo
		 * @param    $ConvertTo
		 * @return   boolean
		 */
		public function setConvertTo($ConvertTo);
		/**
		 * @category Getter for member $ConvertFrom
		 * @return   value of member $ConvertFrom
		 */
		public function getConvertFrom();
		/**
		 * @category Getter for member $ConvertTo
		 * @return   value of member $ConvertTo
		 */
		public function getConvertTo();
		/**
		 * @category Validator for member $ConvertTo and $ConvertFrom
		 * @param    $sys
		 * @return   boolean
		 */
		public function chkSystem($sys);
		/**
		 * @category Setter for member $projectPath
		 * @param    $projectPath
		 * @throws   SchemeRealizerException on false project path 
		 */
		public function setProjectPath($projectPath);
		/**
		 * @category Getter for member $projectPath
		 * @return   value of member $projectPath
		 */
		public function getProjectPath();
		/**
		 * @category Setter for Database-Connection($dsn)
		 * @param    host  (mysql, mongodb)(mandatory)
		 * @param    user  (mysql, mongodb)(mandatory)
		 * @param    pass  (mysql, mondodb)(mandatory)
		 * @param    port                  (optional)
		 */
		public function setDSN($host = NULL
				              ,$user = NULL
				              ,$pass = NULL
				              ,$port = NULL);
		/**
		 * @category Setter for ORM($orm)
		 * @param    $db
		 */
		public function setORM($db);
		/**
		 * @category Getter for ORM($orm)
		 * @return   value of member $orm
		 */
		public function getORM();
		/**
		 * @category Setter for member $engine
		 * @param    $engine
		 */
		public function setEngine($engine);
		/**
		 * @category Getter for member $engine
		 * @return   value of member $engine;
		 */
		public function getEngine();
		/**
		 * @category Getter for member $dsn
		 * @return   Database-Connection
		 */
		public function getDSN();
		/**
		 * @category return tables/collections from database
		 * @return   array with tables/collections
		 */
		public function listDatabaseContent();
		/**
		 * @category return available classes from project path
		 * @return   array with available classes(array(file => class))
		 */
		public function getAvailableClasses();
		/**
		 * @category setter for connection identifier(from/to)
		 * @param    $conId
		 */
		public function setConId($conId = 'from');
		/**
		 * @category getter for member $conId
		 * @return   value of member $conId
		 */
		public function getConId();
		/**
		 * @category map table/collection to a ClassObjList
		 * @param    $tab_collect
		 * @return   ClassObjList
		 */
		public function mapToClass($tab_collect);
		/**
		 * @category map collection/class to a SQLObjList
		 * @param    $class_collect
		 * @return   SQLObjList
		 */
		public function mapToSQL($class_collect);
		/**
		 * @category create a default SQLObjList
		 * @param    $obj(NOSQLObjList or ClassObjList)
		 * @return   SQLObjList
		 */
		public function createDefaultSQLObjList($obj);
		/**
		 * @category setter for member $file
		 * @param    $file
		 */
		public function setDistinctFile($file);
		/**
		 * @category getter for member $file
		 * @return   value of member $file
		 */
		public function getDistinctFile();
		/**
		 * @category get SQL-Code from SQLObjList
		 * @param    $sqlObjList
		 * @param    $tablename
		 * @return   SQL-Code
		 */
		public function getSQLCode($sqlObjList, $tablename);
		/**
		 * @category get error message
		 * @param    $errcode
		 * @param    $errval (optional)
		 * @return   error message
		 */
		public function getErrorMsg($errcode, $errval = '');
	}
