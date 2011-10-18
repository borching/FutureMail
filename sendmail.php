<?php

	define("SQL_SERVER", "localhost");
	$link = mysql_connect(SQL_SERVER, "apex_borching", "gLEJw9mh4DHm");
	mysql_select_db("apex_borching", $link);
	$cmd = "SET NAMES utf8";
	mysql_query($cmd) or die(mysql_error());



	$cmd = "SELECT `id`, `to`, `from`, `title`, `msg`, `sent`, 
		`interval`, `time` 
		FROM  `futuremail` 
		WHERE UNIX_TIMESTAMP( ) >  `time` AND `sent` = 0
		ORDER BY  `time` 
		LIMIT 0 , 5";
	$handle = mysql_query($cmd) or die(mysql_error());

	while ($row = mysql_fetch_assoc($handle)) {
		
	    $id = $row["id"];
	    $email = $row["to"];
	    $title = $row["title"];
	    $msg = $row["msg"];
	    $from = $row["from"];
	    $interval = $row["interval"];
	    $time = $row["time"];

	    $nextTime = $time + $interval; 

	    $sysMsg = "System: This message is sent every $interval seconds.\r\n";
	    $sysMsg .= "It will be sent again at $nextTime\r\n";

	    mail($email, $title, $sysMsg.$msg, null,"-f$from");
	    echo "mail $id sent!";

	// reschedule
	    if ($interval > 0) {
		$cmd = "UPDATE `futuremail` 
			SET `time` = UNIX_TIMESTAMP() + `interval`
			WHERE `id` = $id";
	    } else {
		$cmd = "UPDATE `futuremail`
			SET `sent` = UNIX_TIMESTAMP()
			WHERE `id` = $id";
	    }
	    $handle2 = mysql_query($cmd) or die(mysql_error());


	}



?>