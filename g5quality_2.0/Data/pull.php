<?php
ob_start();


class request{
    
    function recent_domains(){
        if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }

        
           // QUERY TO EVENTUALL SWAP OUT FOR SPEED

        $sql =
        "SELECT que_log.Domain, code, time, status, URLs_Ran, URLs_Remaining, multidomain, mobile, 

                        sum(case when errors.type = 'SpellingError' then 1 end ) as number_spelling_errors, 
                        sum(case when errors.type = 'TextError' then 1 end ) as number_text_errors,
                        sum(case when errors.type = 'LoadingError' then 1  end ) as number_loading_errors,
                        sum(case when errors.type = 'RepetitiveText' then 1  end ) as number_repetitive_errors, 
                        sum(case when errors.type = 'LinkingIssue' then 1 end ) as number_linking_issues,
                        sum(case when errors.type = 'TitleError' then 1  end ) as number_title_errors,
                        sum(case when errors.type = 'GA_E' then 1 end ) as number_ga_errors

            FROM que_log            

             LEFT Join errors on (
            errors.Domain_Code = que_log.code)

            GROUP by code
            order by que_log.time desc LIMIT 15;
        ";
        

        //$sql = "SELECT Domain, code, time, status, URLs_Ran, URLs_Remaining, multidomain, mobile FROM que_log order by time desc LIMIT 30";




           if(!$result = $db->query($sql)){
           die('There was an error running the query [' . $db->error . ']');
           }
           return $result;
    }

    function single_recent_domain($code){
        if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }

        
           // QUERY TO EVENTUALL SWAP OUT FOR SPEED

        $sql =
        "SELECT que_log.Domain, code, time, status, URLs_Ran, URLs_Remaining, multidomain, mobile, 

                        sum(case when errors.type = 'SpellingError' then 1 end ) as number_spelling_errors, 
                        sum(case when errors.type = 'TextError' then 1 end ) as number_text_errors,
                        sum(case when errors.type = 'LoadingError' then 1  end ) as number_loading_errors,
                        sum(case when errors.type = 'RepetitiveText' then 1  end ) as number_repetitive_errors, 
                        sum(case when errors.type = 'LinkingIssue' then 1 end ) as number_linking_issues,
                        sum(case when errors.type = 'TitleError' then 1  end ) as number_title_errors,
                        sum(case when errors.type = 'GA_E' then 1 end ) as number_ga_errors

            FROM que_log   


             LEFT Join errors on (
            errors.Domain_Code = que_log.code)
            
            where code = '$code'
            group by code
            order by que_log.time desc LIMIT 1;
        ";
        

        //$sql = "SELECT Domain, code, time, status, URLs_Ran, URLs_Remaining, multidomain, mobile FROM que_log order by time desc LIMIT 30";




           if(!$result = $db->query($sql)){
           die('There was an error running the query [' . $db->error . ']');
           }
           return $result;
    }


    
    function recent_domains_ajax(){
        if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }

        $sql = "SELECT code FROM que_log  WHERE (status = 'running' or status = 'purgatory') order by time desc LIMIT 30";
           if(!$result = $db->query($sql)){
           die('There was an error running the query [' . $db->error . ']');
           }
           return $result;
    }
    
    
    function wanted_domain($code){
         if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
               
    
         $stmt = $db->prepare("SELECT Domain, time, multidomain, mobile FROM que_log WHERE code = ?");
         $stmt->bind_param('s', $code);
         $stmt->execute();
         $stmt->bind_result($DOMAIN, $time, $multidomain, $mobile);
         
         $wanted_domain = '';
         $timestamp = '';
         $is_multi_domain = '';
         $is_mobile_domain = '';
         
         while ($stmt->fetch())
         {
            $wanted_domain = $DOMAIN;
            $timestamp = $time;
            $is_multi_domain = $multidomain;
            $is_mobile_domain = $mobile;
         }
         
        $return_vars = array($wanted_domain, $timestamp, $is_multi_domain, $is_mobile_domain);
        return $return_vars;
    }
    
    function urls_ran_func($code){
         if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
               
    
         $stmt = $db->prepare("SELECT URLs_Ran FROM que_log WHERE code = ?");
         $stmt->bind_param('s', $code);
         $stmt->execute();
         $stmt->bind_result($URLs_Ran);
         
         $number_ran = 0;
         
         $result = array();
         while ($stmt->fetch())
         {
            $number_ran = $URLs_Ran;
         }
        return $number_ran;
    }
    
    function urls_remain_func($code){
         if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
               
    
         $stmt = $db->prepare("SELECT URLs_Remaining FROM que_log WHERE code = ?");
         $stmt->bind_param('s', $code);
         $stmt->execute();
         $stmt->bind_result($URLs_Remain);
         
         $number_ran = 0;
         
         $result = array();
         while ($stmt->fetch())
         {
            $number_remain = $URLs_Remain;
         }
        return $number_remain;
    }
    
        function status($code){
         if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
               
         $stmt = $db->prepare("SELECT status FROM que_log WHERE code = ?");
         $stmt->bind_param('s', $code);
         $stmt->execute();
         $stmt->bind_result($Status);
         
         $domain_status = '';
         
         $result = array();
         while ($stmt->fetch())
         {
            $domain_status = $Status;
         }
        return $domain_status;
    }
    
    function number_errors_general($domain, $code)
    {
         if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
               //Once more types of errrs are being collected. You will want to loop through each of the returned variables
               //and do a count of how many of each error there is
               
         $stmt = $db->prepare("SELECT URL FROM errors WHERE Domain = ? and Domain_Code = ?");
         $stmt->bind_param('ss', $domain, $code);
         $stmt->execute();
         $stmt->bind_result($URL);
         $stmt->store_result();
         
        return $stmt->num_rows;
        
    }
    
 function number_loading_errors_ajax($code)
    {
         if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
               
             $type = "LoadingError";
               
         $stmt = $db->prepare("SELECT URL FROM errors WHERE Domain_Code = ? and type = ?");
         $stmt->bind_param('ss', $code, $type);
         $stmt->execute();
         $stmt->bind_result($URL);
         $stmt->store_result();
         
        return $stmt->num_rows;
    }

 function number_title_errors($code)
    {
         if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
               
             $type = "TitleError";
               
         $stmt = $db->prepare("SELECT URL FROM errors WHERE Domain_Code = ? and type = ?");
         $stmt->bind_param('ss', $code, $type);
         $stmt->execute();
         $stmt->bind_result($URL);
         $stmt->store_result();
         
        return $stmt->num_rows;
    }
    
     function number_repetitive_errors($code)
    {
         if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
               
             $type = "RepetitiveText";
               
         $stmt = $db->prepare("SELECT URL FROM errors WHERE Domain_Code = ? and type = ?");
         $stmt->bind_param('ss', $code, $type);
         $stmt->execute();
         $stmt->bind_result($URL);
         $stmt->store_result();
         
        return $stmt->num_rows;
    }
    
     function number_text_errors($code)
    {
         if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
               
             $type = "TextError";
               
         $stmt = $db->prepare("SELECT URL FROM errors WHERE Domain_Code = ? and type = ?");
         $stmt->bind_param('ss', $code, $type);
         $stmt->execute();
         $stmt->bind_result($URL);
         $stmt->store_result();
         
        return $stmt->num_rows;
    }
    
    function number_linking_issues($code)
    {
         if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
               
             $type = "LinkingIssue";
               
         $stmt = $db->prepare("SELECT URL FROM errors WHERE Domain_Code = ? and type = ?");
         $stmt->bind_param('ss', $code, $type);
         $stmt->execute();
         $stmt->bind_result($URL);
         $stmt->store_result();
         
        return $stmt->num_rows;
    }

    function number_spelling_errors($code)
    {
         if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
               
             $type = "SpellingError";
               
         $stmt = $db->prepare("SELECT URL FROM errors WHERE Domain_Code = ? and type = ?");
         $stmt->bind_param('ss', $code, $type);
         $stmt->execute();
         $stmt->bind_result($URL);
         $stmt->store_result();
         
        return $stmt->num_rows;
    }
    
    function number_ga_errors($code)
    {
         if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
               
             $type = "GA_E";
               
         $stmt = $db->prepare("SELECT URL FROM errors WHERE Domain_Code = ? and type = ?");
         $stmt->bind_param('ss', $code, $type);
         $stmt->execute();
         $stmt->bind_result($URL);
         $stmt->store_result();
         
        return $stmt->num_rows;
    }
    
    
    
    
 function all_errors($code)
    {
        
         if(isset($db) == false){
   
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
            
         $stmt = $db->prepare("SELECT URL, SourceUrl, type, message FROM errors WHERE Domain_Code = ?");
         $stmt->bind_param('s', $code);
         $stmt->execute();
         $stmt->bind_result($URL, $SourceUrl, $type, $message);
         
         
        $result = array();
         while($stmt->fetch())
         {
            $result[] = array('URL' => $URL, 'SourceUrl'=> $SourceUrl, 'type' => $type, 'message' => $message);
         }
        return $result;
         

    }
    
    
    function all_code($domain, $multidomain, $mobile)
    {
        
         if(isset($db) == false){
   
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
        
         $stmt = $db->prepare("SELECT code, time FROM que_log WHERE Domain = ? AND (status = 'done' or status = 'purgatory') and multidomain = '$multidomain' and mobile = '$mobile' ORDER BY time ASC");
         $stmt->bind_param('s', $domain);
         $stmt->execute();
         $stmt->bind_result($all_code, $all_time);
         
        
         
         $result = array();
         while ($stmt->fetch())
         {
            $result[] = array('code' => $all_code, 'time'=> $all_time);
         }
        return $result;
    }
    

    
        // Now that we have the code from the complete prior domain run
        // We can get all the error information needed from that previous run

    
    function code_history($domain, $multidomain)
    {
        
         if(isset($db) == false){
   
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
        
         $stmt = $db->prepare("SELECT code FROM que_log WHERE Domain = ? AND status = 'done' and multidomain = '$multidomain' ORDER BY time ASC");
         $stmt->bind_param('s', $domain);
         $stmt->execute();
         $stmt->bind_result($code_history);
         

         $result = array();
         while ($stmt->fetch())
         {
            $result[] = array('code' => $code_history);
         }
        return $result;
    }
    
    function environment_codes($raw_domain)
    {
        
         if(isset($db) == false){
   
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }

        $raw_pieces = explode("/", $raw_domain);
        $raw_key_piece = $raw_pieces[2];

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

        $demo_domain = '';
        for ($i = 0; $i < count($raw_pieces); $i++)
        {
            if ($i == 2){$demo_domain = ($demo_domain . $demo_domain_piece);}
            else{$demo_domain = ($demo_domain . $raw_pieces[$i] . "/");}
        }

        $rds_domain = '';
        for ($i = 0; $i < count($raw_pieces); $i++)
        {
            if ($i == 2){$rds_domain = ($rds_domain . $rds_domain_piece);}
            else{$rds_domain = ($rds_domain . $raw_pieces[$i] . "/");}
        }

        $rds2_domain = '';
        for ($i = 0; $i < count($raw_pieces); $i++)
        {
            if ($i == 2){$rds2_domain = ($rds2_domain . $rds2_domain_piece);}
            else{$rds2_domain = ($rds2_domain . $raw_pieces[$i] . "/");}
        }

        $real_stage_domain = '';
        for ($i = 0; $i < count($raw_pieces); $i++)
        {
            if ($i == 2){$real_stage_domain = ($real_stage_domain . $real_stage_domain_piece);}
            else{$real_stage_domain = ($real_stage_domain . $raw_pieces[$i] . "/");}
        }

        $pluto_domain = '';
        for ($i = 0; $i < count($raw_pieces); $i++)
        {
            if ($i == 2){$pluto_domain = ($pluto_domain . $pluto_domain_piece);}
            else{$pluto_domain = ($pluto_domain . $raw_pieces[$i] . "/");}
        }
        
         $result = array();

         $stmt = $db->prepare("SELECT code FROM que_log WHERE Domain = ? AND status = 'done' ORDER BY time DESC LIMIT 1");
         $stmt->bind_param('s', $pro_domain);
         $stmt->execute();
         $stmt->bind_result($pro_code);
         while ($stmt->fetch()){$result[] = array('env' => 'Production', 'code' => $pro_code);}
         
         $stmt = $db->prepare("SELECT code FROM que_log WHERE Domain = ? AND status = 'done' ORDER BY time DESC LIMIT 1");
         $stmt->bind_param('s', $demo_domain);
         $stmt->execute();
         $stmt->bind_result($demo_code);
         while ($stmt->fetch()){$result[] = array('env' => 'Demo', 'code' => $demo_code);}
         
         $stmt = $db->prepare("SELECT code FROM que_log WHERE Domain = ? AND status = 'done' ORDER BY time DESC LIMIT 1");
         $stmt->bind_param('s', $rds_domain);
         $stmt->execute();
         $stmt->bind_result($rds_code);
         while ($stmt->fetch()){$result[] = array('env' => 'Redesign', 'code' => $rds_code);}
         
         $stmt = $db->prepare("SELECT code FROM que_log WHERE Domain = ? AND status = 'done' ORDER BY time DESC LIMIT 1");
         $stmt->bind_param('s', $rds2_domain);
         $stmt->execute();
         $stmt->bind_result($rds2_code);
         while ($stmt->fetch()){$result[] = array('env' => 'Redesign2', 'code' => $rds2_code);}
         
         $stmt = $db->prepare("SELECT code FROM que_log WHERE Domain = ? AND status = 'done' ORDER BY time DESC LIMIT 1");
         $stmt->bind_param('s', $real_stage_domain);
         $stmt->execute();
         $stmt->bind_result($real_stage_code);
         while ($stmt->fetch()){$result[] = array('env' => 'Real-Staging', 'code' => $real_stage_code);}
         
         $stmt = $db->prepare("SELECT code FROM que_log WHERE Domain = ? AND status = 'done' ORDER BY time DESC LIMIT 1");
         $stmt->bind_param('s', $pluto_domain);
         $stmt->execute();
         $stmt->bind_result($pluto_code);
         while ($stmt->fetch()){$result[] = array('env' => 'Pluto', 'code' => $pluto_code);}
         
         return $result;
    }
  
   function domain_history($code_value)
    {
        
        if(isset($db) == false){
   
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
            
         $stmt = $db->prepare("SELECT type FROM errors WHERE Domain_Code = ?");
         $stmt->bind_param('s', $code_value);
         $stmt->execute();
         $stmt->bind_result($type);
         
         
        $result = array();
         while($stmt->fetch())
         {
            $result[] = array('type' => $type);
         }
        return $result;
         

    }
    
    
    
        
 function all_info($code)
    {
        
         if(isset($db) == false){
   
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
            
         $stmt = $db->prepare("SELECT Domain, time, URLs_Ran, URLs_Remaining FROM que_log WHERE code = ?");
         $stmt->bind_param('s', $code);
         $stmt->execute();
         $stmt->bind_result($Domain, $time, $URLs_Ran, $URLs_Remaining);
         
         
        $result = array();
         while($stmt->fetch())
         {
            $result[] = array('URL' => $URL, 'URLs_Remaining'=> $URLs_Remaining, 'time' => $time, 'URLs_Ran' => $URLs_Ran);
         }
        return $result;
         

    }
    
 function all_urls($code)
    {
        
         if(isset($db) == false){
   
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
            
         $stmt = $db->prepare("SELECT ID, LoadTime FROM alldata WHERE code = ?");
         $stmt->bind_param('s', $code);
         $stmt->execute();
         $stmt->bind_result($ID, $LoadTime);
         
         
        $result = array();
         while($stmt->fetch())
         {
            $result[] = array('ID' => $ID, 'LoadTime' => $LoadTime);
         }
        return $result;
         

    }
    
    function number_meta($code)
    {
         if(isset($db) == false){
   
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
            
         $stmt = $db->prepare("SELECT ID, Meta FROM alldata WHERE code = ?");
         $stmt->bind_param('s', $code);
         $stmt->execute();
         $stmt->bind_result($ID, $Meta);
         
        $dupmeta = array();
         while($stmt->fetch())
         {
            $dupmeta[] = array('ID' => $ID, 'Meta' => $Meta);
         }
         
        // match the logic on the reports page for sorting       
        $size_dupmeta = count($dupmeta);

        // get rid of urls where we are NOT concerned about duplicate meta
        for ($j = 0; $j < $size_dupmeta; $j++)
        {
        	if (strpos($dupmeta[$j]['ID'] , '/units/') == true ||
        	    strpos($dupmeta[$j]['ID'] , 'leads') == true ||
        	    strpos($dupmeta[$j]['ID'] , 'sitemap') == true ||
        	    strpos($dupmeta[$j]['ID'] , 'site_map') == true ||
        	    strpos($dupmeta[$j]['ID'] , 'coupon') == true ||
                    strpos($dupmeta[$j]['ID'] , 'site_link') == true ||
        	    strpos($dupmeta[$j]['ID'] , 'privacy') == true)
        	{
                   unset($dupmeta[$j]);
        	}
	
            // if the meta is null get rid of it
        	if ($dupmeta[$j]['Meta']  == '')
        	{
                   unset($dupmeta[$j]);
        	}

            // get rid of ?scroll=true urls, causing issues. and ?unit-size
            if (strpos($dupmeta[$j]['ID'] , '?scrollto=true') == true || strpos($dupmeta[$j]['ID'] , '?unit-size=') == true)
            {
                unset($dupmeta[$j]);
            }

            //sketchy fix, but elimate false duplicate on single site umrella crawls by
            if (strpos($dupmeta[$j]['ID'] , 'zip_') == true && substr($dupmeta[$j]['ID'] , -1) == '/')
            {
                unset($dupmeta[$j]);
            }
        }




        $reset_1_dupmeta = array_values($dupmeta);
        $new_size_dupmeta = count($dupmeta);

        //Make an array of just meta descriptions
        $meta_temp = array();
        for ($k = 0; $k < $new_size_dupmeta; $k++)
        {
                array_push($meta_temp, $reset_1_dupmeta[$k]['Meta']);
        }

        // now count how many times each meta D occurs in the array
        $meta_counts = array_count_values($meta_temp);

        // if that count is more than one clear it from the count array to just get non-dup metas
        $just_meta = array();
        foreach ($meta_counts as $key => $value)
        {
                if ($value == 1)
        	{
                     array_push($just_meta, $key);
        	}
        }

        // now use that unique list to elimate those unique metas from the larger array
        for ($kk = 0; $kk < $new_size_dupmeta; $kk++)
        {
                if (in_array($reset_1_dupmeta[$kk]['Meta'] , $just_meta))
        	    {
                              unset($reset_1_dupmeta[$kk]);
        	    }
        }

        // might need to add an if ($dupmeta contains no data do not execute below code)
        $reset_2_dupmeta = array_values($reset_1_dupmeta);

        //make an array of just the remaining duplicate meta D's
        $all_duplicate_meta = array();
        for ($gg = 0; $gg < count($reset_2_dupmeta); $gg++)
        {					
                 array_push($all_duplicate_meta, $reset_2_dupmeta[$gg]['Meta']);
        }

        // the duplicate meta kings will be unqiue Meta D's that all have a duplicate in the list
        $dup_meta_kings = array_values(array_unique($all_duplicate_meta));

        //how many duplicates in total
        $total_dup = count($all_duplicate_meta) - count($dup_meta_kings);
            
    return $total_dup;
        
    }
    
    function all_meta($code)
    {
         if(isset($db) == false){
   
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
            
         $stmt = $db->prepare("SELECT SourceID, ID, Meta FROM alldata WHERE code = ?");
         $stmt->bind_param('s', $code);
         $stmt->execute();
         $stmt->bind_result($SourceID, $ID, $Meta);
         
        $dupmeta = array();
         while($stmt->fetch())
         {
            $dupmeta[] = array('SourceID' => $SourceID, 'ID' => $ID, 'Meta' => $Meta);
         } 
        return $dupmeta;
    }
    
    
     function time_elapsed($code)
    {
        
         if(isset($db) == false){
   
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
            
         $stmt = $db->prepare("SELECT Time FROM alldata WHERE Code = ? order by time desc Limit 1");
         $stmt->bind_param('s', $code);
         $stmt->execute();
         $stmt->bind_result($Time);
         $end_time = "";
         while ($stmt->fetch())
            {
               $end_time = $Time;
            }
         
         return $end_time;
         
    }
    
    function multi($domain, $multidomain, $mobile)
    {
        
         if(isset($db) == false){
   
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
        
         $stmt = $db->prepare("SELECT code FROM que_log WHERE Domain = ? AND (status = 'done' or status = 'purgatory') and multidomain != '$multidomain' and mobile = '$mobile' order by time desc Limit 1");
         $stmt->bind_param('s', $domain);
         $stmt->execute();
         $stmt->bind_result($code);
         
        
         
         $result = array();
         while ($stmt->fetch())
         {
            $result[] = array('code' => $code);
         }
        return $result;
    }
    
    function mobile($domain, $multidomain, $mobile)
    {
        
         if(isset($db) == false){
   
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
        
         $stmt = $db->prepare("SELECT code FROM que_log WHERE Domain = ? AND (status = 'done' or status = 'purgatory') and multidomain = '$multidomain' and mobile != '$mobile' order by time desc Limit 1");
         $stmt->bind_param('s', $domain);
         $stmt->execute();
         $stmt->bind_result($code);
         
        
         
         $result = array();
         while ($stmt->fetch())
         {
            $result[] = array('code' => $code);
         }
        return $result;
    }
    
    function dictionary()
    {
        
         if(isset($db) == false){
   
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
        
         $stmt = $db->prepare("SELECT word, report_code FROM spelling_ignore");
         $stmt->execute();
         $stmt->bind_result($oneword, $code);
         
         $result = array();
         while ($stmt->fetch())
         {
            $result[] = array('word' => $oneword, 'report_code' => $code);
         }
        return $result;
    }
    

    function number_adjust_spell_error($code)
    {
        
         if(isset($db) == false){
   
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }
        
        
         $stmt = $db->prepare("SELECT word, report_code FROM spelling_ignore");
         $stmt->execute();
         $stmt->bind_result($oneword, $specific_code);
         
         $user_dict = array();
         while ($stmt->fetch())
         {
            $user_dict[] = array('word' => $oneword, 'report_code' => $specific_code);
         }
         

         $type = 'SpellingError';
        $stmt = $db->prepare("SELECT message FROM errors WHERE Domain_Code = ? AND type = ? ");
        $stmt->bind_param('ss' ,$code, $type);
        $stmt->execute();
        $stmt->bind_result($message);

        $spelling_errors = array();
        while ($stmt->fetch())
         {
            $spelling_errors[] = array('message' => $message);
         }
         

            // execute logic to count the right amount of words
            // make an array of just the words
            $just_words = array();
            for ($gg = 0; $gg < count($user_dict); $gg++)
            {                   
                array_push($just_words, $user_dict[$gg]['word']);
            }

            // pull out unique words in the list
            $unique_words = array_values(array_unique($just_words));


            // now count how many times the unique word apears in the master list
            // make a list with word and count as array
            $unique_words_count = array();
            $kk = 0;
            foreach ($unique_words as $oneword) 
            { 
                $unique_words_count[$kk] = array('word' => $oneword, 'count' => 0);
                $counter = 0;
                for ($ii = 0; $ii < count($user_dict); $ii++)
                {                   
                    if ($user_dict[$ii]['word'] == $oneword)
                    {
                        $counter++;
                    }
                }
                $unique_words_count[$kk]['count'] = $counter;
                $kk = $kk + 1;
            }

            // make an array of just words added to dict
            $approved_words = array();
            for ($ii = 0; $ii < count($unique_words_count); $ii++)
            {
                //////////////////////////////////////////////////////
                //////// The all mighty judgement number /////////////
                //////////////////////////////////////////////////////
                if ($unique_words_count[$ii]['count'] >= 8)
                {
                    array_push($approved_words, $unique_words_count[$ii]['word']);
                }
            }


            // go through array of spelling errors and only count the ones that haven't been added to dict
            $spelling_error_count = 0;
            for ($uu = 0; $uu < count($spelling_errors); $uu++)
            {
                if (in_array($spelling_errors[$uu]['message'], $approved_words) == false)
                {
                     $spelling_error_count++;
                }
            }
            
            return $spelling_error_count;
    }

    
}//end request class
           
?>