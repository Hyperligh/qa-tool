<?php
$url = $_GET['url'];
// Create a stream




/*
$opts = array(
  'http'=>array(
    'method'=>"POST",
    'header'=>
              "DNT: 1\r\n" .
             // "Host: www.ilovemymarina.com\r\n" .
              "Viewport: 640 x 1136, devicePixelRatio = 2" .
              "Accept-language: en\r\n" .
              "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53\r\n"
  )
);

*/

$parse = parse_url($url);
$domain = "http://" . $parse['host'] . "/";



echo "<html>";
echo "<iframe src=\"http://127.0.0.1/g5quality_2.0/test_area.php?url=$url&domain=$domain\" width=\"640\" height=\"1136\"></iframe>";
echo "</html>";



?>
