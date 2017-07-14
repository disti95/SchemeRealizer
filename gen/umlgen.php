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
	use function constants\emptyArguments;
	use function constants\forbiddenAttributes;
	use function constants\constModifier;
	use function constants\setPermission;
	use function constants\classModifier;
	use function constants\forbiddenName;
	use function constants\NParents;
	use function constants\interfaceAttributes;
	use function constants\FinalAndAbstract;
	use function constants\interfaceAccessType;
	use function constants\interfacePrototypeHasAccessType;
	use function constants\interfaceImplements;
	use function constants\noModifier;
	use function constants\noConstVal;
	use function constants\attrParseErr;
																	
	class umlgen{
		/**
		 * @author   Michael Watzer
		 * @since    ?
		 * @version  1.0
		 * @category Generator
		 */
		/*
		 * Build-up: [0] => array(name, key, modifier, selected, array keywords, array extends, array implements, array parameter)
		 * Key:
		 * 1 = Attribute
		   2 = Method
		   3 = Class
		   4 = Interface
		   keywords: static, const, final, abstract
		*/
		private $arr         = array();
		private $isInterface = false;
		private $umlName     = '';
		/**
		 * @category Construct
		 * @param    array $arr
		 * @throws   umlgenException
		 */
		public function __construct(array $arr){
			//Check for emptiness
			if(empty($arr)) 
				throw new umlgenException("umlgen:".emptyArguments(array("arr")));
			$this->arr    = $this->getSelectedElementsFromArray($arr); //Filter the Array
			$classnamearr = \utils\Arrays::extractClassName($this->arr, constant('__UML__'));
			if($classnamearr[0])
				throw new umlgenException("umlgen:".$classnamearr[1]);
			$this->umlName = $classnamearr[1];
			if(!is_bool($tmp = \utils\Arrays::isInterface($this->arr, $this->umlName, constant('__UML__')))) 
				throw new umlgenException("umlgen:".$tmp);
			$this->isInterface = $tmp;
		}
		public function getClass() {
			foreach($this->arr as $elem) {
				$value      = $elem[0];
				$type       = $elem[1];
				$attributes = $elem[4];
				$parent     = $elem[5];
				$interfaces = $elem[6];
				//Check for modifier and type
				if(!$this->checkKey($type)) 
					throw new umlgenException("umlgen:".__FORBIDDEN_KEY__);
				if(!$modifier = $this->getUMLNotation($elem[2])) 
					throw new umlgenException("umlgen:".__FORBIDDEN_MODIFIER__);
				//Check if its an class
				if($type == 3 || $type == 4) {
					/*
					 * Little tricky to find this out.
					 * Keep in mind that getUMLNotation returns true if the modifier is false.
					 * Therefore we have to check if its not true to raise an exception!
					 */
					if(!$modifier) 
						throw new umlgenException("umlgen:".classModifier($value));
					$return = ($this->isInterface) ? "<<interface>>\n" : "<<class>>\n";
					if(!\utils\Parsing::validateClassName($value))
						throw new umlgenException("umgen:".forbiddenName($value));
					$return .= $value;
					if($attributes) {
						if($this->isInterface)
							throw new umlgenException("umlgen:".interfacePrototypeHasAccessType($value));
						if(!\utils\Parsing::validateAllAttributes($attributes, 2))
							throw new umlgenException("umlgen:".forbiddenAttributes($value));
						if(count($attributes) > 1)
							throw new umlgenException("umlgen:".__N_CLASS_ERR__);
						$return .= "\n{".$attributes[0]."}";
					}
					if($parent) {
						if(next($parent))
							throw new umlgenException("umlgen:".NParents($value));
						if(isset($parent[0][1]) && $parent[0][1] && isset($parent[0][0]))
							$return .= "\nextends => /path/to/parent:".$parent[0][0];
					}
					if($interfaces) {
						if($this->isInterface)
							throw new umlgenException("umlgen:".interfaceImplements($value));
						$interfaces  = (array)$interfaces;
						$hasSelected = false;
						$istr        = "";
						for($i = 0; $i < count($interfaces); $i++) {
							if($interfaces[$i][1]) {
								$hasSelected = true;
								$istr       .= " /path/to/interface".($i+1).":".$interfaces[$i][0].",";
							}
						}
						if($hasSelected)
							$return .= "\nimplements =>".substr($istr, 0, -1); //Remove last comma
					}
					return $return;
				}
			}
		}
		/**
		 * @category Getter
		 * @throws   umlgenException
		 * @return   multitype:string
		 */
		public function getAttr() {
			if($this->isInterface) 
				throw new umlgenException("umlgen:".interfaceAttributes($this->umlName));
			$attrArr = array();
			foreach($this->arr as $elem){
				$value      = $elem[0];
				$type       = $elem[1];
				$attributes = $elem[4];
				$isStatic   = false;
				$isConst    = false;
				$defval     = $elem[7];
				//Check for modifier and type
				if(!$this->checkKey($type)) 
					throw new umlgenException("umlgen:".__FORBIDDEN_KEY__);
				if(!$modifier = $this->getUMLNotation($elem[2])) 
					throw new umlgenException("umlgen:".__FORBIDDEN_MODIFIER__);
				//Check if its an Attribute
				if($type == 1) {
					if($attributes != false) {
						if(!\utils\Parsing::validateAllAttributes($attributes, 0))  //Attr validation
							throw new umlgenException("umlgen:".forbiddenAttributes($value));
						if(is_array($attributes)) {
							if(in_array(constant('__CONST__'), $attributes)) {
								if($modifier !== true)
									throw new umlgenException("umlgen:".constModifier($value));
								$isConst = true;
							}
							if(in_array(constant('__STATIC__'), $attributes))
								$isStatic = true;
						}
					}
					if($modifier === true && (!$isStatic && !$isConst))
						throw new umlgenException("umlgen:".noModifier($value));
					if($isConst && $isStatic)
						throw new umlgenException("umlgen:".__N_PROPERTIES_ERR__);
					$val = ($modifier !== true) ? $modifier." ".$value : $value;
					if(!empty($defval) || is_string($defval) || is_array($defval)) {
						$val              .= ' = ';
						$tmpArr['$member'] = $defval;
						$tmpArr            = $tmpArr;
						$defval            = \utils\Arrays::getParamStr($tmpArr);
						if(!is_array($defval = \utils\String::validateParamStr($defval)))
							throw new umlgenException("umlgen:".attrParseErr(($isConst)
									                                           ? 'constant'
									                                           : 'member'));
						$defval = \utils\Arrays::getParamStr($defval);
						$val   .= ltrim(rtrim(substr($defval
								                    ,strpos($defval, '=') + 1)));
					}
					else 
						if($isConst)
							throw new umlgenException("umlgen:".noConstVal($value));
					if($isConst)
						$val .= " {".constant('__CONST__')."}";
					if($isStatic) 
						$val .= " {".constant('__STATIC__')."}";
					$attrArr[] = $val;
				}
			}
			return $attrArr;
		}
		/**
		 * @category Getter
		 * @throws   umlgenException
		 * @return   multitype:string
		 */
		public function getMethod() {
			$methArr = array();
			$classIsAbstract = \utils\Arrays::isClassAbstract($this->arr, constant('__CLASS__'));
			foreach($this->arr as $elem){
				$value      = $elem[0];
				$type       = $elem[1];
				$attributes = $elem[4];
				$isAbstract = false;
				$isFinal    = false;
				//Check for modifier and type
				if(!$this->checkKey($type)) 
					throw new umlgenException("umlgen:".__FORBIDDEN_KEY__);
				if(!$modifier = $this->getUMLNotation($elem[2])) 
					throw new umlgenException("umlgen:".__FORBIDDEN_MODIFIER__);
				//Check if its a Method
				if($type == 2) {
					if($attributes != false) 
						if(!\utils\Parsing::validateAllAttributes($attributes, 1)) //Attr validation
							throw new umlgenException("umlgen:".forbiddenAttributes($value));
					if($modifier !== true) {
						if($this->isInterface)
							throw new umlgenException("umlgen:".interfaceAccessType($value));
						$attrval = $modifier." ".$value;
					}
					else
						$attrval = $value;
					if($elem[7]) {
						$params  = (array) $elem[7];
						if(\utils\Arrays::isAssoc($params)) {
							$paramstr = \utils\Arrays::getParamStr($params);
							$params   = \utils\String::validateParamStr($paramstr);
							if(!is_array($params))
								throw new umlgenException("umlgen:".$params);
								$attrval .= '('.\utils\Arrays::getParamStr($params).')';
						}
					}
					else
						$attrval .= '()';
					if(is_array($attributes)) {
						if(in_array(__FINAL__, $attributes))
							$isFinal = true;
						if(in_array(__ABSTRACT__, $attributes))
							$isAbstract = true;
						if($isAbstract & $isFinal)
							throw new umlgenException("umlgen:".FinalAndAbstract($value));
						if($isAbstract & !$classIsAbstract) //Method = abstract and class not, bad idea!
							throw new umlgenException("umlgen:".__CLASS_NOT_ABSTRACT__);
						$attrval .= " {";
						foreach($attributes as $attr) {
							if($attr != end($attributes))
								$attrval .= $attr.",";
							else
								$attrval .= $attr."}";
						}
					}
					if($this->isInterface && ($isAbstract | $isFinal))
						throw new umlgenException("umlgen:".interfaceAccessType($value));
					$methArr[] = $attrval;
				}
			}
			return $methArr;
		}
		/**
		 * @category Flusher
		 * @param    $file
		 * @throws   umlgenException
		 */
		public function flushFile($file){
			$output = $this->getUMLFileContent();
			//Use one of the GOLDEN-UTILS
			$checkListArray = array("emptyFile"=>$file, "existFile"=>array($file, 0), "extensionCheck"=>array($file, "uml"), "emptyContent"=>$output);
			if(($res = utils\File::basicFileValidation($checkListArray)) !== true)
					throw new umlgenException("umlgen: ".$res);
			switch(utils\File::getExtension($file)){
				case "txt":
					if(($res = utils\File::flushFile($file, $output, constant('__UML__'))) !== true)
						throw new umlgenException("umlgen: $res");
				break;
				case "jpg";
					//Flush the Image
					$this->getImage($output, "jpg", $file);	
				break;
				case "png":
					//Flush the Image
					$this->getImage($output, "png", $file);
				break;
				default:
					throw new umlgenException("umlgen:".__EXTENSION_ERR__);
				break;
			}
			//Now set permission
			if(!utils\File::setPerm($file))
				throw new umlgenException("umlgen:".setPermission($file));
		}
		/**
		 * @category Getter
		 * @return   string
		 */
		public function getUMLFileContent() {
			//Get Class
			$output = $this->getClass()."\n";
			
			$output .= "----------------------------------------\n";
			
			if(!$this->isInterface) 
				//Get the Attr
				if(!empty($attrarr = $this->getAttr()))
					foreach($attrarr as $attr) 
						$output .= $attr."\n";
					
			$output .= "----------------------------------------\n";
			
			//Get the Meth
			if(!empty($metharr = $this->getMethod()))
				foreach($metharr as $meth) 
					$output .= $meth."\n";
				
			return $output;
		}
		/**
		 * @category Getter
		 * @return   string
		 */
		public function getUMLHTMLContent() {
			$output = \utils\String::modifyUMLClassHeader($this->getClass());
			
			$output .= "<br />----------------------------------------<br />";
			
			if(!$this->isInterface) 
				//Get the Attr
				if(!empty($attrarr = $this->getAttr()))
					foreach($attrarr as $attr) 
						if($attr != end($attrarr))
							$output .= $attr."<br />";
						else
							$output .= $attr;
				
			$output .= "<br />----------------------------------------<br />";
			
			//Get the Meth
			if(!empty($metharr = $this->getMethod()))
				foreach($metharr as $meth) 
					if($meth != end($metharr))
						$output .= $meth."<br />";
					else
						$output .= $meth;
			
			return $output;
		}
		/**
		 * @category Getter
		 * @return   multitype:
		 */
		public function getArr() {
			return $this->arr;
		}
		/**
		 * @category Imager
		 * @param    $output
		 * @param    $type(png/jpg)
		 * @param    $file
		 */
		public function getImage($output, $type, $file) {
			$height = 20;
			$width  = 0;
			//Determine the pictures height + width, therefore split on \n and \r
			foreach(preg_split("/((\r?\n)|(\r\n?))/", $output) as $line){
				//Get the temp width of the line and if its bigger than the old set it as the new width
				$widthtemp = (imagefontwidth(5) * strlen($line))+10;
				if($widthtemp > $width) 
					$width = $widthtemp;
				//Add 20 pixels to the height
				$height += 20;
			}
			//Create the Image
			$im = imagecreate($width, $height);
			//Set the Background + Foreground Color(White and Black)
			$bg = imagecolorallocate($im, 255, 255, 255);
			$textcolor = imagecolorallocate($im, 0, 0, 0);
			//Go through all lines and add them to the Image
			$i = 1;
			foreach(preg_split("/((\r?\n)|(\r\n?))/", $output) as $line){
				imagestring($im, 5, 5, $i * 20, $line, $textcolor);
				$i++;
			}
			//Set the Picture and destroy it from the Memory
			if($type == "jpg") 
				imagejpeg($im, $file);
			if($type == "png")
				imagepng($im, $file);
			imagedestroy($im);
		}
		/**
		 * @category Translator
		 * @param    $modifier
		 * @return   string
		 */
		public function getUMLNotation($modifier) {
			switch($modifier) {
				case constant('__PUBLIC__'): 
					return "+";
				case constant('__PRIVATE__'): 
					return "-";
				case constant('__PROTECTED__'): 
					return "#";
				case false: 
					return true;
				default: 
					return false;
			}
		}
		/**
		 * @category Error-Checker
		 * @param    $type
		 * @return   boolean
		 */
		public function checkKey($type) {
			if($type > 4 || $type < 1) 
				return false;
			else
				return true;
		}
		/**
		 * @category Error-Checker
		 * @param    $file
		 */
		public function checkExtension($file) {
			switch(substr($file, -4)) {
				case ".txt": 
					return true;
				case ".jpg":
					return true;
				case ".png": 
					return true;
				default: 
					return false;
			}
		}
		/**
		 * @category Filter
		 * @param    array $arr
		 * @throws   umlgenException
		 * @return   multitype:unknown
		 */
		public function getSelectedElementsFromArray(array $arr) {
			$prepArr = array();
			//Check for emptiness
			if(empty($arr)) 
				throw new umlgenException("umlgen:".emptyArguments(array("arr")));
			foreach($arr as $elem) {
				//Check if the Arr has 8 Elements
				if(count($elem) == 8) {
					if($elem[3])
						$prepArr[] = $elem;
				}
				else 
					throw new umlgenException("umlgen:".__ELEMENT_MISSING_ERR__);
			}
			return $prepArr;
		}
	}
	/**
	 * @author   Michael Watzer
	 * @since    ?
	 * @category Selfmade Exception-Class
	 * @version  1.0
	 */
	class umlgenException extends \Exception{}
?>
