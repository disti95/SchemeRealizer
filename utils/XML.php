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
	namespace utils; //To avoid name conflicts
	class XML{
		/**
		 * @author   Michael Watzer
		 * @version  1.0
		 * @since    27.07.2016
		 * @category Util to manage the XML-Operations of SchemeRealizer
		 */
		private $xsd;
		private $xmlFile;
		private $cli;
		/**
		 * @category construct with Validation
		 * @param    $xmlFile
		 * @param    $xsd
		 * @param    $cli
		 * @throws   XMLException
		 */
		public function __construct($xmlFile = "../config/config.xml", $xsd = "../config/config.xsd", $cli = false){
			try {
				$this->setXMLFile($xmlFile);
				$this->setXSD($xsd);
				$this->setCLI($cli);
			}
			catch(XMLException $e){
				throw new XMLException($e->getMessage());
			}
		}
		/**
		 * @category setter with Validation
		 * @param    $xmlFile
		 * @throws   XMLException
		 */
		public function setXMLFile($xmlFile){
			$checkList = array("emptyFile"   => $xmlFile
					         ,"existFile"    => array($xmlFile, 1)
					         ,"readableFile" => $xmlFile);
			//Use the GOLDEN-UTIL
			if(($res = File::basicFileValidation($checkList)) !== true)
				throw new XMLException("XML: ".$res);
			$this->xmlFile = $xmlFile;
		}
		/**
		 * @category setter with Validation
		 * @param    $xsd
		 * @throws   XMLException
		 */
		public function setXSD($xsd){
			$checkList = array("emptyFile"=>$xsd, "existFile"=>array($xsd, 1));
			//Use the GOLDEN-UTIL
			if(($res = File::basicFileValidation($checkList)) !== true) 
				throw new XMLException("XML: ".$res);
			$this->xsd = $xsd;
		}
		/**
		 * @category setter with Validation
		 * @param    $cli
		 * @throws   XMLException
		 */
		public function setCLI($cli) {
			if(!is_bool($cli))
				throw new XMLException("XML: CLI has to be a boolean!");
			$this->cli = $cli;
		}
		/**
		 * @category GETTER-AREA
		 * @return   string
		 */
		public function getXSD(){
			return $this->xsd;
		}
		public function getXMLFile() {
			return $this->xmlFile;
		}
		public function getCLI(){
			return $this->cli;
		}
		/**
		 * @category GOLDEN-UTIL which modifies the awful XML-Error to a fancy one
		 * @param    $error
		 * @param    $cli
		 * @return   string
		 */
		public function libxml_display_error($error) {
			$ret = "";
			switch($error->level) {
				case LIBXML_ERR_WARNING: $ret.="Warning: ".$error->code.": ";
				break;
				case LIBXML_ERR_ERROR: $ret.="Error: ".$error->code.": ";
				break;
				case LIBXML_ERR_FATAL: $ret.="Fatal Error: ".$error->code.": ";
				break;
			}
			$ret.=trim($error->message);
			if($error->file) 
				$ret.=" in ".$error->file;
			else
				$ret.=" in ".$error->line;
			return $ret;
		}
		/**
		 * @category GOLDEN-UTIL which returns the fancy XML-Error messages
		 * @return   string
		 */
		public function libxml_display_errors() {
			$errors = libxml_get_errors();
			$ret = "";
			foreach($errors as $error) {
				$ret.=$this->libxml_display_error($error);
				if($error != end($errors)) {
					if($this->getCLI())
						$ret.="\n";
					else
						$ret.="<br />";
				}
			}
			libxml_clear_errors();
			return $ret;
		}
		/**
		 * @category GOLDEN-UTIL which validates the XML-File with its Schema-File
		 * @return   boolean|string
		 */
		public function validateXML() {
			libxml_use_internal_errors(true); //Allow User-based Error-Handling
			$doc = new \DOMDocument();
			$xml = $doc->load($this->getXMLFile());
			if(!$doc->schemaValidate($this->getXSD()))
				return $this->libxml_display_errors();
			return true;
		}
		/**
		 * @category Get value of XML-Node
		 * @param    Node
		 * @param    Depth(optional)
		 * @return   Node value | false 
		 */
		public function getNodeVal($node, $depth = 0) {
			if(($ret = $this->nodeExist($node)) !== true)
				throw new XMLException($ret);
			$doc = new \DOMDocument();
			$doc->load($this->getXMLFile());
			$tag = $doc->getElementsByTagName($node);
			return $tag->item($depth)->nodeValue;
		}
		/**
		 * @category Set value of XML-Node
		 * @param    Node
		 * @param    Value
		 * @param    Depth(optional)
		 * @return   true | error msg
		 */
		public function setNodeVal($node, $val, $depth = 0) {
			if(($ret = $this->nodeExist($node)) !== true)
				return $ret;
			$tmpConfig = $this->getXMLFile().".tmp";
			if(!is_writeable($this->getXMLFile()))
				return "XML: XML-File ".$this->getXMLFile()." is not writeable!";
			if(!copy($this->getXMLFile(), $tmpConfig))
				return "XML: Unable to copy ".$this->getXMLFile()." to ".$tmpConfig."!";
			$oldConfig                    = $this->getXMLFile();
			$this->setXMLFile($tmpConfig);
			$doc                          = new \DOMDocument();
			$doc->load($this->getXMLFile());
			$tag                          = $doc->getElementsByTagName($node);
			$tag->item($depth)->nodeValue = $val;
			$doc->save($this->getXMLFile());
			$ret                          = $this->validateXML();
			$this->setXMLFile($oldConfig);
			if(!$ret) {
				unlink($tmpConf);
				return "XML: ".$ret;
			}
			else 
				if(!rename($tmpConfig, $this->getXMLFile()))
					return "XML: Unable to rename ".$tmpConfig." to ".$this->getXMLFile()."!";
			return true;
		}
		/**
		 * @category Check if the node exist
		 * @param    Node
		 * @return   true | error msg
		 */
		public function nodeExist($node) {
			if(!($ret = $this->validateXML()))
				return "XML: ".$ret;
			$doc = new \DOMDocument();
			$doc->load($this->getXMLFile());
			if(!$doc->getElementsByTagName($node)->length)
				return "XML: Node ".$node." not found in XML-File ".$this->getXMLFile()."!";
			return true;
		}
	}
	/**
	 * 
	 * @author   Michael Watzer
	 * @category Selfmade Exception-Class
	 * @version  1.0
	 * @since    ?
	 */
	class XMLException extends \Exception {}
?>	