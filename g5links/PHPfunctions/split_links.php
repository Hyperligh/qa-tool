<?php

	$Code = $_POST['code'];
    $Links = $_POST['links'];

	require_once("../PHPfunctions/DatabaseRequests.php");//query file
	$request = new request();
	$current_links = $request->openlinks($Code);
	$row_cnt = $current_links->num_rows + 1;

    $OneLink = explode("\n", $Links);

      $link_array = array();
	  foreach ($OneLink as $value)
	  {
	    
	    $random = mt_rand();
	    $link_array[] = array('count' => $row_cnt, 'code' => $random, 'link' => $value);
	    $row_cnt = $row_cnt + 1;
	    
	  }

echo json_encode($link_array);


?>