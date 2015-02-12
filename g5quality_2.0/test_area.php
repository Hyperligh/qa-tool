<?php
      
    $url = $_GET['url'];
    $domain = $_GET['domain'];
       $ch = curl_init($url); // initialize curl with given url
       curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3"); // set  useragent
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // max. seconds to execute
       curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
       $page_contents = @curl_exec($ch);
       //var_dump($page_contents);
      //echo "<base href='" . $domain . "'>";
    
      echo $page_contents
      
   
?>

