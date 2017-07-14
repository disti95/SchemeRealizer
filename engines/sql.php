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
	namespace engine;
	class sql {
		/**
		 * @author   Michael Watzer
		 * @version  1.0
		 * @since    ?
		 * @category PDO-Class for SQL-Engines
		 */
	    private $INSTANCES = array();
	    private $engine;
	    private $host;
	 	private $database;
	    private $user;
	    private $pass;
	    private $PDO;
	    /**
	     * @category Construct
	     * @throws
	     */
	    public function __construct() {
	    	$numargs = func_num_args(); //Get number of passed arguments
	    	$arglist = func_get_args(); //Get arguments
	    	if($numargs == 1) 
	    		$this->engine = $arglist[0];
	    	elseif($numargs == 4) {
	    		$this->engine = $arglist[0];
	    		$this->host   = $arglist[1];
	    		$this->user   = $arglist[2];
	    		$this->pass   = $arglist[3];
	    	}
	    	else
	    		throw new DatabaseException("sql:Invalid number of arguments in construct!");
	    }
	   /**
	    * @category Setter
	    */
	    public function __destruct() {
	        $this->closeConnection();
	    }
	    /**
	     * @category Opens a Connection for MySQL/MariaDB and SQLite
	     * @param    $database
	     * @throws   DatabaseException
	     */
	    public function openConnection($database) {
	    	//Check PDO for null and if he gives us a valid database
	        if ($this->PDO == null && $database != '') {
	            $this->database = $database;
	            try {
	            	//PDO Constructor for SQLite
	           		if($this->engine == "sqlite") 
	        			$this->PDO = new \PDO($this->engine. ":".$this->database);
	            	//PDO Constructor for MariaDB and MySQL
	            	else {
	            		$dsn  = $this->engine . ':host=' . $this->host;
	           			$dsn .= ';dbname=' . $this->database;
	           			$this->PDO = new \PDO($dsn, $this->user, $this->pass);
	           		}
	           		$this->PDO->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
	           	}
	            catch(\PDOException $e) {
	           		throw new DatabaseException("sql:".$e->getMessage());
	            }
	        }
	    }
	    /**
	     * @category Connection-Closer
	     */
	    public function closeConnection() {
	        if ($this->PDO != null) {
	            unset($this->PDO);
	            $this->PDO = null;
	        }
	    }
	    /**
	     * @category Query-Handler
	     * @param    $sql
	     * @param    array $values
	     * @throws   DatabaseException
	     * @return   PDOStatement
	     */
	    public function prepareAndQuery($sql, array $values) {
	        $s = $this->PDO->prepare($sql);
	        if($s) {
		        foreach ($values as $key => $value) 
		            $s->bindValue($key, $value);
		        if ($s->execute()) 
		            return $s;
	        }
	        else 
	        	throw new DatabaseException("sql:error in statement $sql!");
	        //Get the ErrorInfo to display the Message
	        $errorArray = $s->errorInfo();
	        throw new DatabaseException("sql:".$errorArray[2]);
	    }
	    /**
	     * @category Getter for Connection
	     * @param    $database
	     * @return   multitype:
	     */
	    public function getConnection($database) {
	    	if(strpos($database, "file://") != false) //Get rid of file://
	    		$database = substr($database, 7);
	        if (!isset($this->INSTANCES[$database])) {
	            $databaseConnection = $this->openConnection($database);
	            $this->INSTANCES[$database] = $databaseConnection;
	        }
	        return $this->INSTANCES[$database];
	    }
	    /**
	     * @category Getter
	     */
	    public function getDatabase() {
	    	return $this->database;
	    }
	}
	/**
	 * @category Selfmade Exception
	 * @author   Michael Watzer
	 * @version  1.0
	 * @since    ?
	 */
	class DatabaseException extends \Exception {}
?>
