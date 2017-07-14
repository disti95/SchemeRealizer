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
	 * @author Michael Watzer
	 * @version 1.0
	 * @category CLI-Test for the XML-Schema of the config-File
	 * @since 26.07.2016
	 */
	//Include 
	include_once "../../utils/XML.php";
	include_once "../../utils/File.php";
	
	$xsd = "../../config/config.xsd";
	$xmlFile = "../../config/config.xml";
	$xmlInvalid = "../../config/invalidconfig.xml";
	
	try {
		//Schema-Validation
		$xml = new utils\XML($xmlFile, $xsd, true);
		if(($res = $xml->validateXML()) !== true)
			echo $res."\n";
		else 
			echo "Schema-Validation of XML-File ".$xmlFile." OK!\n";
		
		/* 
		 * Should be invalid
		 * 
		//Schema-Validation
		$xml = new utils\XML($xmlInvalid, $xsd, true);
		if(($res = $xml->validateXML()) !== true)
			echo $res."\n";
		else
			echo "Schema-Validation of XML-File ".$xmlInvalid." OK!\n";
		*/
		
	}
	catch(utils\XMLException $e) {
		echo $e->getMessage()."\n";
	}
?>