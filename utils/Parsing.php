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
	class Parsing{
		/**
		 * @author   Michael Watzer
		 * @version  1.0
		 * @since    10.08.2016
		 * @category util for parsing support
		 */
		/**
		 * @category Validates an Class name(regex according to php.net)
		 * @param    $str
		 * @return   bool
		 */
		public static function validateClassName($str) {
			if(preg_match("/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/", $str) === false || empty($str))
				return false;
			else
				return true;
		}
		/**
		 * @category Validates an Attribute name(regex according to php.net)
		 * @param    $str
		 * @return   bool
		 */
		public static function validateAttributeName($str) {
			if(preg_match("/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/", $str) === false || empty($str))
				return false;
			else
				return true;
		}
		/**
		 * @category Validates a Method name(regex according to php.net)
		 * @param    $str
		 * @return   bool
		 */
		public static function validateMethodName($str) {
			if(preg_match("/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/", $str) === false || empty($str))
				return false;
			else
				return true;
		}
		/**
		 * @category Detects if the Class-Diagram delimiter is set
		 * @param    $str
		 * @return   bool
		 */
		public static function classDiagramDelimiter($str) {
			return preg_match("/^--*$/",$str);
		}
		/**
		 * @category Detects if the Class-Diagram has a modifier
		 * @param    $str
		 * @return   bool | string
		 */
		public static function DiagramHasModifier($str) {
			$modifier = false;
			switch(substr($str, 0, 1)) {
				case __UML_PUBLIC__:
					$modifier = __PUBLIC__;
				break;
				case __UML_PRIVATE__:
					$modifier = __PRIVATE__;
				break;
				case __UML_PROTECTED__:
					$modifier = __PROTECTED__;
				break;
			}
			return $modifier;
		}
		/**
		 * @category Validate the modifier(Seriously, not that easy to get this)
		 * @param    $str
		 * @return   bool
		 */
		public static function validateModifier($str) {
			$valid = true;
			if(($pos = strrpos($str, "{")) !== false) 
				$str = substr($str, 0, $pos);
			$str = trim($str);
			if(strlen($str) > 1) 
				switch($str[0]) {
					case __UML_PUBLIC__:
					case __UML_PRIVATE__:
					case __UML_PROTECTED__:
					break;
					default: 
						$valid = false;
					break;
				}
			return $valid;
		}
		/**
		 * @category Detects if the Class-Diagram has [an] attribute[s]
		 * @param    $str
		 * @return   bool | array
		 */
		public static function DiagramHasAttribute($str){
			/*
			 * Currently supported:
			 * const
			 * static
			 * final
			 * version 1.1:
			 * abstract
			 * extends
			 * implements
			 */
			$attributes = false;
			if(($bracketStartPos = strrpos($str, "{")+1) !== false && ($bracketEndPos = strrpos($str, "}")) !== false) {
				if($bracketStartPos < $bracketEndPos) {
					//length = end - start
					$attributesArr = explode(",", substr($str, $bracketStartPos, $bracketEndPos - $bracketStartPos));
					if(count($attributesArr) > 0) {
						//Trim the entire Array via utils\Arrays
						$array         = new \utils\Arrays();
						$array->setTrimedArray($attributesArr);
						$attributesArr = $array->trimedArray;
						$attributes    = $attributesArr;
					}
				}
			}
			return $attributes;
		}
		/**
		 * @category Attribute validator
		 * @param    $attr
		 * @param    $type(0 => attribute, 1 => method, 2 => class)
		 * @return   bool
		 */
		public static function validateAttribute($attr, $type) {
			switch($type) {
				case 0:
					switch($attr) {
						case __CONST__: 
							return true;
						break;
						case __STATIC__: 
							return true;
						break;
						default: 
							return false;
						break;
					}
				break;
				case 1:
					switch($attr) {
						case __STATIC__: 
							return true;
						break;
						case __ABSTRACT__: 
							return true;
						break;
						case __FINAL__: 
							return true;
						break;
						default: 
							return false;
						break;
					}
				break;
				case 2:
					switch($attr) {
						case __ABSTRACT__: 
							return true;
						break;
						case __FINAL__: 
							return true;
						break;
						default: 
							return false;
						break;
					}
				break;
				default: 
					return false;
				break;
			}		 
		}
		/**
		 * @category Validator/Extractor of Class-Diagram names
		 * @param    $str
		 * @param    $type(0 => attribute, 1 => method, 2 => class/interface)
		 * @return   bool | string
		 */
		public static function DiagramHasName($str, $type) {
			$name = false;
			//trim [and substring $str if the modifier is set]
			$tmpStr = (self::DiagramHasModifier($str) !== false) 
			            ? trim(substr(trim($str), 1)) 
			            : trim($str);
			if($type == 0) 
				$pos = strpos($tmpStr .= " ", " "); //Attribute name ends with " "
			elseif($type == 1) {
				/*
				 * METHODS NEED BRACKETS!
				 * A sacrifice the users have to pay, but standards are at some point inevitable!
				 */
				if(strrpos($tmpStr, "(") === false || strrpos($tmpStr, ")") === false)
					return false;
				$pos = strrpos($tmpStr, ")")+1;
			}
			elseif($type == 2)
				$pos = false; //Class .. no parsing needed
			//" "|) found and attributes available?
			if($pos !== false 
		    && (self::DiagramHasAttribute($str, $type) !== false || strpos($tmpStr, '=') !== false)) 
				$tmpName = substr($tmpStr, 0, $pos);
			else 
				$tmpName = $tmpStr;
			$validName = false;
			switch($type) {
				case 0: 
					$validName = self::validateAttributeName($tmpName);
				break;
				case 1: 
					$validName = self::validateMethodName($tmpName);
				break;
				case 2: 
					$validName = self::validateClassName($tmpName);
				break;
			}
			if($validName)
				$name = $tmpName;
			return trim($name);
		}
		/** 
		 * @category Parse parameters from method prototype
		 * @param    $name
		 * @return   string
		 */
		public static function ParseParameterFromName($name) {
			if(!self::validateMethodName($name) || !(strrpos($name, ')') && strpos($name, '(')))
				return false;
			//Empty parameter list
			if(!(strrpos($name, ')') - (strpos($name, '(') + 1)))
				return '';
			//Actual parsing process
			else 
				return $ret = substr($name
						            ,strpos($name, '(') + 1
						            ,strrpos($name, ')') - (strpos($name, '(') + 1));
		}
		/**
		 * @category Parse parents of an class
		 * @param   $str
		 */
		public static function DiagramHasParent($str) {
			$str    = str_replace(" ", "", $str);
			$tmparr = explode("=>", $str);
			if(count($tmparr) !== 2)
				return false;
			$arr = explode(":", $tmparr[1]);
			if(count($arr) !== 2)
				return false;
			return array($arr[0] => array($arr[1], true));
		}
		/**
		 * @category Parse interfaces of an class
		 * @param    $str
		 */
		public static function DiagramHasInterfaces($str) {
			$str    = str_replace(" ", "", $str);
			$tmparr = explode("=>", $str);
			if(count($tmparr) !== 2) 
				return false;
			$res = array();
			if(strpos($tmparr[1], ",") !== false) {
				$elements = explode(",", $tmparr[1]);
				foreach($elements as $elem) {
					$arr = explode(":", $elem);
					if(\utils\Arrays::in_array_r($arr[1], $res)) //Duplication check
						return false;
					$res[] = array($arr[0] => array($arr[1], true));
				}
			}
			else {
				$arr = explode(":", $tmparr[1]);
				if(\utils\Arrays::in_array_r($arr[1], $res)) //Duplication check
					return false;
				$res[] = array($arr[0] => array($arr[1], true));
			}
			return $res;
		}
		/**
		 * @category Detectors
		 * @param    $str
		 */
		public static function DetectClass($str) {
			if(strpos($str, "<<".constant('__CLASS__').">>") !== false)
				return true;
			else
				return false;
		}
		public static function DetectInterface($str) {
			if(strpos($str, "<<".constant('__INTERFACE__').">>") !== false)
				return true;
			else
				return false;
		}
		public static function DetectClassName($str) {
			if(	  strpos($str, constant('__IMPLEMENTS__')) 	=== false 
			   && strpos($str, constant('__EXTENDS__')) 	=== false
			   && strpos($str, "{") 						=== false 
			   && strpos($str, "<<")						=== false)
				return true;
			else
				return false;
		}
		public static function DetectClassAttribute($str) {
			if(strpos($str, "{") !== false)
				return true;
			else
				return false;
		}
		public static function DetectClassParent($str) {
			if(strpos($str, constant('__EXTENDS__')) !== false)
				return true;
			else
				return false;
		}
		public static function DetectClassInterfaces($str) {
			if(strpos($str, constant('__IMPLEMENTS__')) !== false)
				return true;
			else
				return false;
		}
		/**
		 * @category Attr-Array Validator
		 * @return   bool
		 * @param    $arr
		 * @param    $type
		 */
		public static function validateAllAttributes($arr, $type) {
			$return = true;
			foreach($arr as $attr) {
				if(self::validateAttribute($attr, $type) === false)
					$return = false;
			}
			return $return;
		}
		/**
		 * @category Parse const value
		 * @param    $str
		 * @return   evaluated const value or boolean
		 */
		public static function parseConstVal($str) {
			if(($staPos = strpos($str, '=')) !== false
			&& ($endPos = strpos($str, '{')) !== false) {
				$val = '$const = '.ltrim(rtrim(substr($str
						                      ,$staPos + 1
						                      ,$endPos - ($staPos + 1))));
				if(!is_array($val = \utils\String::validateParamStr($val)))
					return false;
				return ($val['$const'] == null)
				         ? ""
				         : $val['$const'];
			} 
			return false;
		}
		/**
		 * @category Parse member value
		 * @param    $str
		 * @return   evaluated member value or boolean
		 */
		public static function parseMemberVal($str) {
			if(($staPos = strpos($str, '=')) !== false) {
				$val = '$member = '.ltrim(rtrim(substr($str
						                              ,$staPos + 1)));
				if(!is_array($val = \utils\String::validateParamStr($val)))
					return false;
				return ($val['$member'] == null)
				         ? ""
				         : $val['$member'];
			}
			return false;
		}
		/**
		 * @category Evaluate param array
		 * @param    param array
		 * @return   evaluated param array
		 */
		public static function evaluateParamArray(array $paramarr) {
			$evalarr = array();
			$keys    = array_keys($paramarr);
			for($i = 0; $i < count($keys); $i++) {
				if($paramarr[$keys[$i]] !== null) 
					eval('$evalarr[$keys[$i]] = '.$paramarr[$keys[$i]].';');
				else
					$evalarr[$keys[$i]] = $paramarr[$keys[$i]];
			}
			return $evalarr;
		}
		/**
		 * @category Return true if the class is an interface otherwise false
		 * @param    $file
		 * @param    $class
		 * @return   bool
		 */
		public static function classIsInterface($file, $class) {
			$tokens      = token_get_all(file_get_contents($file));
			$detectClass = true;
			$isInterface = false;
			//Parse via Token
			foreach($tokens as $token) {
				if(is_array($token)) {
					if($token[0] == T_CLASS || $token[0] == T_INTERFACE) {
						$detectClass = true;
					    $isInterface = ($token[0] == T_INTERFACE) ? true : false;
					}
					elseif($detectClass && $token[0] == T_STRING) 
						if($token[1] == $class)
							return $isInterface;
				}
			}
			return false;
		}
	}
