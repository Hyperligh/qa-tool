   <?php
    session_start();

        if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }

        $sql = "UPDATE que_log SET status = 'done' WHERE status = 'purgatory'";
           if(!$result = $db->query($sql)){
           die('There was an error running the query [' . $db->error . ']');
           }

            echo "test";
    
    ?>