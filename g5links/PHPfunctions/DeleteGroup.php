<?php
//Grab Passed Domain Code
$group_code = $_POST['group_code'];

if(isset($group_code)){
    //Update Queue Log to set the status to cancelled 
    require_once("../PHPfunctions/DatabaseRequests.php");
    $request = new request();
    $request->ClearGroup($group_code);
    
    
}

?>