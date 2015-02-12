<?php
$domain = $_POST['domain'];
$code = $_POST['code'];
$Multi = $_POST['multi'];
$Mobile = $_POST['mobile'];

if(isset($code, $domain))
{

    require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/html_pieces/domain_accordians.php");    
    $accordian = new accordian();


    $status_color = 'important';
    $URLs_Ran = "0";
    $time = date("Y-m-d H:i:s");
    $status = "waiting";
    $number_loading_errors = "0";
    $number_title_errors = "0";
     

        
    //Now we can load the accordians.
        
    @$accordian->generate($Multi, $Mobile, $domain, $code, $time, $status, $URLs_Ran, $status_color, $number_loading_errors, $number_title_errors);
        
    
    
}
else
{
    echo "missing var";
}


?>