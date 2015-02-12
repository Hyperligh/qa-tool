<?php

  date_default_timezone_set('America/Vancouver');
  $Time = date("Y-m-d H:i:s");


  // code
        $Code = $_POST['code'];
      $GroupName = $_POST['groupname'];


      //connect to the database
      if (!mysql_connect("127.0.0.1","root","") || !mysql_select_db("g5_link"))
      {
          die ('fail');    
      }
      else
      {
         //echo ('connected');
      }
  //storing variables from the form on page

  if (mysql_query("INSERT INTO groups VALUES('$GroupName', '$Code', '$Time')"))
  {
    //echo "did it";
  }
  else
  {
    echo "No Apostrophies please!";
  }

//close connections
mysql_close(mysql_connect("127.0.0.1", "root" , ""));


?>