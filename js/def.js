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
 * @category Global variable definitions.
 */
/*******************************************************************************
** Memory variables
*******************************************************************************/
var sqlArr 						= []; 	//Array to support the SQL-Code generation
var classArr 					= []; 	//Array to support the PHP-Code generation
var NOSQLArr					= [];	//Array to support the NOSQL-Colde generation
var umlArr 						= []; 	//Array to support the UML-Code generation
var shifter 					= 0; 	//To detect double clicks
var detectparentinterfaceselect = -1;	//To detect which parent/interface select is used
var currClassIsInterface        = "";
/*******************************************************************************
** Control variables, has to be consistent with the backend
*******************************************************************************/
var NOTDEF                      = 'undefined';
var CLASS                       = 'class';
var UML                         = 'uml';
var SQL                         = 'sql';
var NOSQL                       = 'nosql';
var FINAL                       = 'final';
var ABSTRACT                    = 'abstract';
var STATIC                      = 'static';
var CONST                       = 'const';
var INTERFACE                   = 'interface';
var PUBLIC                      = 'public';
var PROTECTED                   = 'protected';
var PRIVATE                     = 'private';
var DB                          = 'database';
var NONE                        = 'none';