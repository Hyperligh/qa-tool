<?php
session_start();
    require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/pull.php");
    $request = new request();
    $recent_domains = $request->recent_domains();
    
    $list_domain_codes = array();
    
while($row = $recent_domains->fetch_assoc())
{   
     //Queue log result variables
        $code = $row['code'];
        array_push($list_domain_codes, $code);
}

echo json_encode($list_domain_codes);
        
?>

