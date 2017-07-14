package test.java;
import java.util.Iterator;

import junit.framework.TestCase;
import main.java.ClassObj;
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
 * @version  1.0
 * @category JUnit-Test for the Java parser
 * @since    29.06.2017
 */
public class java_parseCase extends TestCase {
	/**
	 * @category test constructor
	 */
	public void testConstructException() {
		try {
			@SuppressWarnings("unused")
			java_parse jp = new java_parse("/home/michael/workspace/SchemeRealizer/java/bin/test/java"
                                          ,"Person.class");	
		}
		catch(java_parseException e) {
			assertEquals(true, e.getMessage().contains("Person.class is not a valid java class"));
		}
	}
	/**
	 * @category test setClassMembers
	 */
	public void testSetClassMembers() throws java_parseException {
		java_parse jp = new java_parse("/home/michael/workspace/SchemeRealizer/java/bin/test/java"
                					  ,"test.java.Person");	
		jp.setClassMembers();
		Iterator<ClassObj> it = jp.getClList().iterator();
		int i                 = 0;
		while(it.hasNext()) {
			ClassObj obj = it.next();
			switch(i++) {
				case 0:
					assertEquals("name", obj.getName());
					assertEquals("private", obj.getModifier());
					assertEquals(ClassObj.ATTRKEY, obj.getKey());
					assertEquals(null, obj.getKeywords());
					assertEquals(true, obj.isSelect());
					assertEquals(null, obj.getParents());
					assertEquals(null, obj.getInterfaces());
					assertEquals("Michael", obj.getValue());
				break;
				case 1:
					assertEquals("age", obj.getName());
					assertEquals("private", obj.getModifier());
					assertEquals(ClassObj.ATTRKEY, obj.getKey());
					assertEquals(null, obj.getKeywords());
					assertEquals(true, obj.isSelect());
					assertEquals(null, obj.getParents());
					assertEquals(null, obj.getInterfaces());
					assertEquals("23", obj.getValue());
				break;
			}
		}
	}
	/**
	 * @category test removePackage
	 */
	public void testRemovePackage() {
		assertEquals(java_parse.removePackage("test.java.Person"), "Person");
		assertEquals(java_parse.removePackage("hasnodot"), "hasnodot");
	}
	/**
	 * @category test setClass
	 */
	public void testSetClass() throws java_parseException {
		java_parse jp = new java_parse("/home/michael/workspace/SchemeRealizer/java/bin/test/java"
				                      ,"test.java.Person");	
		jp.setClass();
		ClassObj clObj = jp.getClList().get(0);
		assertEquals(clObj.getName(), "Person");
		assertEquals(clObj.getKey(), 3);
		assertEquals(clObj.getModifier(), "public");
		assertEquals(clObj.isSelect(), true);
		assertEquals(clObj.getKeywords(), null);
		assertEquals(clObj.getParents(), null);
		assertEquals(clObj.getInterfaces(), null);
		assertEquals(clObj.getValue(), null);
	}
	/**
	 * @category test setMethod
	 */
	public void testSetMethod() throws java_parseException {
		java_parse jp = new java_parse("/home/michael/workspace/SchemeRealizer/java/bin/test/java"
                                      ,"test.java.Person");	
		jp.setMethods();
		Iterator<ClassObj> it = jp.getClList().iterator();
		while(it.hasNext()) {
			ClassObj clObj = it.next();
			if(clObj.getName().equals("getName")) {
				assertEquals(ClassObj.METHKEY, clObj.getKey());
				assertEquals("public", clObj.getModifier());
				assertEquals(true, clObj.isSelect());
				assertEquals(null, clObj.getKeywords());
				assertEquals(null, clObj.getParents());
				assertEquals(null, clObj.getInterfaces());
				assertEquals(null, clObj.getValue());
			}
			else if(clObj.getName().equals("setName")) {
				assertEquals(ClassObj.METHKEY, clObj.getKey());
				assertEquals("public", clObj.getModifier());
				assertEquals(true, clObj.isSelect());
				assertEquals(null, clObj.getKeywords());
				assertEquals(null, clObj.getParents());
				assertEquals(null, clObj.getInterfaces());
				assertEquals(null, clObj.getValue());
			}
		}
	}
}
