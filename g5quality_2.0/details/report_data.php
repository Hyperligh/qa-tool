<?php

    function urls_ran_func($code){
         if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
               
    
         $stmt = $db->prepare("SELECT URLs_Ran FROM que_log WHERE code = ?");
         $stmt->bind_param('s', $code);
         $stmt->execute();
         $stmt->bind_result($URLs_Ran);
         
         $number_ran = 0;
         
         $result = array();
         while ($stmt->fetch())
         {
            $number_ran = $URLs_Ran;
         }
        return $number_ran;
    }
    
    function number_loading_errors_ajax($code)
    {
         if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
               
             $type = "LoadingError";
               
         $stmt = $db->prepare("SELECT URL FROM errors WHERE Domain_Code = ? and type = ?");
         $stmt->bind_param('ss', $code, $type);
         $stmt->execute();
         $stmt->bind_result($URL);
         $stmt->store_result();
         
        return $stmt->num_rows;
    }
    
?>