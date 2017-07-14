<?php 
	/*
	 SchemeRealizer - A free ORM and Translator for Diagrams, Databases and 
PHP-Classes/Interfaces.
	Copyright (C) 2016  Michael Watzer/Christian Dittrich
	
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU Affero General Public License as published
 by
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
	 * @author   Christian Dittrich
	 * @version  1.0
	 * @since    17.04.2017
	 * @category MongoDB engine for Database Connection
	 */

	namespace engine;	
	class mongo{
		
		private $host;
		private $user;
		private $pwd;
		private $port;
		private $con;
		/**
		 * @category Construct
		 * @throws   MongoException
		 */
		public function __construct() {
			if($this->checkExtension()) {
				$numargs = func_num_args(); //Get number of passed arguments
				$arglist = func_get_args(); //Get arguments
				if($numargs == 3) {
					$this->host = $arglist[0];
					$this->user = $arglist[1];
					$this->pwd  = $arglist[2];
				}
				elseif($numargs == 4) {
					$this->host = $arglist[0];
					$this->user = $arglist[1];
					$this->pwd  = $arglist[2];
					$this->port = $arglist[3];
				}
				else
					throw new mongodbException("mongo: Invalid number of arguments in construct!");
			}
			else 
				throw new mongodbException("mongo: Mongo Extension is Missing!");
		}
		/**
		 * @category destructor
		 */
		public function __destruct() {
			$this->closeConnection();
		}
		/**
		 * @category Connection-Closer
		 */
		public function closeConnection() {
			unset($this->con);
			$this->con = null;
		}
		/**
		 * @category Connection-Opener
		 */

		public function openConnection() {
			try {
				$port      = (empty($this->port))
							   ? ""
							   : ":".$this->port;
				$this->con = new \MongoClient("mongodb://".$this->user.":".$this->pwd."@".$this->host.$port);
			}
			catch(\MongoConnectionException $e) {
				throw new mongodbException("mongodbException:".$e->getMessage());
			}
		}
		/**
		 * @category checks if php has mongo extension
		 */
		public function checkExtension() {
			$extensions = get_loaded_extensions();
			foreach($extensions as $ext)
				if($ext == "mongo")
					return true;
			return false;
		}
		/**
		 * @category Check if an connection is available
		 * @throws   MongoException
		 */
		public function chkConnection() {
			if(get_class($this->con) != "MongoClient")
				throw new mongodbException("mongodbException:".constant('__NO_DB_CON__'));
		}
		/**
		 * @category Get Connection
		 * @return 	 connection
		 */
		public function getConnection() {
			$this->chkConnection();
			return $this->con;
		}
	}
	class mongodbException extends \Exception {}
?>
