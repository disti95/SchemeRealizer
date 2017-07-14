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
 * @category JS-Library which contains the Flushing-Functions. 
 */

/**
 * @category del, add, flush, show the UML-Arr
 * @param    del
 * @param    flush
 * @param    showall
 */
function getUML(del, flush, showall) {
	//Declare Vars
	var selectAttrMeth      = document.getElementById("selectAttrMeth");
	var indexer             = (document.getElementById("table") != null) 
	                            ? document.getElementById("table") 
	                            : document.getElementById("phpFileClass");
	var infoDIV             = document.getElementById("informationDIV");
	var errorMessages       = document.getElementById("errorMessages");
	errorMessages.innerHTML = "";
	//Check if its a valid Array
	if(!umlArr[indexer.selectedIndex][1]) 
		return;
	selectedUML = umlArr[indexer.selectedIndex][1]; 
	//Check if its a valid Array
	if(!selectedUML[selectAttrMeth.selectedIndex]) 
		return
	//Flushing doesn't affect the Array-Content
	if(flush != 1) {
		//For a del set selected false at pos x
		if(del == 1) 
			selectedUML[selectAttrMeth.selectedIndex][3] = false;
		else {
			//If showall is set tag every element as selected
			if(showall == 1) 
				for(var i = 0; i < selectedUML.length; i++) 
					selectedUML[i][3] = true;
			else {
				var modifier = document.getElementById("modifier").value; //Get the Modifier
				//Check Modifier
				if(!checkClassModifier(modifier)) {
					errorMessages.innerHTML = htmlError("Forbidden Class Modifier!");
					return;
				}
				
				//Get the attributes
				var attributes = [], classAttributes = [];
				if(document.getElementById("attribute_static_btn") != null) 
					if(document.getElementById("attribute_static_btn").checked == true) 
						attributes.push(STATIC);
				if(document.getElementById("attribute_static_chk") != null) 
					if(document.getElementById("attribute_static_chk").checked == true) 
						attributes.push(STATIC);
				if(document.getElementById("attribute_const") != null) 
					if(document.getElementById("attribute_const").checked == true) 
						attributes.push(CONST);
				if(document.getElementById("attribute_final") != null) 
					if(document.getElementById("attribute_final").checked == true) 
						attributes.push(FINAL);
				if(document.getElementById("attribute_abstract") != null) 
					if(document.getElementById("attribute_abstract").checked == true) 
						attributes.push(ABSTRACT);
				if(document.getElementById("attribute_final_class") != null)
					if(document.getElementById("attribute_final_class").checked == true) 
						classAttributes.push(FINAL);
				if(document.getElementById("attribute_abstract_class") != null) 
					if(document.getElementById("attribute_abstract_class").checked == true) 
						classAttributes.push(ABSTRACT);
				if(document.getElementById("attribute_none_class") != null) 
					if(document.getElementById("attribute_none_class").checked == true)
						classAttributes = false;
	
				if(modifier == NONE 
				&& selectedUML[selectAttrMeth.selectedIndex][1] == 1 
				&& (!in_array(attributes, STATIC) && !in_array(attributes, CONST))) {
					errorMessages.innerHTML = htmlError(selectedUML[selectAttrMeth.selectedIndex][0] 
					                                  + " required a modifier!");
					return;
				}
				
				if(currClassIsInterface == INTERFACE) {
					var validateRet = validateInterfaceMembers(selectedUML);
					if(validateRet != true) {
						errorMessages.innerHTML = htmlError(validateRet);
						return;
					}
				}
				//Set new class values
				for(var i = 0; i < selectedUML.length; i++) {
					var parent     = selectedUML[i][5];
					var interfaces = selectedUML[i][6];
					if(selectedUML[i][1] == 3 || selectedUML[i][1] == 4) {
						setSelectedParentsInterfaces("parentsinterfaces"
								                    ,parent
								                    ,(currClassIsInterface == INTERFACE) 
								                       ? false 
								                       : interfaces
								                    ,UML);
						setSelectedParentsInterfaces("parentsinterfaces_selected"
								                    ,parent
								                    ,(currClassIsInterface == INTERFACE) 
								                       ? false 
								                       : interfaces
								                    ,UML);
						if(classAttributes.length > 0 || classAttributes == false) {
							selectedUML[i][4]     = classAttributes;
							var retCheckClassAttr = checkClassAttributes(selectedUML[i]
							                                            ,currClassIsInterface);
							if(retCheckClassAttr != true) {
								errorMessages.innerHTML = htmlError(retCheckClassAttr);
								return;
							}
						}
					}
				}
					
				selectedUML[selectAttrMeth.selectedIndex][4] = (attributes.length > 0) 
				                                                 ? attributes 
				                                                 : false
				selectedUML[selectAttrMeth.selectedIndex][2] = (modifier == "none") 
				                                                 ? false         
				                                                 : modifier;
				selectedUML[selectAttrMeth.value][3]         = true; //Visible
				if(selectedUML[selectAttrMeth.selectedIndex][1] == 2) {
					var param_key   = $("#selectParameter :selected").text();
					var default_val = document.getElementById("defaultValue").value;
					selectedUML[selectAttrMeth.selectedIndex][7][param_key] = (default_val.length) 
					                                                            ? default_val 
					                                                            : null;
				}
				else if(selectedUML[selectAttrMeth.selectedIndex][1] == 1) {
					var attrVal = document.getElementById("attrValue").value;
					if(!attrVal.length && in_array(attributes, CONST)) 
						var attributeValue = '';
					else if(!attrVal.length && !in_array(attributes, CONST))
						var attributeValue = false;
					else
						var attributeValue = attrVal;
					selectedUML[selectAttrMeth.selectedIndex][7] = attributeValue;
				}
				 //Validate the attributes
				if(!checkAttributes(selectedUML[selectAttrMeth.selectedIndex])) {
					errorMessages.innerHTML = htmlError("Forbidden attribute detected!");
					return;
				}
				if(!isClassAbstract() && in_array(attributes, ABSTRACT)) {
					errorMessages.innerHTML = htmlError("Unable to set access type " + ABSTRACT + " for method "
												      + selectedUML[selectAttrMeth.selectedIndex][0] 
												      + ", because the class itself is not declared as " 
												      + ABSTRACT + "!");
					return;
				}
			}
		}	
	}
	//Declare Vars
	var uml  = umlArr[indexer.selectedIndex][0]; //Filename
	var type = $('input[name="type"]:checked').val();
	//If he selects no type set .txt as default
	if(type == null) 
		type = 1;
	//Execute Req 
	$.post("/SchemeRealizer/req/getUML.php", { arr: JSON.stringify(selectedUML), type: type, uml: uml, flush: flush })
	.done(function(data) {
		//Check for JSON
		if(!isJSON(data)) {
			errorMessages.innerHTML = htmlError("Invalid JSON-Format!");
			return;
		}
		//Errors?
		if(hasError(data)) {
			setError(data, errorMessages);
			return;
		}
		//Get JSON-Object and set the result into the infoDIV
		var obj           = JSON.parse(data);
		infoDIV.innerHTML = obj[0].val;
	});
}
/**
 * @category add, del, flush, show the Class-Arr
 * @param    del
 * @param    flush
 * @param    showall
 */
function getClass(del, flush, showall) {
	//Declare Vars
	var selectedFile        = (document.getElementById("umlFile") != null) 
	                            ? document.getElementById("umlFile") 
	                            : document.getElementById("table");
	var selectAttrMeth      = document.getElementById("selectAttrMeth");
	var infoDIV             = document.getElementById("informationDIV");
	var errorMessages       = document.getElementById("errorMessages");
	errorMessages.innerHTML = "";
	//Flushing doesn't affect the Array-Content
	if(flush != 1) {
		//For a del set selected false at pos x
		if(del == 1) {
			//Check if its a valid Array
			if(classArr[selectedFile.selectedIndex][1][selectAttrMeth.value]) { 
				var elem = classArr[selectedFile.selectedIndex][1][selectAttrMeth.value]; //Get the selected Element
				//Is it an Attribute?
				if(elem[1] == 1) {
					//Has the Attribute a Getter or Setter?
					if(checkForAttrGetSet(classArr[selectedFile.selectedIndex][1], elem[0], 2) || checkForAttrGetSet(classArr[selectedFile.selectedIndex][1], elem[0], 3)){
						errorMessages.innerHTML = htmlError("Not able to remove Attribute, Setter or Getter is set!");
						return;
					}
				}
				classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][3] = false;
			}
		}
		else {
			//If showall is set tag every element as selected
			if(showall == 1) 
				for(var i = 0; i < classArr[selectedFile.selectedIndex][1].length; i++) 
					classArr[selectedFile.selectedIndex][1][i][3] = true;
			else {
				//Is it a Setter?
				if(classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][1] == 3) {
					//Check if the Attr is already set
					if(!checkForAttrGetSet(classArr[selectedFile.selectedIndex][1], classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][0], 1)) {
						errorMessages.innerHTML = htmlError("Please set Attribute before Setter!");
						return;
					}
				}
				//Is it a Getter?
				if(classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][1] == 2) { 
					//Check if the Attr is already set
					if(!checkForAttrGetSet(classArr[selectedFile.selectedIndex][1], classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][0], 1)) {
						errorMessages.innerHTML = htmlError("Please set Attribute before Getter!");
						return;
					}
				}
				
				var modifier = document.getElementById("modifier").value; //Get the Modifier
				//Check Modifier
				if(!checkClassModifier(modifier)) {
					errorMessages.innerHTML = htmlError("Forbidden Class Modifier!");
					return;
				}
				
				//Get the attributes
				var attributes = [], classAttributes = [];
				if(document.getElementById("attribute_static_btn") != null) 
					if(document.getElementById("attribute_static_btn").checked == true) 
						attributes.push(STATIC);
				if(document.getElementById("attribute_static_chk") != null) 
					if(document.getElementById("attribute_static_chk").checked == true) 
						attributes.push(STATIC);
				if(document.getElementById("attribute_const") != null) 
					if(document.getElementById("attribute_const").checked == true) 
						attributes.push(CONST);
				if(document.getElementById("attribute_final") != null) 
					if(document.getElementById("attribute_final").checked == true) 
						attributes.push(FINAL);
				if(document.getElementById("attribute_abstract") != null) 
					if(document.getElementById("attribute_abstract").checked == true) 
						attributes.push(ABSTRACT);
				if(document.getElementById("attribute_final_class") != null)
					if(document.getElementById("attribute_final_class").checked == true)
						classAttributes.push(FINAL);
				if(document.getElementById("attribute_abstract_class") != null) 
					if(document.getElementById("attribute_abstract_class").checked == true)
						classAttributes.push(ABSTRACT);
				if(document.getElementById("attribute_none_class") != null) 
					if(document.getElementById("attribute_none_class").checked == true)
						classAttributes = false;
				
				if(modifier == NONE 
				&& classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][1] == 1 
				&& (!in_array(attributes, STATIC) && !in_array(attributes, CONST))) {
					errorMessages.innerHTML = htmlError(classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][0]
					                                  + " required a modifier!");
					return;
				}
				
				if(currClassIsInterface == INTERFACE) {
					var validateRet = validateInterfaceMembers(classArr[selectedFile.selectedIndex][1]);
					if(validateRet != true) {
						errorMessages.innerHTML = htmlError(validateRet);
						return;
					}
				}
				//Set new class values
				for(var i = 0; i < classArr[selectedFile.selectedIndex][1].length; i++) {
					var parent     = classArr[selectedFile.selectedIndex][1][i][5];
					var interfaces = classArr[selectedFile.selectedIndex][1][i][6];
					if(classArr[selectedFile.selectedIndex][1][i][1] == 5 
					|| classArr[selectedFile.selectedIndex][1][i][1] == 6) {
						setSelectedParentsInterfaces("parentsinterfaces"
								                    ,parent
								                    ,(currClassIsInterface == INTERFACE) 
								                       ? false 
								                       : interfaces
								                    ,CLASS);
						setSelectedParentsInterfaces("parentsinterfaces_selected"
								                    ,parent
								                    ,(currClassIsInterface == INTERFACE) 
								                       ? false 
								                       : interfaces
								                    ,CLASS);
						if(classAttributes.length > 0 || classAttributes == false) {
							classArr[selectedFile.selectedIndex][1][i][4] = classAttributes;
							var retCheckClassAttr                         = checkClassAttributes(classArr[selectedFile.selectedIndex][1][i], currClassIsInterface);
							if(retCheckClassAttr != true) {
								errorMessages.innerHTML = htmlError(retCheckClassAttr);
								return;
							}
						}
					}
				}
				classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][4] = (attributes.length > 0) 
				                                                                     ? attributes 
				                                                                    : false;
				classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][2] = (modifier == "none")    
				                                                                     ? false      
				                                                                     : modifier;
				classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][3] = true; //Visible
				if(classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][1] == 2
				|| classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][1] == 3
				|| classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][1] == 4) {
					var param_key   = $("#selectParameter :selected").text();
					var default_val = document.getElementById("defaultValue").value;
					classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][7][param_key] = (default_val.length) 
					                                                                                ? default_val 
					                                                                                : null;
				}
				else if(classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][1] == 1) {
					var attrVal = document.getElementById("attrValue").value;
					if(!attrVal.length && in_array(attributes, CONST))
						var attributeVal = '';
					else if(!attrVal.length && !in_array(attributes, CONST))
						var attributeVal = false;
					else
						var attributeVal = attrVal;
					classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][7] = attributeVal;
				}
				
				//Validate the attributes
				if(!checkAttributes(classArr[selectedFile.selectedIndex][1][selectAttrMeth.value])) { 
					errorMessages.innerHTML = htmlError("Forbidden attribute detected!");
					return;
				}
				if(!isClassAbstract() && in_array(attributes, ABSTRACT)) {
					errorMessages.innerHTML = htmlError("Unable to set access type " + ABSTRACT + " for method "
												  	  + classArr[selectedFile.selectedIndex][1][selectAttrMeth.value][0]
												   	  + ", because the class itself is not declared as " 
												      + ABSTRACT + "!");
					return;
				}
			}
		}	
	}
	//Declare Vars
	var node = (document.getElementById("umlFile") != null) 
                 ? "umlFile" 
                 : "table";
	var cl   = $("#"+node+" option:selected").text();
	//Execute Req 
	$.post("/SchemeRealizer/req/getClass.php", { arr: JSON.stringify(classArr[selectedFile.selectedIndex][1]), cl: cl, flush: flush })
	.done(function(data) {
		//Check for JSON
		if(!isJSON(data)) {
			errorMessages.innerHTML = htmlError("Invalid JSON-Format!");
			return;
		}
		//Errors?
		if(hasError(data)) {
			setError(data, errorMessages);
			return;
		}
		//Get JSON-Object and set the result into the infoDIV
		var obj           = JSON.parse(data);
		infoDIV.innerHTML = obj[0].val;
	});
}
/**
 * @category add, del, flush, show the SQL-Arr
 * @param    del
 * @param    flush
 * @param    showall
 */
function getSQL(del, flush, showall) {
	//Declare Vars
	var elem                = document.getElementById("column");
	var indexer             = (document.getElementById("umlFile") != null) 
	                            ? document.getElementById("umlFile") 
	                            : document.getElementById("phpFileClass");
	var datatype            = document.getElementById("datatype");
	var infoDIV             = document.getElementById("informationDIV");
	var dbms                = (document.getElementById("dbms").value).toLowerCase();
	var tab                 = (document.getElementById("umlFile") != null) 
	                            ? $("#umlFile option:selected").text() 
	                            : $("#phpFileClass option:selected").text();
	var errorMessages       = document.getElementById("errorMessages");
	errorMessages.innerHTML = "";
	if(dbms != "mongodb") {
		if(document.getElementById("null").checked)
			var isnull = 1;
		else
			var isnull = -1;
		if(document.getElementById("ai").checked)
			var isai = 1;
		else
			var isai = -1;
	}
	if(del == 1) {
		//Set selected false
		if(sqlArr[indexer.selectedIndex][elem.selectedIndex]) {
			sqlArr[indexer.selectedIndex][elem.selectedIndex][6] = false;
			//Set SQL-Code
			getSQLCode(dbms, tab, flush, infoDIV);
			return;
		}
	}
	else {
		//Showall?
		if(showall == 1) {
			if(sqlArr[indexer.selectedIndex]) {
				//Go through the entire sqlArr
				for(var i = 0; i < sqlArr[indexer.selectedIndex].length; i++) 
					//Set selected true
					if(sqlArr[indexer.selectedIndex][i]) 
						sqlArr[indexer.selectedIndex][i][6] = true;
				//Set SQL-Code
				getSQLCode(dbms
						  ,tab
						  ,flush
						  ,infoDIV);
				return;
			}
		}
		else {
			//Flush?
			if(flush == 1) {
				//Set SQL-Code
				getSQLCode(dbms
						  ,tab
						  ,flush
						  ,infoDIV);
				return;
			}
			else {
				if(dbms == "mongodb") {
					var translateTo	= "mongodb";
					var dbname		= document.getElementById("dbname").value;
					var colparam 	= $("#colparam option:selected").text();
					var colparamval = document.getElementById("colparamval").value;
					var key			= $("#column option:selected").text();
					var datatype	= $("#datatype option:selected").text();
					var value 		= document.getElementById("val").value;
					NOSQLArr [0] 	 = "mongodb";
					if(dbname != null)
						NOSQLArr [1] = dbname;
					NOSQLArr [2] = indexer.options[indexer.selectedIndex].text;
					//if(colparamval != null) 
						NOSQLArr [3] = [colparam, colparamval];
					if(value != null)
						NOSQLArr [4] = [key, value, datatype];
					alert(JSON.stringify(NOSQLArr));
				}
				else {
					var index      = document.getElementById("index").value;
					var defaultVal = document.getElementById("default").value;
					//Change the Element
					if(sqlArr[indexer.selectedIndex][elem.selectedIndex]) {
						tmpArr    = [];
						tmpArr[0] = $("#column option:selected").text();
						//Check DBMS concerning the size
						switch(dbms) {
							case "mysql":
								tmpArr[2] = document.getElementById("size").value;
							break;
							case "sqlite":
								tmpArr[2] = -1;
							break;
							default:
								errorMessages.innerHTML = htmlError("Invalid DBMS!");
								return;
							break;
						}
						tmpArr[1] = $("#datatype option:selected").text();
						tmpArr[3] = index;
						tmpArr[4] = isnull;
						tmpArr[5] = isai;
						tmpArr[6] = true;
						tmpArr[7] = (defaultVal.length > 0)
						              ? defaultVal
						              : false;
						arr       = [];
						arr[0]    = tmpArr;
						//Validate Array
						$.post("/SchemeRealizer/req/funcReq.php", { sqlArr:JSON.stringify(arr), dbms:dbms })
						.done(function(data) {
							//Check JSON
							if(!isJSON(data)) {
								errorMessages.innerHTML = htmlError("JSON-Format Error!");
								return;
							}
							//Check for ERROR
							if(hasError(data)) {
								setError(data, errorMessages);
								return;
							}
							//Replace the tmpArr with the elem of sqlArr
							sqlArr[indexer.selectedIndex][elem.selectedIndex] = arr[0];
							//Set SQL-Code
							getSQLCode(dbms
									  ,tab
									  ,flush
									  ,infoDIV);
							return;
						});
					}
				}
			}
		}
	}
}
/**
 * @category add, del, flush, show the MONGODB-Arr
 * @param    del
 * @param    flush
 * @param    showall
 */
function getMONGO(del, flush, showall) {
	//Declare Vars
	var elem                = document.getElementById("column");
	var indexer             = (document.getElementById("umlFile") != null) 
	                            ? document.getElementById("umlFile") 
	                            : document.getElementById("phpFileClass");
	var datatype            = document.getElementById("datatype");
	var infoDIV             = document.getElementById("informationDIV");
	var dbms                = (document.getElementById("dbms").value).toLowerCase();
	var tab                 = (document.getElementById("umlFile") != null) 
	                            ? $("#umlFile option:selected").text() 
	                            : $("#phpFileClass option:selected").text();
	var errorMessages       = document.getElementById("errorMessages");
	errorMessages.innerHTML = "";
	if(dbms != "mongodb") {
		if(document.getElementById("null").checked)
			var isnull = 1;
		else
			var isnull = -1;
		if(document.getElementById("ai").checked)
			var isai = 1;
		else
			var isai = -1;
	}
	if(del == 1) {
		//Set selected false
		if(sqlArr[indexer.selectedIndex][elem.selectedIndex]) {
			sqlArr[indexer.selectedIndex][elem.selectedIndex][6] = false;
			//Set SQL-Code
			getSQLCode(dbms, tab, flush, infoDIV);
			return;
		}
	}
	else {
		//Showall?
		if(showall == 1) {
			if(sqlArr[indexer.selectedIndex]) {
				//Go through the entire sqlArr
				for(var i = 0; i < sqlArr[indexer.selectedIndex].length; i++) 
					//Set selected true
					if(sqlArr[indexer.selectedIndex][i]) 
						sqlArr[indexer.selectedIndex][i][6] = true;
				//Set SQL-Code
				getSQLCode(dbms
						  ,tab
						  ,flush
						  ,infoDIV);
				return;
			}
		}
		else {
			//Flush?
			if(flush == 1) {
				//Set SQL-Code
				getSQLCode(dbms
						  ,tab
						  ,flush
						  ,infoDIV);
				return;
			}
			else {
				var colname			= indexer.options[indexer.selectedIndex].text;
				if(NOSQLArr.length == 0) {
					var translateTo	= "mongodb";
					NOSQLArr[0] 	= translateTo;
					NOSQLArr[2]		= {};
				}
				if(NOSQLArr[2][colname] == null) {
					NOSQLArr[2][colname] = new Array({},{});
				}
				if (document.getElementById("dbname").style.display == "block") {
						var dbname		= document.getElementById("dbnameval").value;
						if(dbname != null)
							NOSQLArr [1] = dbname;
						alert(JSON.stringify(NOSQLArr));
				}
				else if (document.getElementById("colparamdiv").style.display == "block") {
					var colparam 	= $("#colparam option:selected").text();
					var colparamval = document.getElementById("colparamval").value;
					if(colparamval != null) 
						NOSQLArr [2][colname][0][colparam] = [colparamval];
					alert(JSON.stringify(NOSQLArr));
				}	
				else if (document.getElementById("addVal").style.display == "block") {
					var key			= $("#column option:selected").text();
					var datatype	= $("#datatype option:selected").text();
					var value 		= document.getElementById("val").value;
					//if(value != null)
						NOSQLArr [2][colname][1]["pseudo"] = ["dfas", "asdfa"];
					alert(JSON.stringify(NOSQLArr));
				}
			}
		}
	}
}
/**
 * @return Function to set the SQL-Code, maybe there is a better way to realize this...
 * @param  dbms
 * @param  tab
 * @param  flush
 * @param  infoDIV
 */
function getSQLCode(dbms, tab, flush, infoDIV) {
	var indexer       = (document.getElementById("umlFile") != null) 
	                      ? document.getElementById("umlFile") 
	                      : document.getElementById("phpFileClass");
	var errorMessages = document.getElementById("errorMessages");
	if(sqlArr[indexer.selectedIndex]) {
		$.post("/SchemeRealizer/req/getSQL.php", { arr: JSON.stringify(sqlArr[indexer.selectedIndex]), dbms: dbms, tab: tab, flush: flush })
		.done(function(data) {
			//Check JSON
			if(!isJSON(data)) {
				errorMessages.innerHTML = htmlError("JSON-Format Error!");
				return;
			}
			//Check for ERROR
			if(hasError(data)) {
				setError(data, errorMessages);
				return;
			}
			var obj           = JSON.parse(data);
			infoDIV.innerHTML = obj[0].val;
		});
	}
}