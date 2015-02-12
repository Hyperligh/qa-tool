<?php

class db_class{

   public function db_call(){
            
        $db =  new mysqli("127.0.0.1","root","","g5_link");
        if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        }
        
 
        return $db;
    }
}
?>