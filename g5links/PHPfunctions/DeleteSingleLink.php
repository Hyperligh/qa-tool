<?php

$Code = $_POST['code'];
$URL_Code = $_POST['url_code'];

if(isset($Code)){
    //Update Queue Log to set the status to cancelled 
    require_once("../PHPfunctions/DatabaseRequests.php");
    $request = new request();
    $request->ClearOneLink($Code, $URL_Code);
}

?>