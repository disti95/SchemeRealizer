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
	use constants\forbiddenName;
	use constants\NParents;
	use constants\classModifier;
	use constants\FinalAndAbstract;
	use \utils as utils;
	use function constants\emptyArguments;
	use function constants\forbiddenAttributes;
	use function constants\constModifier;
	use function constants\classModifier;
	use function constants\forbiddenName;
	use function constants\NParents;
	use function constants\interfaceAttributes;
	use function constants\interfaceAccessType;
	use function constants\interfaceImplements;
	use function constants\interfacePrototypeHasAccessType;
	use function constants\noModifier;
	use function constants\noConstVal;
	use function constants\attrParseErr;
											
	class classgen{
		/**
		 * @author   Michael Watzer
		 * @version  1.0
		 * @since    ?
		 * @category Generator
		 */
		/*
		Build-up: [0] => array(value, key, modifier, select, array keywords, array extends, array implements, array param|const value|member value)
		1 = Attribute
		2 = Getter
		3 = Setter
		4 = Other Methods(f.e UML)
		5 = Class
		6 = Interface
		keywords: static, const, final, abstract
		*/
		private $prepArr = array();
		private $classname;
		private $isInterface;
		/**
		 * @category Construct
		 * @param    array $prepArr
		 * @param    $classname
		 * @throws   classgenException
		 */
		public function __construct(array $prepArr, $classname) {
			//Check for emptiness
			if(empty($classname) && empty($prepArr))
				throw new classgenException("classgen:".emptyArguments(array("classname", "prepArr")));
			if(empty($classname))
				throw new classgenException("classgen:".emptyArguments(array("classname")));
			if(empty($prepArr)) 
				throw new classgenException("classgen:".emptyArguments(array("prepArr")));
			if(!is_bool($tmp = \utils\Arrays::isInterface($prepArr, $classname, constant('__CLASS__'))))
				throw new classgenException("classgen:".$tmp);
			$this->isInterface = $tmp;
			$this->prepArr     = $this->getSelectedElementsFromArr($prepArr);
			$this->classname   = $classname;
		}
		/**
		 * @category Filter
		 * @param    array $arr
		 * @throws   classgenException
		 * @return   multitype:unknown
		 */
		public function getSelectedElementsFromArr(array $arr) {
			$filteredArray = array();
			foreach($arr as $elem) {
				//Key exists?
				if(!array_key_exists(3, $elem))
					throw new classgenException("classgen:".__FILTER_ARRAY_ERR__);
				//Is the element selected?
				if($elem[3]) 
					$filteredArray[] = $elem;
			}
			return $filteredArray;
		}
		/**
		 * @category Getter
		 * @throws   classgenException
		 * @return   string
		 */
		public function getClass() {
			foreach($this->prepArr as $values) {
				$value      = $values[0];
				$type       = $values[1];
				$attribute  = $values[4];
				$parent     = $values[5];
				$interfaces = $values[6];
				$modifier   = $values[2];
				//Check if the modifier, attribute and key is allowed
				if(!$this->checkModifier($modifier)) throw new classgenException("classgen:".__FORBIDDEN_MODIFIER__);
				if(!$this->checkKey($type)) throw new classgenException("classgen:".__FORBIDDEN_KEY__);
				if($type == 5 || $type == 6) { //Whereas 5 = class and 6 = interface
					if($modifier != false) throw new classgenException("classgen:".classModifier($value));
					$return      = "";
					$including   = array();
					$includestr  = "";
					if($attribute != false) {
						if($this->isInterface)
							throw new classgenException("classgen:".interfacePrototypeHasAccessType($value));
						if(!\utils\Parsing::validateAllAttributes($attribute, 2)) //Attr validation
							throw new classgenException("classgen:".forbiddenAttributes($value));
						if(count($attribute) > 1)
							throw new classgenException("classgen:".__N_CLASS_ERR__);
						$return .= $attribute[0]." ";
					}
					if(!\utils\Parsing::validateClassName($value))
						throw new classgenException("classgen:".forbiddenName($value));
					$return .= (($this->isInterface) ? constant('__INTERFACE__') : constant('__CLASS__'))." $value";
					if($parent != false) {
						$parent = (array)$parent;
						$key    = key($parent);
						if(next($parent)) 
							throw new classgenException("classgen:".NParents($value));
						if($parent[$key][1]) {
							$pval = $parent[$key][0];
							if(!in_array($key, $including)) //prevent redeclare error
								$including[] = $key;
							$return .= " ".__EXTENDS__." $pval";	
						}
					}
					if($interfaces != false){
						if($this->isInterface) 
							throw new classgenException("classgen:".interfaceImplements($value));
						$interfaces  = (array)$interfaces;
						$hasSelected = false;
						$istr        = "";
						foreach($interfaces as $iface) {
							$iface    = (array)$iface;
							$key      = key($iface);
							$selected = $iface[$key][1];
							if($selected) {
								$hasSelected = true;
								if(!in_array($key, $including)) //prevent redeclare error
									$including[] = $key;
								$ival  = $iface[$key][0];
								$istr .= " ".$ival.",";
							}
						}
						if($hasSelected) 
							$return .= " ".__IMPLEMENTS__.substr($istr, 0, -1); //Remove last comma
					}
					foreach($including as $inc) 
						$includestr .= __INCLUDE_ONCE__." '$inc';\n";
					return $includestr.$return;
				}
			}
		}
		/**
		 * @category Getter
		 * @throws   classgenException
		 * @return   multitype:string
		 */
		public function getAttributes() {
			if($this->isInterface)
				throw new classgenException("classgen:".interfaceAttributes($this->classname));
			$attribute = array();
			foreach($this->prepArr as $values) {
				$isStatic   = false;
				$isConst    = false;
				$value      = $values[0];
				$type       = $values[1];
				$modifier   = $values[2];
				$attributes = $values[4];
				$defval     = $values[7];
				//Check if the modifier, attribute and key is allowed
				if(!$this->checkModifier($modifier)) throw new classgenException("classgen:".__FORBIDDEN_MODIFIER__);
				if(!$this->checkKey($type)) throw new classgenException("classgen:".__FORBIDDEN_KEY__);
				if($type == 1) {
					if($attributes != false) { 
						if(!\utils\Parsing::validateAllAttributes($attributes, 0)) //Attr validation
							throw new classgenException("classgen:".forbiddenAttributes($value));
					}
					//Attr validation
					if(is_array($attributes)) {
						if(in_array(__STATIC__, $attributes))
							$isStatic = true;
						if(in_array(__CONST__, $attributes)) {
							if($modifier != false)
								throw new classgenException("classgen:".constModifier($value));
							$isConst = true;
						}
					}
					elseif(!$modifier && (!$isConst && !$isStatic))
						throw new classgenException("classgen:".noModifier($value));
					if($isStatic && $isConst)
						throw new classgenException("classgen:".__N_PROPERTIES_ERR__);
					$val = (!$modifier) ? "" : $modifier." ";
					if($isStatic)
						$val .= __STATIC__." ";
					if(!empty($defval) || is_string($defval) || is_array($defval)) {
						$val              .= (($isConst) ? __CONST__." " : '$')."$value = ";
						$tmpArr['$member'] = $defval;
						$defval            = \utils\Arrays::getParamStr($tmpArr);
						if(!is_array($defval = \utils\String::validateParamStr($defval)))
							throw new classgenException("classgen:".attrParseErr(($isConst)
									                                               ? 'constant'
									                                               : 'member'));
						$defval = \utils\Arrays::getParamStr($defval);
						$val   .= ltrim(rtrim(substr($defval
								                    ,strpos($defval, '=') + 1))).';';
					}
					else 
						if($isConst)
							throw new classgenException("classgen:".noConstVal($value));
						else
							$val .= "$".$value.";";
					$attribute[] = $val;
				}
			}
			return $attribute;
		}
		/**
		 * @category Getter
		 * @throws classgenException
		 * @return multitype:string
		 */
		public function getGetter() {
			$getter = array();
			$classIsAbstract = \utils\Arrays::isClassAbstract($this->prepArr, "uml");
			foreach($this->prepArr as $values) {
				$value      = $values[0];
				$type       = $values[1];
				$modifier   = $values[2];
				$attributes = $values[4];
				$parameter  = $values[7];
				$abstract   = false;
				$final      = false;
				//Check if the modifier, key is allowed
				if(!$this->checkModifier($modifier)) throw new classgenException("classgen:".__FORBIDDEN_MODIFIER__);
				if(!$this->checkKey($type)) throw new classgenException("classgen:".__FORBIDDEN_KEY__);
				if($type == 2) {
					if($attributes != false) {
						if(!\utils\Parsing::validateAllAttributes($attributes, 1)) //Attr validation
							throw new classgenException("classgen:".forbiddenAttributes($value));
					}
					if($modifier) {
						if($this->isInterface) 
							throw new classgenException("classgen:".interfaceAccessType($value));
						else 
							$attrval = $modifier." ";
					}
					else 
						$attrval = "";
					if(is_array($attributes)) {
						if(in_array(__FINAL__, $attributes) && in_array(__ABSTRACT__, $attributes))
							throw new classgenException("classgen:".FinalAndAbstract($value));
						if(in_array(__FINAL__, $attributes)) {
							$attrval .= __FINAL__." ";
							$final   = true;
						}
						if(in_array(__STATIC__, $attributes))
							$attrval .= __STATIC__." ";
						if(in_array(__ABSTRACT__, $attributes)) {
							if(!$classIsAbstract) //Method = abstract and class not, bad idea!
								throw new classgenException("classgen:".__CLASS_NOT_ABSTRACT__);
							$attrval .= __ABSTRACT__." ";
							$abstract = true;
						}
					}
					if($this->isInterface && ($abstract | $final)) 
						throw new classgenException("classgen:".interfaceAccessType($value));
					$paramstr = "";
					if($parameter) {
						$parameter = (array) $parameter;
						if(\utils\Arrays::isAssoc($parameter)) {
							$paramstr  = \utils\Arrays::getParamStr($parameter);
							$parameter = \utils\String::validateParamStr($paramstr);
							if(!is_array($parameter))
								throw new classgenException("classgen:".$parameter);
							$paramstr  = \utils\Arrays::getParamStr($parameter);
						}
					}
					$getter[] = $attrval."function get".$this->getNotation($value)."($paramstr)".\utils\String::getGetterBodyStr($value
							                                                                                                    ,($this->isInterface | $abstract));
				}
			}
			return $getter;
		}
		/**
		 * @category Getter
		 * @throws classgenException
		 * @return multitype:string
		 */
		public function getSetter() {
			$setter = array();
			$classIsAbstract = \utils\Arrays::isClassAbstract($this->prepArr, "uml");
			foreach($this->prepArr as $values) {
				$value      = $values[0];
				$type       = $values[1];
				$modifier   = $values[2];
				$attributes = $values[4];
				$parameter  = $values[7];
				$abstract   = false;
				$final      = false;
				//Check if the modifier, key is allowed
				if(!$this->checkModifier($modifier)) throw new classgenException("classgen:".__FORBIDDEN_MODIFIER__);
				if(!$this->checkKey($type)) throw new classgenException("classgen:".__FORBIDDEN_KEY__);
				if($type == 3) {
					if($attributes != false) {
						if(!\utils\Parsing::validateAllAttributes($attributes, 1)) //Attr validation
							throw new classgenException("classgen:".forbiddenAttributes($value));
					}
					if($modifier) {
						if($this->isInterface)
							throw new classgenException("classgen:".interfaceAccessType($value));
						else
							$attrval = $modifier." ";
					}
					else
						$attrval = "";
					if(is_array($attributes)) {
						if(in_array(__FINAL__, $attributes) && in_array(__ABSTRACT__, $attributes))
							throw new classgenException("classgen:".FinalAndAbstract($value));
						if(in_array(__FINAL__, $attributes)) {
							$attrval .= __FINAL__." ";
							$final   = true;
						}
						if(in_array(__STATIC__, $attributes))
							$attrval .= __STATIC__." ";
						if(in_array(__ABSTRACT__, $attributes)) {
							if(!$classIsAbstract) //Method = abstract and class not, bad idea!
								throw new classgenException("classgen:".__CLASS_NOT_ABSTRACT__);
							$attrval .= __ABSTRACT__." ";
							$abstract = true;
						}
					}
					if($this->isInterface && ($abstract | $final))
						throw new classgenException("classgen:".interfaceAccessType($value));
					$paramstr = "";
					if($parameter) {
						$parameter = (array) $parameter;
						if(\utils\Arrays::isAssoc($parameter)) {
							$paramstr  = \utils\Arrays::getParamStr($parameter);
							$parameter = \utils\String::validateParamStr($paramstr);
							if(!is_array($parameter))
								throw new classgenException("classgen:".$parameter);
							$paramstr  = \utils\Arrays::getParamStr($parameter);
						}
					}
					$setter[] = $attrval."function set".$this->getNotation($value)."($paramstr)".\utils\String::getSetterBodyStr($value
					                                                                                                            ,($this->isInterface | $abstract));
				}
			}
			return $setter;
		}
		/**
		 * @category Getter
		 * @throws classgenException
		 * @return multitype:string
		 */
		public function getOthers() {
			$others = array();
			$classIsAbstract = \utils\Arrays::isClassAbstract($this->prepArr, "uml");
			foreach($this->prepArr as $values) {
				$value      = (substr($values[0], -2) == '()') 
				                ? substr($values[0], 0, strlen($values[0]) - 2)
				                : $values[0];
				$type       = $values[1];
				$modifier   = $values[2];
				$attributes = $values[4];
				$parameter  = $values[7];
				$abstract   = false;
				$final      = false;
				//Check if the modifier, key is allowed
				if(!$this->checkModifier($modifier)) throw new classgenException("classgen:".__FORBIDDEN_MODIFIER__);
				if(!$this->checkKey($type)) throw new classgenException("classgen:".__FORBIDDEN_KEY__);	
				if($type == 4) {
					if($attributes != false) {
						if(!\utils\Parsing::validateAllAttributes($attributes, 1)) //Attr validation
							throw new classgenException("classgen:".forbiddenAttributes($value));
					}
					if($modifier) {
						if($this->isInterface) 
							throw new classgenException("classgen:".interfaceAccessType($value));
						else 
							$attrval = $modifier." ";
					}
					else 
						$attrval = "";
					if(is_array($attributes)) {
						if(in_array(__FINAL__, $attributes) && in_array(__ABSTRACT__, $attributes))
							throw new classgenException("classgen:".FinalAndAbstract($value));
						if(in_array(__FINAL__, $attributes)) {
							$attrval .= __FINAL__." ";
							$final   = true;
						}
						if(in_array(__STATIC__, $attributes))
							$attrval .= __STATIC__." ";
						if(in_array(__ABSTRACT__, $attributes)) {
							if(!$classIsAbstract) //Method = abstract and class not, bad idea!
								throw new classgenException("classgen:".__CLASS_NOT_ABSTRACT__);
							$attrval .= __ABSTRACT__." ";
							$abstract = true;
						}
					}
					if($this->isInterface && ($abstract | $final)) 
						throw new classgenException("classgen:".interfaceAccessType($value));
					$paramstr = "";
					if($parameter) {
						$parameter = (array) $parameter;
						if(\utils\Arrays::isAssoc($parameter)) {
							$paramstr  = \utils\Arrays::getParamStr($parameter);
							$parameter = \utils\String::validateParamStr($paramstr);
							if(!is_array($parameter))
								throw new classgenException("classgen:".$parameter);
							$paramstr  = \utils\Arrays::getParamStr($parameter);
						}
					}
					$others[] = $attrval."function $value($paramstr)".\utils\String::getOtherBodyStr($value 
							                                                                        ,($this->isInterface | $abstract));
				}
			}
			return $others;
		}
		/**
		 * @category Getter
		 * @throws classgenException
		 * @return multitype:string
		 */
		public function getPHPFile() {
			$file = "<?php";
			$file .= "\n\n";
			$file .= $this->getClass()." {"; //Keep in mind that this also contains the includes
			$file .= "\n\n";
			if(!$this->isInterface) {
				if(!empty($attr = $this->getAttributes())) {
					foreach($attr as $attributes) 
						$file .= "\t".$attributes."\n";
					$file .= "\n";
				}
			}
			if(!empty($getters = $this->getGetter())) {
				foreach($getters as $getter) 
					$file .= "\t".$getter."\n";
				$file .= "\n";
			}
			if(!empty($setters = $this->getSetter())) {
				foreach($setters as $setter) 
					$file .= "\t".$setter."\n";
				$file .= "\n";
			}
			if(!empty($other = $this->getOthers())) {
				foreach($other as $others) 
					$file .= "\t".$others."\n";
				$file .= "\n";
			}
			$file .= "}";
			$file .= "\n\n";
			$file .= "?>";
			return $file;
		}
		/**
		 * @category Flusher
		 * @param $file
		 * @throws classgenException
		 */
		public function flushFile($file) {
			if(($res = utils\File::flushFile($file, $this->getPHPFile(), "class")) !== true)
				throw new classgenException("classgen:".$res);
		}
		/**
		 * @category String-Modifying
		 * @param unknown_type $attribute
		 * @throws classgenException
		 * @return string
		 */
		public function getNotation($attribute){
			if(!$notation = strtoupper(substr($attribute, 0, 1)).substr($attribute, 1)) {
				throw new classgenException("classgen:".__NOTATION_ERR__);
			}
			return $notation;
		}
		/**
		 * @category Error-Control
		 * @param    $modifier
		 */
		public function checkModifier($modifier) {
			switch($modifier){
				case "public": 
					return true;
				case "protected": 
					return true;
				case "private": 
					return true;
				case false: 
					return true;
				default: 
					return false;
			}
		}
		/**
		 * @category Error-Control
		 * @param    $key
		 * @return   boolean
		 */
		public function checkKey($key) {
			if($key >= 1 && $key <= 6)
				return true;
			else
				return false;
		}
	}
	/**
	 * @author Michael Watzer
	 * @since ?
	 * @version 1.0
	 * @category Selfmade Exception-Class
	 */
	class classgenException extends \Exception{}
?>
