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
	 * @since    10.08.2016
	 * @version  1.1
	 * @category View
	 */
	function getPP($from, $to){
		$projectPath = "";
		try {
			$xml = new \utils\XML(($from == "database" ? "../" : "")."config/config.xml"
					             ,($from == "database" ? "../" : "")."config/config.xsd");
			$projectPath = $xml->getNodeVal("path");
		}
		catch(\utils\XMLException $e) {}
		
		if($to == "class") 
			$mapper = "mapToClass";
		else if($to == "uml") 
			$mapper = "mapToUML";
		else 
			$mapper = "mapToDB";
		echo <<<PP
		<div id="showMap">
			<div class="form-group">
				<h3><label for="projectpath">Project:</label></h3>
				<input type="text" class="form-control" id="projectpath" value="$projectPath" placeholder="/path/to/project"/>
			</div>
			<button onclick="$mapper('$from')" id="$mapper" class="btn btn-primary">Mapping!</button>
		</div>
PP;
	}