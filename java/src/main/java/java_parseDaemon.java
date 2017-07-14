package main.java;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.io.StringReader;
import java.net.Socket;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import org.w3c.dom.Document;
import org.xml.sax.InputSource;
import org.xml.sax.SAXException;

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
 * @category Socket(Listener)
 * @since    03.07.2017
 */
public class java_parseDaemon extends Thread {
	private BufferedReader br;
	private PrintWriter    pw;
	private Socket         socket;
	
	public java_parseDaemon(Socket socket) {
		this.socket = socket;
	}
	public void run() {
		try {
			this.br = new BufferedReader(new InputStreamReader(socket.getInputStream()));
			this.pw = new PrintWriter(socket.getOutputStream(), true);
			java_parseMain.writers.add(pw);
			while(true) {
				String input = br.readLine();
				if(!input.isEmpty()) {
					for(PrintWriter pw : java_parseMain.writers) {
						DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
						DocumentBuilder builder        = factory.newDocumentBuilder();
						Document doc                   = builder.parse(new InputSource(new StringReader(input)));
						String dir                     = doc.getElementsByTagName("dir").item(0).getTextContent();
						String cl                      = doc.getElementsByTagName("class").item(0).getTextContent();
						java_parse jp                  = new java_parse(dir, cl);
						jp.setClass();
						jp.setClassMembers();
						jp.setMethods();
						pw.println(jp.createXML());
					}
				}
			}
		}
		catch(IOException e) {
			System.out.println(e.getMessage());
		}
		catch(java_parseException e) {
			System.out.println(e.getMessage());
		} 
		catch (SAXException e) {
			System.out.println(e.getMessage());
		} 
		catch (ParserConfigurationException e) {
			System.out.println(e.getMessage());
		}
		finally {
			try {
				socket.close();
			}
			catch(IOException e) {
				System.out.println(e.getMessage());
			}
		}
	}
}
