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
	namespace gen;
	use \utils as utils;
	use \engine as engine;
	class sqlgen{
		/**
		 * @author   Michael Watzer
		 * @since    ?
		 * @version  1.0
		 * @category Generator
		 */
		private $table;
		private $dbms;
		/*
		* Build-Up = array(name, datatype, size, index, null, autoincrement, selected, default)
		* index: 1 = PRIMARY, 2 = UNIQUE, 3 = INDEX, -1 = false
		* null:  1 = true,   -1 = false
		* autoincrement: 1 = true, -1 = false
		*/
		private $arr;
		/**
		 * @category Construct
		 * @param    $arr
		 * @param    $dbms
		 * @param    $table
		 * @throws   sqlgenException
		 */
		public function __construct($arr, $dbms, $table){
			//Check for Emptiness
			if(empty($arr)) 
				throw new sqlgenException("sqlgen: Empty Array!");
			if(empty($table))
				throw new sqlgenException("sqlgen: Empty table value!");
			if(empty($dbms))
				throw new sqlgenException("sqlgen: Empty DBMS value!");
			//Check if the Table fits the regex
			if(!regexName($table)) 
				throw new sqlgenException("sqlgen: Table ".$table." does not fit the regex!");
			//Check DBMS and Validate the Array
			switch($dbms){
				case constant('__MYSQL__'): 
					$validationResult = validateMySQL($arr);
				break;
				case constant('__SQLITE__'): 
					$validationResult = validateSQLite($arr);
				break;
				default: 
					throw new sqlgenException("sqlgen: Forbidden DBMS!");
				break;
			}
			if($validationResult !== true)
				throw new sqlgenException($validationResult);
			$this->table = $table;
			$this->arr   = $this->getSelectedElementsFromArray($arr); //Filter the Array
			$this->dbms  = $dbms;
		}
		/**
		 * @category SQL-Code Generator
		 * @return   string
		 */
		public function getSQLCode() {
			/*
			 * Build-Up = array(name, datatype, size, index, null, autoincrement, selected, default)
			 * index: 1 = PRIMARY, 2 = UNIQUE, 3 = INDEX, -1 = false
			 * null:  1 = true,   -1 = false
			 * autoincrement: 1 = true, -1 = false
			 */
			$unique   = array();
			$primary  = array();
			$defaults = ($this->dbms == constant('__MYSQL__'))
			              ? " ENGINE=InnoDB DEFAULT CHARACTER SET=utf8"
			              : "";
			$output   = "CREATE TABLE ".$this->table."(\n";
			//Code-Generation for MySQL
			if($this->dbms == constant('__MYSQL__')) {
				$key = array();
				foreach($this->arr as $elem) {
					$output .= "\t".$elem[0]." ".strtoupper($elem[1]);
					//Is Size allowed?
					if(maxSizePerTypeMySQL($elem[1]) != false)
						$output .= "(".$elem[2].")";
					if($elem[4] == 1)
						$output .= " NULL";
					else
						$output .= " NOT NULL";
					if($elem[7] != false)
						$output .= " DEFAULT ".getDefaultValue($elem[7], $elem[1], $elem[2]);
					if($elem[5] == 1)
						$output .= " AUTO_INCREMENT";
					//Get Indizes
					switch($elem[3]){
						case 1: 
							$primary[] = $elem[0];
						break;
						case 2: 
							$unique[] = $elem[0];
						break;
						case 3: 
							$key[] = $elem[0];
						break;
					}
					if($elem != end($this->arr)) 
						$output .= ",\n";
				}
				//Set the different indizes
				if(count($unique) >= 1) {
					$output .= ",\n";
					$output .= "\tUNIQUE (";
					foreach($unique as $elem) {
						$output .= $elem;
						if($elem != end($unique))
							$output .= ",";
					}
					$output .= ")";
				}	
				if(count($primary) >= 1) {
					$output .= ",\n";
					$output .= "\tPRIMARY KEY (";
					foreach($primary as $elem) {
						$output .= $elem;
						if($elem != end($primary))
							$output .= ",";
					}
					$output .= ")";
				}
				if(count($key) >= 1) {
					$output .= ",\n";
					foreach($key as $elem) {
						$output .= "\tKEY ".$elem." (".$elem.")";
						if($elem != end($key))
							$output .= ",\n";
					}
				}
			}
			//Code-Generation for SQLite
			if($this->dbms == constant('__SQLITE__')) {
				$ai = false; //AI-Tag for Code-Generation
				foreach($this->arr as $elem) {
					$output .= "\t".$elem[0]." ".strtoupper($elem[1]);
					//Check if we have an AI
					if($elem[5] == 1) {
						$output .= " PRIMARY KEY AUTOINCREMENT";
						$ai = true;
					}
					//Check for NULL
					if($elem[4] == 1) 
						$output .= " NULL";
					else
						$output .= " NOT NULL";
					if($elem[7] != false) 
						$output .= " DEFAULT ".$elem[7];
					//Get Indizes
					switch($elem[3]) {
						case 1: 
							$primary[] = $elem[0];
						break;
						case 2: 
							$unique[] = $elem[0];
						break;
					}
					if($elem != end($this->arr))
						$output .= ",\n";
				}
				//Get PK's
				if(!$ai) {
					//Check if we have some PK's
					if(count($primary) > 0) {
						$output .= ",\n";
						$output .= "\tPRIMARY KEY (";
						foreach($primary as $elem) {
							$output .= $elem;
							if($elem != end($primary)) 
								$output .= ",";
						}	
						$output .= ")";
					}
				}
				//Get Unique's
				if(count($unique) > 0) {
					$output .= ",\n";
					$output .= "\tUNIQUE (";
					foreach($unique as $elem) {
						$output .= $elem;
						if($elem != end($unique))
							$output .= ",";
					}
					$output .= ")";
				}
 			}
			$output .= "\n)$defaults;";
			return $output;
		}
		/**
		 * @category Getter
		 * @return   array
		 */
		public function getArr() {
			return $this->arr;
		}
		/**
		 * @category Flusher
		 * @param    $file
		 * @throws   sqlgenException
		 * @throws   umlgenException
		 */
		public function flushFile($file) {
			if($this->dbms == constant('__MYSQL__')) {
				if(($res = utils\File::flushFile($file, $this->getSQLCode(), $this->dbms)) !== true)
					throw new sqlgenException("sglgen: ".$res);
			}
			elseif($this->dbms == constant('__SQLITE__')) {
				//Use one of the GOLDEN-UTILS
				$checkListArray = array("emptyFile"      => $file
						               ,"existFile"      => array($file, 0)
						               ,"extensionCheck" => array($file, constant('__SQLITE__'))
						               ,"emptyContent"   => $this->getSQLCode());
				if(($res = utils\File::basicFileValidation($checkListArray)) !== true)
					throw new sqlgenException("sqlgen: ".$res);
				try {
					$sql     = new engine\sql(constant('__SQLITE__'));
					$sql->getConnection($file);
					$sqlCode = utils\File::stripContent($this->getSQLCode());
					$sql->prepareAndQuery($sqlCode, array());
					$sql->closeConnection();
					//Now set permission
					if(!utils\File::setPerm($file))
						throw new umlgenException("sqlgen: Not able the set the permission of ".$file."!");
				}
				catch(DatabaseException $e) {
					throw new sqlgenException("sqlgen: ".$e->getMessage());
				}
			}
		}
		/**
		 * @category Filter
		 * @param    array $arr
		 * @throws   sqlgenException
		 * @return   multitype:unknown
		 */
		public function getSelectedElementsFromArray(array $arr) {
			$prepArr = array();
			//Check for emptiness
			if(empty($arr))
				throw new sqlgenException("sqlgen: Arr is empty!");
			foreach($arr as $elem) {
				//Check if the Arr has 8 Elements
				if(count($elem) == 8) {
					if($elem[6])
						$prepArr[] = $elem;
				}
				else
					throw new sqlgenException("sqlgen: prepArr Attribute missing!");
			}
			return $prepArr;
		}
	}
	/**
	 * @author   Michael Watzer
	 * @version  1.0
	 * @since    ?
	 * @category Selfmade Exception-Class
	 */
	class sqlgenException extends \Exception{}
?>
