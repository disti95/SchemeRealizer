<?php 
	/*
	 SchemeRealizer - A free ORM and Translator for Diagrams, Databases and PHP-Classes.
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
	 * @category Test class to check if errors are detected
	 * @version  1.0
	 * @since    05.06.2017
	 */
	class ExtendsClass  {}
	class ExtendsClass2 {}
	class IFace         {}
	class IFace2        {}
	class PHP_Parser_ErrorClass implements IFace extends ExtendsClass{} //keyword extends after keyword implements
	class PHP_Parser_ErrorClass extends ExtendsClass, ExtendsClass2{}   //multiple superclasses