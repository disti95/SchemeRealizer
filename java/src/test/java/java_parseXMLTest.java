package test.java;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;
import main.java.java_parse;
import main.java.java_parseException;

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
 * @author   Michael Watzer
 * @since    13.07.2017
 * @category CLI-Test for the XML creation
 * @version  1.0
 */

public class java_parseXMLTest {
	public static void main(String[] args) throws java_parseException, IOException {
		java_parse jp = new java_parse("/home/michael/workspace/SchemeRealizer/java/bin/test/java"
				  					  ,"test.java.Person");	
		jp.setClass();
		jp.setClassMembers();
		jp.setMethods();
		FileWriter fw     = new FileWriter("/home/michael/workspace/SchemeRealizer/java/src/test/resources/Person.xml");
		BufferedWriter bw = new BufferedWriter(fw);
		bw.write(jp.createXML());
		bw.close();
		fw.close();
	}
}
