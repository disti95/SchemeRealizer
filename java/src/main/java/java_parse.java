package main.java;
import java.io.File;
import java.io.StringWriter;
import java.lang.reflect.Field;
import java.lang.reflect.Method;
import java.lang.reflect.Modifier;
import java.util.ArrayList;
import java.util.Iterator;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.transform.TransformerFactory;
import javax.xml.transform.dom.DOMSource;
import javax.xml.transform.stream.StreamResult;
import org.w3c.dom.Document;
import org.w3c.dom.Element;

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
 * @category Java Parser
 * @since    28.07.2017
 */
public class java_parse {
	private String dir;
	private String className;
	private ArrayList<String> errorStack = new ArrayList<String>();
	private String LineSep               = System.getProperty("line.separator");
	private ArrayList<ClassObj> clList   = new ArrayList<ClassObj>();
	/**
	 * @category construct
	 */
	public java_parse(String dir, String className) throws java_parseException {
		this.setDir(dir);
		this.setClassName(className);
		String res = this.basicClassValidation();
		if(res != null) {
			this.errorStack.add(res);
			this.throwErrorStack();
		}
	}
	/**
	 * @category getter for member dir
	 * @return   value of member dir
	 */
	public String getDir() {
		return this.dir;
	}
	/**
	 * @category setter for member dir
	 * @param    String dir
	 */
	public void setDir(String dir) {
		this.dir = dir;
	}
	/**
	 * @category setter for member className
	 * @param    String className
	 */
	public void setClassName(String className) {
		this.className = className;
	}
	/**
	 * @category getter for member className
	 * @return   value of member className
	 */
	public String getClassName() {
		return this.className;
	}
	/**
	 * @category set class
	 */
	public void setClass() throws java_parseException {
		try {
			Class<?> cl = Class.forName(this.getClassName());
			ClassObj clObj = new ClassObj(java_parse.removePackage(cl.getName())
					                     ,Modifier.isInterface(cl.getModifiers())
					                        ? 4
					                        : 3
					                     ,this.getModifierFromClass(cl)
					                     ,true
					                     ,this.getKeywordsFromClass(cl)
					                     ,this.getParentsFromClass(cl)
					                     ,this.getInterfacesFromClass(cl)
					                     ,null);
			this.getClList().add(clObj);
		}
		catch(ClassNotFoundException e) {
			throw new java_parseException(e.getMessage() + this.LineSep);
		}
	}
	/**
	 * @category set class members
	 */
	public void setClassMembers() throws java_parseException {
		try {
			Class<?> obj = Class.forName(this.getClassName());
			for(Field f : obj.getDeclaredFields()) {
				ClassObj clObj = new ClassObj(f.getName()
						                     ,ClassObj.ATTRKEY
						                     ,this.getModifierFromField(f)
						                     ,true
						                     ,this.getKeywordsFromField(f)
						                     ,null
						                     ,null
						                     ,this.getFieldValue(f.getName())); 
				this.getClList().add(clObj);
			}
		}
		catch(ClassNotFoundException e) {
			throw new java_parseException(e.getMessage() + this.LineSep);
		}
 	}
	/**
	 * @category set methods
	 */
	public void setMethods() throws java_parseException {
		try {
			Class<?> obj = Class.forName(this.getClassName());
			for(Method m : obj.getMethods()) {
				ClassObj clObj = new ClassObj(m.getName()
						                     ,ClassObj.METHKEY
						                     ,this.getModifierFromMethod(m)
						                     ,true
						                     ,this.getKeywordsFromMethod(m)
						                     ,null
						                     ,null
						                     ,null);
				this.getClList().add(clObj);
			}
		} 
		catch(ClassNotFoundException e) {
			throw new java_parseException(e.getMessage());
		}
	}
	/**
	 * @category get modifier from Field-Object
	 * @return   modifier or null on error
	 */
	public String getModifierFromField(Field f) {
		if(Modifier.isPublic(f.getModifiers()))
			return "public";
		else if(Modifier.isPrivate(f.getModifiers()))
			return "private";
		else if(Modifier.isProtected(f.getModifiers()))
			return "protected";
		return null;
	}
	/**
	 * @category get modifier from Class-Object
	 * @return   modifier or null
	 */
	public String getModifierFromClass(Class<?> c) {
		if(Modifier.isPublic(c.getModifiers()))
			return "public";
		else if(Modifier.isPrivate(c.getModifiers()))
			return "private";
		else if(Modifier.isProtected(c.getModifiers()))
			return "protected";
		return null;
	}
	/**
	 * @category get modifier from Method-Object
	 * @return   modifier or null on error
	 */
	public String getModifierFromMethod(Method m) {
		if(Modifier.isPrivate(m.getModifiers()))
			return "private";
		else if(Modifier.isPublic(m.getModifiers()))
			return "public";
		else if(Modifier.isProtected(m.getModifiers()))
			return "protected";
		return null;
	}
	/**
	 * @category get keywords from Method-Object
	 */
	public String[] getKeywordsFromMethod(Method m) {
		ArrayList<String> keyList = new ArrayList<String>();
		if(Modifier.isAbstract(m.getModifiers()))
			keyList.add("abstract");
		if(Modifier.isStatic(m.getModifiers()))
			keyList.add("static");
		if(Modifier.isSynchronized(m.getModifiers()))
			keyList.add("synchronized");
		if(Modifier.isNative(m.getModifiers()))
			keyList.add("native");
		if(Modifier.isFinal(m.getModifiers()))
			keyList.add("final");
		if(Modifier.isStrict(m.getModifiers()))
			keyList.add("strictfp");
		return (keyList.size() > 0)
			     ? keyList.toArray(new String[0])
			     : null;
	}
	/**
	 * @category get keywords from Class-Object
	 */
	public String[] getKeywordsFromClass(Class<?> c) {
		ArrayList<String> keyList = new ArrayList<String>();
		if(Modifier.isAbstract(c.getModifiers()))
			keyList.add("abstract");
		if(Modifier.isStatic(c.getModifiers()))
			keyList.add("static");
		if(Modifier.isFinal(c.getModifiers()))
			keyList.add("final");
		if(Modifier.isStrict(c.getModifiers()))
			keyList.add("strictfp");
		return (keyList.size() > 0)
			     ? keyList.toArray(new String[0])
			     : null;
	}
	/**
	 * @category get keywords from Field-Object
	 * @return   String[] or null
	 */
	public String[] getKeywordsFromField(Field f) {
		ArrayList<String> keyList = new ArrayList<String>();
		if(Modifier.isFinal(f.getModifiers()))
			keyList.add("final");
		if(Modifier.isStatic(f.getModifiers()))
			keyList.add("static");
		if(Modifier.isVolatile(f.getModifiers()))
			keyList.add("volatile");
		if(Modifier.isTransient(f.getModifiers()))
			keyList.add("transient");
		return (keyList.size() > 0)
			     ? keyList.toArray(new String[0])
			     : null;
	}
	/**
	 * @category get value from Field
	 * @return   String or null
	 */
	public String getFieldValue(String fieldName) throws java_parseException { 
		 try {
			Class<?> c = Class.forName(this.getClassName());
 			Object obj = c.newInstance();
			Field  f   = c.getDeclaredField(fieldName);
			f.setAccessible(true);
			if(f.getType() == int.class
			|| f.getType() == double.class
			|| f.getType() == char.class
			|| f.getType() == String.class
			|| f.getType() == long.class
			|| f.getType() == boolean.class
			|| f.getType() == short.class
			|| f.getType() == float.class
			|| f.getType() == boolean.class
			|| f.getType() == byte.class) {
				if(f.get(obj) != null)
					return f.get(obj).toString();
				else
					return null;
			}
		} 
		catch (InstantiationException e) {
			throw new java_parseException(e.getMessage() + this.LineSep);
		} 
		catch (IllegalAccessException e) {
			throw new java_parseException(e.getMessage() + this.LineSep);
		} 
		catch (ClassNotFoundException e) {
			throw new java_parseException(e.getMessage() + this.LineSep);
		} 
		catch (NoSuchFieldException e) {
			throw new java_parseException(e.getMessage() + this.LineSep);
		} 
		catch (SecurityException e) {
			throw new java_parseException(e.getMessage() + this.LineSep);
		}
		return null;
	}
	/**
	 * @category get class parent
	 * @return   String[] or null
	 */
	public String[] getParentsFromClass(Class<?> c) throws java_parseException {
		String[] superClass = {null};
		if(c.getSuperclass() != null
 	   && !java_parse.removePackage(c.getSuperclass().getName()).equals("Object"))
			superClass[0] = java_parse.removePackage(c.getSuperclass().getName());
		return (superClass[0] != null)
		         ? superClass
			     : null;
	}
	/**
	 * @category get interfaces from class
	 * @return   String[] or null
	 */
	public String[] getInterfacesFromClass(Class<?> c) throws java_parseException {
		ArrayList<String> iList = new ArrayList<String>();
		for(Class<?> i : c.getInterfaces()) {
			iList.add(java_parse.removePackage(i.getName()));
		}
		return (iList.size() > 0)
			     ? iList.toArray(new String[0])
			     : null;
	}
	/**
	 * @category remove package from string
	 * @param    String
	 * @return   String
	 */
	public static String removePackage(String s) {
		int pos = s.lastIndexOf(".");
		return s.substring((pos == -1)
				             ? 0
				             : pos + 1);
	}
	/**
	 * @category getter for member clList
	 * @return   value of clList
	 */
	public ArrayList<ClassObj> getClList() {
		return this.clList;
	}
	/**
	 * @category create XML out of clList
	 */
	public String createXML() throws java_parseException {
		try {
			DocumentBuilderFactory docFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder docBuilder        = docFactory.newDocumentBuilder();
			Document doc                      = docBuilder.newDocument();		
			Element root                      = doc.createElement("JavaParser");
			doc.appendChild(root);
			Iterator<ClassObj> it             = this.getClList().iterator();
			while(it.hasNext()) {
				Element objNode    = null;
				ClassObj clObj     = it.next();
				Element name       = doc.createElement("name");
				Element key        = doc.createElement("key") ;
				Element modifier   = doc.createElement("modifier");
				Element select     = doc.createElement("select");
				Element keywords   = doc.createElement("keywords");
				Element parents    = doc.createElement("parent");
				Element interfaces = doc.createElement("interfaces");
				Element value      = doc.createElement("value");
				name.appendChild(doc.createTextNode(clObj.getName()));
				key.appendChild(doc.createTextNode(Integer.toString(clObj.getKey())));
				if(clObj.getModifier() != null)
					modifier.appendChild(doc.createTextNode(clObj.getModifier()));
				select.appendChild(doc.createTextNode(Boolean.toString(clObj.isSelect())));
				if(clObj.getKeywords() != null) {
					for(String s : clObj.getKeywords()) {
						keywords.appendChild(doc.createTextNode(s));	
					}
				}
				if(clObj.getParents() != null) 
					parents.appendChild(doc.createTextNode(clObj.getParents()[0]));
				if(clObj.getInterfaces() != null) {
					for(String s : clObj.getInterfaces()) {
						interfaces.appendChild(doc.createTextNode(s));
					}
				}
				if(clObj.getValue() != null)
					value.appendChild(doc.createTextNode(clObj.getValue()));
				switch(clObj.getKey()) {
					case ClassObj.ATTRKEY:
						objNode = doc.createElement("Attribute");
					break;
					case ClassObj.METHKEY:
						objNode = doc.createElement("Method");
					break;
					case ClassObj.IFKEY:
					case ClassObj.CLKEY:
						objNode = doc.createElement("Class");
					break;
				}
				objNode.appendChild(name);
				objNode.appendChild(key);
				objNode.appendChild(modifier);
				objNode.appendChild(select);
				objNode.appendChild(keywords);
				objNode.appendChild(parents);
				objNode.appendChild(interfaces);
				objNode.appendChild(value);
				root.appendChild(objNode);
			}
			TransformerFactory tf   = TransformerFactory.newInstance();
			Transformer transformer = tf.newTransformer();
			StringWriter writer     = new StringWriter();
			transformer.transform(new DOMSource(doc), new StreamResult(writer));
			return writer.getBuffer().toString();
		} 
		catch (ParserConfigurationException e) {
			throw new java_parseException(e.getMessage());
		} 
		catch (TransformerConfigurationException e) {
			throw new java_parseException(e.getMessage());
		} 
		catch (TransformerException e) {
			throw new java_parseException(e.getMessage());
		}
	}
	/**
	 * @category throw error stack
	 */
	public void throwErrorStack() throws java_parseException {
		Iterator<String> it = this.errorStack.iterator();
		String err          = this.LineSep;
		while(it.hasNext()) 
			err = err + it.next();
		throw new java_parseException(err);
	}
	/**
	 * @category basic class validation
	 * @return   null or error message
	 */
	public String basicClassValidation() {
		File f     = new File(this.getDir());
		String out = "";
		if(!f.exists())
			out += this.getDir() + " does not exist!" + this.LineSep;
		if(!f.canRead())
			out += this.getDir() + " is not readable!" + this.LineSep;
		if(!f.isDirectory())
			out += this.getDir() + " is not a directory!" + this.LineSep;
		try {
			Class.forName(this.getClassName());
		}
		catch(ClassNotFoundException e) {
			out += this.getDir() + ":" + this.getClassName() + " is not a valid java class!" + this.LineSep;
		}
		return (out.length() > 0)
			     ? out
			     : null;
	}
}
