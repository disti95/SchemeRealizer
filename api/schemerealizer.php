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
	namespace api;
	
	/**
	 * @category Class to reflect a sql column
	 * @author   Michael Watzer
	 * @version  1.0
	 * @since    25.06.2017
	 */
	class SQLObj implements ISQLObj {
		const NAME     = 0;
		const DATATYPE = 1;
		const SIZE     = 2;
		const INDEX    = 3;
		const NULLABLE = 4;
		const AI       = 5;
		const SELECTED = 6;
		const DEFVAL   = 7;
		
		private $name;
		private $datatype;
		private $size;
		private $index;
		private $nullable;
		private $ai;
		private $selected;
		private $defval;
		private $srerror;
		private $prepArr;
		private $nameOfidx = array("name"
								  ,"datatype"
								  ,"size"
								  ,"index"
								  ,"nullable"
								  ,"ai"
								  ,"selected"
								  ,"defval");
		
		/**
		 * @category construct
		 * @param    array $prepArr
		 */
		public function __construct(array $prepArr) {
			$this->srerror = new SRError();
			for($i = 0; $i <= self::DEFVAL; $i++) {
				if(!isset($prepArr[$i]))
					throw new SQLObjException("SQLObj: ".$this->getErrorMsg(1, $i."(".$this->nameOfidx[$i].")"));
				switch($i) {
					case self::NAME:
						$this->setName($prepArr[$i]);
					break;
					case self::DATATYPE:
						$this->setDatatype($prepArr[$i]);
					break;
					case self::SIZE:
						$this->setSize($prepArr[$i]);
					break;
					case self::INDEX:
						$this->setIndex($prepArr[$i]);
					break;
					case self::NULLABLE:
						$this->setNull($prepArr[$i]);
					break;
					case self::AI:
						$this->setAI($prepArr[$i]);
					break;
					case self::SELECTED:
						$this->setSelected($prepArr[$i]);
					break;
					case self::DEFVAL:
						$this->setDefault($prepArr[$i]);
					break;
				}
			}
			$this->setPrepArr($prepArr);
		}
		/**
		 * @category setter for member $prepArr;
		 * @param    $prepArr
		 */
		public function setPrepArr(array $prepArr) {
			$this->prepArr = $prepArr;
		}
		/**
		 * @category getter for member $prepArr
		 * @return   value of member $prepArr
		 */
		public function getPrepArr() {
			return $this->prepArr;
		}
		/**
		 * @category setter for member $name
		 * @param    $name
		 */
		public function setName($name) {
			$this->name = $name;
		}
		/**
		 * @category getter for member $name
		 * @return   value of member $name
		 */
		public function getName() {
			return $this->name;
		}
		/**
		 * @category setter for member $datatype
		 * @param    $datatype
		 */
		public function setDatatype($datatype) {
			$this->datatype = $datatype;
		}
		/**
		 * @category getter for member $datatype
		 * @param    value of member $datatype
		 */
		public function getDatatype() {
			return $this->datatype;
		}
		/**
		 * @category setter for member $size
		 * @param    $size
		 */
		public function setSize($size) {
			$this->size = $size;
		}
		/**
		 * @category getter for member $size
		 * @return   value of member $size
		 */
		public function getSize() {
			return $this->size;
		}
		/**
		 * @category setter for member $index
		 * @param    $index
		 */
		public function setIndex($index) {
			$this->index = $index;
		}
		/**
		 * @category getter for member $index
		 * @return   value of member $index
		 */
		public function getIndex() {
			return $this->index;
		}
		/**
		 * @category setter for member $null
		 * @param    $null
		 */
		public function setNull($null) {
			$this->nullable = $null;
		}
		/**
		 * @category getter for member $null
		 * @return   value of member $null
		 */
		public function getNull() {
			return $this->nullable;
		}
		/**
		 * @category setter for member $ai
		 * @param    $ai
		 */
		public function setAI($ai) {
			$this->ai = $ai;
		}
		/**
		 * @category getter for member $ai
		 * @return   value of member $ai
		 */
		public function getAI() {
			return $this->ai;
		}
		/**
		 * @category setter for member $selected
		 * @param    $selected
		 */
		public function setSelected($selected) {
			$this->selected = $selected;
		}
		/**
		 * @category getter for member $selected
		 * @return   $value of member selected
		 */
		public function getSelected() {
			return $this->selected;
		}
		/**
		 * @category setter for member $default
		 * @param    $default
		 */
		public function setDefault($default) {
			$this->defval = $default;
		}
		/**
		 * @category getter for member $default
		 * @return   value of member $default
		 */
		public function getDefault() {
			return $this->defval;	
		}
		/**
		 * @category getter for member $srerror
		 * @return   value of member $srerror
		 */
		public function getSRError() {
			return $this->srerror;
		}
		/**
		 * @category get error message
		 * @param    $errcode
		 * @param    $errval (optional)
		 * @return   error message
		 */
		public function getErrorMsg($errcode, $errval = '') {
			$val = (strlen($errval))
			         ? '('.$errval.')'
			         : '';
			switch($errcode) {
				case 1:
					return "array index $errval missing!";
				break;
				default:
					return 'forbidden error code('.$errcode.')';
				break;
			}
		}
	}
	class SQLObjException extends \Exception {}
	/**
	 * @version  1.0
	 * @author   Michael Watzer
	 * @category Class to reflect a table
	 * @since    26.06.2017 
	 */
	class SQLObjList implements ISQLObjList {
		const ON      = 1;
		const OFF     = -1;
		const PRIMARY = 1;
		const UNIQUE  = 2;
		const INDEX   = 3;
		
		private $sqlObjList = array();
		private $prepArr;
		private $srerror;
		
		/**
		 * @category construct
		 * @param    array $prepArr
		 */
		public function __construct(array $prepArr) {
			$this->srerror = new SRError();
			foreach($prepArr as $elem) 
				$this->addSQLObj($elem);
			$this->prepArr = $prepArr;
		}
		/**
		 * @category add a column to the table
		 * @param    array $prepArr
		 */
		public function addSQLObj(array $prepArr) {
			try {
				$sqlObj = new SQLObj($prepArr);
				\array_push($this->sqlObjList, $sqlObj);
			}
			catch(\api\SQLObjException $e) {
				throw new SQLObjListException("SchemeRealizer: ".$e->getMessage());	
			}
		}
		/**
		 * @category getter for member $srerror
		 * @return   value of member $srerror
		 */
		public function getSRError() {
			return $this->srerror;
		}
		/**
		 * @category getter for member $sqlObjList
		 * @return   value of member $sqlObjList
		 */
		public function getSQLObjList() {
			return $this->sqlObjList;
		}
		/**
		 * @category getter for member $prepArr
		 * @return   value of member $prepArr
		 */
		public function getPrepArr() {
			return $this->prepArr;
		}
		/**
		 * @category get error message
		 * @param    $errcode
		 * @param    $errval (optional)
		 * @return   error message
		 */
		public function getErrorMsg($errcode, $errval = '') {
			$val = (strlen($errval))
			         ? '('.$errval.')'
					 : '';
			switch($errcode) {
				
			}
		}
	}
	class SQLObjListException extends \Exception {}
	/**
	 * @version  1.0
	 * @author   Michael Watzer
	 * @category Class to reflect a class
	 * @since    24.06.2017
	 */
	class ClassObjList implements IClassObjList {
		const ATTR       = 1;
		const GETTER     = 2;
		const SETTER     = 3;
		const OTHERMETH  = 4;
		const CLASSES    = 5;
		const INTERFACES = 6;
		
		const UMLMETH    = 2;
		const UMLCLASS   = 3;
		const UMLIFACE   = 4;
		 
		private $classObjList = array();
		private $type;
		private $srerror;
		/**
		 * @category construct
		 * @param    array $prepArr
		 */
		public function __construct(array $prepArr, $type = 'class') {
			$this->srerror = new SRError();
			foreach($prepArr as $elem) 
				$this->addClassObj($elem);
			$this->setType($type);
		}
		/**
		 * @category setter for member $type
		 * @param    $type
		 */
		public function setType($type) {
			if($type != constant('__CLASS__') && $type != constant('__UML__'))
				throw new ClassObjListException("SchemeRealizer: ".$this->getErrorMsg(1, $type));
			$this->type = $type;
		}
		/**
		 * @category getter for member $type
		 * @return   value of member $type;
		 */
		public function getType() {
			return $this->type;
		}
		/**
		 * @category getter for member $srerror
		 * @return   value of member $srerror
		 */
		public function getSRError() {
			return $this->srerror;
		}
		/**
		 * @category add an member/method to the class
		 * @param    array $prepArr
		 */
		public function addClassObj(array $prepArr) {
			try {
				$classObj = new ClassObj($prepArr);
				\array_push($this->classObjList, $classObj);
			}
			catch(ClassObjException $e) {
				throw new ClassObjListException("ClassObjList: ".$e->getMessage());
			}
		}
		/**
		 * @category return ClassObj members
		 * @return   Filtered ClassObj array with members only
		 */
		public function getMembers() {
			$attrObj = array();
			foreach($this->getClassObjList() as $elem)
				if($elem->getKey() == self::ATTR) 
					\array_push($attrObj, $elem);
			return $attrObj;
		}
		/**
		 * @category return ClassObj methods
		 * @return   Filtered ClassObj array with methods only
		 */
		public function getMethods() {
			$methObj = array();
			foreach($this->getClassObjList() as $elem) {
				if($this->getType() == constant('__CLASS__')) {
					if($elem->getKey() == self::GETTER 
					|| $elem->getKey() == self::SETTER
					|| $elem->getKey() == self::OTHERMETH)
						\array_push($methObj, $elem);
				}
				elseif($this->getType() == constant('__UML__')) {
					if($elem->getKey() == self::UMLMETH) 
						\array_push($methObj, $elem);
				}
			}
			return $methObj;
		}
		/**
		 * @category return ClassObj class
		 * @return   Filtered ClassObj array with class only
		 */
		public function getClass() {
			$classObj = array();
			foreach($this->getClassObjList() as $elem) {
				if($this->getType() == constant('__CLASS__') 
			    && $elem->getKey()  == self::CLASSES)
					\array_push($classObj, $elem);
			    elseif($this->getType() == constant('__UML__')
			    	&& $elem->getKey()  == self::UMLCLASS) 
			    	\array_push($classObj, $elem);
			}
			return $classObj;
		}
		/**
		 * @category return ClassObj interface
		 * @return   Filtered ClassObj array with interface only
		 */
		public function getInterface() {
			$interfaceObj = array();
			foreach($this->getClassObjList() as $elem) {
				if($this->getType() == constant('__CLASS__')
				&& $elem->getKey()  == self::INTERFACES)
					\array_push($interfaceObj, $elem);
				elseif($this->getType() == constant('__UML__')
					&& $elem->getKey()  == self::UMLIFACE)
					\array_push($interfaceObj, $elem);
			}
			return $interfaceObj;
		}
		/**
		 * @category getter for member $classObjList
		 * @return   value of member $classObjList
		 */
		public function getClassObjList() {
			return $this->classObjList;
		}
		/**
		 * @category get error message
		 * @param    $errcode
		 * @param    $errval (optional)
		 * @return   error message
		 */
		public function getErrorMsg($errcode, $errval = '') {
			$val = (strlen($errval))
				     ? '('.$errval.')'
					 : '';
			switch($errcode) {
				case 1:
					return "forbidden type '$erroval', allowed values are ".constant('__CLASS__')." and ".constant('__UML__')."!";
				break;
				default:
					return 'forbidden error code('.$errcode.')';
				break;
			}
		}
	}
	class ClassObjListException extends \Exception {}
	/**
	 * @version  1.0
	 * @author   Michael Watzer
	 * @category Class to reflect a member/method
	 * @since    22.06.2017
	 */
	class ClassObj implements IClassObj {	
		const NAME        = 0;
		const KEY         = 1;
		const MODIFIER    = 2;
		const SELECT      = 3;
		const KEYWORDS    = 4;
		const PARENTS     = 5;
		const INTERFACES  = 6;
		const VALUES      = 7;
		
		private $name;
		private $key;
		private $modifier;
		private $select;
		private $keywords;
		private $parents;
		private $interfaces;
		private $values;
		private $prepArr;
		private $srerror;
		private $nameOfidx = array("name"
				                  ,"key"
				                  ,"modifier"
				                  ,"select"
				                  ,"keywords"
				                  ,"parents"
				                  ,"interfaces"
				                  ,"values");
		
		/**
		 * @category construct
		 * @param    array $prepArr
		 */
		public function __construct(array $prepArr) {
			$this->srerror = new SRError();
			for($i = 0; $i <= self::VALUES; $i++) {
				if(!isset($prepArr[$i]))
					throw new ClassObjException("ClassObj: ".$this->getErrorMsg(1, $i."(".$this->nameOfidx[$i].")"));
				switch($i) {
					case self::NAME:
						$this->setName($prepArr[$i]);
					break;
					case self::KEY:
						$this->setKey($prepArr[$i]);
					break;
					case self::MODIFIER:
						$this->setModifier($prepArr[$i]);
					break;
					case self::SELECT:
						$this->setSelected($prepArr[$i]);	
					break;
					case self::KEYWORDS:
						$this->setKeywords($prepArr[$i]);
					break;
					case self::PARENTS:
						$this->setParents($prepArr[$i]);
					break;
					case self::INTERFACES:
						$this->setInterfaces($prepArr[$i]);
					break;
					case self::VALUES:
						$this->setValues($prepArr[$i]);
					break;
				}
			}
			$this->setPrepArr($prepArr);
		}
		/**
		 * @category setter for member $prepArr;
		 * @param    $prepArr
		 */
		public function setPrepArr(array $prepArr) {
			$this->prepArr = $prepArr;
		}
		/**
		 * @category getter for member $prepArr
		 * @return   value of member $prepArr
		 */
		public function getPrepArr() {
			return $this->prepArr;
		}
		/**
		 * @category setter for member $name
		 * @param    $name
		 */
		public function setName($name) {
			$this->name = $name;
		}
		/**
		 * @category getter for member $name
		 * @return   value of member $name
		 */
		public function getName() {
			return $this->name;
		}
		/**
		 * @category setter for member $key
		 * @param    $key
		 */
		public function setKey($key) {
			$this->key = $key;
		}
		/**
		 * @category getter for member $key
		 * @return   value of member $key
		 */
		public function getKey() {
			return $this->key;
		}
		/**
		 * @category setter for member $modifier
		 * @param    $modifier
		 */
		public function setModifier($modifier) {
			$this->modifier = $modifier;
		}
		/**
		 * @category getter for member $modifier
		 * @return   value of member $modifier
		 */
		public function getModifier() {
			return $this->modifier;
		}
		/**
		 * @category setter for member $selected
		 * @param    $selected
		 */
		public function setSelected($selected) {
			$this->selected = $selected;
		}
		/**
		 * @category getter for member $selected
		 * @return   $selected
		 */
		public function getSelected() {
			return $this->selected;
		}
		/**
		 * @category setter for member $keywords
		 * @param    $keywords
		 */
		public function setKeywords($keywords) {
			$this->keywords = $keywords;
		}
		/**
		 * @category getter for member $keywords
		 * @return   value of member $keywords
		 */
		public function getKeywords() {
			return $this->keywords;
		}
		/**
		 * @category setter for member $parents
		 * @param    $parents
		 */
		public function setParents($parents) {
			$this->parents = $parents;
		}
		/**
		 * @category getter for member $parents
		 * @return   value of member $parents
		 */
		public function getParents() {
			return $this->parents;
		}
		/**
		 * @category setter for member $interfaces
		 * @param    $interfaces
		 */
		public function setInterfaces($interfaces) {
			$this->interfaces = $interfaces;
		}
		/**
		 * @category getter for member $interfaces
		 * @return   value of member $interfaces
		 */
		public function getInterfaces() {
			return $this->interfaces;
		}
		/**
		 * @category setter for member $values
		 * @param    $values
		 */
		public function setValues($values) {
			$this->values = $values;
		}
		/**
		 * @category getter for member $values
		 * @return   value of member $values
		 */
		public function getValues() {
			return $this->values;
		}
		/**
		 * @category getter for member $srerror
		 * @return   value of member $srerror
		 */
		public function getSRError() {
			return $this->srerror;
		}
		/**
		 * @category get error message
		 * @param    $errcode
		 * @param    $errval (optional)
		 * @return   error message
		 */
		public function getErrorMsg($errcode, $errval = '') {
			$val = (strlen($errval))
			         ? '('.$errval.')'
					 : '';
			switch($errcode) {
				case 1:
					return "array index $errval missing!";
				break;
				default:
					return 'forbidden error code('.$errcode.')';
				break;
			}
		}
	}
	class ClassObjException extends \Exception{}
	/**
	 * @author   Michael Watzer
	 * @version  1.0
	 * @category SchemeRealizer API for third-party projects
	 * @since    29.05.2017
	 * @see      api/doc/schemerealizer.pdf
	 */
	class SchemeRealizer implements ISchemeRealizer{
		private $ConvertFrom;
		private $ConvertTo;
		private $srerror;
		private $projectPath;
		private $dsn;
		private $engine;
		private $orm;
		private $conId;
		private $file;
		/**
		 * @category Construct
		 * @param    $ConvertFrom
		 * @param    $ConvertTo
		 * @param    $type (optional)
		 * @throws   SchemeRealizerException on false ConvertFrom/ConvertTo value
		 * @return   void
		 */
		public function __construct($ConvertFrom, $ConvertTo, $type = 'cli') {
			$this->srerror = new SRError();
			if(!$this->setConvertFrom($ConvertFrom)) 
				$this->getSRError()->pushError(1, $this->getErrorMsg(1, $ConvertFrom));
			if(!$this->setConvertTo($ConvertTo))
				$this->getSRError()->pushError(2, $this->getErrorMsg(2, $ConvertTo));
			if($this->getSRError()->hasError())
				throw new SchemeRealizerException('SchemeRealizer: '.$this->getSRError()->printErrorStack($type));
		}
		/**
		 * @category Setter for member $ConvertFrom
		 * @param    $ConvertFrom
		 * @return   boolean
		 */
		public function setConvertFrom($ConvertFrom) {
			if($ret = $this->chkSystem($ConvertFrom))
				$this->ConvertFrom = $ConvertFrom;
			return $ret;
		}
		/**
		 * @category Setter for member $ConvertTo
		 * @param    $ConvertTo
		 * @return   boolean
		 */
		public function setConvertTo($ConvertTo) {
			if($ret = $this->chkSystem($ConvertTo))
				$this->ConvertTo = $ConvertTo;
			return $ret;
		}
		/**
		 * @category Getter for member $ConvertFrom
		 * @return   value of member $ConvertFrom
		 */
		public function getConvertFrom() {
			return $this->ConvertFrom;
		}
		/**
		 * @category Getter for member $ConvertTo
		 * @return   value of member $ConvertTo
		 */
		public function getConvertTo() {
			return $this->ConvertTo;
		}
		/**
		 * @category Getter for member $srerror
		 * @return   value of member $srerror
		 */
		public function getSRError() {
			return $this->srerror;
		}
		/**
		 * @category Validator for member $ConvertTo and $ConvertFrom
		 * @param    $sys
		 * @return   boolean
		 */
		public function chkSystem($sys) {
			if($sys == constant('__CLASS__')
			|| $sys == constant('__UML__')
			|| $sys == constant('__MYSQL__')
			|| $sys == constant('__SQLITE__')
			|| $sys == constant('__MONGODB__'))
				return true;
			return false;
		}
		/**
		 * @category Setter for member $projectPath
		 * @param    $projectPath
		 * @throws   SchemeRealizerException on false projectPath value
		 */
		public function setProjectPath($projectPath) {
			$chkList = array('directory' => $projectPath
					        ,'empty'     => true);
			if(($res = \utils\Directory::basicDirectoryValidation($chkList)) !== true)
				throw new SchemeRealizerException('SchemeRealizer: '.$res);
			$this->projectPath = $projectPath;
		}
		/**
		 * @category Getter for member $projectPath
		 * @return   value of member $projectPath
		 */
		public function getProjectPath() {
			return $this->projectPath;
		}
		/**
		 * @category Setter for Database-Connection($dsn)
		 * @param    host  (mysql, mongodb)(mandatory)
		 * @param    user  (mysql, mongodb)(mandatory)
		 * @param    pass  (mysql, mondodb)(mandatory)
		 * @param    port                  (optional)
		 */
		public function setDSN($host = NULL
							  ,$user = NULL
							  ,$pass = NULL
							  ,$port = NULL) {
			if($this->getConId() == 'from')
				$engine = $this->getConvertFrom();
			elseif($this->getConId() == 'to')
				$engine = $this->getConvertTo();
			else
				throw new SchemeRealizerException("SchemeRealizer: ".$this->getErrorMsg(8));
			$this->setEngine($engine);
			if($this->getSRError()->hasError())
				throw new SchemeRealizerException("SchemeRealizer: ".$this->getSRError()->printErrorStack());
			switch($engine) {
				case constant('__MYSQL__'):
					$this->dsn = new \engine\sql($engine
					                            ,$host
					                            ,$user
					                            ,$pass);
				break;
				case constant('__SQLITE__'):
					$this->dsn = new \engine\sql($engine);
				break;
				case constant('__MONGODB__'):
					try {
						$this->dsn = new \engine\mongo($host
													  ,$user
													  ,$pass
													  ,($port == NULL)
													     ? ''
														 : $port);
					}
					catch(\engine\mongodbException $e) {
						throw new SchemeRealizerException("SchemeRealizer: ".$e->getMessage());
					}
				break;
			}
		}
		/**
		 * @category Setter for member $engine
		 * @param    $engine
		 */
		public function setEngine($engine) {
			if(!checkDBMS($engine))
				$this->getSRError()->pushError(3, $this->getErrorMsg(3, $engine));
			$this->engine = $engine;
		}
		/**
		 * @category Getter for member $engine
		 * @return   value of member $engine;
		 */
		public function getEngine() {
			return $this->engine;
		}
		/**
		 * @category Setter for ORM($orm)
		 * @param    $db
		 */
		public function setORM($db) {
			if(empty($this->getEngine()))
				$this->getSRError()->pushError(4, $this->getErrorMsg(4));
			if(empty($this->getDSN()))
				$this->getSRError()->pushError(5, $this->getErrorMsg(5));
			if($this->getSRError()->hasError())
				throw new SchemeRealizerException("SchemeRealizer: ".$this->getSRError()->printErrorStack());
			switch($this->getEngine()) {
				case constant('__MONGODB__'):
					try {
						$orm = new \orm\mongodb_orm($this->getDSN());
						$orm->setDatabase($db);
						$this->orm = $orm;
					}
					catch(\orm\mongodb_ormException $e) {
						throw new SchemeRealizerException("SchemeRealizer: ".$e->getMessage());
					}
				break;
				case constant('__MYSQL__'):
					try {
						$this->getDSN()->getConnection($db);
						$this->orm = new \orm\mysql_orm($this->getDSN());
					}
					catch(\engine\DatabaseException $e) {
						throw new SchemeRealizerException("SchemeRealizer: ".$e->getMessage());
					}
					catch(\orm\mysql_ormException $e) {
						throw new SchemeRealizerException("SchemeRealizer: ".$e->getMessage());
					}
				break;
				case constant('__SQLITE__'):
					try {
						$this->getDSN()->getConnection($db);
						$this->orm = new \orm\sqlite_orm($this->getDSN());
					}
					catch(\engine\DatabaseException $e) {
						throw new SchemeRealizerException("SchemeRealizer :".$e->getMessage());
					}
					catch(\orm\sqlite_ormException $e) {
						throw new SchemeRealizerException("SchemeRealizer: ".$e->getMessage());
					}
				break;
			}
		}
		/**
		 * @category Getter for ORM($orm)
		 * @return   value of member $orm
		 */
		public function getORM() {
			return $this->orm;
		}
		/**
		 * @category Getter for member $dsn
		 * @return   Database-Connection
		 */
		public function getDSN() {
			return $this->dsn;
		}
		/**
		 * @category return tables/collections from database
		 * @return   array with tables/collections
		 */
		public function listDatabaseContent() {
			$content = array();
			if(empty($this->getORM())) 
				$this->getSRError()->pushError(6, $this->getErrorMsg(6));
			switch($this->getEngine()) {
				case constant('__MYSQL__'):
				case constant('__SQLITE__'):
					$this->getORM()->setClass();
					$content = $this->getORM()->getClass();
				break;
				case constant('__MONGODB__'):
					$content = $this->getORM()->getCollections();
				break;
			}
			return $content;
		}
		/**
		 * @category return available classes from project path
		 * @return   array with available classes(array(file => class))
		 */
		public function getAvailableClasses() {
			if($this->getConvertFrom() != constant('__CLASS__')
			&& $this->getConvertFrom() != constant('__UML__')) 
				throw new SchemeRealizerException("SchemeRealizer: ".$this->getErrorMsg(10, $this->getConvertFrom()));
			if(empty($this->getProjectPath())) 
				throw new SchemeRealizerException("SchemeRealizer: ".$this->getErrorMsg(11));
			try {
				$projectdir = new \utils\Directory($this->getProjectPath()
						                          ,$this->getConvertFrom());
			}
			catch(\utils\DirectoryException $e) {
				throw new SchemeRealizerException("SchemeRealizer: ".$e->getMessage());
			}
			$classes = array();
			foreach($projectdir->getFiles() as $file) {
				if($this->getConvertFrom() == constant('__CLASS__'))
					foreach(\utils\File::getClassesFromFile($file) as $class)
						$classes[] = array($file => $class);
				elseif($this->getConvertFrom() == constant('__UML__')) {
					try {
						$umlparser = new \uml\uml_parse($file);
						$umlparser->setArr();
					}
					catch(\uml\uml_parseException $e) {
						throw new SchemeRealizerException("SchemeRealizer: ".$e->getMessage());
					}
					try {
						$classObjList = new ClassObjList($umlparser->getArr());
						foreach($classObjList->getClass() as $class)
							$classes[] = array($file => $class->getName());
					}
					catch(ClassObjListException $e) {
						throw new SchemeRealizerException("SchemeRealizer: ".$e->getMessage());
					}
				}
			}
			return $classes;
		}
		/**
		 * @category setter for connection identifier(from/to)
		 * @param    $conId
		 */
		public function setConId($conId = 'from') {
			if(strtoupper($conId) == 'TO') 
				if(!checkDBMS($this->getConvertTo()))
					throw new SchemeRealizerException("SchemeRealizer: ".$this->getErrorMsg(3, $this->getConvertTo()));
				else
					$this->conId = 'to';
			elseif(strtoupper($conId) == 'FROM')
				if(!checkDBMS($this->getConvertFrom()))
					throw new SchemeRealizerException("SchemeRealizer: ".$this->getErrorMsg(3, $this->getConvertFrom()));
				else 
					$this->conId = 'from';
			else
				throw new SchemeRealizerException("SchemeRealizer: ".$this->getErrorMsg(7, $conId));
		}
		/**
		 * @category getter for member $conId
		 * @return   value of member $conId
		 */
		public function getConId() {
			return $this->conId;
		}
		/**
		 * @category map table/collection to a ClassObjList
		 * @param    $tab_collect
		 * @return   ClassObjList
		 */
		public function mapToClass($tab_collect) {
			if(empty($this->getORM()))
				throw new SchemeRealizerException("SchemeRealizer: ".$this->getErrorMsg(6));
			if(!in_array($tab_collect, $this->listDatabaseContent()))
				throw new SchemeRealizerException("SchemeRealizer: ".$this->getErrorMsg(9, ($this->getEngine() == constant('__MONGODB__')) 
						                                                                     ? 'collection '.$tab_collect
						                                                                     : 'table '     .$tab_collect));
			if($this->getConvertTo() != constant('__CLASS__') 
			&& $this->getConvertTo() != constant('__UML__'))
				throw new SchemeRealizerException("SchemeRealizer: ".$this->getErrorMsg(10, $this->getConvertTo()));
			switch($this->getEngine()) {
				case constant('__SQLITE__'):
					try {
						$this->getORM()->setAttr($tab_collect);
						$classObjList = new ClassObjList(($this->getConvertTo() == constant('__CLASS__'))
								                           ? $this->getORM()->classGenArrNotation()
								                           : $this->getORM()->umlGenArrNotation()
							                             ,$this->getConvertTo());
					}
					catch(\orm\sqlite_ormException $e) {
						throw new SchemeRealizerException("SchemeRealizer: ".$e->getMessage());
					}
				break;
				case constant('__MYSQL__'):
					try {
						$this->getORM()->setAttr($tab_collect);
						$classObjList = new ClassObjList(($this->getConvertTo() == constant('__CLASS__'))
								                           ? $this->getORM()->classGenArrNotation()
								                           : $this->getORM()->umlGenArrNotation()
							                             ,$this->getConvertTo());
					}
					catch(\orm\mysql_ormException $e) {
						throw new SchemeRealizerException("SchemeRealizer: ".$e->getMessage());
					}
				break;
				case constant('__MONGODB__'):
					try {
						$this->getORM()->setCollection($tab_collect);
						$classObjList = new ClassObjList(($this->getConvertTo() == constant('__CLASS__'))
								                           ? $this->getORM()->classGenArrNotation()
								                           : $this->getORM()->umlGenArrNotation()
							                             ,$this->getConvertTo());
					}
					catch(\orm\mongodb_ormException $e) {
						throw new SchemeRealizerException("SchemeRealizer: ".$e->getMessage());
					}
				break;
			}
			return $classObjList;
		}
		/**
		 * @category map collection/class to a SQLObjList
		 * @param    $class_collect
		 * @return   SQLObjList
		 */
		public function mapToSQL($class_collect) {
			if($this->getConvertTo() != constant('__SQLITE__')
			&& $this->getConvertTo() != constant('__MYSQL__')) 
				throw new SchemeRealizerException("SchemeRealizer: ".$this->getErrorMsg(12, $this->getConvertTo()));
			switch($this->getConvertFrom()) {
				case constant('__CLASS__'):
				case constant('__UML__'):
					$file = false;
					foreach($this->getAvailableClasses() as $elem) 
						if(!empty($this->getDistinctFile())) {
							if(key($elem) == $this->getDistinctFile())
								if($elem[key($elem)] == $class_collect)
									$file = $this->getDistinctFile();
						}
						else 
							if($elem[key($elem)] == $class_collect)
								$file = key($elem);
					if(!$file)
						throw new SchemeRealizerException("SchemeRealizer: ".$this->getErrorMsg(9, 'class '.$class_collect));
					if($this->getConvertFrom() == constant('__CLASS__')) {
						$obj = new \php\php_parse_token($file, $class_collect);
						$obj->setAttr();
					}
					else {
						$obj = new \uml\uml_parse($file);
						$obj->setArr();
					}
					$classObjList = new ClassObjList($obj->getArr());
					$sqlObjList   = $this->createDefaultSQLObjList($classObjList->getMembers());
				break;
				case constant('__MONGODB__'):
					if(empty($this->getORM()))
						throw new SchemeRealizerException("SchemeRealizer: ".$this->getErrorMsg(6));
					if(!in_array($class_collect, $this->listDatabaseContent()))
						throw new SchemeRealizerException("SchemeRealizer: ".$this->getErrorMsg(9, 'collection '.$class_collect));
					/*
					 * NOSQlObj, NOSQLObjList missing
					 */
				break;
				default:
					throw new SchemeRealizerException("SchemeRealizer: ".$this->getErrorMsg(13, $this->getConvertFrom()));
				break;
			}
			return $sqlObjList;
		}
		/**
		 * @category create a default SQLObjList
		 * @param    $obj(NOSQLObjList or ClassObjList)
		 * @return   SQLObjList
		 */
		public function createDefaultSQLObjList($obj) {
			$sqlObjArr = array();
			if($this->getConvertTo() == constant('__SQLITE__'))
				$size = \api\SQLObjList::OFF;
			else
				$size = 11;
			foreach($obj as $attr) 
				$sqlObjArr[] = array($attr->getName()
						            ,constant('__SQL_INT__')
						            ,$size
						            ,SQLObjList::OFF
						            ,SQLObjList::OFF
					                ,SQLObjList::OFF
					                ,true
					  	            ,false);
			$sqlObjList = new SQLObjList($sqlObjArr);
			return $sqlObjList;
		}
		/**
		 * @category setter for member $file
		 * @param    $file
		 */
		public function setDistinctFile($file) {
			if(!empty($file)) {
				$chkList = array('emptyFile'    => $file
						        ,'existFile'    => array($file, 1)
						        ,'emptyContent' => $file
						        ,'readableFile' => $file);
				if(($res = \utils\File::basicFileValidation($chkList)) !== true)
					throw new SchemeRealizerException("SchemeRealizer: ".$res);
			}
			$this->file = $file;
		}
		/**
		 * @category getter for member $file
		 * @return   value of member $file
		 */
		public function getDistinctFile() {
			return $this->file;
		}
		/**
		 * @category get SQL-Code from SQLObjList
		 * @param    $sqlObjList
		 * @param    $tablename
		 * @return   SQL-Code
		 */
		public function getSQLCode($sqlObjList, $tablename) {
			if($this->getConvertTo() != constant('__SQLITE__')
			&& $this->getConvertTo() != constant('__MYSQL__'))
				throw new SchemeRealizerException("SchemeRealizer: ".$this->getErrorMsg(12, $this->getConvertTo()));
			try {
				$sqlgen = new \gen\sqlgen($sqlObjList->getPrepArr()
						                 ,$this->getConvertTo()
						                 ,$tablename);
				$sqlCode = $sqlgen->getSQLCode();
			}
			catch(\gen\sqlgenException $e) {
				throw new SchemeRealizerException("SchemeRealizer: ".$e->getMessage());
			}
			return $sqlCode;
		}
		/**
		 * @category get error message
		 * @param    $errcode
		 * @param    $errval (optional)
		 * @return   error message
		 */
		public function getErrorMsg($errcode, $errval = '') {
			$val = (strlen($errval))
                     ? '('.$errval.')'
		             : '';
			switch($errcode) {
				case 1:
					return 'forbidden ConvertFrom value'.$val;
				break;
				case 2:
					return 'forbidden ConvertTo value'.$val;
				break;
				case 3:
					return $val.' is not a database management system!';
				break;
				case 4:
					return 'database engine not set!';
				break;
				case 5:
					return 'database connection not set!';
				break;
				case 6:
					return 'orm not set!';
				break;
				case 7:
					return 'forbidden connection id, allowed values are from/to!';
				break;
				case 8:
					return 'connection identifier not set!';
				break;
				case 9:
					return $errval.' not found!';
				break;
				case 10:
					return $errval.' is not a class-based type!';
				break;
				case 11:
					return 'project path not set!';
				break;
				case 12:
					return $errval.' is not a sql-based database management system!';
				break;
				case 13:
					return 'unable to convert '.$errval.' to SQLObjList!';
				break;
				default:
					return 'forbidden error code('.$errcode.')';
				break;
			}
		}
	}
	class SchemeRealizerException extends \Exception {}