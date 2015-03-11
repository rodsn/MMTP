<?php
 
	include "vars.php";
	$tabl = "login";
 
 
	mysql_connect("$host", "$user", "$pass")or die("connection failed");
	mysql_select_db($data) or die("database selection failed");
 
 
	$username = $_GET["user"];
	$password = $_GET["pass"];
	$cmd_mode = $_GET["mode"];
	$msg_send = $_GET["send"];
	$msg_data = $_GET["data"];
	$msg_head = $_GET["head"];
	$msg_date = $_GET["date"];
 
	$username = checkString($username);
	$password = checkString($password);
 
	$sql = "SELECT * FROM " . $tabl . " WHERE username = '" . $username . "' AND password = SHA1('" . $password . "')";
 
	$result = mysql_query($sql);
	$tblrow = mysql_num_rows($result);
 
	if ( $tblrow > 0) {
		$ok = "1";
		
		$tabl = "MMTP";
 
 
		mysql_connect("$host", "$user", "$pass")or die("connection failed");
		mysql_select_db($data) or die("database selection failed");
 
		if ($cmd_mode == "check" or $cmd_mode == "get") {
 
			if($cmd_mode == "check"){
				$sql = "SELECT * FROM `" . $tabl . "` WHERE `sto` = '" . $username . "' and `seen` = '0' ORDER BY id";
			}elseif ($cmd_mode == "get") {
				$sql = "SELECT * FROM `" . $tabl . "` WHERE `sto` = '" . $username . "' ORDER BY id DESC";
			}
			$result = mysql_query($sql);
			$count = mysql_num_rows($result);
            $n = 1;
			if ($count > 0) {
	            //echo "{";
				while($row = mysql_fetch_array($result)) {
					foreach ($row as $key => $value) {
						if (!is_numeric($key) and (!is_null($key))) {
							if ($key == "sfrom"){
								$nkey = "from";
							}elseif ($key == "sto") {
								$nkey = "to";
							}elseif ($key == "content"){
								$nkey = "content";
							}elseif ($key == "seen"){
								$nkey = "seen";
							}elseif ($key == "id"){
                                $nkey = "id";
                            }elseif ($key == "title"){
                            	$nkey = "title";
                            }
							$db[$nkey . "_" . $n] = $value;
 
						}
					}
					$n++;
				}
			}else{
				$db['error'] = 'no messages found';
			}
 
			$total = count($db);
		    $count = 0;
            $n--;
 
		    foreach ($db as $k => $v) {
 
	            if ($count == 0) {
	               echo '{' . PHP_EOL . "msg = " . $n . ", " . PHP_EOL;  
	            }
 
 
	            echo $k . ' = "' . $v . '",' . PHP_EOL;
 
	            $count++;
 
	            if ($count == $total) {
	                    echo '}';  
	            }
	    	}
	    }elseif($cmd_mode == "seen"){
    		$sql = "UPDATE " . $tabl . " SET `seen` = '1' WHERE `seen` = '0' and `content` = '" . $msg_data . "' and `id` = '" . $msg_date . "'";
    		$result = mysql_query($sql);
    		$rows = mysql_affected_rows();
    		if($rows > 0){
    			echo "1";
    		}else{
    			echo "0";
    		}
	    }elseif($cmd_mode == "send"){
	    	$sql = "INSERT INTO `" . $tabl . "` (sfrom, sto, title, content, seen, id) VALUES ('" . $username . "', '" . $msg_send . "', '" . $msg_head . "', '" . $msg_data . "', '0',  NOW())";
	    	$result = mysql_query($sql);
	    	if (!$result) {
	    		echo "0";
	    	}else{
	    		echo "1";
	    	}
 
	    }else{
	    	echo "no mode selected.";
	    }
	}else{
		echo "not logged";
	}
 
 
 
 
    function checkString($string) {
            $string = stripslashes($string);
            $string = mysql_real_escape_string($string);
            return $string;
    }
 
?>	