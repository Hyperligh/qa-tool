
<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/pull.php");
    
    $request = new request();
    $recent_domains = $request->recent_domains();
    
while($row = $recent_domains->fetch_assoc())
{
    
     //Queue log result variables
        $code = $row['code'];
        $domain = $row['Domain'];
        $time = $row['time'];
        $status = $row['status'];
        $URLs_Ran = $row['URLs_Ran'];
	
    $number_loading_errors = $request->number_loading_errors($domain, $code);
    
    if ($number_loading_errors > 0)
    {
	echo ("
		<li>
		<h1>$number_loading_errors</h1>
                <h5>Broken Pages</h5>
                </li>");
    }
    
}
?>