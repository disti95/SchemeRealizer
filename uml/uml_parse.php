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
	namespace uml;
	use constants\umlParentParse;

	use function constants\forbiddenAttributes;
	use function constants\noModifier;
	use function constants\constModifier;
	use function constants\umlValidName;
	use function constants\FinalAndAbstract;
	use function constants\forbiddenModifier;
	use function constants\NParents;
	use function constants\umlParentParse;
	use function constants\parseParameter;
	use function constants\interfacePrototypeHasAccessType;
	use function constants\interfaceAttributes;
	use function constants\interfaceAccessType;
	use function constants\interfaceImplements;
use function constants\attrParseErr;
												
	class uml_parse{
		/**
		 * @author   Michael Watzer
		 * @since    ?
		 * @version  1.0
		 * @category Parser
		 */
		/**
		 * Build-up: [0] => array(name, key, modifier, select, array keywords, array extends, array implements, array parameter|const value|member value)
		 * 1 = Attribute
		   2 = Getter
		   3 = Setter
		   4 = Other Methods(f.e UML)
		   5 = Class
		   6 = Interface
		   keywords: static, const, final, abstract
		 */
		private $arr = array();
		private $file;
		/**
		 * @category Construct
		 * @param    $file
		 * @throws   uml_parseException
		 */
		public function __construct($file){
			$checkList = array("emptyFile"=>$file
					          ,"existFile"=>array($file, 1)
							  ,"extensionCheck"=>array($file, "umlTXT")
				              ,"readableFile"=>$file);
			//Basic file validation -> emptiness, existence and so on
			if(($res = \utils\File::basicFileValidation($checkList)) !== true)
				throw new uml_parseException("uml_parse:$res");
			else 
				$this->file = $file;
		}
		/**
		 * @category Setter/Parser
		 * @throws   uml_parseException
		 */
		public function setArr() {
			$delimiterCount = 0; 
			$family         = -1; //0 = class, 1 = interface
			$fileOpen       = fopen($this->file, "r");
			while($line = fgets($fileOpen)) {
				$value       = trim($line);
				$isDelimiter = (\utils\Parsing::classDiagramDelimiter($value)) ? true : false;
				if(!$isDelimiter && !empty($value)) {
					//Whereas 0 = Header(Class/Interface), 1 = Attributes, 2 = Methods
					switch($delimiterCount) {
						case 0:
							if($family === -1)
								if(\utils\Parsing::DetectClass($value))
									$family = 0;
								elseif(\utils\Parsing::DetectInterface($value))
									$family = 1;
								else
									throw new uml_parseException("uml_parse:".__FORBIDDEN_FAMILY__);
							if(\utils\Parsing::DetectClassName($value))
								if(($name = \utils\Parsing::DiagramHasName($value, 2)) === false)
									throw new uml_parseException("uml_parse:".umlValidName($value));
							if(\utils\Parsing::DetectClassInterfaces($value)) {
								if($family == 1)
									throw new uml_parseException("uml_parse:".interfaceImplements($name));
								if(($interfaces = \utils\Parsing::DiagramHasInterfaces($value)) === false)
									throw new uml_parseException("uml_parse:".umlInterfaceParse($name));
							}
							/**
							 * An interface has no access type and implementation!
							 */
							if(\utils\Parsing::DetectClassAttribute($value)) {
								if($family === 1) 
									throw new uml_parseException("uml_parse:".interfacePrototypeHasAccessType($name));
								$attributes = \utils\Parsing::DiagramHasAttribute($value);
								if(is_array($attributes)) {
									if(\utils\Parsing::validateAllAttributes($attributes, 2) === false)
										throw new uml_parseException("uml_parse:".forbiddenAttributes($name));
									if(count($attributes) > 1)
										throw new uml_parseException("uml_parse:".__N_CLASS_ERR__);
								}
							}
							if(\utils\Parsing::DetectClassParent($value)) 
								if(($parent = \utils\Parsing::DiagramHasParent($value)) === false)
									throw new uml_parseException("uml_parse:".umlParentParse($name));
						break;
						case 1:
							/**
							 * Interface and attributes?
							 */
							if($family === 1)
								throw new uml_parseException("uml_parse:".interfaceAttributes($name));
							if($family !== -1) { //Get rid of old sins
								if(!isset($name)) 
									throw new uml_parseException("uml_parse:".__NO_CLASS_NAME__);
								if(!isset($parent))
									$parent = false;
								if(is_array($parent) && next($parent) !== false)
									throw new uml_parseException("uml_parse:".NParents($name));
								if(!isset($attributes))
									$attributes = false;
								if(!isset($interfaces))
									$interfaces = false;
								$this->arr[] = array($name
										            ,5 
										            ,false
										            ,true
										            ,$attributes
										            ,$parent
										            ,$interfaces
										            ,false);
								$family      = -1;
								$isInterface = false;
							}
							$val = false;
							if(($name = \utils\Parsing::DiagramHasName($value, 0)) === false)
								throw new uml_parseException("uml_parse:".umlValidName($value));
							$attributes = \utils\Parsing::DiagramHasAttribute($value);
							$isConst    = false;
							$isStatic   = false;
							if(is_array($attributes)) {
								if(\utils\Parsing::validateAllAttributes($attributes, 0) === false)
									throw new uml_parseException("uml_parse:".forbiddenAttributes($name));
								if(count($attributes) > 1)
									throw new uml_parseException("uml_parse:".__N_PROPERTIES_ERR__);
								if(in_array(__CONST__, $attributes)) 
									$isConst = true;
								if(in_array(__STATIC__, $attributes))
									$isStatic = true;
							}
							if($isConst && !($val = \utils\Parsing::parseConstVal($value))) 
								throw new uml_parseException("uml_parse:".attrParseErr('constant'));
							if(!$isConst)
								$val = \utils\Parsing::parseMemberVal($value);
							if(!$isConst && !\utils\Parsing::validateModifier($value))
								throw new uml_parseException("uml_parse:".forbiddenModifier($value[0]));
							$modifier = \utils\Parsing::DiagramHasModifier($value);
							//modifier missing and no const?
							if($modifier === false && (!$isConst && !$isStatic))
								throw new uml_parseException("uml_parse:".noModifier($name));
							//const and modifier .. bad idea
							if($isConst && $modifier !== false)
								throw new uml_parseException("uml_parse:".constModifier($name));
							$this->arr[] = array($name, 1, $modifier, false, $attributes, false, false, $val);
						break;
						case 2:
							if($family !== -1) { //Get rid of old sins
								if(!isset($name))
									throw new uml_parseException("uml_parse:".__NO_INTERFACE_NAME__);
								if(!isset($interfaces))
									$interfaces = false;
								$this->arr[] = array($name
										            ,6
									  	            ,false
										            ,true
										            ,false
								        		    ,$parent
							        			    ,false
								        		    ,false);
								$family      = -1;
								$isInterface = true;
							}
							if(($name = \utils\Parsing::DiagramHasName($value, 1)) === false)
								throw new uml_parseException("uml_parse:".umlValidName($value));
							if(($parameter = \utils\Parsing::ParseParameterFromName($name)) === false) 
								throw new uml_parseException("uml_parse:".parseParameter($name));
							if(strlen($parameter)) {
								$parameter = \utils\String::validateParamStr($parameter);
								if(!is_array($parameter))
									throw new uml_parseException("uml_parse:".$parameter);
							}
							else
								$parameter = false;
							$name = substr($name, 0, strpos($name, "("));
							/**
							 * An interface has no access type and implementation
							 */
							$attributes = \utils\Parsing::DiagramHasAttribute($value);
							$isAbstract = false;
							$isFinal    = false;
							if(is_array($attributes)) {
								if(\utils\Parsing::validateAllAttributes($attributes, 1) === false)
									throw new uml_parseException("uml_parse:".forbiddenAttributes($name));
								if(count($attributes) > 2)
									throw new uml_parseException("uml_parse:".__N_METHODS_ERR__);
								if(in_array(__FINAL__, $attributes)) //final check
									$isFinal = true;
								if(in_array(__ABSTRACT__, $attributes)) { //Method = abstract and class not, bad idea!
									if(!\utils\Arrays::isClassAbstract($this->arr, "uml"))
										throw new uml_parseException("uml_parse:".__CLASS_NOT_ABSTRACT__);
									$isAbstract = true;
								}
							}
							if($isInterface && ($isAbstract || $isFinal))
								throw new uml_parseException("uml_parse:".interfaceAccessType($name));
							//final and abstract .. bad idea
							if($isAbstract && $isFinal)
								throw new uml_parseException("uml_parse:".FinalAndAbstract($name));
							if(!$isInterface && !\utils\Parsing::validateModifier($value))
								throw new uml_parseException("uml_parse:".forbiddenModifier($value[0]));
							if($isInterface && \utils\Parsing::validateModifier($value))
								throw new uml_parseException("uml_parse:".interfaceAccessType($name));
							$modifier = ($isInterface) ? false : \utils\Parsing::DiagramHasModifier($value);
							$this->arr[] = array($name, 4, $modifier, false, $attributes, false, false, $parameter);
						break;
						default: break; break;
					}
				}
				if($isDelimiter)
					$delimiterCount++;
			}
			/*
			 * For the frontend it is essential that the class is at the end of the Array
			 */
			$classElem = $this->arr[0];
			\array_shift($this->arr);
			\array_push($this->arr, $classElem);
			fclose($fileOpen);
		}
		/**
		 * @category Getter
		 * @return   array
		 */
		public function getArr(){
			return $this->arr;
		}
	}
	/**
	 * @author   Michael Watzer
	 * @version  1.0
	 * @since    ?
	 * @category Derivated Exception
	 */
	class uml_parseException extends \Exception{}
?>
