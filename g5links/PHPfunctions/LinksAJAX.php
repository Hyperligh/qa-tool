<?php

$Code = $_POST['code'];
$All_Data = $_POST['alldata'];
$All_Data = json_decode($All_Data, true);

  for ($j = 0; $j < count($All_Data); $j++)
  {
        if(isset($Code))
        {
            require_once("../Content/Links.php");    
            $linkgroup = new links();

            $count = $All_Data[$j]['count'];
            $urlcode = $All_Data[$j]['code'];
            $url = $All_Data[$j]['link'];
        
            $linkgroup->generate($Code, $url, $urlcode, $count);
            $count = $count + 1;
        }
        else
        {
            echo "missing var";
        }
  }

?>