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
 * @version  1.0
 * @since    29.07.2016
 * @category JS-Library which contains the Functions concerning the Config-Area.
 */

/**
 * @category Function to set the default flushing path
 * @param    0 = Class, 1 = UML, 2 = SQL
 */
function setDefaultPath(method) {
	var infoDIV       = document.getElementById("informationDIV");
	infoDIV.innerHTML = "";
	var path          = "";
	switch(method) {
		case 0: path = document.getElementById("defPathcl").value;
		break;
		case 1: path = document.getElementById("defPathuml").value;
		break;
		case 2: path = document.getElementById("defPathsql").value;
		break;
		default: 
			infoDIV.innerHTML = htmlError("Invalid Path indicator!");
			return;
		break;
	}
	$.post("/SchemeRealizer/req/config.php", { path: path, method : method })
	.done(function(data) {
		if(!isJSON(data)) {
			infoDIV.innerHTML = htmlError("JSON-Format Error!");
			return;
		}
		if(hasError(data)) 
			setError(data, infoDIV);
		else {
			var obj = JSON.parse(data);
			infoDIV.innerHTML += obj[0].val;	
		}
	});
}
/**
 * @category Function to return the default flushing path
 * @param    0 = Class, 1 = UML, 2 = SQL
 */
function getDefaultPath(method) {
	var infoDIV       = document.getElementById("informationDIV");
	infoDIV.innerHTML = "";
	if(method < 0 && method > 2) {
		infoDIV.innerHTML = htmlError("Invalid Path indicator!");
		return;
	}

	$.post("/SchemeRealizer/req/config.php", { getpath: 1, method : method })
	.done(function(data) {
		if(!isJSON(data)) {
			infoDIV.innerHTML = htmlError("JSON-Format Error!");
			return;
		}
		if(hasError(data)) 
			setError(data, infoDIV);
		else {
			var obj = JSON.parse(data);
			infoDIV.innerHTML += obj[0].val;	
		}
	});
}
/**
 * @category Function to reset the config.xml file
 */
function resetConfig() {
	var infoDIV       = document.getElementById("informationDIV");
	infoDIV.innerHTML = "";
	$.post("/SchemeRealizer/req/config.php", { reset : 1 })
	.done(function(data) {
		if(!isJSON(data)) {
			infoDIV.innerHTML = htmlError("JSON-Format Error!");
			return;
		}
		if(hasError(data)) 
			setError(data, infoDIV);
		else {
			var obj = JSON.parse(data);
			infoDIV.innerHTML += obj[0].val;	
		}	
	});
}
/**
 * @category Function to set the default Parser
 * @param    0 = Class, 1 = UML, 2 = SQL
 */
function setDefaultParser(method) {
	var infoDIV       = document.getElementById("informationDIV");
	var parser        = "";
	infoDIV.innerHTML = "";
	switch(method) {
		case 0: 
			parser = document.getElementById("defClassParser").value; 
		break;
		default:
			infoDIV.innerHTML = htmlError("Invalid Parser indicator!");
			return;
		break;
	}
	$.post("/SchemeRealizer/req/config.php", { method: method, parser: parser })
	.done(function(data){
		if(!isJSON(data)) {
			infoDIV.innerHTML = htmlError("JSON-Format Error!");
			return;
		}
		if(hasError(data)) 
			setError(data, infoDIV);
		else {
			var obj = JSON.parse(data);
			infoDIV.innerHTML += obj[0].val;	
		}	
	});
}
/**
 * @category Function to get the default Parser
 * @param    0 = Class, 1 = UML, 2 = SQL
 */
function getDefaultParser(method) {
	var infoDIV       = document.getElementById("informationDIV");
	infoDIV.innerHTML = "";
	if(method < 0 && method > 2) {
		infoDIV.innerHTML = htmlError("Invalid Parser indicator!");
		return;
	}
	$.post("/SchemeRealizer/req/config.php", { method: method, getparser: 1 })
	.done(function(data) {
		if(!isJSON(data)) {
			infoDIV.innerHTML = htmlError("JSON-Format Error!");
			return;
		}
		if(hasError(data)) 
			setError(data, infoDIV);
		else {
			var obj = JSON.parse(data);
			infoDIV.innerHTML += obj[0].val;	
		}	
	});
}
/**
 * @category Function to set the default project path
 */
function setProjectPath() {
	var infoDIV       = document.getElementById("informationDIV");
	var projpath      = document.getElementById("projectPath").value;
	infoDIV.innerHTML = "";

	$.post("/SchemeRealizer/req/config.php", { setprojectpath : projpath })
	.done(function(data) {
		if(!isJSON(data)) {
			infoDIV.innerHTML = htmlError("JSON-Format Error!");
			return;
		}
		if(hasError(data)) 
			setError(data, infoDIV);
		else {
			var obj = JSON.parse(data);
			infoDIV.innerHTML += obj[0].val;
		}	
	});
}
/**
 * @category Function to get the default project path
 */
function getProjectPath() {
	var infoDIV       = document.getElementById("informationDIV");
	infoDIV.innerHTML = "";

	$.post("/SchemeRealizer/req/config.php", { getprojectpath : 1 })
	.done(function(data) {
		if(!isJSON(data)) {
			infoDIV.innerHTML = htmlError("JSON-Format Error!");
			return;
		}
		if(hasError(data)) 
			setError(data, infoDIV);
		else {
			var obj = JSON.parse(data);
			infoDIV.innerHTML += obj[0].val;
		}
	});
}
/**
 * @category Function to set the DSN
 */
function setDSN() {
	var infoDIV       = document.getElementById("informationDIV");
	var host          = document.getElementById("host").value;
	var user          = document.getElementById("user").value;
	var db            = document.getElementById("db").value;
	infoDIV.innerHTML = "";
	
	$.post("/SchemeRealizer/req/config.php", { setDSN : 1, host : host, user : user, db : db })
	.done(function(data) {
		if(!isJSON(data)) {
			infoDIV.innerHTML = htmlError("JSON-Format Error!");
			return;
		}
		if(hasError(data)) 
			setError(data, infoDIV);
		else {
			var obj = JSON.parse(data);
			infoDIV.innerHTML += obj[0].val;
		}
	});
}
/**
 * @category Function to get the DSN
 */
function getDSN() {
	var infoDIV       = document.getElementById("informationDIV");
	infoDIV.innerHTML = "";
	
	$.post("/SchemeRealizer/req/config.php", { getDSN : 1 })
	.done(function(data) {
		if(!isJSON(data)) {
			infoDIV.innerHTML = htmlError("JSON-Format Error!");
			return;
		}
		if(hasError(data)) 
			setError(data, infoDIV);
		else {
			var obj = JSON.parse(data);
			infoDIV.innerHTML += obj[0].val;
		}
	});
}