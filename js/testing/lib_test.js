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
 * @since    14.05.2017
 * @category Testing the Standard JS-Library.
 */

/******************************************************************************
 * Testing
 ******************************************************************************/

var lib = require("./commonjs/lib.js");
var ret = lib.in_array(['private'
                       ,'static'
                       ,['const'
                        ,['protected']
                        ,['abstract'
                         ,'final']
                        ]
                       ],'abstract');

if(ret)
	console.log('function in_array OK!');
else
	console.log('function in_array NOK!');

ret = lib.checkClassModifier("static");

if(!ret)
	console.log('function checkClassModifier OK!');
else
	console.log('function checkClassModifier NOK!');