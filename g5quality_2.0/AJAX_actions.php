<?php
//php script that connects to the database
require 'VariableHouse.php';
  
//format in 12 h cycle instead of 24 h cycle
  $hourchange = date('h');
  if ($hourchange > 12)
  {
  $hourchange = $hourchange - 12;
  }
  
  date_default_timezone_set('America/Vancouver');
  $Time = date("Y-m-d H:i:s");
  $User_ID = 'false';

  // code
      $Code = $_POST['code'];
      $Domain = $_POST['domain'];

      $Multi_Domain = $_POST['multi_domain'];
      $Mobile = $_POST['mobile'];
      

      //$Browser_Type = $_POST['browser_type'];
      $Browser_Type = 'Firefox'; 

      //connect to the database
      if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
  //storing variables from the form on page
        
  
    $sql = "INSERT INTO $pass_domain VALUES('$Domain', '$User_ID', '$Time', '$Browser_Type', '$Code', '$Multi_Domain', '$Mobile')";
    if(!$result = $db->query($sql)){
           die('There was an error running the query [' . $db->error . ']');
    }
    else{
        echo "<span class='label label-default' style='background-color:#78cd51;'><i class ='fa fa-arrow-circle-down' style='padding-right:20px; color:#34383C;'></i><span style='color:#34383C;'>Your Request has been processed<span><i class ='fa fa-arrow-circle-down' style='padding-left:20px; color:#34383C;'></i></span>";
    }
      
    $sql2 = "INSERT INTO que_log VALUES('$Domain', '$Code', 'false', NOW(), 'waiting', '0', '0', '$Multi_Domain' ,'$Mobile')";
    if(!$result = $db->query($sql2)){
           die('There was an error running the query [' . $db->error . ']');
    }
    /*
    $sql3 = "INSERT INTO errors VALUES('$Domain', '', '', '$Code', '', '', '', '' ,'')";
    if(!$result = $db->query($sql3)){
           die('There was an error running the query [' . $db->error . ']');
    }
    */


?>
