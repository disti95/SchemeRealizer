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
	/**
	 * @author   Michael Watzer
	 * @version  1.0
	 * @category Unit-test for utils/Directory.php
	 * @since    01.06.2017
	 */
	//Including
	include_once "../../utils/Directory.php";
	include_once "../../native/Validate.php";
	
	if(!native\Validate::checkOS()) {
		echo "Linux OS Required! \n";
		exit(1);
	}
	if(!native\Validate::checkPHPUnit()) {
		echo "PHPUnit is not installed! \n";
		exit(1);
	}
	
	use PHPUnit\Framework\TestCase;
	
	class DirectoryCase extends TestCase{
		/**
		 * @category test static method basicDirectoryValidation
		 */
		public function testBasicDirectoryValidation() {
			$nOk = array('directory' => '/home/doesnotexist');
			$this->assertEquals(is_string(\utils\Directory::basicDirectoryValidation($nOk)), true);
			$ok  = array('directory' => \utils\Directory::HOME()
					    ,'writeable' => true
					    ,'empty'     => true);
			$this->assertEquals(true, \utils\Directory::basicDirectoryValidation($ok));
			$nOk = array('directory' => \utils\Directory::HOME().'/workspace/SchemeRealizer/examples/testdir'
					    ,'empty'     => true
					    ,'writeable' => true);
			$this->assertEquals(is_string(\utils\Directory::basicDirectoryValidation($nOk)), true);
			$ok  = array('directory' => \utils\Directory::HOME().'/workspace/SchemeRealizer/examples/testdir');
			$this->assertEquals(true, \utils\Directory::basicDirectoryValidation($ok));
		}
	}