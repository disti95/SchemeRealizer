package test.java;
import junit.framework.TestCase;

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
 * @category JUnit-Test for Person
 * @since    29.06.2017
 */
public class personCase extends TestCase {
	/**
	 * @category test Person class
	 */
	public void testPerson() {
		Person p = new Person("Michael Watzer", 23);
		TestCase.assertTrue(p.getName().equals("Michael Watzer"));
		TestCase.assertTrue(p.getAge() == 23);
	}
}
