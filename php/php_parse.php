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
	namespace php;
	use function constants\noModifier;
	use function constants\constModifier;
	use function constants\forbiddenName;
	use function constants\validClassName;
	use function constants\emptyArguments;
	use function constants\isNULL;
	use function constants\FinalAndAbstract;
	use function constants\redundantError;
	use function constants\parseParameter;
	use function constants\interfaceAttributes;
	use function constants\interfaceAccessType;
	use function constants\interfaceImplements;
	use function constants\interfacePrototypeHasAccessType;
	use function constants\attrParseErr;
														
	class php_parse {
		/**
		 * @author   Michael Watzer
		 * @since    ?
		 * @version  1.0
		 * @category Introspection based Parser
		 */
		/*
		Build-up: [0] => array(name, key, modifier, selected, array keywords, array extends, array implements, array parameter|const value|member value)
		Key:
		1 = Attribute
		2 = Method
		3 = Class
		4 = Interface
		keywords: static, final, const, abstract
		*/
		private $arr         = array();
		private $rc;
		private $isInterface = false;
		/**
		 * @category Construct
		 * @param    ReflectionClass $rc
		 * @throws   php_parseException
		 */
		public function __construct(\ReflectionClass $rc) {
			//Check for emptiness
			if(empty($rc)) 
				throw new php_parseException("php_parse:".emptyArguments(array("ReflectionClass")));
			if($rc == null)
				throw new php_parseException("php_parse:".isNULL("ReflectionClass"));
			$this->rc          = $rc;
			$this->isInterface = $this->rc->isInterface(); 
		}
		/**
		 * @category Setter
		 * @throws php_parseException
		 */
		public function setAttr() {
			try {
				if($this->getIsInterface())
					throw new php_parseException("php_parse: ".interfaceAttributes($this->rc->getName()));
				//Get Attributes into the Array
				$attributes = $this->rc->getProperties();
				foreach($attributes as $attr) {
					$keywords = false;
					$name     = $attr->getName();
					$value    = false;
					if(!\utils\Parsing::validateAttributeName($name))
						throw new php_parseException("php_parse:".forbiddenName($name));
					if($attr->isStatic())
						 $keywords = array(__STATIC__);
					if($attr->isPublic()) {
						$modifier = __PUBLIC__;
						$value    = $attr->getValue();
					}
					elseif($attr->isProtected())
						$modifier = __PROTECTED__;
					elseif($attr->isPrivate())
						$modifier = __PRIVATE__;
					else
						if(!$attr->isStatic) 
							throw new php_parseException("php_parse:".noModifier($name));
						else 
							$modifier = false;
					$this->arr[] = array($name, 1, $modifier, false, $keywords, false, false, $value);
				}
				//Get Constants into the Array
				$constants = $this->rc->getConstants();
				foreach($constants as $key => $value) {
					$this->arr[] = array($key, 1, false, false, array(__CONST__), false, false, $value);
				}
			}
			catch(ReflectionException $e) {
				throw new php_parseException("php_parse: ".$e->getMessage());
			}
		}
		/**
		 * @category Setter
		 * @throws php_parseException
		 */
		public function setMethods(){
			try {
				//Get Methods into the Array
				$methods = $this->rc->getMethods();
				foreach($methods as $method) {
					/**
					 * The programming language itself ensures that all methods are abstract and public!
					 */
					if(($this->getIsInterface()) 
					&& ($method->isFinal()
					 || $method->isPrivate() 
					 || $method->isProtected()))
						throw new php_parseException("php_parse: ".interfaceAccessType($method->getName()));
					$modifier   = false;
					$attributes = array();
					if($method->isStatic()) 
						$attributes[] = __STATIC__;
					if(!$this->getIsInterface()) {
						if($method->isFinal() || $method->isAbstract()) {
							if($method->isFinal())
								$attributes[] = __FINAL__;
							elseif($method->isAbstract()) {
								if(!$this->rc->isAbstract()) //Method = abstract and class not, bad idea!
									throw new php_parseException("php_parse:".__CLASS_NOT_ABSTRACT__);
								$attributes[] = __ABSTRACT__;
							}
						}
						if($method->isProtected()) 
							$modifier = __PROTECTED__;
						elseif($method->isPrivate())
							$modifier = __PRIVATE__;
					}
					if(!$modifier)
						$modifier  = ($method->isPublic()) ? __PUBLIC__ : false;
					$parameter = false;
					//Validate name
					$name = $method->getName();
					if(!\utils\Parsing::validateMethodName($name))
						throw new php_parseException("php_parse:".forbiddenName($name));
					//Parse Parameter
					foreach($method->getParameters() as $para) {
                        			$paramname = "";
						if($para->isArray())
							$paramname .= "array ";
						if($para->isCallable())
							$paramname .= "callable ";
						if($para->getClass() != "")
							$paramname .= $para->getClass()->getName()." ";
						$paramname .= "$".$para->getName();
						//Extract default value
						$parameter[$paramname] = $para->isDefaultValueAvailable() 
								                   ? $para->getDefaultValue()
								                   : null;
					}
					$this->arr[] = array($name
                                        ,2
                                        ,$modifier
                                        ,false
                                        ,empty($attributes) ? false : $attributes
                                        ,false
                                        ,false
                                        ,$parameter);
				}
			}	
			catch(ReflectionException $e) {
				throw new php_parseException("php_parse: ".$e->getMessage());
			}
		}
		/**
		 * @category Setter
		 * @throws php_parseException
		 */
		public function setClass(){
			try {
				if(!\utils\Parsing::validateClassName($this->rc->getName()))
					throw new php_parseException("php_parse: ".forbiddenName($this->rc->getName()));
				$keywords   = array();
				$interfaces = array();
				$parents    = array();
				if(!$this->getIsInterface()) {
					if($this->rc->isAbstract())
						$keywords[] = __ABSTRACT__;
					elseif($this->rc->isFinal()) 
						$keywords[] = __FINAL__;
					$rc = $this->rc;
					while($parent = $rc->getParentClass()) {
						if(!empty($parents) && in_array(array($parent->getName(), true), $parents))
							throw new php_parseException("php_parse: ".redundantError($parent->getName(), "Parent"));
						$parents[] = array($parent->getName(), true);
						$rc = $parent;
					}
				}
				/**
				 * ReflectionClass returns the extended interface as an interface, instead of a class 
                                 */
				foreach($this->rc->getInterfaceNames() as $iface) {
					if(!empty($interfaces) && in_array(array($iface, true), $interfaces))
						throw new php_parseException("php_parse: ".redundantError($iface, "Interface"));
					if($this->getIsInterface())
						$parents[] = array($iface, true);
					else
						$interfaces = array($iface, true);
				}
				$this->arr[] = array($this->rc->getName()
						            ,($this->getIsInterface()) ? 4 : 3
						            ,false
						            ,true
						            ,empty($keywords)   ? false : $keywords
						            ,empty($parents)    ? false : $parents
						            ,empty($interfaces) ? false : $interfaces 
						            ,false);
			}
			catch(ReflectionException $e) {
				throw new php_parseException("php_parse: ".$e->getMessage());
			}
		}
		/**
		 * @category Getter
		 * @return multitype:
		 */
		public function getArr() {
			return $this->arr;
		}
		/**
		 * @category Getter
		 * @return   bool
		 */
		public function getIsInterface() {
			return $this->isInterface;
		}
	}
	class php_parse_token{
		/**
		 * @author   Michael Watzer
		 * @version  1.0
		 * @since    20.08.2016
		 * @category Token based Parser
		 */
		/*
			Build-up: [0] => array(name, key, modifier, selected, array keywords, array extends, array implements, array parameter|const value|member value)
			Key:
			1 = Attribute
			2 = Method
			3 = Class
			Keywords: const, static, final, abstract
		*/
		/*
		 * Possible parser Token: http://php.net/manual/de/tokens.php
		 */
		private $arr         = array();
		private $file;
		private $class;
		private $isInterface = false;
		/**
		 * @category Construct
		 * @throws   php_parseException
		 */
		public function __construct($file, $class){
			$checkList = array("emptyFile"=>$file, "existFile"=>array($file, 1), "extensionCheck"=>array($file, "class"), "readableFile"=>$file);
			//Basic Checks via utils\File
			if(($res = \utils\File::basicFileValidation($checkList)) !== true) 
				throw new php_parseException("php_parse: $res");
			if(empty($class))
				throw new php_parseException("php_parse:".emptyArguments(array("class")));
			//Does the Class name fit the php.net standard?
			if(!\utils\Parsing::validateClassName($class)) 
				throw new php_parseException("php_parse:".validClassName($class));
			$this->class       = $class;
			$this->file        = $file;
			$this->isInterface = \utils\Parsing::classIsInterface($this->file, $this->class); 
		}
		/**
		 * @category Setter 
		 * @throws   php_parseException
		 */
		public function setAttr() {
			//Get File-Content via High-Level function file_get_contents
			$contentStr  = file_get_contents($this->file);
			$tokens      = token_get_all($contentStr);
			//Declare Indicators
			$classDetect = array(false, false); //0 = class detected 1 = $this->class detected
			$loopcontrol = false;
			$isStatic    = false;
			$isConst     = false;
			$attrToken   = array(false, false);
		    $i           = 0;
			//Go through each Token
			foreach($tokens as $token) {
				//Have we already detect the class?
				if($classDetect[0] && $classDetect[1]) {
					if($this->getIsInterface())
						throw new php_parseException("php_parse:".interfaceAttributes($this->class));
					if(is_array($token)) {
						//New class detected?
						if($token[0] == T_CLASS || $token[0] == T_INTERFACE) {
							$loopcontrol = true;
							continue;
						}
						if($loopcontrol) 
							break; //New class detected + array means to break out!
						//Const?
						if($token[0] == T_CONST) {
							$isConst   = true;
							$attrToken = array(false, true);
						}
						//Static?
						if($token[0] == T_STATIC) {
							$isStatic     = true;
							$attrToken[1] = true; //static members do not necessarily need a modfifier
						}
						//private, public or protected?
						if($token[0] == T_PRIVATE || $token[0] == T_PROTECTED || $token[0] == T_PUBLIC) 
							$attrToken = array($token[1], true);
						//If the function-Statement follows after the modifier or const, reset the indicators
						elseif($attrToken[1] && $token[0] == T_FUNCTION) {
							$attrToken = array(false, false);
							$isConst   = false;
							$isStatic  = false;
						}
						//Modifier or Const and Variable ? Sounds good
						elseif(($attrToken[1] && $token[0] == T_VARIABLE) || ($isConst && $token[0] == T_STRING)) {
							$val  = false;
							$name = $token[1];
							$j    = $i;
							//Get rid of whitespaces
							while(is_array($tokens[++$j]) && $tokens[$j][0] == 379);
							if($tokens[$j++] == '=') {
								for($n = $j; $tokens[$n] != end($tokens); $n++) 
									if($tokens[$n] == ';') {
										$val = true;
										break;
									}
								if(!$val)
									throw new php_parseException("php_parse:".attrParseErr(($isConst) 
											                                                 ? 'constant' 
											                                                 : 'member'));
								$val = "";
								while(is_array($tokens[++$j]) && $tokens[$j][0] == 379);
								while($arr = $tokens[$j++]) 
									if(is_array($arr))
										$val .= $arr[1];
									elseif($arr == ';')
										break;
									else
										$val .= $arr;
								$val = '$member = '.$val;
								if(!is_array($val = \utils\String::validateParamStr($val)))
									throw new php_parseException("php_parse:".attrParseErr(($isConst) 
											                                                 ? 'constant' 
											                                                 : 'member'));
								else 
									$val = ($val['$member'] == null)
								             ? ""
								             : $val['$member'];
							}
							else
								if($isConst)
									throw new php_parseException("php_parse:".__CONST_ASSIGNMENT__);
							$name = (!$isConst)
							          ? \utils\String::RemoveFirstLetter($name)
							          : $name;
							if(!\utils\Parsing::validateAttributeName($name))
								throw new php_parseException("php_parse:".forbiddenName($name));
							if($isStatic && $isConst)
								throw new php_parseException("php_parse:".__N_PROPERTIES_ERR__);
							if($isConst && $attrToken[0] !== false)
								throw new php_parseException("php_parse:".constModifier($name));
							if((!$isConst && !$isStatic) && $attrToken[0] === false)
								throw new php_parseException("php_parse:".noModifier($name));
							$attr = false;
							if($isConst xor $isStatic) {
								if($isStatic)
									$attr = array(__STATIC__);
								else 
									$attr = array(__CONST__);
							}
							$this->arr[] = array(trim($name), 1, $attrToken[0], false, $attr, false, false, $val);
							$attrToken   = array(false, false);
							$isStatic    = false;
							$isConst     = false;
							$name        = "";
						}
					}
				}
				else {
					//Searching for the class
					if(is_array($token)) {
						if($token[0] == T_CLASS) 
							$classDetect[0] = true;
						elseif($classDetect[0] && $token[0] == T_STRING) 
							if($token[1] == $this->class) 
								$classDetect[1] = true;
					}
				}
				$loopcontrol = false; //Loop break out
				$i++;
			}
		}
		/**
		 * @category Setter
		 * @throws php_parseException
		 */
		public function setMethods() {
			//Get File-Content via High-Level function via file_get_contents
			$contentStr = file_get_contents($this->file);
			$tokens     = token_get_all($contentStr);
			//Declare Indicators
			$classDetect     = array(false, false); //0 = class detected 1 = $this->class detected
			$loopcontrol     = false;
			$isFunction      = false;
			$attributes      = array();
			$params          = false;
			$modifier        = array(false, false);
			$classIsAbstract = false;
			$i               = 0;
			$name            = "";
			$openbracket     = 0;
			$closebracket    = 0;
			//Go through each Token
			foreach($tokens as $token) {
				//Have we already detect the class?
				if($classDetect[0] && $classDetect[1]) {
					if(is_array($token)) {
						//New class detected?
						if($token[0] == T_CLASS || $token[0] == T_INTERFACE) {
							$loopcontrol = true;
							continue;
						}
						if($loopcontrol) break; //New class detected + array means to break out!
						if($token[0] == T_PRIVATE || $token[0] == T_PROTECTED || $token[0] == T_PUBLIC) 
							$modifier = array($token[1], true);
						elseif($token[0] == T_STATIC) 
							$attributes[] = __STATIC__;
						elseif($token[0] == T_FINAL) 
							$attributes[] = __FINAL__;
						elseif($token[0] == T_ABSTRACT) 
							$attributes[] = __ABSTRACT__;
						elseif($token[0] == T_FUNCTION) 
							$isFunction = true;
						//A Variable can only be set in the parameter list, otherwise we call it an Attribute
						elseif(!$isFunction && $token[0] == T_VARIABLE) {
							//Reset indicators
							$modfier    = array(false, false);
							$isFunction = false;
							$params     = false;
							$attributes = array();
							$name       = "";
						}
						elseif(($isFunction && $token[0] == T_STRING) || $params) 
							$name .= $token[1];
					}
					//A String can only follow after the function-Statement
					elseif($isFunction && is_string($token)) {
						if($this->getIsInterface()
					    && (in_array(__ABSTRACT__, $attributes)
						||  in_array(__FINAL__   , $attributes)
						||  $modifier[0] == __PRIVATE__
                        ||  $modifier[0] == __PROTECTED__)) 
							throw new php_parseException("php_parse:".interfaceAccessType($name)); 
						//Parameter indicator
						if($token == "(" && (++$openbracket)) {
							$params = true;
							$name  .= $token;
						} 
						//EO-Function indicator
						elseif($token == ")" && ($openbracket === (++$closebracket))) {
							$name .= $token;
							//Validation
							if(!\utils\Parsing::validateMethodName($name))
								throw new php_parseException("php_parse:".forbiddenName($name));
							if(count($attributes) == 0)
								$attributes = false;
							if($attributes !== false && (in_array(__ABSTRACT__, $attributes) && in_array(__FINAL__, $attributes)))
								throw new php_parseException("php_parse:".FinalAndAbstract($name));
							if($attributes !== false && (in_array(__ABSTRACT__, $attributes) && !$classIsAbstract)) //Method = abstract and class not, bad idea!
								throw new php_parseException("php_parse:".__CLASS_NOT_ABSTRACT__);
							if(($parameter = \utils\Parsing::ParseParameterFromName($name)) === false) 
								throw new php_parseException("php_parse:".parseParameter($name));
							if(strlen($parameter)) {
								$parameter = \utils\String::validateParamStr($parameter);
								if(!is_array($parameter))
									throw new php_parseException("php_parse:".$parameter);
							}
							else
								$parameter = false;
							$name         = substr($name, 0, strpos($name, "("));
							$this->arr[]  = array(trim($name)
                                                 ,2
                                                 ,(!$modifier[0]) ? __PUBLIC__ : $modifier[0]
                                                 ,false
                                                 ,$attributes
                                                 ,false
                                                 ,false
                                                 ,$parameter);
							//Reset indicators
							$modfier      = array(false, false);
							$isFunction   = false;
							$params       = false;
							$attributes   = array();
							$name         = "";
							$openbracket  = 0;
							$closebracket = 0;
						}
						elseif($params) 
							$name .= $token;
					}
				}
				else {
					//Searching for the class
					if(is_array($token)) {
						if($token[0] == T_CLASS || $token[0] == T_INTERFACE)
							$classDetect[0] = true;
						elseif($classDetect[0] && $token[0] == T_STRING) {
							if($token[1] == $this->class) {
								if($tokens[$i-4][0] == T_ABSTRACT) //For abstract dependency validation
									$classIsAbstract = true;
								$classDetect[1] = true;
							}
						}
					}
				}
				$loopcontrol = false; //Loop break out!
				$i++; //To remember the current round
			}
		}
		public function setClass() {
			//Get File-Content via High-Level function via file_get_contents
			$contentStr = file_get_contents($this->file);
			$tokens     = token_get_all($contentStr);
			//Declare Indicators
			$classDetect   = array(false, false); //0 = class detected 1 = $this->class detected
			$i             = 0;
			$hasParents    = false;
			$hasInterfaces = false;
			$attention     = false;
			$attributes    = array();
			$interfaces    = array();
			$parents       = array();
			$name          = "";
			foreach($tokens as $token) {
				//Searching for the class or attribute
				if(is_array($token) && $token[0] !== 379) { //379 equals an empty str
					if($classDetect[0] && $classDetect[1]){
						if($hasInterfaces) {
							if($token[0] == T_STRING) {
								if(!empty($interfaces) && in_array(array($token[1], true), $interfaces))
									throw new php_parseException("php_parse: ".redundantError($token[1], "Interface"));
								$interfaces[] = array($token[1], true);
							}
							else
								if(!$attention)
									$hasInterfaces = false;
						}
						if($hasParents) {
							if($token[0] == T_STRING) {
								if(!empty($interfaces))
									throw new php_parseException("php_parse: ".constant('__EXTENDS_AFTER_IMPLEMENTS__'));
								if(!empty($parents) && !$this->getIsInterface())
									throw new php_parseException("php_parse: ".constant('__MULTIPLE_SUPERCLASSES__'));
								if(!empty($parents) && in_array(array($token[1], true), $parents))
									throw new php_parseException("php_parse: ".redundantError($token[1], "Parent"));
								$parents[] = array($token[1], true);
							}
							else
								$hasParents = false;
						}
						if($token[0] == T_IMPLEMENTS)
							$hasInterfaces = true;
						if($token[0] == T_EXTENDS)
							$hasParents = true;
					}
					if($token[0] == T_CLASS || $token[0] == T_INTERFACE)
						$classDetect[0] = true;
					if($classDetect[0] && $token[0] == T_STRING) {
						if($token[1] == $this->class) {
							if($tokens[$i-4][0] == T_ABSTRACT) //$token[$i-4] ... very hard to find out
								$attributes[] = __ABSTRACT__;
							elseif($tokens[$i-4][0] == T_FINAL)
								$attributes[] = __FINAL__;
							$name           = $token[1];
							$classDetect[1] = true;
						}
					}
				}
				elseif($token == "," && $classDetect[0] && $classDetect[1])
					$attention = true;
				elseif($token == "{" && $classDetect[0] && $classDetect[1]) {
					if(empty($interfaces))
						$interfaces = false;
					else
						if($this->getIsInterface()) 
							throw new php_parseException("php_parse:".interfaceImplements($name));
					if(empty($parents))
						$parents = false;
					if(empty($attributes))
						$attributes = false;
					else 
						if($this->getIsInterface())
							throw new php_parseException("php_parse:".interfacePrototypeHasAccessType(trim($name)));	
					$this->arr[] = array(trim($name)
                                        ,($this->getIsInterface()) ? 4 : 3
                                        ,false
                                        ,true
                                        ,$attributes
                                        ,$parents
                                        ,$interfaces
                                        ,false);
					break;				
				}
				$i++;
			}
		}
		/**
		 * @category Getter
		 * @return multitype:
		 */
		public function getArr() {
			return $this->arr;
		}
		/**
		 * @category Getter
		 * @return   bool
		 */
		public function getIsInterface() {
			return $this->isInterface;
		}
	}
	/**
	 * @author Michael Watzer
	 * @version 1.0
	 * @since ?
	 * @category Selfmade Exception-Class
	 */
	class php_parseException extends \Exception{}
?>
