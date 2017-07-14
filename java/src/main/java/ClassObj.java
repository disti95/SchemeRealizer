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
 * @author   Michael Watzer
 * @version  1.0
 * @category Class to reflect a class object
 * @since    01.07.2017
 */
public class ClassObj {
	private String   name;
	private int      key;
	private String   modifier;
	private boolean  select;
	private String[] keywords;
	private String[] parents;
	private String[] interfaces;
	private String   value;
	public static final int ATTRKEY = 1;
	public static final int METHKEY = 2;
	public static final int CLKEY   = 3;
	public static final int IFKEY   = 4;
	
	/**
	 * @category construct
	 */
	public ClassObj(String   name
			       ,int      key
			       ,String   modifier
			       ,boolean  select
			       ,String[] keywords
			       ,String[] parents
			       ,String[] interfaces
			       ,String   value) {
		super();
		this.setName(name);
		this.setKey(key);
		this.setModifier(modifier);
		this.setSelect(select);
		this.setKeywords(keywords);
		this.setParents(parents);
		this.setInterfaces(interfaces);
		this.setValue(value);
	}
	/**
	 * @category Getter/Setter
	 */
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public int getKey() {
		return key;
	}
	public void setKey(int key) {
		this.key = key;
	}
	public String getModifier() {
		return modifier;
	}
	public void setModifier(String modifier) {
		this.modifier = modifier;
	}
	public boolean isSelect() {
		return select;
	}
	public void setSelect(boolean select) {
		this.select = select;
	}
	public String[] getKeywords() {
		return keywords;
	}
	public void setKeywords(String[] keywords) {
		this.keywords = keywords;
	}
	public String[] getParents() {
		return parents;
	}
	public void setParents(String[] parents) {
		this.parents = parents;
	}
	public String[] getInterfaces() {
		return interfaces;
	}
	public void setInterfaces(String[] interfaces) {
		this.interfaces = interfaces;
	}
	public String getValue() {
		return value;
	}
	public void setValue(String value) {
		this.value = value;
	}
}
