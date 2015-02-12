<?php

$Code = $_POST['code'];
$Word_Code = $_POST['word_code'];
$Word = $_POST['word'];

// connect to the database
				if(isset($db) == false){
				            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
				            $db_class = new db_class();
				            $db = $db_class->db_call();
				}

	$sql2 = "INSERT INTO spelling_ignore VALUES('$Word', '$Code')";
	if(!$result = $db->query($sql2)){
		 die('There was an error running the query [' . $db->error . ']');
	}



?>