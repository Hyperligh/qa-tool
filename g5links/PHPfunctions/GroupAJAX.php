<?php

$Code = $_POST['code'];
$GroupName = $_POST['groupname'];

if(isset($Code, $GroupName))
{

    require_once("../Content/Group.php");    
    $group = new group();

    $group->generate($Code, $GroupName);
        
    
}
else
{
    echo "missing var";
}


?>