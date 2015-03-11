<?php
 
	include "vars.php";
	$tabl = "login";
 
 
	mysql_connect("$host", "$user", "$pass")or die("connection failed");
	mysql_select_db($data) or die("database selection failed");
 
 
	$username = $_GET["user"];
	$password = $_GET["pass"];
	$cmd_mode = $_GET["mode"];
 
	$username = checkString($username);
	$password = checkString($password);
 
 
	if ($cmd_mode == "login"){
		$sql = "SELECT * FROM " . $tabl . " WHERE username = '" . $username . "' AND password = SHA1('" . $password . "')";
 
		$result = mysql_query($sql);
		$tblrow = mysql_num_rows($result);
 
		if ($tblrow > 0) {
			echo "1";
		}else{
			echo "0";
		}
 
	}elseif($cmd_mode == "create"){
		$sql = "INSERT INTO " . $tabl . " (username, password) VALUES ('" . $username . "', SHA1('" . $password . "'))";
		$res = mysql_query($sql);
		$row = mysql_affected_rows();
		if($row > 0) {
			echo "1";
		}else{
			echo "0";
		}
	}elseif($cmd_mode == "delete"){
		$sql = "DELETE FROM " . $tabl . " WHERE username = '" . $username . "' and password = SHA1('" . $password . "')";
		$res = mysql_query($sql);
		$row = mysql_affected_rows();
		if($row > 0) {
			echo "1";
		}else{
			echo "0";
		}
	}
 
 
    function checkString($string) {
            $string = stripslashes($string);
            $string = mysql_real_escape_string($string);
            return $string;
    }
 
?>