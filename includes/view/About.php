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
	 * @author   Christian Dittrich
	 * @version  1.1
	 * @since    19.03.2017
	 * @category View functions for About page
	 */
    /**
	 * @return   String
	 * @category function which holds the html code of the About section
	 */
	function getAbout(){
		$about = <<<ABOUT
			<div id="subContentDIV">
				<h2><b>About.</b></h2>
ABOUT;
		$about .= <<<ABOUT
			<h3><b>Where can I view the source code?</b></h3>
				<h4><a href='https://github.com/watzerm/SchemeRealizer' target='_blank'>View on GitHub!</a></h4>
ABOUT;
		$about .= <<<ABOUT
			<h3><b>What does SchemeRealizer provide?</b></h3>
				<li>Convert your documentation into code and vice versa</li>
				<li>An GUI-based ORM to flush your database into code and vice versa</li>
				<li>In general a translation from one system to another, as long as SchemeRealizer provides it</li>
				<li>An API for third-party projects to use the full functionality of SchemeRealizer</li>
				<li>A clean and technical documentation for developers</li> 
ABOUT;
		$about .= <<<ABOUT
			<h3><b>Which Systems are supported?</b></h3>
				<li>PHP-based classes</li>
				<li>PHP-based interfaces</li>
				<li>UML class diagrams in .txt files</li>
				<li>MySQL</li>
				<li>MariaDB</li>
				<li>SQLite</li>
				<li>MongoDB</li>
ABOUT;
		$about .= <<<ABOUT
			<h3><b>Where is the documentation?</b></h3>
				There are several documentation files in the doc directory.<br />
				<h4><b>Short explanation</b></h4>
					<h5><b>Architecture</b></h5>
						Contains the software architecture of SchemeRealizer.<br />
						SchemeRealizer is based on a simple MVC architecture where JSON-Strings are passed from one page to another.<br />
					<h5><b>NORM</b></h5>
						Which syntax does a class-diagram follow?
					<h5><b>api</b></h5>
						A genereal documentation about the API for third-party projects.<br />
						Furthermore a tech. documentation where members and methods are described.<br />
					<h5><b>gen</b></h5>
						A documentation concerning the validation process for SQL-based engines.<br />
					<h5><b>php</b></h5>
						Documentation concerning the parsing process.<br />
						With basic math and theoretical informatic knowledge you can get a sneak peek on how we parse PHP-based classes.<br />
					<h5><b>structure</b></h5>
						The syntax on how JSON-Strings are build-up in SchemeRealizer.<br />
					<h5><b>utils</b></h5>
						A Javadoc like documentation about the utils-classes, the core of SchemeRealizer.<br />
ABOUT;
		$about .= <<<ABOUT
			<h3><b>License?</b></h3>
				GNU Affero General Public License.<br />
ABOUT;
		$about .= <<<ABOUT
			<h3><b>Who developed SchemeRealizer?</b></h3>
				SchemeRealizer is developed and maintained by two developers in Austria, Vienna.<br />
			<h4><b>Contact?</b></h4>
				<li>Michael Watzer, lead developer(<a href="mailto:michael.watzer@gmx.at">michael.watzer@gmx.at</a>)</li>
				<li>Christian Dittrich, developer (<a href="mailto:christiandittrich@gmx.at">christiandittrich@gmx.at</a>)</li>
ABOUT;
		$about .= "</div>";
		return $about;
    }
