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
 * @category JS-Library which contains the necessary functions for the mapping process.
 */

/**
 * @category Function to map classes/class-diagrams to Databases
 * @param    from(class | uml)
 */
function mapToDB(from) {
	var type = from.trim();
	if(type != UML && type != CLASS)
		return;
	var dbms        = (document.getElementById("dbms").value).toLowerCase();
	var projectpath = document.getElementById("projectpath").value;
	var exportDBMS  = document.getElementById("exportDBMS");
	var infoDIV     = document.getElementById("informationDIV");
	exportDBMS.innerHTML = "";
	infoDIV.innerHTML    = "";
	//Execute the AJAX-Request
	$.post("/SchemeRealizer/req/toDatabase.php", { dbms: dbms, projectpath: projectpath, type: type })
		.done(function(data) {
		//Check JSON
		if(!isJSON(data)) {
			infoDIV.innerHTML = htmlError("Invalid JSON-Format!");
			return;
		}
		//Check for Errors
		if(hasError(data)) {
			setError(data, infoDIV);
			return;
		}
		var obj       = JSON.parse(data);
		var datatypes = obj[0].val.datatypes;
		var elements  = obj[0].val.elements;
		//Validate length
		if(elements.length < 1 || datatypes.length < 1) {
			infoDIV.innerHTML = htmlError("JSON-Error: Invalid length of elements or datatypes!");
			return;
		}
		var fromNotation = (from == UML) 
		                     ? "diagram" 
		                     : CLASS;
		//Get the Datatypes and Elements into the Select-Fields
		$("#exportDBMS").load("/SchemeRealizer/includes/"+fromNotation+"2"+dbms+"Export.php", function() {
			var selectDatatypes = document.getElementById("datatype");
			
			var fileSelect = (from == UML) 
			                   ? document.getElementById("umlFile") 
			                   : document.getElementById("phpFileClass");
			//Clear the sqlArr
			sqlArr = [];
			//Fill the sqlArr with the data
			for(var i = 0; i < elements.length; i++) {
				sqlArr[i] = [];
				for(var j = 0; j < elements[i][1].length; j++) {	
					if(dbms == "mysql")
						sqlArr[i][j] = [elements[i][1][j][0], "int", 11, -1, -1, -1, false, false];
					else
						sqlArr[i][j] = [elements[i][1][j][0], "int", -1, -1, -1, -1, false, false];
				}
			}
			var fileList = [];
			//Get the file names
			for(var i = 0; i < elements.length; i++) 
				fileList[i] = elements[i][0];
			//Fill the Select-Fields
			fillSelect(fileSelect, fileList);
			fillSelect(selectDatatypes, datatypes);
			fileSelect.selectedIndex = 0;
			setAttrMethPerFile(SQL);
			if(dbms == "mongodb") {
				var collectionparam = document.getElementById("colparam");
				fillSelect(collectionparam, obj[0].val.colparams);
				showMongoView();
			}
			showColumn();
		});
		var showMap = document.getElementById("showMap");
		//Close Mapping-Form
		showMap.style.display = "none";
	});
}
/**
 * @category Function to map class-diagrams or databases to Classes
 * @param    from(uml | database)
 */
function mapToClass(from) {
	type = from.trim();
	var para;
	if(type == UML) {
		//UML-Handling
		var projectpath = document.getElementById("projectpath").value;
		para = { type: type, projectpath: projectpath };
	}
	else if(type == DB) {
		var dbms = (document.getElementById("dbms").value).toLowerCase(); //Particular DBMS
		var para; //Parameters for the AJAX-Request
		switch(dbms) {
			//MySQL-Handling
			case "mysql":
				//Declare Vars
				var host = document.getElementById("host").value;
				var user = document.getElementById("user").value;
				var pwd  = document.getElementById("pwd").value;
				var db   = document.getElementById("db").value;
				para     = { host: host, user: user, pwd: pwd, type: dbms, db: db };
			break;
			//SQLite-Handling
			case "sqlite":
				//Declare Vars
				var projectpath = document.getElementById("projectpath").value;
				para            = { type: dbms, projectpath: projectpath };
			break;
			//MongoDB-Handling
			case "mongodb":
				//Declare Vars
				var host = document.getElementById("host").value;
				var user = document.getElementById("user").value;
				var pwd  = document.getElementById("pwd").value;
				var db   = document.getElementById("db").value;
				var port = document.getElementById("port").value;
				para     = { host: host, user: user, pwd: pwd, type: dbms, db: db, port: port };
			break;
			default:
				return;
			break;
		}
	}
	else
		return;
	var infoDIV       = document.getElementById("informationDIV");
	infoDIV.innerHTML = "";
	//Execute Req 
	$.post("/SchemeRealizer/req/toClass.php", para)
	.done(function(data) {
		//Check for JSON
		if(!isJSON(data)) {
			infoDIV.innerHTML = htmlError("Invalid JSON-Format!");
			return;
		}
		//Errors?
		if(hasError(data)) {
			setError(data, infoDIV);
			return;
		}
		//Get the JSON-Object
		var obj        = JSON.parse(data);
		var fileSelect = (from == UML)
		                   ? document.getElementById("umlFile") 
		                   : document.getElementById("table");
		//Clear/Set classArr
		classArr = [];
		classArr = obj[0].val;
		fileList = [];
		for(var i = 0; i < classArr.length; i++) 
			fileList[i] = classArr[i][0];
		//The Database-Form has an extra show-DIV
		if(from == DB) 
			document.getElementById("showDBMS").style.display = "none";

		var showMap                = document.getElementById("showMap");
		showMap.style.display      = "none";
		var showAttrMeth           = document.getElementById("showAttrMeth");
		showAttrMeth.style.display = "block";
		//Fill the File-Selection with the Arr
		fillSelect(fileSelect, fileList);
		fileSelect.selectedIndex = 0;
		setAttrMethPerFile(CLASS);
	});
}
/**
 * @categpry Function to map classes or databases to Class-Diagrams
 * @param    from(class | database)
 */
function mapToUML(from) {
	var type = from.trim();
	var para;
	if(type == DB) {
		var dbms = (document.getElementById("dbms").value).toLowerCase(); //Particular DBMS
		var para; //Parameters for the AJAX-Request
		switch(dbms) {
			//MySQL-Handling
			case "mysql":
				//Declare Vars
				var host = document.getElementById("host").value;
				var user = document.getElementById("user").value;
				var pwd  = document.getElementById("pwd").value;
				var db   = document.getElementById("db").value;
				para     = { host: host, user: user, pwd: pwd, type: dbms, db: db };
			break;
			//SQLite-Handling
			case "sqlite":
				//Declare Vars
				var projectpath = document.getElementById("projectpath").value;
				para            = { type: dbms, projectpath: projectpath };
			break;
			//MongoDB-Handling
			case "mongodb":
				//Declare Vars
				var host = document.getElementById("host").value;
				var user = document.getElementById("user").value;
				var pwd  = document.getElementById("pwd").value;
				var db   = document.getElementById("db").value;
				var port = document.getElementById("port").value;
				para     = { host: host, user: user, pwd: pwd, type: dbms, db: db, port: port };
			break;
			default:
				return;
			break;
		}
	}
	else if(type == CLASS) {
		var projectpath = document.getElementById("projectpath").value;
		para            = { projectpath: projectpath, type: type };
	}
	else 
		return;
	var infoDIV       = document.getElementById("informationDIV");
	infoDIV.innerHTML = "";
	//Execute the AJAX-Request
	$.post("/SchemeRealizer/req/toUML.php", para)
		.done(function(data) {
		//Check for JSON
		if(!isJSON(data)) {
			infoDIV.innerHTML = htmlError("Invalid JSON-Format!");
			return;
		}
		//Errors?
		if(hasError(data)) {
			setError(data, infoDIV);
			return;
		}
		umlArr        = []; //Clear the umlArr
		var obj       = JSON.parse(data);
		umlArr        = obj[0].val; 
		var fileClass = []; 
		//Fill arr with the filenames
		for(var i = 0; i < umlArr.length; i++) 
			fileClass[i] = umlArr[i][0];
		var showMap      = document.getElementById("showMap");
		var showAttrMeth = document.getElementById("showAttrMeth");
		var fileSelect   = (document.getElementById("table")) 
		                     ? document.getElementById("table") 
		                     : document.getElementById("phpFileClass");
		showMap.style.display = "none";
		if(from == DB) 
			document.getElementById("showDBMS").style.display = "none";
		showAttrMeth.style.display = "block";
		fillSelect(fileSelect, fileClass);
		//Set the filename selectedIndex to 0 and fill the Attr/Meth Select-Field with the data
		fileSelect[0].selectedIndex = 0;
		setAttrMethPerFile(UML);
	});
}
