<?php 
	/*
	SchemeRealizer - A free ORM and Translator for Diagrams, Databases and PHP-Classes/Interfaces.
	Copyright (C) 2017  Michael Watzer/Christian Dittrich
	
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
	namespace net;
	
	/**
	 * @author   Michael Watzer
	 * @since    04.07.2017
	 * @version  1.0
	 * @category Socket-Client
	 */
	class SocketClient {
		private $server;
		private $port;
		private $socket;
		
		public function __construct($server, $port) {
			$socket = \socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			if(!$socket)
				throw new SocketClientException("SocketClient: unable to create socket: "
						                       .socket_strerror(socket_last_error()));
			$result = \socket_connect($socket, $server, $port);
			if(!$result)
				throw new SocketClientException("SocketClient: unable to create connection: "
						                       .socket_strerror(socket_last_error()));
			$this->socket = $socket;
			$this->setServer($server);
			$this->setPort($port);
		}
		/**
		 * @category getter for member $server
		 * @return   value of member $server
		 */
		public function getServer() {
			return $this->server;
		}
		/**
		 * @category getter for member $port
		 * @return   value of member $port
		 */
		public function getPort() {
			return $this->port;
		}
		/**
		 * @category setter for member $server
		 * @param    $server
		 */
		public function setPort($port) {
			$this->port = $port;
		}
		/**
		 * @category setter for member $port
		 * @param    $port
		 */
		public function setServer($server) {
			$this->server = $server;
		}
		/**
		 * @category socket writer
		 * @return   response
		 */
		public function writeSocket($msg) {
			$msg      = \preg_replace('/\>\s+/','>',$msg)."\n";
			\socket_write($this->socket, $msg, strlen($msg));
			$response = "";
			while(true) {
				$line      = \socket_read($this->socket, 2048);
				$response .= $line;
				if(strpos($line, "\n") !== false)
					break;
			}
			return $response;
		}
		/**
		 * @category socket close
		 */
		public function closeSocket() {
			\socket_close($this->socket);
		}
	}
	/**
	 * @category Exception-Class for SocketClient
	 * @author   Michael Watzer
	 * @version  1.0
	 * @since    04.07.2017
	 */
	class SocketClientException extends \Exception {}
?>
