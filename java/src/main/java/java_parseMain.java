package main.java;
import java.io.IOException;
import java.io.PrintWriter;
import java.net.ServerSocket;
import java.util.HashSet;

/**
 * @author   Michael Watzer
 * @version  1.0
 * @category Main
 * @since    03.07.2017
 */
public class java_parseMain {
	protected static HashSet<PrintWriter> writers = new HashSet<PrintWriter>();
	public static void main(String[] args) throws java_parseException, IOException {
		int port;
		if(args.length != 1) 
			port = 32727;
		else
			port = Integer.parseInt(args[0]);
		ServerSocket listener = new ServerSocket(port);
		try {
			while(true) {
				new java_parseDaemon(listener.accept()).start();
			}
		}
		finally {
			listener.close();
		}
	}
}
