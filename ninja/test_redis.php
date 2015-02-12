<?php

require($_SERVER['DOCUMENT_ROOT'] .'/ninja/predis-1.0/src/Autoloader.php');

Predis\Autoloader::register();
$client = new Predis\Client();




//$sample_array =  array('Domain' => $sample_array);




$i = 0;

while($i < 50){

	$sample_array = array('URL' => 'http://www.metrostorage.com/', 'ID' => $i);
	$sample_array = json_encode($sample_array);

	$client->rpush("Domains", $sample_array);

	$i++;
}


?>