package test.java;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;
import java.net.InetSocketAddress;
import java.net.Socket;
import java.net.UnknownHostException;

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
 * @category Socket-Testing
 * @since    13.07.2017
 */
public class SocketTest {
	private static Socket c;
	public static void main(String[] args) throws UnknownHostException, IOException {
		c           = new Socket();
		c.connect(new InetSocketAddress("localhost", 32727), 10*1000);
		String msg  = "<?xml version='1.0' encoding='utf-8'?>";
		       msg += "<JavaParse>"
		       		+ "  <dir>/home/michael/workspace/SchemeRealizer/java/bin/test/java</dir>"
		            + "  <class>test.java.Person</class>"
		            + "</JavaParse>";
		PrintWriter pw    = new PrintWriter(new OutputStreamWriter(c.getOutputStream()));
		pw.println(msg);
		pw.flush();
		BufferedReader br = new BufferedReader(new InputStreamReader(c.getInputStream()));
		String line       = br.readLine();
		System.out.println(line);
		br.close();
		pw.close();
		c.close();
	}
}
