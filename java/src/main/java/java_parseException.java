package main.java;

/*
SchemeRealizer - A free ORM and Translator for Diagrams, Databases and PHP-Classes/Interfaces.
Copyright (C) 2017  Michael Watzer/Christian Dittrich

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
 * @category Derivated exception class
 * @author   Michael Watzer
 * @version  1.0
 * @since    28.07.2017
 */
public class java_parseException extends Exception {
	private static final long serialVersionUID = 1L;
	public java_parseException(String msg) {
		super(msg);
	}
}