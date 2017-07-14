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
	namespace orm;
	use \engine as engine;
	use \utils as utils;
	
	class mongodb_orm {
	   /**
		* @author   Christian Dittrich
		* @version  1.0
		* @category ORM for the MongoDB engine
		* @since    21.04.2017
		*/
		private $con;
		private $db;
		private $collection;
		/**
		 * @category Construct
		 * @param    MongoDB connection
		 */
		public function __construct($con) {
			$this->con = $con;
		}
		/**
		 * @category request List of Databases
		 * @return   list of Database names
		 */
		public function getDatabases() {
			$databases = $this->con->listDBs();
			$dblist    = array();
			foreach($databases['databases'] as $database)
				$dblist[] = $database['name'];
			return $dblist;
		}
		/**
		 * @category setter for Database
		 * @throws	 mongodb_ormException
		 * @param    database
		 */
		public function setDatabase($database) {
			try {
				$this->db = $this->con->selectDB($database);
			}
			catch(\Exception $e) {
				throw new mongodb_ormException("mongodb_orm:".$e->getMessage());
			}
		}
		/**
		 * @category getDatabase
		 * @return   Database name
		 */
		function getDatabase() {
			return $this->db->getName();
		}
		/**
		 * @category getCollection
		 * @return   Collection name
		 */
		function getCollection() {
			return $this->collection->getName();
		}
		/**
		 * @category sets Collection var
		 * @throws   mongodb_ormException
		 * @param    Collection name
		 */
		public function setCollection($col) {
			try {
				$this->collection = $this->db->selectCollection($col);
			}
			catch(\Exception $e) {
				throw new mongodb_ormException("mongodb_orm:".$e->$getMessage());
			}
		}
		/**
		 * @category gets all Keys of the selected Cursors
		 * @throws   mongodb_ormException
		 * @return   array of Keys
		 */
		public function getKeysOfCollection() {
			$keys = array();
			if(get_class($this->collection) == "MongoCollection") {
				if(!$this->CollectionIsEmpty($this->collection)) {
					$array = iterator_to_array($this->collection->find());
					foreach($array as $k => $v)
						$keys = array_merge($this->getKeysOfDocument($v), $keys);
					$keys = array_unique($keys);
					asort($keys);
					return $keys;
				}
				else{
					return "";
				}
			}
			else {
				throw new mongodb_ormException("mongodb_orm: No Collection selected!");	
			}
		}
		/**
		 * @category getting all keys of a collection
		 * @param	 Document/Objekt
		 * @return	 String
		 */
		function getKeysOfDocument($doc) {
			$keys = array();
			foreach($doc as $a => $b) {
				if(is_Array($b)) {
						$keys = array_merge($this->getKeysOfDocument($b), $keys);
				}
				if(!(is_int($a)))
					$keys[] = $a;
			}
			return $keys;
		}
		/**
		 * @category request List of Collections
		 * @throws   mongodb_ormException
		 * @return	 $colList
		 */
		public function getCollections() {
				if(get_class($this->db) == "MongoDB") {
					$collections = $this->db->getCollectionNames();
					$colList     = array();
					foreach($collections as $collection)
						if(!empty($collection)) $colList[] = $collection;
					return $colList;
				}
				else
					throw new mongodb_ormException("mongodb_orm: No Database selected!");
		}
		/**
		 * @category checks if Collection is empty
		 * @param 	 $collection
		 * @return	 boolean
		 */
		public function CollectionIsEmpty($collection) {
			if($collection->find() == null){
				return true;				
			}
			else
			{
				return false;
			}
		}
		/**
		 * @category insert data into MongoDB
		 * $data
		 */
		function InsertData($data){
			$this->collection->insert($data);
		}
		/**
		 * @category generates ClassArr
		 * @throws   mongodb_ormException
		 * @return   classgen Array
		 */
		public function classGenArrNotation() {
			$classgenArr = array();
			if(!get_class($this->collection) == "MongoCollection")
				throw new mongodb_ormException("mongodb_orm: Please set the Collection first!");
			$arr = $this->getKeysOfCollection();
			//var_dump($arr);
			foreach($arr as $key)
				$classgenArr[] = array($key, 1, "private", false, false, false, false, false);
			foreach($arr as $getter)
				$classgenArr[] = array($getter, 2, "public", false, false, false, false, false);
			foreach($arr as $setter)
				$classgenArr[] = array($setter, 3, "public", false, false, false, false, array('$'.$setter => null));
			$classgenArr[] = array($this->collection->getName(), 5, false, true, false, false, false, false); //Header(Class)
			return $classgenArr;
		}
		/**
		 * @category generates UMLArr
		 * @throws   mongodb_ormException
		 * @return   umlgen Array
		 */
		public function umlGenArrNotation() {
			$umlgenArr = array();
			if(!get_class($this->collection) == "MongoCollection")
				throw new mongodb_ormException("mongodb_orm:Please set the Collection first!");
			$arr = $this->getKeysOfCollection();
			//var_dump($arr);
			foreach($arr as $key)
				$umlgenArr[] = array($key, 1, "private", false, false, false, false, false);
			foreach($arr as $key)
				$umlgenArr[] = array("get".utils\String::FirstLetterUP($key), 2, "public", false, false, false, false, false);
			foreach($arr as $key)
				$umlgenArr[] = array("set".\utils\String::FirstLetterUP($key), 2, "public", false, false, false, false, array('$'.$key => null));
			$umlgenArr[] = array($this->collection->getName(), 3, false, true, false, false, false, false); //Header(Class)
			return $umlgenArr;
		}
	}
	class mongodb_ormException extends \Exception {}
