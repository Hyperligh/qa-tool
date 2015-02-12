<?php
//Grab Passed Domain Code
$group_code = $_POST['code'];

if(isset($group_code)){
    //Update Queue Log to set the status to cancelled 
    require_once("../PHPfunctions/DatabaseRequests.php");
    $request = new request();
    $request->ClearLinks($group_code);
    
    
}

?>