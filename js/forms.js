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
 * @category JS-Library which contains the Functions concerning the Form-Settings.
 */

/**
 * @category Function to show the particular config-Form
 */
function showConfOnChange() {
	var selectConf      = document.getElementById("selectConf").value;
	var showPathClass   = document.getElementById("showPathClassConf");
	var showPathUML     = document.getElementById("showPathUMLConf");
	var showPathSQL     = document.getElementById("showPathSQLConf");
	var showParserClass = document.getElementById("showParserClassConf");
	var showProjectPath = document.getElementById("showProjectPath");
	var showDSN         = document.getElementById("showDSN");
	var divArr          = [showPathClass
	                      ,showPathUML
	                      ,showPathSQL
	                      ,showParserClass
	                      ,showProjectPath
	                      ,showDSN];
	for(var i = 0; i < divArr.length; i++) 
		if(selectConf == i)
			divArr[i].style.display = "block";
		else
			divArr[i].style.display = "none";
}
/**
 * @category Function to Shift a Parent/Interface from one select to another
 * @param    shifting: 0 = select || 1 = unselect
 */
function shiftParentsInterfaces(shifting) {
	if(document.getElementById("parentsinterfaces") == null || document.getElementById("parentsinterfaces_selected") == null) 
		return;
	if(shifter == 0) {
		shifter++;
		detectparentinterfaceselect = shifting;
	}
	else if(shifter == 1 && detectparentinterfaceselect == shifting) { //Shifter = 1 -> double click
		var parentsinterfaces 			= "#parentsinterfaces";
		var parentsinterfaces_selected	= "#parentsinterfaces_selected";
		var exists  = 0;
		switch(shifting) {
			case 0:
				$(parentsinterfaces+" :selected").each(function(i, selected) {
				    $(parentsinterfaces_selected).each(function() {
				    	if($(this).val() == $(selected).val()) 
				    		exists = 1;
				    });
				    if(!exists) {
				    	$(parentsinterfaces_selected).append("<option value = '"+$(selected).val()+"'>"+$(selected).text()+"</option>");
				    	$(parentsinterfaces+" option[value='"+$(selected).val()+"']").remove();
				    }
				 });
			break;
			case 1:
				$(parentsinterfaces_selected+" :selected").each(function(i, selected) {
					$(parentsinterfaces).each(function() {
				    	if($(this).val() == $(selected).val()) 
				    		exists = 1;
				    });
					if(!exists) {
						$(parentsinterfaces).append("<option value = '"+$(selected).val()+"'>"+$(selected).text()+"</option>");
						$(parentsinterfaces_selected+" option[value='"+$(selected).val()+"']").remove();
					}
				});
			break;
			default:
				return;
			break;
		}
		shifter = 0;
		detectparentinterfaceselect = -1;
	}
	else {
		shifter = 0;
		detectparentinterfaceselect = -1;
	}
}
/**
 * @category Function to fill the select with its parents/interfaces
 */
function setParentsInterfaces() {
	var showParentsInterfaces = document.getElementById("showParentsInterfaces");
	if(document.getElementById("parentsinterfaces") 		 == null
	|| document.getElementById("parentsinterfaces_selected") == null)
		return;
	//Clear the select fields
	clearSelect(document.getElementById("parentsinterfaces"));
	clearSelect(document.getElementById("parentsinterfaces_selected"));
	var parent, interfaces;
	if(detectSrc() == UML) {
		indexer = (document.getElementById("table") != null) 
		            ? document.getElementById("table") 
		            : document.getElementById("phpFileClass");
		for(var i = 0; i < umlArr[indexer.selectedIndex][1].length; i++) {
			if(umlArr[indexer.selectedIndex][1][i][1] == 3
			|| umlArr[indexer.selectedIndex][1][i][1] == 4) {
				parent     = umlArr[indexer.selectedIndex][1][i][5];
				interfaces = umlArr[indexer.selectedIndex][1][i][6];
				break;
			}
		}
		if(interfaces != false) {
			key = $.map(interfaces, function(v, i) {
				if(v[1] != false) 
					$("#parentsinterfaces_selected").append("<option value = 'i:"+i+"'>"+v[0]+"</option>");
				else 
					$("#parentsinterfaces").append("<option value = 'i:"+i+"'>"+v[0]+"</option>");
			});
		}
	}
	else if(detectSrc() == CLASS) {
		indexer = (document.getElementById("umlFile") != null) 
		            ? document.getElementById("umlFile") 
		            : document.getElementById("table");
		for(var i = 0; i < classArr[indexer.selectedIndex][1].length; i++) {
			if(classArr[indexer.selectedIndex][1][i][1] == 5
			|| classArr[indexer.selectedIndex][1][i][1] == 6) {
				parent     = classArr[indexer.selectedIndex][1][i][5];
				interfaces = classArr[indexer.selectedIndex][1][i][6];
				break;
			}
		}
		if(interfaces != false) {
			for(var j = 0; j < interfaces.length; j++) {
				key = $.map(interfaces[j], function(v, i) {
					if(v[1] != false) 
						$("#parentsinterfaces_selected").append("<option value = 'i:"+i+"'>"+v[0]+"</option>");
					else 
						$("#parentsinterfaces").append("<option value = 'i:"+i+"'>"+v[0]+"</option>");
				});
			}
		}
	}
	else 
		return;
	if(parent != false) {
		key = $.map(parent, function(v, i) {
			if(v[1] != false) 
				$("#parentsinterfaces_selected").append("<option value = 'p:"+i+"'>"+v[0]+"</option>");
			else 
				$("#parentsinterfaces").append("<option value = 'p:"+i+"'>"+v[0]+"</option>");
		});
	}
	if(parent == false && interfaces == false)
		showParentsInterfaces.style.display = "none";
	else
		showParentsInterfaces.style.display = "inline-flex";
}
/**
 * @category select/unselect parents/interfaces via the multiple select
 * @param    from       (uml|class)
 * @param    doc        (select field)
 * @param    parents    (parant array)
 * @param    interfaces (interface array)
 */
function setSelectedParentsInterfaces(doc, parents, interfaces, from) {
	if(doc != "parentsinterfaces" && doc != "parentsinterfaces_selected")
		return;
	if(from != UML && from != CLASS)
		return;
	$("#"+doc+" option").each(function(i, selected) {
		var type  = $(selected).val().substring(0, 1); //Extract i:,p:
		var index = $(selected).val().substring(2);    //Remove i:,p:
		var val   = $(selected).text();
		switch(type) {
			case "p":
				if(parents != false) {
					key = $.map(parents, function(v, key) {
						if(parents[key][0] == val) { //Just to be sure
							if(doc == "parentsinterfaces_selected")
								parents[key][1] = true;
							else if(doc == "parentsinterfaces") 
								parents[key][1] = false;
						}
					});
				}
			break;
			case "i":
				//Interfaces distinguish depending on its source
				if(interfaces != false) {
					if(from == "uml") {
						key = $.map(interfaces, function(v, key) {
							if(interfaces[key][0] == val) { //Just to be sure
								if(doc == "parentsinterfaces_selected") 
									interfaces[key][1] = true;
								else if(doc == "parentsinterfaces")
									interfaces[key][1] = false;
							}
						});
					}
					else if(from == "class") {
						for(var j = 0; j < interfaces.length; j++) {
							key = $.map(interfaces[j], function(v, key) {
								if(interfaces[j][key][0] == val) { //Just to be sure
									if(doc == "parentsinterfaces_selected")
										interfaces[j][key][1] = true;
									else if(doc == "parentsinterfaces")
										interfaces[j][key][1] = false;
								}
							});
						}
					}
				}
			break;
		}
	});
}
/**
 * @category Function to change the visibility of particular HTML-Elements
 */
function changeDBMSForm() {
	//Declare Variables
	var datatype  = document.getElementById("datatype");
	var dbms      = (document.getElementById("dbms").value).toLowerCase();
	var showIndex = document.getElementById("showIndex"); 
	var showAI    = document.getElementById("showAI"); 
	var showSize  = document.getElementById("showSize"); 
	var infoDIV   = document.getElementById("informationDIV");
	var indexer   = (document.getElementById("umlFile") != null) 
	                  ? document.getElementById("umlFile") 
	                  : document.getElementById("phpFileClass");
	var elem      = document.getElementById("column");
	//Check DBMS
	if(dbms != "mysql" && dbms != "sqlite" && dbms != "mongodb") {
		infoDIV.innerHTML = htmlError("Forbidden DBMS!");
		return;
	}
	var type = datatype.options[datatype.selectedIndex].text; //Get the Text of the Option-Field
	//Visibility for the MySQL-Form
	if(dbms == "mysql") {
		$.post("/SchemeRealizer/req/toDatabase.php", { maxSize: type })
		.done(function(data){
			//Check for JSON
			if(!isJSON(data)) {
				infoDIV.innerHTML = htmlError("Invalid JSON-Format!");
				return;
			}
			//If there is no Max-Size for this data type, hide size and index
			if(data.toLowerCase().trim() == "false") {
				showIndex.style.display = "none";
				showSize.style.display  = "none";
				//Just to make sure
				document.getElementById("index").selectedIndex = 0;
				document.getElementById("size").value          = "-1";  
			}
			else {
				showIndex.style.display = "block";
				showSize.style.display  = "block";
				if(sqlArr[indexer.selectedIndex][elem.selectedIndex]) {
					if(sqlArr[indexer.selectedIndex][elem.selectedIndex][2] == -1) 
						document.getElementById("size").value = "";
					else
						document.getElementById("size").value = sqlArr[indexer.selectedIndex][elem.selectedIndex][2];  
				}
			}
		});
	}
	if(dbms == "mongodb"){return;}
	$.post("/SchemeRealizer/req/toDatabase.php", { AIType: type, dbms: dbms })
	.done(function(data){
		//Check for JSON
		if(!isJSON(data)) {
			infoDIV.innerHTML = htmlError("Invalid JSON-Format!");
			return;
		}
		//AI depend on Index
		var indexDependency = AIDependIndex((dbms == "mysql") 
				                              ? 0 
				                              : 1
				                            ,showAI
				                            ,document.getElementById("index").value);
		//If the datatype is forbidden for AI-Tagging or a invalid index is set, hide AI
		if(!indexDependency || data.toLowerCase().trim() == "false") {
			showAI.style.display = "none";
			$("#ai").prop("checked", false); //Just to make sure
		}
		else 
			showAI.style.display = "block";
	});
}
/**
 * @category AI Depend on Index
 * @param    dbms(E.g mysql, sqlite,..)
 * @param    ai(auto increment)
 * @return   true||false
 */
function AIDependIndex(dbms, showAI, key) {
	//AI depend on Index
	if(dbms == 1 && (key == 1)) {
		showAI.style.display = "block";
		return true;
	}
	else {
		if(dbms == 0 && (key == 1 | key == 2 | key == 3)) {
			showAI.style.display = "block";
			return true;
		}
		else {
			showAI.style.display = "none";
			return false;
		}
	}
}
/**
 * @category Function to set the Formular concerning the DBMS
 */
function setDBMSForm(from) {
	var dbmsOrigin = document.getElementById("dbms").value;
	var dbms       = dbmsOrigin.toLowerCase();
	switch(dbms) {
		case "sqlite":
			$("#DBMSForm").load("/SchemeRealizer/includes/sqliteImport2"+from+".php");
		break;
		case "mongodb":
			$("#DBMSForm").load("/SchemeRealizer/includes/mongodbImport2"+from+".php");
		break;
		default:
			$("#DBMSForm").load("/SchemeRealizer/includes/mysqlImport2"+from+".php");
		break;
	}
}
/**
 * @category Set the modifier-select-field depending on the class/uml-Arr
 */
function setModifierOnSelect() {
	//Declare Vars
	var selectAttrMeth = document.getElementById("selectAttrMeth");
	var modifier       = document.getElementById("modifier"); //Get the Modifier
	//Check for NULL
	if(selectAttrMeth == null || modifier == null)
		return;
	//Check which page is set
	var mod = "";
	if(detectSrc() == CLASS) {
		var indexer = (document.getElementById("umlFile") != null) ? document.getElementById("umlFile") : document.getElementById("table"); 
		mod = classArr[indexer.selectedIndex][1][selectAttrMeth.value][2];
	}
	else if(detectSrc() == UML){
		var indexer = (document.getElementById("table") != null) ? document.getElementById("table") : document.getElementById("phpFileClass"); 
		mod = umlArr[indexer.selectedIndex][1][selectAttrMeth.value][2];
	}
	else 
		return;
	switch(mod) {
		case false: 
			modifier.selectedIndex = 3;
		break;
		case PUBLIC: 
			modifier.selectedIndex = 2;
		break;
		case PROTECTED: 
			modifier.selectedIndex = 1;
		break;
		case PRIVATE: 
			modifier.selectedIndex = 0;
		break;
	}
}
/**
 * @category Show the Attribute-Form on select, depending on the class/uml-Arr
 */
function setAttributesOnSelect() {
	//Declare Vars
	var showStaticBtn     = document.getElementById("showStaticBtn");
	var showStaticChk     = document.getElementById("showStaticChk");
	var showFinalAbstract = document.getElementById("showFinalAbstract");
	var showConstDIV      = document.getElementById("showConst");
	var selectAttrMeth    = document.getElementById("selectAttrMeth");
	//Check for NULL
	if(selectAttrMeth    == null || showStaticBtn == null 
	|| showStaticChk     == null || showConstDIV  == null 
	|| showFinalAbstract == null)
		return;
	var type;       //Whereas 1 = Attribute, 2, 3, 4 = Method
	var attributes; //Currently static, final, const, abstract
	//Which one is set?
	if(detectSrc() == CLASS) {
		var indexer = (document.getElementById("umlFile") != null) 
		                ? document.getElementById("umlFile") 
		                : document.getElementById("table"); 
		type       = classArr[indexer.selectedIndex][1][selectAttrMeth.value][1];
		attributes = classArr[indexer.selectedIndex][1][selectAttrMeth.value][4];
		src        = CLASS;
	}
	else if(detectSrc() == UML){
		var indexer = (document.getElementById("table") != null) 
		                ? document.getElementById("table") 
		                : document.getElementById("phpFileClass"); 
		type        = umlArr[indexer.selectedIndex][1][selectAttrMeth.value][1];
		attributes  = umlArr[indexer.selectedIndex][1][selectAttrMeth.value][4];
		src         = UML;
	}
	else 
		return;
	if(currClassIsInterface == CLASS) {
		$("#attribute_static_btn").prop("checked", false);
		$("#attribute_static_chk").prop("checked", false);
		$("#attribute_const").prop("checked", false);
		$("#attribute_final").prop("checked", false);
		$("#attribute_abstract").prop("checked", false);
		$("#attribute_none").prop("checked", false);
		if(type == 1) {
			showStaticBtn.style.display     = "block";
			showStaticChk.style.display     = "none";
			showFinalAbstract.style.display = "none";
			showConst();
			//Check radiobtn and checkbox depending on the Arr
			if(typeof attributes[0] != NOTDEF) {
				switch(attributes[0]) {
					case CONST: 
						$("#attribute_const").prop("checked", true);
					break;
					case STATIC: 
						$("#attribute_static_btn").prop("checked", true);
					break;
				}
			}
		}
		if( (src == CLASS && (type == 2 || type == 3 || type == 4))
		 || (src == UML   && (type == 2))) {
			showStaticBtn.style.display     = "none";
			showStaticChk.style.display     = "block";
			showConstDIV.style.display      = "none";
			showFinalAbstract.style.display = "block";
			//Check radiobtn and checkbox depending on the Arr
			if(attributes != false) {
				switch(attributes.length) {
					case 1: 
						switch(attributes[0]) {
							case FINAL:  
								$("#attribute_final").prop("checked", true);
							break;
							case ABSTRACT: 
								$("#attribute_abstract").prop("checked", true);
							break;
							case STATIC:
								$("#attribute_static_chk").prop("checked", true);
								$("#attribute_none").prop("checked", true);
							break;
						}
					break;
					case 2:	
						if(attributes[0] == STATIC || attributes[1] == STATIC) 
							$("#attribute_static_chk").prop("checked", true);
						if(attributes[0] == FINAL || attributes[1] == FINAL) 
							$("#attribute_final").prop("checked", true);
						if(attributes[0] == ABSTRACT || attributes[1] == ABSTRACT) 
							$("#attribute_abstract").prop("checked", true);
					break;
				}
			}
			else 
				$("#attribute_none").prop("checked", true);
		}
	}
	else if(currClassIsInterface == INTERFACE) {
		$("#attribute_abstract_class").prop("checked", false);
		$("#attribute_final_class").prop("checked", false);
		$("#attribute_static_btn").prop("checked", false);
		$("#attribute_static_chk").prop("checked", false);
		$("#attribute_const").prop("checked", false);
		$("#attribute_final").prop("checked", false);
		$("#attribute_abstract").prop("checked", false);
		$("#attribute_none").prop("checked", false);
		if(attributes != false) 
			if(attributes.length == 1)
				if(attributes[0] == STATIC)
					$("#attribute_static_chk").prop("checked", true);
	}
}
/**
 * @category Method fill parameter Select-Field with it's parameter
 */
function setParameterOnSelect() {
	var selectAttrMeth  = document.getElementById("selectAttrMeth");
	var showParam 	    = document.getElementById("showSelectParameter");
	var selectParameter = document.getElementById("selectParameter");
	var param, type;
	if(detectSrc() == CLASS) {
		var indexer = (document.getElementById("umlFile") != null) 
		                ? document.getElementById("umlFile") 
		                : document.getElementById("table");
		param       = classArr[indexer.selectedIndex][1][selectAttrMeth.value][7];
		type        = classArr[indexer.selectedIndex][1][selectAttrMeth.value][1];
	}
	else if(detectSrc() == UML) {
		var indexer = (document.getElementById("table") != null) 
		                ? document.getElementById("table") 
		                : document.getElementById("phpFileClass"); 
		param       = umlArr[indexer.selectedIndex][1][selectAttrMeth.value][7];
		type        = umlArr[indexer.selectedIndex][1][selectAttrMeth.value][1];
	}
	else
		return;
	if((param !== false) 
	&& ((detectSrc() == CLASS && (type == 2 || type == 3 || type == 4))
	||  (detectSrc() == UML   && (type == 2)))) {
		showParam.style.display = 'block';
		var values              = [];
		var i                   = 0;
		$.each(param, function(key, value) {
			values[i++] = key;
		});
		clearSelect(selectParameter);
		fillSelect(selectParameter, values);
	}
	else 
		showParam.style.display = 'none';
}
/**
 * @category Method fills Default Value field
 */
function setDefaultValue() {
	var selectAttrMeth    = document.getElementById("selectAttrMeth");
	var defaultParamValue = document.getElementById("defaultValue");
	var param;
	
	if(detectSrc() == CLASS) {
		var indexer = (document.getElementById("umlFile") != null) 
		                ? document.getElementById("umlFile") 
		                : document.getElementById("table");
		param       = classArr[indexer.selectedIndex][1][selectAttrMeth.value][7];
	}
	else if(detectSrc() == UML) {
		var indexer = (document.getElementById("table") != null) 
		                ? document.getElementById("table") 
		                : document.getElementById("phpFileClass"); 
		param       = umlArr[indexer.selectedIndex][1][selectAttrMeth.value][7];
	}
	else
		return
    defaultParamValue.value = "";
	if(param[$("#selectParameter :selected").text()] != undefined 
	&& param[$("#selectParameter :selected").text()] != null) {
		//Encapsulate the value of the parameter in an array, so we get the array(..) syntax if it's really an array
		var params = [param[$("#selectParameter :selected").text()]]; 
		$.post("/SchemeRealizer/req/funcReq.php", { getParamStr: params })
		.done(function(data) {
			data = data.trim();
			data = lTrimStr(data, '"');
			data = lTrimStr(data, "'");
			data = rTrimStr(data, '"');
			data = rTrimStr(data, "'");
			defaultParamValue.value = data;
		});
	}
}
/**
 * @category Attr/Meth Select-Field depends on the selected File
 * @param    type(uml||sql||class)
 */
function setAttrMethPerFile(type) {
	//Declare Vars
	var select
	   ,arr
	   ,indexer;
	var selectedClassArr = null;
	switch(type) {
		case UML: 
			indexer = (document.getElementById("table") != null) 
			            ? document.getElementById("table") 
			            : document.getElementById("phpFileClass");
			select  = document.getElementById("selectAttrMeth");
			var j   = 0;
			arr     = [];
			//Exclude the Class/Interface
			for(var i = 0; i < umlArr[indexer.selectedIndex][1].length; i++) {
				var membertype = umlArr[indexer.selectedIndex][1][i][1];
				if(membertype == 1
				|| membertype == 2) {
					arr[j] = umlArr[indexer.selectedIndex][1][i];
					j++;
				}
			}
			selectedClassArr = umlArr[indexer.selectedIndex][1];
		break;
		case CLASS:
			if(document.getElementById("umlFile") != null) {
				indexer = document.getElementById("umlFile");
				var j   = 0;
				arr     = [];
				//Exclude the Class/Interface
				for(var i = 0; i < classArr[indexer.selectedIndex][1].length; i++) {
					var membertype = classArr[indexer.selectedIndex][1][i][1];
					if(membertype == 1
					|| membertype == 2
					|| membertype == 3
					|| membertype == 4) {
						arr[j] = classArr[indexer.selectedIndex][1][i];
						j++;
					}
				}
			}
			else {
				indexer = document.getElementById("table");
				var tmpArr = []; //tmpArr which collects all Attr given in the classArr
				var j      = 0;
				for(var i = 0; i < classArr[indexer.selectedIndex][1].length; i++) {
					if(classArr[indexer.selectedIndex][1][i][1] == 1) {
						tmpArr[j] = classArr[indexer.selectedIndex][1][i][0];
						j++;
					}
				}
				//Add GET: SET: for more usability
				var names = ((tmpArr).concat(arrStrToElem(tmpArr, "get", 0))).concat(arrStrToElem(tmpArr, "set", 0));
				/**
				 * REASON:
				 * 0 : { 0: name },
				 * 1 : { 0: name },
				 * 2 : { 0: name }
				 */
				arr = [];
				for(var j = 0; j < names.length; j++) {
					arr[j]    = [];
					arr[j][0] = names[j];
				}
			}
			select           = document.getElementById("selectAttrMeth");
			selectedClassArr = classArr[indexer.selectedIndex][1];
		break;
		case SQL: 
			indexer = (document.getElementById("umlFile") != null) ? document.getElementById("umlFile") : document.getElementById("phpFileClass");
			select  = document.getElementById("column");
			arr     = sqlArr[indexer.selectedIndex]; //Get the SQL-File
		break;
		default: 
			return;
	}
	clearSelect(select); //Clear the Select-Field
	//Get the Attr
	var Attr = [];
	for(var i = 0; i < arr.length; i++)
		Attr[i] = arr[i][0];
	fillSelect(select, Attr); //Fill the Select-Field
	//Deactive modifier select and attributes if it's an interface
	if(selectedClassArr != null) {
		classIsInterface(selectedClassArr, function(classType) {
			currClassIsInterface  = classType;
			var classAttr         = document.getElementById("classAttr");
			var modifierSelect    = document.getElementById("modifierSelect");
			var showFinalAbstract = document.getElementById("showFinalAbstract");
			var showConst         = document.getElementById("showConst");
			var showStaticBtn     = document.getElementById("showStaticBtn");
			var showStaticChk     = document.getElementById("showStaticChk");
			var showAttrValue     = document.getElementById("showAttrValue");
			
			if(classType == INTERFACE) {
				if(classAttr != null)
					classAttr.style.display = NONE;
				if(modifierSelect != null)
					modifierSelect.style.display = NONE;
				if(showFinalAbstract != null)
					showFinalAbstract.style.display = NONE;
				if(showConst != null)
					showConst.style.display = NONE;
				if(showStaticBtn != null)
					showStaticBtn.style.display = NONE;
				if(showAttrValue != null)
					showAttrValue.style.display = NONE;
				if(showStaticChk != null)
					showStaticChk.style.display = "block";
			}
			else if(classType == CLASS) {
				if(classAttr != null)
					classAttr.style.display = "block";
				if(modifierSelect != null)
					modifierSelect.style.display = "block";
			}
			setAttributesOnSelect();
		});
	}
	//Now set the Modifier and Attributes
	setModifierOnSelect();
	setAttributesOfClass();
	setParentsInterfaces();
	setParameterOnSelect();
	setDefaultValue();
	setAttrValue();
}
/**
 * @category Function to handle the attribute value
 */
function setAttrValue() {
	var selectAttrMeth = document.getElementById("selectAttrMeth");
	var showAttrValue  = document.getElementById("showAttrValue");
	var attrValue      = document.getElementById("attrValue");
	var attrVal, type;
	
	if(detectSrc() == CLASS) {
		var indexer = (document.getElementById("umlFile") != null) 
		                ? document.getElementById("umlFile") 
		                : document.getElementById("table");
	    attrVal     = classArr[indexer.selectedIndex][1][selectAttrMeth.value][7];
	    type        = classArr[indexer.selectedIndex][1][selectAttrMeth.value][1];
	}
	else if(detectSrc() == UML) {
		var indexer = (document.getElementById("table") != null) 
		                ? document.getElementById("table") 
		                : document.getElementById("phpFileClass"); 
		attrVal     = umlArr[indexer.selectedIndex][1][selectAttrMeth.value][7];
		type        = umlArr[indexer.selectedIndex][1][selectAttrMeth.value][1];
	}
	else
		return
    attrValue.value = "";
	if(attrVal !== false && type == 1) {
		showAttrValue.style.display = 'block';
		$.post("/SchemeRealizer/req/funcReq.php", { getAttrValStr: attrVal })
		.done(function(data) {
			data = data.trim();
			data = lTrimStr(data, '"');
			data = lTrimStr(data, "'");
			data = rTrimStr(data, '"');
			data = rTrimStr(data, "'");
			attrValue.value = data;
		});
	}
	else 
		showAttrValue.style.display = 'none';
}
/**
 * @category Make attribute value visible/invisible
 * @param    1 = visible, 0 = invisible
 */
function showAttrValue(visible) {
	var showAttrValue  = document.getElementById("showAttrValue");
	if(showAttrValue != null) {
		if(visible)
			showAttrValue.style.display = 'block';
		else
			showAttrValue.style.display = 'none';
	}
}
/**
 * @category Function to check the radio-btn if the class is abstract/final
 */
function setAttributesOfClass() {
	if(document.getElementById("attribute_final_class")	   == null 
	|| document.getElementById("attribute_abstract_class") == null
	|| document.getElementById("attribute_none_class")     == null) 
		return;
	var attributes, indexer;
	if(detectSrc() == CLASS) {
		indexer = (document.getElementById("umlFile") != null) 
		            ? document.getElementById("umlFile") 
		            : document.getElementById("table");
		for(var i = 0; i < classArr[indexer.selectedIndex][1].length; i++) {
			if(classArr[indexer.selectedIndex][1][i][1] == 5
			|| classArr[indexer.selectedIndex][1][i][1] == 6) {
				attributes = classArr[indexer.selectedIndex][1][i][4];
				break;
			}
		}
	}
	else if(detectSrc() == UML) {
		indexer = (document.getElementById("table") != null) 
		            ? document.getElementById("table") 
		            : document.getElementById("phpFileClass");
		for(var i = 0; i < umlArr[indexer.selectedIndex][1].length; i++) {
			if(umlArr[indexer.selectedIndex][1][i][1] == 3
			|| umlArr[indexer.selectedIndex][1][i][1] == 4) {
				attributes = umlArr[indexer.selectedIndex][1][i][4];
				break;
			}
		}
	}
	else
		return;

	if(attributes != false) {
		if(attributes == ABSTRACT) {
			$("#attribute_abstract_class").prop("checked", true);
		}
		else if(attributes == FINAL) {
			$("#attribute_final_class").prop("checked", true);
		}
	}
	else {
		$("#attribute_none_class").prop("checked", true);
		$("#attribute_abstract_class").prop("checked", false);
		$("#attribute_final_class").prop("checked", false);
	}
}
/**
 * @category Function to adapt the DBMS-Form depending on the SQL-Arr content
 */
function showColumn() {
	var indexer = (document.getElementById("umlFile") != null) 
	                ? document.getElementById("umlFile") 
	                : document.getElementById("phpFileClass");
	var elem     = document.getElementById("column");
	var datatype = document.getElementById("datatype");
	var dmbs	 = document.getElementById("dbms").value.toLowerCase();
	if(dmbs != "mongodb") {
		if(!sqlArr[indexer.selectedIndex][elem.selectedIndex]) 
			return;
		for(var i = 0; i < datatype.length; i++) 
			if(sqlArr[indexer.selectedIndex][elem.selectedIndex][1] == datatype.options[i].text) 
				datatype.selectedIndex = i;
		if(sqlArr[indexer.selectedIndex][elem.selectedIndex][4] == 1)
			document.getElementById("null").checked = true;
		else
			document.getElementById("null").checked = false;
		if(sqlArr[indexer.selectedIndex][elem.selectedIndex][5] == 1)
			document.getElementById("ai").checked = true;
		else
			document.getElementById("ai").checked = false;
		if(sqlArr[indexer.selectedIndex][elem.selectedIndex][3] == 1) 
			document.getElementById("index").selectedIndex = 1;
		else if(sqlArr[indexer.selectedIndex][elem.selectedIndex][3] == 2)
			document.getElementById("index").selectedIndex = 2;
		else if(sqlArr[indexer.selectedIndex][elem.selectedIndex][3] == -1)
			document.getElementById("index").selectedIndex = 0;
		if(sqlArr[indexer.selectedIndex][elem.selectedIndex][7] != false) 
			document.getElementById("default").value = sqlArr[indexer.selectedIndex][elem.selectedIndex][7];
		else
			document.getElementById("default").value = '';
		//MySQL/MariaDB relevant
		if(dbms == "mysql") {
			if(sqlArr[indexer.selectedIndex][elem.selectedIndex][3] == 3)
				document.getElementById("index").selectedIndex = 3;
			document.getElementById("size").value = sqlArr[indexer.selectedIndex][elem.selectedIndex][2];
		}
	}
	changeDBMSForm();
}
/**
 * @category Function to display/hide the const attribute
 */
function showConst() {
	if(document.getElementById("attribute_const") != null 
	&& document.getElementById("modifier")        != null) {
		//Which one is set?
		if(detectSrc() == CLASS) {
			var indexer = (document.getElementById("umlFile") != null) 
			                ? document.getElementById("umlFile") 
			                : document.getElementById("table"); 
			type = classArr[indexer.selectedIndex][1][selectAttrMeth.value][1];
		}
		else if(detectSrc() == UML) {
			var indexer = (document.getElementById("table") != null) 
			                ? document.getElementById("table") 
			                : document.getElementById("phpFileClass"); 
			type = umlArr[indexer.selectedIndex][1][selectAttrMeth.value][1];		
		}
		else
			return;
		if(document.getElementById("modifier").selectedIndex == 3 && type == 1) 
			document.getElementById("showConst").style.display = "block";
		else {
			document.getElementById("showConst").style.display = "none";
			$("#attribute_const").prop("checked", false);
		}
	}
}

/**
 * @category Functino to display the selected MongoDB View
 */
function showMongoView() {
	var selaction = document.getElementById("selaction").value;
	if(selaction == 1) {
		document.getElementById("dbname").style.display = "block";
		document.getElementById("colparamdiv").style.display = "none";
		document.getElementById("addVal").style.display = "none";
	}
	else if(selaction == 2) {
		document.getElementById("dbname").style.display = "none";
		document.getElementById("colparamdiv").style.display = "block";
		document.getElementById("addVal").style.display = "none";
	}
	else if(selaction == 3) {
		document.getElementById("dbname").style.display = "none";
		document.getElementById("colparamdiv").style.display = "none";
		document.getElementById("addVal").style.display = "block";
	}
}