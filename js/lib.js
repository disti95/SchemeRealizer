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
 * @category Standard JS-Library which contains the main functions.
 */

/**
 * @category Validate JSON
 * @return   true || false
 */
function isJSON(str) {
	try {
		JSON.parse(str);
	}
	catch(e) {
		return false;
	}
	return true;
}
/**
 * @category Function to check if an error has occurred
 * @param    json string
 * @return   true||false
 */
function hasError(JSONStr) {
	var ret = false;
	JSON.parse(JSONStr, function(k,v) {
		if(k == "error" && v == true)
			ret = true;
	});
	return ret;
}
/**
 * @category Function to fill the Select-Field with the data of an Array
 * @param    selection field
 * @param    array
 */
function fillSelect(select, arr) {
	for(var j = 0; j < arr.length; j++) {
		var child = document.createElement("option");
		child.value = j;
		child.innerHTML = arr[j];
		select.appendChild(child);
	}
}
/**
 * @category Clears a Selection-Field
 * @param    select
 */
function clearSelect(select) {
	//Check for NULL
	if(select == null)
		return;
	//Clear
	while(select.options.length > 0)
		select.remove(0);
}
/**
 * @category Function to set the Error-HTML
 * @param    error msg
 * @return   error div
 */
function htmlError(val) {
	return "<br /><div id='errorDIV'><h3>"+val+"</h3></div><br />";
}
/**
 * @category Function to set the multiple Error-Messages
 * @param    json string
 * @param    div
 */
function setError(JSONStr, docDIV) {
	JSON.parse(JSONStr, function(k,v) {
		if(k == "val")
			docDIV.innerHTML += v;
	});
}
/**
 * @category Function to validate the modifier
 * @param    mod
 * @returns  true||false
 */
function checkClassModifier(mod) {
	switch(mod) {
		case PRIVATE:
		case PUBLIC:
		case PROTECTED:
		case NONE:
			return true;
		break;
		default: 
			return false;
		break;
	}
}
/**
 * @category Check if the Attr is set itself or its Getter/Setter
 * @param    array
 * @param    string
 * @param    type
 * @return   true||false
 */
function checkForAttrGetSet(arr, str, type) {
	for(var i = 0; i < arr.length; i++) {
		if(arr[i][0] == str) {
			if(arr[i][1] == type) {
				if(arr[i][3] == true)
					return true;
			}
		}
	}
	return false;
}
/**
 * @category Appends a string to all elements of an Array at pos x
 * @return   Concatenated array
 */
function arrStrToElem(preArr, str, pos) {
	var arr = [];
	for(var i = 0; i < preArr.length; i++) 
		arr[i] = preArr[i].substr(0, pos) + str + preArr[i].substr(pos);
	return arr;
}
/**
 * @category Check the attributes
 * @param    array
 * @return   true||false
 */
function checkAttributes(arr) {
	var type       = arr[1];
	var attributes = arr[4];
	switch(type) {
		case 1:
			if(typeof attributes[0] != NOTDEF) {
				switch(attributes[0]) {
					case CONST: 
					case STATIC:
						return true;
					break;
					default: 
						return false;
					break;
				}
			}
			else 
				return true;
		break;
		case 2:
		case 3:
		case 4:
			if(attributes != false) {
				switch(attributes.length) {
					case 1: 
						switch(attributes[0]) {
							case FINAL:  
								return true;
							break;
							case STATIC: 
								return true;
							break;
							case ABSTRACT: 
								return true;
							break;
							default: 
								return false;
							break;
						}
					break;
					case 2:	
						if((attributes[0] == STATIC   || attributes[1]   == STATIC) && 
						  ((attributes[0] == FINAL    || attributes[1]   == FINAL   || 
						    attributes[0]   == ABSTRACT || attributes[1] == ABSTRACT)))   
							return true;
						else 
							return false;
					break;
					default: 
						return false;
					break;
				}
			}
			else 
				return true;
		break;
	}
}
/**
 * @category Validate the attributes of an class
 * @param    type(class|interface)
 */
function checkClassAttributes(arr, type) {
	var attributes = arr[4];
	if(attributes != false) {
		if(attributes > 1)
			return "Detected more than one class attribute!";
		if(attributes[0] == ABSTRACT || attributes[0] == FINAL) {
			if(type == INTERFACE)
				return "Access type not allowed in interface prototype!";
			else
				return true;
		}
		else 
			return "Forbidden attribute " + attributes[0] + "!";
	}
	else 
		return true;
}
/**
 * @category Decide if the class is an interface or a class
 * @param    array
 * @param    callback function
 */
function classIsInterface(arr, cb) {
	var type    = (classArr.length > 0) 
	                ? CLASS 
	                : UML;
	var infoDIV = document.getElementById("informationDIV");
	$.post("/SchemeRealizer/req/funcReq.php", { isInterface : arr, src : type})
    .done(function(data) {
	  if(!isJSON(data)) {
		   infoDIV.innerHTML = htmlError("Invalid JSON-Format!");
		   return;
	  }
	  if(hasError(data)) {
		  setError(data, infoDIV);
		  return;
	  }
	  var obj = JSON.parse(data);
	  if(typeof(cb) == 'function')
		  cb(obj[0].val);
    });
}
/**
 * @category Check if the given object is an Array
 * @param    Object
 * @returns  bool
 */
function isArray(arr) {
	return arr && arr.constructor === Array;
}
/**
 * @category validate the methods if it's an interface
 * @param    selected File/UML
 * @param    type: uml/class
 * @return   error msg|bool
 */
function validateInterfaceMembers(arr) {
	var indexer, selectedArr, type;
	if(detectSrc() == CLASS) {
		indexer     = (document.getElementById("umlFile") != null) 
		                ? document.getElementById("umlFile") 
		                : document.getElementById("table"); 
		selectedArr = classArr[indexer.selectedIndex][1];
		type        = CLASS;
	}
	else if(detectSrc() == UML) {
		indexer     = (document.getElementById("table") != null) 
		                ? document.getElementById("table") 
		                : document.getElementById("phpFileClass");
		selectedArr = umlArr[indexer.selectedIndex][1];
		type        = UML;
	}
	else
		return;
	
	for(var i = 0; i < selectedArr.length; i++) {
		var currArr = selectedArr[i];
		if(currArr[1] == 1)
			return "Interface can't contain member variables!";
		if((type == CLASS && (currArr[1] == 2 || currArr[1] == 3))
		|| (type == UML   &&  currArr[1] == 2)) {
			var name = currArr[0];
			if(currArr[2] != false) 
				return "Access type not allowed in method " + name + "!";
			/**
			 * Validate attributes
			 */
			if(currArr[4] != false && isArray(currArr[4])) 
				for(i = 0; i < currArr[4].length; i++) 
					if(currArr[4][i] == FINAL || currArr[4][i] == ABSTACT)
						return "Access type not allowed in method " + name + "!";
		}
	}
	return true;
}
/**
 * @category left trim of character c
 * @param    string
 * @param    char
 * @return   trimmed string
 */
function lTrimStr(string, char) {
	while(string.substring(0, 1) == char) 
		string = string.substring(1, string.length);
	return string;
}
/**
 * @category right trim of character c
 * @param    string
 * @param    char
 * @return   trimmed string
 */
function rTrimStr(string, char) {
	while(string.substring(string.length - 1, string.length) == char)
		string = string.substring(0, string.length - 1);
	return string;
}
/**
 * @category detect if the classArr or the umlArr is set
 * @return   uml || class || false
 */
function detectSrc() {
	if(classArr.length)
		return CLASS;
	else if(umlArr.length)
		return UML;
	else 
		return false;
}
/**
 * @category check if the class is abstract
 * @return   boolean
 */
function isClassAbstract() {
	var indexer
	   ,selectedArr
	   ,type;
	if(detectSrc() == CLASS) {
		indexer     = (document.getElementById("umlFile") != null) 
        			    ? document.getElementById("umlFile") 
                        : document.getElementById("table"); 
		selectedArr = classArr[indexer.selectedIndex][1];
		type        = CLASS
	}
	else if(detectSrc() == UML) {
		indexer     = (document.getElementById("table") != null) 
                        ? document.getElementById("table") 
                        : document.getElementById("phpFileClass");
        selectedArr = umlArr[indexer.selectedIndex][1];
        type        = UML;
	}
	else
		return false;
	
	for(var i = 0; i < selectedArr.length; i++) 
		if((type == CLASS && selectedArr[i][1] == 5)
	    || (type == UML   && selectedArr[i][1] == 3)) 
			if(isArray(selectedArr[i][4])) 
				for(var j = 0; j < selectedArr[i][4].length; j++) 
					if(selectedArr[i][4][j] == ABSTRACT)
						return true;
	return false;
}
/**
 * @category check if the given element exist in the array
 * @param    haystack
 * @param    needle
 * @return   boolean
 */
function in_array(haystack, needle) {
	for(var i = 0; i < haystack.length; i++) 
		if(isArray(haystack[i])) {
			if(in_array(haystack[i], needle))
				return true;
		}
		else
			if(haystack[i] == needle) 
				return true;
	return false;
}