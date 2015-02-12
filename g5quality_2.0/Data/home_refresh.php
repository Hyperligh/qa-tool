<?php

$exitloop = ('false');
while ($exitloop == 'false')
{
    
        $getvalue = new getvalue();
        class getvalue
        {

                function number_loading_errors($domain, $code)
                {
                // make our database connections
                if(isset($db) == false){
                            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
                            $db_class = new db_class();
                            $db = $db_class->db_call();
                        }
                
                // gather necessary information from DB

                    // loading errors
                         $type = "LoadingError";
                               
                         $stmt = $db->prepare("SELECT URL FROM errors WHERE Domain = ? and Domain_Code = ? and type = ?");
                         $stmt->bind_param('sss', $domain, $code, $type);
                         $stmt->execute();
                         $stmt->bind_result($URL);
                         $stmt->store_result();
                         
                        return $stmt->num_rows;
                        $number_loading_errors = $getvalue->number_loading_errors($domain, $code);
                }
        }
}
?>