<?php
$real_path = 
require($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/VariableHouse.php");
class db_class{

   public function db_call(){
        
        $db =  new mysqli("127.0.0.1","root","","g5_qa");  
        //$db =  new mysqli("127.0.0.1","app_user1","","g5_qa_dev");
        if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        }
        
 
        return $db;
    }
}
?>
