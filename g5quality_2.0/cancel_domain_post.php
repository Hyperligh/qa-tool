<?php
//Grab Passed Domain Code
$domain_code = $_POST['domain_code'];



if(isset($domain_code)){
    //Update Queue Log to set the status to cancelled 
    require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/updates.php");
    $update = new update();
    $update->set_cancelled($domain_code);
    
    
}

?>