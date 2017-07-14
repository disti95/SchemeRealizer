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
	/**
	 * @author   Michael Watzer
	 * @version  1.0
	 * @since    13.07.2017
	 * @category Unit-Test for the Java-Parse
	 */
	//Including
	include_once "../../utils/File.php";
	include_once "../../utils/Directory.php";
	$arr = array("../../net/SocketClient.php"
			    ,"../../native/Validate.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res."\n");

	if(!native\Validate::checkOS()) {
		echo "Linux OS Required! \n";
		exit(1);
	}
	if(!native\Validate::checkPHPUnit()) {
		echo "PHPUnit is not installed! \n";
		exit(1);
	}

	use PHPUnit\Framework\TestCase;
	class JavaParseCase extends TestCase {
		/**
		 * @category test Java-Parse socket
		 */
		public function testJavaParseSocket() {
			$request  = "<?xml version='1.0' encoding='utf-8'?>
						 <JavaParse>
							 <dir>/home/michael/workspace/SchemeRealizer/java/bin/test/java</dir>
							 <class>test.java.Person</class>
						 </JavaParse>";
			$sc       = new \net\SocketClient("localhost", 32727);
			$response = $sc->writeSocket($request);
			$sc->closeSocket();
			echo $response;
		}
	}