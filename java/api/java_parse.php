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
	/**
	 * @author   Michael Watzer
	 * @version  1.0
	 * @since    14.07.2017
	 * @category API to access the Java-Parser
	 */
	 namespace java\api;
	 use function constants\validClassName;
		
	 class java_parse {
	 	private $dir;
	 	private $class;
	 	/**
	 	 * @category construct
	 	 * @param    $dir
	 	 * @param    $class
	 	 */
	 	public function __construct($dir, $class) {
	 		$chkList = array("directory" => $dir
	 				        ,"empty"     => true);
	 		if(($res = \utils\Directory::basicDirectoryValidation($chkList)) !== true)
	 			throw new java_parseException("java_parse:".$res);
	 		if(!\utils\String::chkJavaClassName($class))
	 			throw new java_parseException("java_parse:".validClassName($class));
	 		$this->setClass($class);
	 		$this->setDir($dir);
	 	}
	 	/**
	 	 * @category getter
	 	 * @return   value of member $dir
	 	 */
	 	public function getDir() {
	 		return $this->dir;
	 	}
	 	/**
	 	 * @category getter
	 	 * @return   value of member class
	 	 */
	 	public function getClass() {
	 		return $this->class;
	 	}
	 	/**
	 	 * @category setter
	 	 * @param    $dir
	 	 */
	 	public function setDir($dir) {
	 		$this->dir = $dir;
	 	}
	 	/**
	 	 * @category setter
	 	 * @param    $class
	 	 */
	 	public function setClass($class) {
	 		$this->class = $class;
	 	}
	 	/**
	 	 * @category get XML-Request
	 	 * @return   str
	 	 */
	 	public function getRequest() {
	 		/**
	 		 * SHOULD BY REPLACED WITH utils\xml.php IN THE FUTURE!!!
	 		 */
	 		$dom  = new \DOMDocument("1.0", "utf-8");
	 		$root = $dom->createElement("JavaParse");
	 		$dir  = $dom->createElement("dir",  $this->getDir());
	 		$cl   = $dom->createElement("class",$this->getClass());
	 		$dom->appendChild($root);
	 		$root->appendChild($dir);
	 		$root->appendChild($cl);
	 		return $dom->saveXML();
	 	}
	 	/**
	 	 * @category Presentation-Layer to transform the XML-Response to an SR-Array
	 	 * @return   array $arr
	 	 */
	 	public function getArr() {
	 		$arr = array();
	 		try {
	 			/**
	 			 * MAKE IT CONFIGURABLE VIA THE CONFIG-SYSTEM!!!
	 			 */
	 			$sc       = new \net\SocketClient("localhost", 32727);
	 			$response = $sc->writeSocket($this->getRequest());
	 			$sc->closeSocket();
	 			/**
	 			 * SHOULD BY REPLACED WITH utils\xml.php IN THE FUTURE!!!
	 			 */
	 			$xml = simplexml_load_string($response);
	 			foreach($xml->Class as $class) {
	 				$arr[] = array((string) $class->name
	 						      ,(int)    $class->key
	 						      ,(string) $class->modifier
	 						      ,(boolean)$class->select
	 						      ,(array)(empty($class->keywords))   
	 						         ? false 
	 						         : $class->keywords
	 						      ,(array)(empty($class->parents))    
	 						         ? false 
	 						         : $class->parents
	 						      ,(array)(empty($class->interfaces)) 
	 						         ? false 
	 						         : $class->interfaces
	 						      ,false);
	 			}
	 			foreach($xml->Attribute as $attr) {
	 				$arr[] = array((string) $attr->name
		 						  ,(int)    $attr->key
		 						  ,(string) $attr->modifier
		 						  ,(boolean)$attr->select
		 						  ,(array)(empty($attr->keywords)) 
	 						         ? false 
	 						         : $attr->keywords
		 						  ,false
		 						  ,false
		 						  ,(array)(empty($attr->value))    
	 						         ? false 
	 						         : $attr->value);
	 			}
	 			foreach($xml->Method as $meth) {
	 				$arr[] = array((string) $meth->name
		 						  ,(int)    $meth->key
		 						  ,(string) $meth->modifier
		 						  ,(boolean)$meth->select
		 						  ,(array)(empty($meth->keywords)) 
	 						         ? false 
	 						         : $meth->keywords
		 						  ,false
		 						  ,false 
		 						  ,(array)(empty($meth->value))    
	 						         ? false 
	 						         : $meth->value);
	 			}
	 			return $arr;
	 		}
	 		catch(\net\SocketClientException $e) {
	 			throw new java_parseException("java_parse:".$e->getMessage());
	 		}
	 	}
	 }
	 /**
	  * @author   Michael Watzer
	  * @category Derivated Exception-Class
	  * @since    14.07.2017
	  * @version  1.0
	  */
	 class java_parseException extends \Exception {}