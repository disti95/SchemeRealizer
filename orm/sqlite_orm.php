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
	namespace orm;
	use \engine as engine;
	use \utils as utils;
	
	class sqlite_orm{
		/**
		 * @author   Michael Watzer
		 * @since    ?
		 * @version  1.0
		 * @category ORM
		 */
		private $connection;
		private $attr  = array(); //Arr for the Attributes
		private $class = array(); //Attr for the Classes
		private $table = array();
		/**
		 * @category Construct
		 * @param    sql $con
		 */
		public function __construct (engine\sql $con) {
			$this->connection = $con;
		}
		/**
		 * @category Setter
		 * @throws   sqlite_ormException
		 */
		public function setClass() {
			$stmt = <<<STMT
			select name 
			  from sqlite_master 
			 where type  = 'table' 
			   and name <> 'sqlite_sequence';
STMT;
			$res = $this->connection->prepareAndQuery($stmt, array());
			while($rows = $res->fetch()) 
				$this->class[] = $rows["name"];
			if(empty($this->class)) 
				throw new sqlite_ormException("sqlite_orm:No tables available!");
		}
		/**
		 * @category Setter
		 * @param    $table
		 * @throws   sqlite_ormException
		 */
		public function setAttr($table) {
			$attributes = array();
			$stmt       = <<<STMT
			pragma table_info($table);
STMT;
			$res = $this->connection->prepareAndQuery($stmt, array());
			while($rows = $res->fetch()) 
				$attributes[] = $rows['name'];
			if(empty($attributes)) 
				throw new sqlite_ormException("sqlite_orm:No attributes available!");
			$this->table              = $table;
			$this->attr[$this->table] = $attributes;
		}
		/**
		 * @category Getter
		 * @param    $table
		 * @param    $attr
		 * @return   default value
		 */
		public function getDefaultValue($table, $attr) {
			$default = false;
			$stmt    = <<<STMT
			pragma table_info($table);
STMT;
			$res  = $this->connection->prepareAndQuery($stmt, array());
			while($row = $res->fetch())
				if($row['name'] == $attr)
					$default = substr($row['dflt_value'], 1, -1);
			return $default;
		}
		/**
		 * @category Getter
		 * @return   attributes array
		 */
		public function getAttr() {
			return $this->attr;
		}
		/**
		 * @category Getter
		 * @return   Class name
		 */
		public function getClass() {
			return $this->class;
		}
		/**
		 * @category Translator
		 * @throws   sqlite_ormException
		 * @return   array
		 */
		public function classGenArrNotation() {
			$classgenArr = array();
			if(empty($this->attr))
				throw new sqlite_ormException("sqlite_orm:Please set the Attributes first!");
			foreach($this->attr[$this->table] as $attr) 
				$classgenArr[] = array($attr, 1, "private", false, false, false, false, $this->getDefaultValue($this->table, $attr));
			foreach($this->attr[$this->table] as $getter) 
				$classgenArr[] = array($getter, 2, "public", false, false, false, false, false);
			foreach($this->attr[$this->table] as $setter) 
				$classgenArr[] = array($setter, 3, "public", false, false, false, false, array('$'.$setter => null));
			$classgenArr[] = array($this->table, 5, false, true, false, false, false, false); //Header(Class)
			return $classgenArr;
		}
		/**
		 * @category Translator
		 * @throws   mysql_ormException
		 * @return   array
		 */
		public function umlGenArrNotation() {
			$umlgenArr = array();
			if(empty($this->attr))
				throw new sqlite_ormException("sqlite_orm:Please set the Attributes first!");
			foreach($this->attr[$this->table] as $attr)
				$umlgenArr[] = array($attr, 1, "private", false, false, false, false, $this->getDefaultValue($this->table, $attr));
			foreach($this->attr[$this->table] as $attr)
				$umlgenArr[] = array("get".utils\String::FirstLetterUP($attr), 2, "public", false, false, false, false, false);
			foreach($this->attr[$this->table] as $attr)
				$umlgenArr[] = array("set".utils\String::FirstLetterUP($attr), 2, "public", false, false, false, false, array('$'.$attr => null));
			$umlgenArr[] = array($this->table, 3, false, true, false, false, false, false); //Header(Class)
			return $umlgenArr;
		}
	}
	/**
	 * @author   Michael Watzer
	 * @since    ?
	 * @version  1.0
	 * @category Selfmade Exception-Class
	 */
	class sqlite_ormException extends \Exception{}
?>