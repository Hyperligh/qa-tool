<?php

	$local_ornot = 1;
		if ($local_ornot == 1)
		{
				//local database
				$mysql_host = '127.0.0.1';
				$mysql_user = 'root';       
				$mysql_pass = '';
				$mysql_db = 'g5_qa';
				$pass_domain = 'pass_domain';
		}
		else
		{

				//192.168.2.44 database
				$mysql_host = '192.168.2.44';
				$mysql_user = 'app_user1';       
				$mysql_pass = '';
				$mysql_db = 'g5_qa_dev';
				$pass_domain = 'pass_domain';
		}


	
	$alldata = 'alldata';
	$domain_overview = 'domain_overview';
	$que_log = 'que_log';
  

	// this is just for dev purposes
	$connection_error = 'Could Not Connect to Specified Database or Database is Down';
	$conncection_confirm = 'Connected!';


?>