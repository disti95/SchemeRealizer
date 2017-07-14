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
	 * @category View-Control
	 */
	include_once "view/toClass.php";
	include_once "view/ProjectPath.php";
?>
<div id="subContentDIV">
	<h2><b>Class-Diagram to Class.</b></h2>
	<?php 
		getPP("uml", "class");
		getView("uml");
	?>
</div>
<!-- DIV for error messages -->
<div id="errorMessages"></div>
<!-- DIV for the information output -->
<div id="informationDIV"></div>