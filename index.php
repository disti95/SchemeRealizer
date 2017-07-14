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
	 * @since ?
	 * @category Index
	 */
	//Including
	include_once "utils/File.php";
	include_once "utils/Directory.php";
	$arr = array("error/error.php"
			    ,"utils/XML.php"
				,"constants/constants.php");
	if(($res = utils\File::setIncludes($arr)) !== true)
		die($res);
?>

<!DOCTYPE>
<html>
	<head>
		<title>SchemeRealizer</title>
		<!-- Including BootStrap and JQuery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
		<script src="/SchemeRealizer/bs/js/bootstrap.js"></script>
		<link href="/SchemeRealizer/bs/css/bootstrap.css" rel="stylesheet">
		<!-- Include selfmade .css -->
		<link rel="stylesheet" href="/SchemeRealizer/css/index.css">
		<!-- Include selfmade .js -->
		<script src="/SchemeRealizer/js/config.js"></script>
		<script src="/SchemeRealizer/js/def.js"></script>
		<script src="/SchemeRealizer/js/flushing.js"></script>
		<script src="/SchemeRealizer/js/forms.js"></script>
		<script src="/SchemeRealizer/js/lib.js"></script>
		<script src="/SchemeRealizer/js/mapping.js"></script>
		<!-- Inlcude favicon -->
		<link rel="icon" href="/SchemeRealizer/graphics/favicon.png" type="image/x-icon" />
		<!-- Set Meta-Tag for mobile first -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	</head>
<body>
	<!-- Set the BootStrap-NavBar -->
	<nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/SchemeRealizer"><span id="projectcolor">SchemeRealizer</span></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="#"><a href="/SchemeRealizer">Home</a></li>
            <li><a href="/SchemeRealizer/view/about">About</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Functions<span class="caret"></span></a>
              <ul class="dropdown-menu">
 				<li><a href="/SchemeRealizer/view/database2class">Database to Class</a></li>
 				<li><a href="/SchemeRealizer/view/diagram2class">Class-Diagram to Class</a></li>
 				<li><a href="/SchemeRealizer/view/class2database">Class to Database</a></li>
 				<li><a href="/SchemeRealizer/view/diagram2database">Class-Diagram to Database</a></li>
    			<li><a href="/SchemeRealizer/view/class2diagram">Class to Class-Diagram</a></li>
    			<li><a href="/SchemeRealizer/view/database2diagram">Database to Class-Diagram</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="/SchemeRealizer/view/config"><span class="glyphicon glyphicon-cog"></span> Config</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Set the Container, so we can place the Data in it -->
	<div class="container">
		<div id="subcontainer">
			<?php 
				/**
				 * View Control
				 */
				if(isset($_GET["view"])) {
					switch($_GET["view"]){
						case "database2class": 
							include_once "includes/database2class.php";
						break;
						case "diagram2class": 
							include_once "includes/diagram2class.php";
						break;
						case "class2database": 
							include_once "includes/class2database.php";
						break;
						case "diagram2database": 
							include_once "includes/diagram2database.php";
						break;
						case "class2diagram": 
							include_once "includes/class2diagram.php";
						break;
						case "database2diagram": 
							include_once "includes/database2diagram.php";
						break;
						case "config": 
							include_once "includes/config.php";
						break;
						case "about": 
							include_once "includes/about.php";
						break;
						default: 
							echo \error\error::setError("View: ".$_GET["view"]." isn't available!");
						break;
					}
				}
				else
					include_once "includes/getStarted.php";
			?>
		</div>
	</div>
</body>
</html>
