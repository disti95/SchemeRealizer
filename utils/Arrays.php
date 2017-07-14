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
	namespace utils;
	use function constants\classAndInterface;
	use function constants\noClassOrInterface;
	class Arrays{
		/**
		 * @author   Michael Watzer
		 * @version  1.0
		 * @since    11.08.2016
		 * @category Util for Array-Operations
		 */
		public $emptiness   = false;
		public $trimedArray = array();
		/**
		 * @category Extract the Attr(1), Getter(2), Setter(3), Others(4) from an Array
		 * @param    $type
		 * @param    $arr
		 * @return   bool|array
		 */
		public static function getTypeOfArr($type, array $arr) {
			if(!is_array($arr))
				return false;
			$parsedArr = array();
			foreach($arr as $elem) {
				if(count($elem) < 2) 
					return false;
				if($elem[1] == $type) 
					$parsedArr[] = $elem;
			}
			return $parsedArr;
		}
		/**
		 * @category Check if a specific type exist in the uml array
		 * @param    type
		 * @param    uml array
		 * @return   bool
		 */
		public static function typeInUMLArr($type, array $umlArr) {
			foreach($umlArr as $elem) 
				if(isset($elem[1]))
					if($elem[1] == $type)
						return true;
			return false;
		}
		/**
		 * @category Translate param array to string
		 * @param    $arr
		 * @return   string
		 */
		public static function getParamStr(array $arr, $startstr = "") {
			$str  = $startstr;
			$keys = array_keys($arr);
			
			for($i = 0; $i < count($keys); $i++) {
				if(!strlen($startstr) && self::isAssoc($arr))
					$str .= $keys[$i].(isset($arr[$keys[$i]]) ? ' = ' : "");
				elseif(strlen($startstr) && self::isAssoc($arr))
					$str .= "'$keys[$i]'".' => ';
				if(is_array($arr[$keys[$i]])) {
					$str .= "array(";
					$str = self::getParamStr($arr[$keys[$i]], $str);
					$str .= ")";
				}
				else {
					if(isset($arr[$keys[$i]])) {
						$evalret = false;
						if(strtolower(substr($arr[$keys[$i]], 0, 5)) == 'array') {
							$is_arr = '$evalret = is_array('.$arr[$keys[$i]].');';
						    if(\native\Validate::chkPHPSyntax($is_arr))
								eval($is_arr);
						}
						if($evalret)
							$str .= $arr[$keys[$i]];
						else
							$str .= "'".$arr[$keys[$i]]."'";
					}
				}
				if($i != count($keys) - 1)
					$str .= ', ';
			}
			return $str;
		}
		/**
		 * @category determine if an array is associative
		 * @param    $arr
		 * @return   boolean
		 */
		public static function isAssoc(array $arr) {
			if(array() === $arr)
				return false;
			return array_keys($arr) !== range(0, count($arr) - 1);
		}
		/**
		 * @category Checks if an class is abstract or not
		 * @param    array $arr 
		 * @param    $comesFromParser
		 * @return   boolean
		 */
		public static function isClassAbstract(array $arr, $comesFromParser) {
			foreach($arr as $elem) {
				if(($comesFromParser == "uml" && $elem[1] == 5) || ($comesFromParser == "class" && $elem[1] == 3)) { 
					if(is_array($elem[4]) && in_array(__ABSTRACT__, $elem[4]))
						return true;
				}
			}
			return false;
		}
		/**
		 * @category Setter
		 * @param    $val
		 * @return   bool
		 */
		public function setEmptiness($val) {
			foreach($val as $elem) {
				if(is_array($elem)) 
					$this->setEmptiness($elem);
				if(empty($elem)) 
					$this->emptiness = true;
 			}
		}
		/**
		 * @category Trimmer
		 * @param    $val
		 * @return   array
		 */
		public function setTrimedArray($val) {
			foreach($val as $elem) {
				if(is_array($elem))
					$this->setTrimedArray($elem);
				else
					$this->trimedArray[] = trim($elem);
			}
		}
		/**
		 * @category Comparer
		 * @param   $arr1
		 * @param   $arr2
		 */
		public static function compareArr($arr1, $arr2) {
			$equals = true;
			foreach($arr1 as $elem) {
				if(!in_array($elem, $arr2))
					$equals = false;
			}
			return $equals;
		}
		/**
		 * @category Search an Array recursive
		 * @param    $elem
		 * @param    $arr
		 * @return   bool
		 */
		public static function in_array_r($elem, $arr) {
			foreach($arr as $item) {
				if($item == $elem || (is_array($item) && self::in_array_r($elem, $item)))
					return true;
				return false;
			}
		}
		/**
		 * @category check if this class is an interface or a class
		 * @param    $prepArr
		 * @param    $name
		 * @param    $src(uml|class)
		 * @return   boolean|string
		 */
		public static function isInterface($prepArr, $name, $src) {
			$src = (empty($src)) ? constant('__CLASS__') : $src;
			if(empty($name))
				return constant('__CLASS_NAME_MISSING__');
			if($src != constant('__CLASS__') && $src != constant('__UML__'))
				return constant('__SRC_ERROR__');
			$classDetect = $interfaceDetect = 0;
			foreach($prepArr as $elem) {
				if(!isset($elem[1]))
					continue;
				if(($src == constant('__CLASS__') && $elem[1] == 5)
				|| ($src == constant('__UML__')   && $elem[1] == 3))
					$classDetect |= 1;
				elseif(($src == constant('__CLASS__') && $elem[1] == 6)
				    || ($src == constant('__UML__')   && $elem[1] == 4))
					$interfaceDetect |= 1;
			}
			if($classDetect & $interfaceDetect)
				return classAndInterface($name);
			elseif(!($classDetect | $interfaceDetect))
				return noClassOrInterface($name);
			return ($interfaceDetect) ? true : false;
		}
		/**
		 * @category extract the class/interface name from the preparation array
		 * @param    preparation array
		 * @param    src(class|uml)
		 * @return   array(error(true|false), msg(classname|errormsg))
		 */
		public static function extractClassName($preparr, $src) {
			$src = (empty($src)) ? constant('__CLASS__') : $src;
			if($src != constant('__CLASS__') && $src != constant('__UML__'))
				return array(true, constant('__SRC_ERROR__'));
			$classname = $interfacename = '';
			foreach($preparr as $elem) {
				if(!isset($elem[1]) || !isset($elem[0]))
					continue;
				if(($src == constant('__CLASS__') && $elem[1] == 5)
				|| ($src == constant('__UML__')   && $elem[1] == 3))
					$classname = $elem[0];
				elseif(($src == constant('__CLASS__') && $elem[1] == 6)
				    || ($src == constant('__UML__')   && $elem[1] == 4))
				    $interfacename = $elem[0];
			}
			if(strlen($classname) && strlen($interfacename))
				return array(true, classAndInterface($classname));
			elseif(empty($classname) && empty($interfacename))
				return array(true, constant('__CLASS_NAME_MISSING__'));
			return (strlen($classname)) 
			         ? array(false, $classname) 
			         : array(false, $interfacename);
		}
	}
?>
