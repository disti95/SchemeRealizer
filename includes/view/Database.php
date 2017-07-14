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
	 * @version  1.1
	 * @since    10.08.2016
	 * @category View
	 */
	function getExportDBStart($type) {
		$projectPath = "";
		try {
			$xml = new \utils\XML("config/config.xml"
				             	 ,"config/config.xsd");
			$projectPath = $xml->getNodeVal("path");
		}
		catch(\utils\XMLException $e) {}
		$viewlabel = ($type == "uml")
		               ? "Class-Diagram"
		               : "Class";
		echo <<<DBVIEW
			<div id="subContentDIV">
				<h2><b>$viewlabel to Database.</b></h2>
				<div id="showMap">
					<!-- Set the Form -->
					<div class="form-group">
						<h3><label for="projectpath">Project:</label></h3>
						<input type="text" class="form-control" name="projectpath" id="projectpath" value="$projectPath" placeholder="path/to/project"/>
					</div>
					<div class="form-group">
						<h3><label for="dbms">DBMS:</label></h3>
						<select id="dbms" class="form-control">
							<option>MySQL</option>
							<option value="MySQL">MariaDB</option>
							<option>SQLite</option>
							<option>MongoDB</option>
						</select>
					</div>
					<button id="mapToDB" onclick="mapToDB('$type')" class="btn btn-primary">Mapping!</button>
				</div>
			<!-- DIV for the particular DBMS -->
			<div id="exportDBMS"></div></div>
			<!-- DIV for error messages -->
			<div id="errorMessages"></div>
			<!-- Output div concerning js -->
			<div id="informationDIV"></div>
DBVIEW;
	}
	
	function getExportMongoDB($type, $engine) {
		$viewlabel = ($type == "class")
		               ? "PHP-File/Class:"
		               : "UML-File:";
		$viewid = ($type == "class")
		            ? "phpFileClass"
		            : "umlFile";
		echo <<<DBVIEW
		<div class="form-group">
			<h3><label for="$viewid">$viewlabel</label></h3>
			<select id="$viewid" class="form-control" onchange="setAttrMethPerFile('sql');showColumn()">
			</select>
		</div>
		<div class="form-group">
			<h3><label for="selaction">Action:</label></h3>
			<select id="selaction" onchange="showMongoView()" class="form-control">
				<option value=1>select DB name</option>
				<option value=2>set Collection parameters</option>
				<option value=3>add Values</option>
			</select>
		</div>
		<div id="dbname">
			<div class="form-group">
				<h3><label for="dbname">Database name:*optional</label></h3>
				<input type="text" id="dbnameval" class="form-control">
			</div>
		</div>
		<div id="colparamdiv">
			<div class="form-group">
				<h3><label for="colparam">Collection parameter:</label></h3>
				<select id="colparam" class="form-control">
				</select>
			</div>
			<div id="colparamdiv" class="form-group">
				<h3><label for="colparamval">Collection parameter value:</label></h3>
				<input type="text" id="colparamval" class="form-control">
			</div>
		</div>
		<div id="addVal">
			<div class="form-group">
				<h3><label for="elem">Column:</label></h3>
				<select id="column" class="form-control" onchange="showColumn()">
				</select>
			</div>
			<div class="form-group">
				<h3><label for="datatype">Data type:</label></h3>
				<select id="datatype" class="form-control" onchange="changeDBMSForm()">
				</select>
			</div>
			<div class="form-group">
				<h3><label for="val">Value:</label></h3>
				<input text="text" class="form-control" id="val"/>
			</div>
			<div class="form-group">
				<h3><label for="datatype">Add to:</label></h3>
				<select id="addto" class="form-control">
				</select>
			</div>
		</div>
		<button onclick="getMONGO(0, 0, 0)" id="exportDB" class="btn btn-primary">Add!</button>
		<button onclick="getMONGO(1, 0, 0)" id="exportDB" class="btn btn-primary">Remove!</button>
		<button onclick="getMONGO(0, 0, 1)" id="exportDB" class="btn btn-primary">Show All!</button>
		<button onclick="getMONGO(0, 1, 0)" id="exportDB" class="btn btn-primary">Flush!</button>
DBVIEW;
	}
	
	function getExportDB($type, $engine) {
		$viewlabel = ($type == "class") 
		               ? "PHP-File/Class:" 
		               : "UML-File:";
		$viewid = ($type == "class") 
		            ? "phpFileClass" 
		            : "umlFile";
        echo <<<DBVIEW
		<div class="form-group">
			<h3><label for="$viewid">$viewlabel</label></h3>
			<select id="$viewid" class="form-control" onchange="setAttrMethPerFile('sql');showColumn()">
			</select>
		</div>
DBVIEW;
        if($engine == "mongodb") {
echo <<<DBVIEW
		<div class="form-group">
			<h3><label for="dbname">Database name:*optional</label></h3>
			<input type="text" id="dbname" class="form-control">
		</div>
		<div class="form-group">
			<h3><label for="colparam">Collection parameter:</label></h3>
			<select id="colparam" class="form-control">
			</select>
		</div>
		<div class="form-group">
			<h3><label for="colparamval">Collection parameter value:</label></h3>
			<input type="text" id="colparamval" class="form-control">
		</div>
DBVIEW;
        }
echo <<<DBVIEW
		<div class="form-group">
			<h3><label for="elem">Column:</label></h3>
			<select id="column" class="form-control" onchange="showColumn()">
			</select>
		</div>
		<div class="form-group">
			<h3><label for="datatype">Data type:</label></h3>
			<select id="datatype" class="form-control" onchange="changeDBMSForm()">
			</select>
		</div>
DBVIEW;
 		if($engine == "mysql") {
		echo'
		<!-- Show it, because first datatype is int! -->
		<div class="form-group" id="showSize">
			<h3><label for="size">Size:</label></h3>
			<input type="text" class="form-control" id="size" placeholder="255 | 65,30"/>
		</div>';
	}
	if($engine != "mongodb") {
		echo <<<DBVIEW
		<div class="form-group">
			<h3><label for="default">Default:</label></h3>
			<input text="text" class="form-control" id="default"/>
		</div>
DBVIEW;
		echo <<<DBVIEW
		<!-- Show it, because first datatype is int! -->
		<div class="form-group" id="showIndex">
			<h3><label for="index">Index:</label></h3>
			<select id="index" class="form-control" onchange="changeDBMSForm()">
				<option value=-1>Non</option>
				<option value=1>Primary</option>
				<option value=2>Unique</option>
DBVIEW;
			if($engine == "mysql")
				echo '<option value=3>Index</option>';
		echo <<<DBVIEW
			</select>
		</div>
		<div class="checkbox">
			<label><input type="checkbox" id="null">Null</label>
		</div>
		<!-- Show it, because first datatype is int! -->
		<div class="checkbox" id="showAI">
			<label><input type="checkbox" id="ai">AUTOINCREMENT</label>
		</div>
DBVIEW;
		}
		else {
			echo <<<DBVIEW
		<div class="form-group">
			<h3><label for="val">Value:</label></h3>
			<input text="text" class="form-control" id="val"/>
		</div>
		<div class="form-group">
			<h3><label for="datatype">Add to:</label></h3>
			<select id="addto" class="form-control">
			</select>
		</div>
DBVIEW;
		}
echo <<<DBVIEW
		<button onclick="getSQL(0, 0, 0)" id="exportDB" class="btn btn-primary">Add!</button>
		<button onclick="getSQL(1, 0, 0)" id="exportDB" class="btn btn-primary">Remove!</button>
		<button onclick="getSQL(0, 0, 1)" id="exportDB" class="btn btn-primary">Show All!</button>
		<button onclick="getSQL(0, 1, 0)" id="exportDB" class="btn btn-primary">Flush!</button>
DBVIEW;
	}
	function getImportDBStart($type) {
		$viewlabel = ($type == "uml") 
		               ? "Class-Diagram" 
		               : "Class";
		$from = ($type == "uml") 
		           ? "diagram" 
		           : "class";
		echo <<<DBVIEW
		<div id="subContentDIV">
			<h2><b>Database to $viewlabel.</b></h2>
			<!-- Set the Form -->
			<div class="form-group" id="showDBMS">
				<h3><label for="dbms">DBMS:</label></h3>
				<select id="dbms" class="form-control" onchange="setDBMSForm('$from')">
					<option>MySQL</option>
					<option value="MySQL">MariaDB</option>
					<option>SQLite</option>
					<option>MongoDB</option>
				</select>
			</div>
			<!-- DIV concerning the DBMS-Form -->
			<div id="DBMSForm"></div>
		</div>
		<!-- DIV for error messages -->
		<div id="errorMessages"></div>
		<!-- Output div concerning js -->
		<div id="informationDIV"></div>
DBVIEW;
	}
	function getImportDB($type, $engine) {
		$mapper = ($type == "uml") 
		            ? "mapToUML" 
		            : "mapToClass";
		if($engine == "mysql" || $engine == "mongodb") {
			try {
				$xml  = new \utils\XML("../config/config.xml"
				               	      ,"../config/config.xsd"); 
				$host = $xml->getNodeVal("host");
				$user = $xml->getNodeVal("user");
				$db   = $xml->getNodeVal("db");
			}
			catch(\utils\XMLException $e) {}
			echo <<<DBVIEW
				<div id="showMap">
					<div class="form-group">
						<h3><label for="host">Host:</label></h3>
						<input type="text" class="form-control" id="host" value='$host' placeholder="127.0.0.1"/>
					</div>
					<div class="form-group">
						<h3><label for="user">User:</label></h3>
						<input type="text" class="form-control" id="user" value='$user' placeholder="root"/>
					</div>
					<div class="form-group">
						<h3><label for="pwd">Password:</label></h3>
						<input type="password" class="form-control" id="pwd" placeholder="********"/>
					</div>
					<div class="form-group">
						<h3><label for="db">Database:</label></h3>
						<input type="text" class="form-control" id="db" value='$db' placeholder="MyDatabase"/>
					</div>
DBVIEW;
			if($engine == "mongodb") {
				echo <<<DBVIEW
					<div class="form-group">
						<h3><label for="db">Port: *optional*</label></h3>
						<input type="text" class="form-control" id="port"/>
					</div>
DBVIEW;
			}
			echo <<<DBVIEW
					<button onclick="$mapper('database')" id="$mapper" class="btn btn-primary">Mapping!</button>
				</div>
DBVIEW;
		}
		else {
			getPP("database", $type);
		}
	}
?>
