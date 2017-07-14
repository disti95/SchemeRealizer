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
	 * @version 1.1
	 * @since 10.08.2016
	 * @category View
	 */
	function getView() {
		echo <<<CONFIG
		<div id="subContentDIV">
			<h2><b>Config.</b></h2>
			<hr>
			<div class="form-group">
					<h3><label for="selectAttrMeth">Select:</label></h3>
					<select id="selectConf" onchange="showConfOnChange()" class="form-control">
						<option value=0>PATH:Class</option>
						<option value=1>PATH:UML</option>
						<option value=2>PATH:SQL</option>
						<option value=3>PARSER:Class</option>
						<option value=4>PROJECT:Directory</option>
						<option value=5>DATABASE:DSN</option>
					</select>
			</div>	
			<!-- default Path for class flushing -->
			<div id="showPathClassConf">
				<div class="form-group">
					<h3><label for="defPathcl">Class Flushing-Directory:</label></h3>
					<input type="text" id="defPathcl" class="form-control" placeholder="/path/to/dir">
				</div>
				<button onclick="setDefaultPath(0)" id="setDefaultPath" class="btn btn-primary">Set Path!</button>
				<button onclick="getDefaultPath(0)" id="getDefaultPath" class="btn btn-primary">Get Path!</button>
			</div>
			<!-- default Path for uml flushing -->
			<div id="showPathUMLConf">
				<div class="form-group">
					<h3><label for="defPathuml">UML Flushing-Directory:</label></h3>
					<input type="text" id="defPathuml" class="form-control" placeholder="/path/to/dir">
				</div>
				<button onclick="setDefaultPath(1)" id="setDefaultPath" class="btn btn-primary">Set Path!</button>
				<button onclick="getDefaultPath(1)" id="getDefaultPath" class="btn btn-primary">Get Path!</button>
			</div>
			<!-- default Path for sql flushing -->
			<div id="showPathSQLConf">
				<div class="form-group">
					<h3><label for="defPathsql">SQL Flushing-Directory:</label></h3>
					<input type="text" id="defPathsql" class="form-control" placeholder="/path/to/dir">
				</div>
				<button onclick="setDefaultPath(2)" id="setDefaultPath" class="btn btn-primary">Set Path!</button>
				<button onclick="getDefaultPath(2)" id="getDefaultPath" class="btn btn-primary">Get Path!</button>
			</div>
			<!-- default Parser for Classes -->
			<div id="showParserClassConf">
				<div class="form-group">
					<h3><label for="defClassParser">Class Parser:</label></h3>
					<select id="defClassParser" class="form-control">
						<option value=0>Reflection</option>
						<option value=1>Token</option>
					</select>
				</div>
				<button onclick="setDefaultParser(0)" id="setDefaultParser" class="btn btn-primary">Set Parser!</button>
				<button onclick="getDefaultParser(0)" id="getDefaultParser" class="btn btn-primary">Get Parser!</button>
			</div>
			<!-- default project path -->
			<div id="showProjectPath">
				<div class="form-group">
					<h3><label for="projectPath">Project Path:</label></h3>
					<input type="text" id="projectPath" class="form-control" placeholder="/path/to/project">
				</div>
				<button onclick="setProjectPath()" id="setProjectPath" class="btn btn-primary">Set Path!</button>
				<button onclick="getProjectPath()" id="getProjectPath" class="btn btn-primary">Get Path!</button>
			</div>
			<!-- SQL-based Database settings -->
			<div id="showDSN">
				<div class="form-group">
					<h3><label for="host">Host:</label></h3>
					<input type="text" class="form-control" id="host" placeholder="127.0.0.1"/>
				</div>
				<div class="form-group">
					<h3><label for="user">User:</label></h3>
					<input type="text" class="form-control" id="user" placeholder="root"/>
				</div>
				<div class="form-group">
					<h3><label for="db">Database:</label></h3>
					<input type="text" class="form-control" id="db" placeholder="MyDatabase"/>
				</div>
				<button onclick="setDSN()" id="setDSN" class="btn btn-primary">Set DSN!</button>
				<button onclick="getDSN()" id="getDSN" class="btn btn-primary">Get DSN!</button>
			</div>
			<!-- Reset -->
			<hr>
			<h3>Reset Config:</h3>
			<button onclick="resetConfig()" style="width:100%;" id="resetSettings" class="btn btn-primary">Reset!</button>
		</div>
		<!-- infoDIV for the information output -->
		<div id="informationDIV"></div>
CONFIG;
	}
?>