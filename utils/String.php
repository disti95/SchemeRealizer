<?php 
	/*
	 SchemeRealizer - A free ORM and Translator for Diagrams, Databases and PHP-Classes/Interfaces.
	Copyright (C) 2016  Michael Watzer
	
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
	namespace utils;
	class String{
		/**
		 * @author   Michael Watzer
		 * @version  1.0
		 * @since    10.08.2016
		 * @category Sting-Modifier
		 */
		/**
		 * @category strtoupper on the Character 0
		 * @param    $str
		 */
		public static function FirstLetterUP($str) {
			return strtoupper(substr($str, 0, 1)).substr($str, 1);
		}
		/**
		 * @category substr which returns the entire String except Character 0
		 * @param    $str
		 */
		public static function RemoveFirstLetter($str) {
			return substr($str, 1);
		}
		/**
		 * @param  $str
		 * @param  $extensionexcluded
		 * @return string
		 */
		public static function getFileFromPath($str, $extensionexcluded = true) {
			$substr = $str;
			while(($pos = strpos($substr, '/')) !== false){
				$substr = substr($substr, $pos+1);
			}
			if($extensionexcluded) 
				$substr = substr($substr, 0, strpos($substr, '.'));
			return $substr;
		}
		/**
		 * @category Returns the deepth of a path
		 * @param    $str
		 * @return   string|boolean
		 */
		public static function getDeepthOfPath($str) {
			for($i = 0; $i < strlen($str); $i++) {
				if($str[$i] != "." && $str[$i] != "/")
					return substr($str, 0, $i);
			}
			return false;
		}
		/**
		 * @category Modify the Header for a better style
		 * @param    $str
		 * @return   string
		 */
		public static function modifyUMLClassHeader($str) {
			$output = str_replace("<", "&lt;", $str);
			$output = str_replace(">>", "&gt;&gt; <br />", $output);
			$output = str_replace("{", "<br />{", $output);
			$output = str_replace(__EXTENDS__, "<br />".__EXTENDS__, $output);
			$output = str_replace(__IMPLEMENTS__, "<br />".__IMPLEMENTS__, $output);
			return $output;
		}
		/**
		 * @category Convert parameter string to array
		 * @param    $str
		 * @return   array|boolean
		 */
		public static function StrParamToArray($str) {
			$paramarr       = explode(",", $str);
			$retarr         = array();
			$endparenthesis = true;
			$openbracket    = $closebracket = 0;
			$elemstr        = "";
			foreach($paramarr as $elem) {
				if(strpos($elem, "(") && ($openbracket += self::cntCharStr($elem, "("))) 
					$endparenthesis = false;  
				if(!$endparenthesis && strpos($elem, ")")) 
					if(($closebracket += self::cntCharStr($elem, ")")) === $openbracket) 
						$endparenthesis = true;
				if($endparenthesis) {
					$elemstr .= ltrim($elem);
					$elemarr  = explode("=", $elemstr);
					$elemval  = "";
					for($i = 1; $i < count($elemarr); $i++) 
						$elemval .= (($i !== 1) ? '=' : '').$elemarr[$i];
					$elemstr        = "";
					$endparenthesis = true;
					$openbracket    = $closebracket = 0;
					$retarr[trim(ltrim($elemarr[0]))] = (strlen($elemval)) 
					                                      ? trim(ltrim($elemval)) 
					                                      : null;
				}
				else 
					$elemstr .= ltrim($elem).', ';
			}
			return $retarr;
		}
		/**
		 * @category Count occurrence of character x in string s
		 * @param    $str
		 * @param    $char
		 * @return   int
		 */
		public static function cntCharStr($str, $char) {
			$counter = 0;
			for($i = 0; isset($str[$i]); $i++) 
				if($str[$i] == $char)
					$counter++;
			return $counter;
		}
		/**
		 * @category Extract value from command line argument
		 * @param    command line argument
		 * @return   string|boolean
		 */
		public static function getValFromArg($str) {
			$pos;
			if(!($pos = strpos($str, '=')))
				return false;
			return substr($str, $pos + 1);
		}
		/**
		 * @category Validate parameter string
		 * @param    param str
		 * @return   string|array
		 */
		public static function validateParamStr($str) {
			$code = __FUNC_TMP__." ($str) {}";
			if(!\native\Validate::chkPHPSyntax($code))
				return "$code is not valid PHP code!";
			if(!($parametertmp = \utils\String::StrParamToArray($str)))
				return "unable to convert string '$str' to array!";
			return \utils\Parsing::evaluateParamArray($parametertmp);
		}
		/**
		 * @category get getter body string
		 * @param    name of function
		 * @param    is it an abstract function?
		 * @return   getter function body str
		 */
		public static function getGetterBodyStr($name, $isAbstract = false) {
			return ($isAbstract) 
					  ? ";"
					  : " {\n\t\treturn \$this->".$name.";\n\t}";
		}
		/**
		 * @category get setter body string
		 * @param    name of function
		 * @param    is it an abstract function?
		 * @return   setter function body str
		 */
		public static function getSetterBodyStr($name, $isAbstract = false) {
			return ($isAbstract)
		              ? ";"
		              : " {\n\t\t\$this->".$name." = $".$name.";\n\t}";
		}
		/**
		 * @category get other body string
		 * @param    name of function
		 * @param    is it an abstract function?
		 * @return   other function body str
		 */
		public static function getOtherBodyStr($name, $isAbstract = false) {
			return ($isAbstract)
			        ? ";"
					: " {\n\n\t}";
		}
		/**
		 * @category get comment frame
		 * @param    comment
		 * @return   string
		 */
		public static function getCommentFrame($comment) {
			$out  = "################################################################################\n";
			$out .= "### $comment\n";
			$out .= "################################################################################\n";
			return $out;
		}
		/**
		 * @category get html success message
		 * @param    message
		 * @return   html success message
		 */
		public static function getSuccessMsg($msg) {
			return "<br /><div id='successDIV'><h3>".$msg."</h3></div><br />";
		}
		/**
		 * @category get DSN string
		 * @param    host
		 * @param    user
		 * @param    db
		 * @return   DSN string
		 */
		public static function getDSN($host, $user, $db) {
			return $user."@host=".$host.";dbname=".$db;
		}
		/**
		 * @category get allowed interpreter concerning the controlling-system
		 * @param    interpreter
		 * @return   boolean
		 */
		public static function chkInterpreter($interpreter) {
			if(strtoupper($interpreter) == 'PHP' 
			|| strtoupper($interpreter) == 'PHPUNIT'
			|| strtoupper($interpreter) == 'NODE')
				return true;
			return false;
		}
		/**
		 * @category add quotes to string
		 * @param    string
		 * @return   string
		 */
		public static function addQuotes($val) {
			return "'".$val."'";
		}
		/**
		 * @category Random string generation, mostly used for testing purpose
		 * @param    $length
		 */
		public static function getRandomString($length) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charlen    = strlen($characters);
			$randStr    = '';
			for($i = 0; $i < $length; $i++)
				$randStr .= $characters[rand(0, $charlen - 1)];
			return $randStr;
		}
		/**
		 * @category Check if Java classname is valid
		 * @return   boolean
		 */
		public static function chkJavaClassName($str) {
			$regex = '/^[A-Za-z_$]+[a-zA-Z0-9_$]*$/';
			$parts = explode('.', $str);
			foreach($parts as $part) 
				if(!preg_match($regex, $part))
					return false;
			return true;
		}
	}
?>