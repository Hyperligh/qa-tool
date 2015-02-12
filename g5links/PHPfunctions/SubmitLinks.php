<?php

    $All_Data = $_POST['data'];
    $All_Data = json_decode($All_Data, true);
    $Code = $_POST['code'];

      //connect to the database
       if(isset($db) == false){
            require_once("../PHPfunctions/DatabaseConnection.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
  //storing variables from the form on page

  for ($j = 0; $j < count($All_Data); $j++)
  {
    $urlcode = $All_Data[$j]['code'];
    $url = $All_Data[$j]['link'];

    //$value_test = addslashes($value);
    $sql = "INSERT INTO urls VALUES('$Code', '$urlcode', '$url')";
    if(!$result = $db->query($sql)){
      die('There was an error running the query [' . $db->error . ']');
    }
  }

?>