
<html>
<body>
<?php 


//Extract domain 
// $domain = "http://www.metrostorage.com.g5demo.com/";
 $domain = "http://www.legendsatlauracreek.com/";

// get other environment runs 
if (strpos($domain, 'g5demo') == true){$raw_domain = str_replace('.g5demo.com', '', $domain);}
elseif (strpos($domain, 'redesign.g5cloud') == true){$raw_domain = str_replace('.redesign.g5cloud.me', '', $domain);}
elseif (strpos($domain, 'redesign2.g5cloud') == true){$raw_domain = str_replace('.redesign2.g5cloud.me', '', $domain);}
elseif (strpos($domain, 'real-staging.g5search.com') == true){$raw_domain = str_replace('.real-staging.g5search.com', '', $domain);}
elseif (strpos($domain, 'pluto.g5search.com') == true){$raw_domain = str_replace('.pluto.g5search.com', '', $domain);}
else{$raw_domain = $domain;}

// strip the "/" at the end of the domain
$raw_domain = substr($raw_domain, 0, -1);


$raw_pieces = explode("/", $raw_domain);
        $raw_key_piece = $raw_pieces[2];
        echo ($raw_key_piece);
        echo ('</br>');

        $pro_domain_piece = ($raw_key_piece . "/");
        $demo_domain_piece = ($raw_key_piece . ".g5demo.com/");
        $rds_domain_piece = ($raw_key_piece . ".redesign.g5cloud.me/");
        $rds2_domain_piece = ($raw_key_piece . ".redesign2.g5cloud.me/");
        $real_stage_domain_piece = ($raw_key_piece . ".real-staging.g5search.com/");
        $pluto_domain_piece = ($raw_key_piece . ".pluto.g5search.com/");

        $pro_domain = '';
        for ($i = 0; $i < count($raw_pieces); $i++)
        {
        	if ($i == 2){$pro_domain = ($pro_domain . $pro_domain_piece);}
        	else{$pro_domain = ($pro_domain . $raw_pieces[$i] . "/");}
        }
        echo($pro_domain);
        echo('</br>');

        $demo_domain = '';
        for ($i = 0; $i < count($raw_pieces); $i++)
        {
        	if ($i == 2){$demo_domain = ($demo_domain . $demo_domain_piece);}
        	else{$demo_domain = ($demo_domain . $raw_pieces[$i] . "/");}
        }
        echo($demo_domain);
        echo('</br>');

        $rds_domain = '';
        for ($i = 0; $i < count($raw_pieces); $i++)
        {
        	if ($i == 2){$rds_domain = ($rds_domain . $rds_domain_piece);}
        	else{$rds_domain = ($rds_domain . $raw_pieces[$i] . "/");}
        }
        echo($rds_domain);
        echo('</br>');

        $rds2_domain = '';
        for ($i = 0; $i < count($raw_pieces); $i++)
        {
        	if ($i == 2){$rds2_domain = ($rds2_domain . $rds2_domain_piece);}
        	else{$rds2_domain = ($rds2_domain . $raw_pieces[$i] . "/");}
        }
        echo($rds2_domain);
        echo('</br>');

        $real_stage_domain = '';
        for ($i = 0; $i < count($raw_pieces); $i++)
        {
        	if ($i == 2){$real_stage_domain = ($real_stage_domain . $real_stage_domain_piece);}
        	else{$real_stage_domain = ($real_stage_domain . $raw_pieces[$i] . "/");}
        }
        echo($real_stage_domain);
        echo('</br>');

        $pluto_domain = '';
        for ($i = 0; $i < count($raw_pieces); $i++)
        {
        	if ($i == 2){$pluto_domain = ($pluto_domain . $pluto_domain_piece);}
        	else{$pluto_domain = ($pluto_domain . $raw_pieces[$i] . "/");}
        }
        echo($pluto_domain);
        echo('</br>');




        /*
        $pro_domain = ($raw_domain . "/");
        $demo_domain = ($raw_domain . ".g5demo.com/");
        $rds_domain = ($raw_domain . ".redesign.g5cloud.me/");
        $rds2_domain = ($raw_domain . ".redesign2.g5cloud.me/");
        $real_stage_domain = ($raw_domain . ".real-staging.g5search.com/");
        $pluto_domain = ($raw_domain . ".pluto.g5search.com/");
        */



?>

<p>Click the button to display an alert box.</p>

<button onclick="myFunction()">Try it</button>


<script type="text/javascript">
function myFunction() {
	var domain = 'http://g5-clw-6gclne9-the-lodge-at-wi.herokuapp.com/';
if (domain.indexOf("www.") == -1 && domain.indexOf("herokuapp") == -1) {
		alert ('replace with www');
		domain.replace('http://', 'http://www.');
	}
	else
	{
		alert ('dont do it');
	}
}

	</script>


</body>
</html>