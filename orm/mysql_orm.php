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
	
	class mysql_orm{
		/**
		 * @author   Michael Watzer
		 * @version  1.0
		 * @since    ?
		 * @category ORM
		 */
		private $connection; //PDO-Connection
		private $database;
		private $attr  = array(); //Array which contains the Attributes
		private $class = array(); //Array which contains the Classes
		private $table;
		/**
		 * @category  Construct
		 * @param sql $connection
		 */
		public function __construct(engine\sql $connection) {
			$this->connection = $connection;
		}
		/**
		 * @category Setter
		 * @throws   mysql_ormException
		 */
		public function setClass() {
			$query = $this->connection->prepareAndQuery("show tables", array());
			while($row = $query->fetch()) {
				$this->class[] = $row["Tables_in_".$this->connection->getDatabase()];
			}
			//Check for tables
			if(empty($this->class)) 
				throw new mysql_ormException("mysql_orm:No tables available!");
		}
		/**
		 * @category Setter
		 * @param    $table
		 * @throws   mysql_ormException
		 */
		public function setAttr($table) {
			$attributes = array();
			$query = $this->connection->prepareAndQuery("describe ".$table, array());
			while($row = $query->fetch()) {
				$attributes[] = $row["Field"];
			}
			//Check for attributes
			if(empty($attributes)) 
				throw new mysql_ormException("mysql_orm:No attributes available!");
			$this->table = $table;
			$this->attr[$this->table] = $attributes;
		}
		/**
		 * @category Getter
		 * @param    $table
		 * @param    $attribute
		 * @return   default value
		 */
		public function getDefaultValue($table, $attr) {
			$stmt = <<<STMT
			select column_default 
			  from information_schema.columns
			 where table_name   = :table
			   and table_schema = :db
			   and column_name  = :attr
STMT;
			$query = $this->connection->prepareAndQuery($stmt, array(':table' => $table
					                                                ,':attr'  => $attr
					                                                ,':db'    => $this->connection->getDatabase()));
			$row = $query->fetch();
			return (isset($row['column_default']) && $row['column_default'] != '') 
			         ? $row['column_default']
			         : false;
		}
		//Getter for the Arr's
		public function getAttr() {
			return $this->attr;
		}
		public function getClass() {
			return $this->class;
		}
		/**
		 * @category Translator
		 * @throws   mysql_ormException
		 * @return   array
		 */
		public function classGenArrNotation() {
			$classgenArr = array();
			if(empty($this->attr)) 
				throw new mysql_ormException("mysql_orm:Please set the Attributes first!");
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
				throw new mysql_ormException("mysql_orm:Please set the Attributes first!");
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
	 * @category Selfmade Exception-Class
	 * @version  1.0
	 */
	class mysql_ormException extends \Exception{}
?>