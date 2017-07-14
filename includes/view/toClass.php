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
	function getView($type) {
		$viewlabel = ($type == "uml") ? "UML-File:" : "Table:";
		$viewid    = ($type == "uml") ? "umlFile" : "table";
		echo <<<CLVIEW
		<div id="showAttrMeth">
			<div class="form-group">
				<h3><label for="$viewid">$viewlabel</label></h3>
				<select id="$viewid" class="form-control" onchange="setAttrMethPerFile('class');">
				</select>
			</div>
			<hr/>
			<div class="form-group">
				<h3><label for="classsettings">Class Settings:</label></h3>
				<div id="showParentsInterfaces">
					<select id="parentsinterfaces" class="form-control" style="width:30%;" multiple onclick="shiftParentsInterfaces(0)">
					</select>
					<select id="parentsinterfaces_selected" class="form-control" style="width:30%;" multiple onclick="shiftParentsInterfaces(1)">
					</select>
				</div>
				<div id='classAttr'>
				  <div class="radio">
					  <label><input type="radio" id="attribute_final_class" name="attribute_cl" value=3>final</label>
				  </div>
				  <div class="radio">
					  <label><input type="radio" id="attribute_abstract_class" name="attribute_cl" value=4>abstract</label>
				  </div>
				  <div class="radio">
					  <label><input type="radio" id="attribute_none_class" name="attribute_cl" value=5>none</label>
				  </div>
		       </div>
			</div>
			<hr/>
			<div class="form-group">
				<h3><label for="selectAttrMeth">Select Attribute/Method:</label></h3>
				<select id="selectAttrMeth" onchange="setModifierOnSelect();setAttributesOnSelect();setParameterOnSelect();setDefaultValue();setAttrValue()" class="form-control">
				</select>
			</div>
			<div id="showSelectParameter">
				<div class="form-group">
					<h3><label for="selectParameter">Select Parameter:</label></h3>
					<select id="selectParameter" onchange="setDefaultValue()" class="form-control">
					</select>
				</div>
				<div class="form-group">
            		<h3><label for="defaultValue">Default value:</label></h3>
            	    <input type="text" id="defaultValue" class="form-control">
               	</div>
			</div>
			<div id="showAttrValue">
				<div class="form-group">
					<h3><label for="attrValue">Value:</label></h3>
					<input type="text" id="attrValue" class="form-control" value=''>
				</div>
			</div>
			<div id='modifierSelect'>
			  <div class="form-group">
				  <h3><label for="modifier">Modifier:</label></h3>
				  <select id="modifier" class="form-control" onchange="showConst()">
					  <option>private</option>
					  <option>protected</option>
					  <option>public</option>
					  <option>none</option>
				  </select>
			  </div>
			</div>
			<div id="showStaticBtn">
				<div class="radio">
					<label><input type="radio" id="attribute_static_btn" name="attribute" value=1 onClick='showAttrValue(0)'>static</label>
				</div>
			</div>
			<div id="showConst">
				<div class="radio">
					<label><input type="radio" id="attribute_const" name="attribute" value=2 onClick='showAttrValue(1)'>const</label>
				</div>
			</div>
			<div id="showStaticChk">
				<div class="checkbox">
					<label><input type="checkbox" id="attribute_static_chk" name="attribute" value=1>static</label>
				</div>
			</div>
			<div id="showFinalAbstract">
				<div class="radio">
					<label><input type="radio" id="attribute_final" name="attribute" value=3>final</label>
				</div>
				<div class="radio">
					<label><input type="radio" id="attribute_abstract" name="attribute" value=4>abstract</label>
				</div>
				<div class="radio">
					<label><input type="radio" id="attribute_none" name="attribute" value=5>none</label>
				</div>
			</div>
			<button onclick="getClass(0, 0, 0)" id="importUML" class="btn btn-primary">Add!</button>
			<button onclick="getClass(1, 0, 0)" id="importUML" class="btn btn-primary">Remove!</button>
			<button onclick="getClass(0, 0, 1)" id="importUML" class="btn btn-primary">Show All!</button>
			<button onclick="getClass(0, 1, 0)" id="importUML" class="btn btn-primary">Flush!</button>
		</div>
CLVIEW;
	}
?>
