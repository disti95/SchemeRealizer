package test.java;

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
 * @since    29.06.2017
 * @category Test class
 */
public class Person {
	private String name = "Michael";
	private int    age  = 23;

	/**
	 * @category construct
	 */
	public Person(String name, int age) {
		this.setName(name);
		this.setAge(age);
	}
	public Person() {}
	/**
	 * @category getter
	 */
	public String getName() {
		return this.name;
	}
	public int getAge() {
		return this.age;
	}
	/**
	 * @category setter
	 */
	public void setName(String name) {
		this.name = name;
	}
	public void setAge(int age) {
		this.age = age;
	}
}