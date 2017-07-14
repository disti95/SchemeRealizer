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
	namespace gen;
	class nosqlgen{
		/**
		 * @author   Christian Dittrich
		 * @version  1.0
		 * @category class to create json out of array
		 * @since    23.04.2017
		 */
		private $arr;
		/*
		 * Build-Up = array(translateTo, database, CollectionName, array(option => value), array(array(key, value, Type)))
		 * Example  = array("mongodb", "SchemeTest", "sqlgencheck", array("capped" => "true"), array(array("name", "chris", "String")));
		 */
		/**
		 * @category Constructor
		 * @param    $arr
		 * @throws   nosqlgenException
		 */
		public function __construct($arr) {
			if(!is_array($arr))
				throw new nosqlgenException("nosqlgen: invalid arry given!");
			//if(count($arr) != __MONGODB_ARR_SIZE__)
				//sthrow new nosqlgenException("nosqlgen: invalid array size, 5 elements required!");
			$this->arr = $arr;
		}
		/**
		 * @category Generates: Select Database, Create Collection(with options), Document Insert
		 * @return   String
		 */
		public function getNosqlCode() {
			$output = "";
			if($this->arr[0] == "mongodb") {
				$output .= "use ".$this->arr[1]."\n";
				foreach($this->arr[2] as $colname => $col) {
					$output .= "db.createCollection('".$colname."',{";
					$counter = -1;
					foreach($col[0] as $opt => $value) {
						if(++$counter)
							$output .= ", ";
						$output .= $opt.":".$value;
						$output .= "\n";
					}
					$output .= "})\n";
					$output .= "db.".$colname.".insert(";
					$output .= $this->generateJSON($col[1]);
					$output .= ")";
				}
			}
			return $output;
		}
		/**
		 * @category Generate JSON code
		 * @return   String
		 */
		public function generateJSON($docs) {
			// Example  = array("mongodb","SchemeTest","sqlgencheck",array("capped"=>"true"),array(array("name","chris","String")));
			// missing: array= [1,2,3] object={key:val,key:val},   add missing datatypes
			$output  = "{\n";
			$counter = 0;
			foreach($docs as $key => $doc) {
				if($counter) {
					$output .= ", ";
				}
				else {
					$counter++;
				}
				if(is_array($doc[0])) {
					if($doc[1] == __ARRAY__) {
						$output.='"'.$key.'":[';
						$count  = 0;
						foreach($doc[0] as $wert){
							if($count != 0){
								$output .=", ";
							}else{
								$count++;									
							}
							$output .= $this->generateArrayJSON($wert);
						}
						$output .= ']';
					}
					if($doc[1] == __OBJECT__)
						$output .='"'.$key.'":'.$this->generateJSON($doc[0])."\n";
					if($doc[1] == __TIMESTAMP__)
						$output .= $this->generateDatatypeNotation($doc[0], $doc[1]);
				}
				else {
					$output .= '"'.$key.'":';
					$output .= $this->generateDatatypeNotation($doc[0], $doc[1]);
				}
			}
			return $output .= "\n}";
		}
		/**
		 * @category generates a JSON Array
		 * @param    Array $wert
		 * @return   String
		 */
		public function generateArrayJSON($wert) {
			$output = "";
			if(is_array($wert[0])) {
				if($wert[1] == __ARRAY__) 
					foreach($wert[1] as $w)
						$output .= $this->generateArrayJSON($w);
				if($wert[1] == __OBJECT__) 
						$output .= $this->generateJSON($wert[0]);
			}
			else {
				$output .= $this->generateDatatypeNotation($wert[0], $wert[1]);
			}
			return $output;
		}
		/**
		 * @category Flusher
		 * @param $file
		 * @throws nosqlgenException
		 */
		public function flushFile($file) {
			//Method????
			if(($res = utils\File::flushFile($file, $this->getNosqlCode(), $this->dbms)) !== true)
				throw new nosqlgenException("nosglgen: ".$res);
		}
		/**
		 * @category generates the DataType notation for JSON
		 * @param    Value  $val
		 * @param 	 String $Type
		 * @return   String
		 */
		function generateDatatypeNotation($val, $type){
			$output = "";
			if($type == __STRING__)
				$output .= '"'.$val.'"';
			if($type == __INTEGER__ || $type == __BOOLEAN__)
				$output .= $val;
			if($type == __NUMBERLONG__)
				$output .= 'NumberLong('.$val.')';
			if($type == __NUMBERINT__)
				$output .= 'NumberInt('.$val.')';
			if($type == __NUMBERDECIMAL__)
				$output .= 'NumberDecimal('.$val.')';
			if($type == __OBJECTID__ && is_int($val) && strlen((string)$val) == 24)
				$output .= 'ObjectId("'.$val.'")';
			if($type == __JSONDATE__ && is_int($val))
				$output .= 'new Date('.$val.')';
			if($type == __TIMESTAMP__ && count($val) == 2 && is_int($val[1] && is_int($val[2])))
				$output .= 'Timestamp('.$val[0].', '.$val[1].')';
			if($type == __ISODATE__ && is_int($val))
				$output .= 'ISODATE('.$val.')';
			if($type == null)
				$output .= 'null';
			return $output;
		}
	}
	/**
	 * @author   Christian Dittrich
	 * @version  1.0
	 * @since    1.5.2017
	 * @category Selfmade Exception-Class
	 */
	class nosqlgenException extends \Exception{}
?>