<?php
//Grab Passed Domain Code

$group_code = $_POST['code'];

if(isset($group_code)){
    //Update Queue Log to set the status to cancelled 
    require_once("../PHPfunctions/DatabaseRequests.php");
    $request = new request();
    $OpenLinks = $request->openlinks($group_code);
    $list_urls = array();

    while ($row = $OpenLinks->fetch_assoc())
        {
            									
            $opencode = $row['Code'];
            $onelink = $row['URL'];
            array_push($list_urls, $onelink);

         }
    
    echo json_encode($list_urls);
    
}  

?>