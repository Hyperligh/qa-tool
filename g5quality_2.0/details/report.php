<?php
session_start();


$code = $_GET['id'];
$real_path = $_SERVER['DOCUMENT_ROOT'];


require_once($real_path . "/g5quality_2.0/Data/pull.php");
// make query calls from the pull file to get needed variables
$feed_me = new request();
$errors = $feed_me->all_errors($code);
//que log var returns an array containing both the domain and the multi domain
//This was required to seperate multidomain runs in the domain history
$que_log_vars = $feed_me->wanted_domain($code);
$allurl = $feed_me->all_urls($code);
$totalurl = count($allurl);


//Extract domain 
$domain = $que_log_vars[0];

// get other environment runs 
if (strpos($domain, 'g5demo') == true){$raw_domain = str_replace('.g5demo.com', '', $domain);}
elseif (strpos($domain, 'redesign.g5cloud') == true){$raw_domain = str_replace('.redesign.g5cloud.me', '', $domain);}
elseif (strpos($domain, 'redesign2.g5cloud') == true){$raw_domain = str_replace('.redesign2.g5cloud.me', '', $domain);}
elseif (strpos($domain, 'real-staging.g5search.com') == true){$raw_domain = str_replace('.real-staging.g5search.com', '', $domain);}
elseif (strpos($domain, 'pluto.g5search.com') == true){$raw_domain = str_replace('.pluto.g5search.com', '', $domain);}
else{$raw_domain = $domain;}

// strip the "/" at the end of the domain
$raw_domain = substr($raw_domain, 0, -1);

$environment_codes = $feed_me->environment_codes($raw_domain);
$code_length = count($environment_codes);
// print_r ($environment_codes);


// reformat the time stamp into a more readable format
$time_stamp = $que_log_vars[1];
$time_stamp_parts = explode(" " , $time_stamp);
$date = $time_stamp_parts[0];
$time = $time_stamp_parts[1];
$date_parts = explode("-" , $date);
$year = $date_parts[0];
$month = $date_parts[1];
$day = intval($date_parts[2]);
$time_parts = explode(":" , $time);
$hour = intval($time_parts[0]);
$minute = $time_parts[1];
$second = $time_parts[2];

if($month == "01"){$month = 'January';}
elseif($month == "02"){$month = 'February';}
elseif($month == "03"){$month = 'March';}
elseif($month == "04"){$month = 'April';}
elseif($month == "05"){$month = 'May';}
elseif($month == "06"){$month = 'June';}
elseif($month == "07"){$month = 'July';}
elseif($month == "08"){$month = 'August';}
elseif($month == "09"){$month = 'September';}
elseif($month == "10"){$month = 'October';}
elseif($month == "11"){$month = 'November';}
else{$month = 'December';}

if ($day == 1 || $day == 21 || $day == 31)
{$day = $day . '<sup>st</sup>';}
elseif ($day == 2 || $day == 22)
{$day = $day . '<sup>nd</sup>';}
elseif ($day == 3 || $day == 23)
{$day = $day . '<sup>rd</sup>';}
else
{$day = $day . '<sup>th</sup>';}

if($hour > 12)
{$hour = $hour - 12; $ampm = 'PM';}
else
{$ampm = 'AM';}




// extract mobile and multidomain from returned array 
$multi_domain = $que_log_vars[2];
    if($multi_domain == 'true'){$multi_nav = 'Single Domain Runs';}
    else{$multi_nav = 'Multi Domain Runs';}
$is_mobile = $que_log_vars[3];
    if($is_mobile == 'true'){$mobile_nav = 'Desktop Runs';}
    else{$mobile_nav = 'Mobile Runs';}

// sort through meta D's and filter duplicates
$allmeta = $feed_me->all_meta($code);
$dupmeta = $allmeta;
//echo '</br></br>';print_r($dupmeta);
$size_dupmeta = count($dupmeta);

//echo '</br></br>'; echo $size_dupmeta;

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

    // get rid of ?scroll=true urls, causing issues.
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
//echo '</br></br>'; echo $new_size_dupmeta;

//Make an array of just meta descriptions
$meta_temp = array();
for ($k = 0; $k < $new_size_dupmeta; $k++)
{
        array_push($meta_temp, $reset_1_dupmeta[$k]['Meta']);
}
//echo '</br></br>'; print_r($meta_temp);

// now count how many times each meta D occurs in the array
$meta_counts = array_count_values($meta_temp);
//echo '</br></br>'; print_r($meta_counts);

// if that count is more than one clear it from the count array to just get non-dup metas
$just_meta = array();
foreach ($meta_counts as $key => $value)
{
        if ($value == 1)
	{
             array_push($just_meta, $key);
	}
}
//echo '</br></br>'; print_r($just_meta);

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
//echo '</br></br>'; print_r($reset_2_dupmeta);

//make an array of just the remaining duplicate meta D's
$all_duplicate_meta = array();
for ($gg = 0; $gg < count($reset_2_dupmeta); $gg++)
{					
         array_push($all_duplicate_meta, $reset_2_dupmeta[$gg]['Meta']);
}
//echo '</br></br>';print_r($all_duplicate_meta);

// the duplicate meta kings will be unqiue Meta D's that all have a duplicate in the list
$dup_meta_kings = array_values(array_unique($all_duplicate_meta));
//echo '</br></br>'; print_r($dup_meta_kings);

//how many duplicates in total
$total_dup = count($all_duplicate_meta) - count($dup_meta_kings);


// get the codes for previous and next runs
$all_code = $feed_me->all_code($domain, $multi_domain, $is_mobile);
$total_runs = count($all_code);
for ($i = 0; $i < $total_runs; $i++) {
    if ($all_code[$i]['code'] == $code)
    {
        if ($i > 0){$previous_run_code = $all_code[$i - 1]['code'];}
	else{$previous_run_code = 'null';}
	$current_run = ($i + 1);
	if (($i+1) < $total_runs){$next_run_code = $all_code[$i + 1]['code'];}
	else{$next_run_code = 'null';}
    }
}

$multi_opposite = $feed_me->multi($domain, $multi_domain, $is_mobile);
$mobile_opposite = $feed_me->mobile($domain, $multi_domain, $is_mobile);
//print_r($multi_opposite);
//print_r($mobile_opposite);


// pull database for spelling dictionary 
$feed_me = new request();
$dictionary = $feed_me->dictionary();

// also this value needs to be altered in the pull.php file under the number_adjust_spell_error function
//////////////////////////////////////////////////////
//////// The all mighty judgement number /////////////
//////////////////////////////////////////////////////
$count_limiter = 8;

	// make an arrray of just the words
	$just_words = array();
	for ($gg = 0; $gg < count($dictionary); $gg++)
	{					
	    array_push($just_words, $dictionary[$gg]['word']);
	}

	// pull out unique words in the list
	$unique_words = array_values(array_unique($just_words));

		// now count how many times the unique word apears in the master list
		// make a list with word and count as array
		$unique_words_count = array();
		$kk = 0;
		foreach ($unique_words as $oneword) 
		{
			
			$unique_words_count[] = array('word' => $oneword, 'count' => 0);

			for ($ii = 0; $ii < count($dictionary); $ii++)
			{					
			    if ($dictionary[$ii]['word'] == $oneword)
			    {
			    	$unique_words_count[$kk]['count']++;
 			    }
			}
			$kk = $kk + 1;
		}




// current run bug counts
$loadingerror_count = 0;
$linkingissues_count = 0;
$texterrors_count = 0;
$repetitivetext_count = 0;
$titleerrors_count = 0;
$spellingerrors_count = 0;
$gaerror_count = 0;
foreach($errors as $single_error)
{
     $type = $single_error['type'];
     $message = $single_error['message'];
     if($type == "LoadingError"){$loadingerror_count = $loadingerror_count + 1;}
     if($type == "LinkingIssue"){$linkingissues_count = $linkingissues_count + 1;}
     if($type == "TextError"){$texterrors_count = $texterrors_count + 1;}
     if($type == "RepetitiveText"){$repetitivetext_count = $repetitivetext_count + 1;}
     if($type == "TitleError"){$titleerrors_count = $titleerrors_count + 1;}

     // need to filer all spelling errors through the user dictionary
     if($type == "SpellingError")
     	{
     		if (in_array($message, $unique_words)) 
     		{
     			foreach ($unique_words_count as $oneword) 
				{
					$a_word = $oneword['word'];
					$counter = $oneword['count'];
					if ($a_word == $message && $counter < $count_limiter)
					{
	     				$spellingerrors_count = $spellingerrors_count + 1;
	     			}
     			}
			}
			else
			{
				$spellingerrors_count = $spellingerrors_count + 1;
			}
  
     	}

     if($type == "GA_E"){$gaerror_count = $gaerror_count + 1;}
}
$SEOSEM_count = $titleerrors_count + $linkingissues_count + $gaerror_count;
$ErrorTotal = $loadingerror_count + $linkingissues_count + $texterrors_count + $repetitivetext_count + $titleerrors_count + $spellingerrors_count + $gaerror_count;

require_once($real_path . "/g5quality_2.0/templates/header-report.php");

?>

<div id="content" class="col-lg-12 col-sm-12 col-lg-offset-0 col-sm-offset-0">
					
    <?php
    if($total_runs > 1){
    require_once($real_path . "/g5quality_2.0/Domain_History.php");
    }
    ?>

    <div class="block" style="height: 250px;">
	    <div class="centered">
		<a href = "/g5quality_2.0/home.php" ><img src="/g5quality_2.0/QA_Ninja_Logo.png"></a>
	    </div>  
    </div>
			</br>
			<center>
			    </br>
			    
			    <?php if (isset($previous_run_code) && $previous_run_code != 'null')
			    { ?>
			    <a style="width: 120px;" class="btn btn-sm btn-inverse"  href = "/g5quality_2.0/details/report.php?id=<?php echo($previous_run_code); ?>">
				<i class ="fa fa-arrow-left" style="padding-right:20px;"></i>
				Previous Run 
			    </a>
			    <?php }
			    else
			    { ?>
                            <a style="width: 120px;" class="btn btn-sm btn-inverse disabled"  href = "">
				<i class ="fa fa-arrow-left" style="padding-right:20px;"></i>
				Previous Run
			    </a>
			    <?php } ?>


			    
			    <a class="btn btn-sm btn-inverse " target="_blank" href = "<?php echo($domain); ?>">
				<!-- <i class ="fa fa-sun-o" style="padding-right:20px;"></i> -->
				<i style="padding-right:20px;"></i>
				<?php echo($domain); ?>
				<i style="padding-left:20px;"></i>
				<!-- <i class ="fa fa-sun-o" style="padding-left:20px;"></i> -->
			    </a>
			    
			    <?php if (isset($next_run_code) && $next_run_code != 'null')
			    { ?>
			    <a style="width: 120px;" class="btn btn-sm btn-inverse"  href = "/g5quality_2.0/details/report.php?id=<?php echo($next_run_code); ?>">
				Next Run
				<i class ="fa fa-arrow-right" style="padding-left:40px;"></i>
			    </a>
			    <?php }
			    else
			    { ?>
			    <a style="width: 120px;" class="btn btn-sm btn-inverse disabled"  href = "">
				Next Run
				<i class ="fa fa-arrow-right" style="padding-left:40px;"></i>
			    </a>
			    <?php } ?>
			    
			    </br></br>
			</center>

    <div class="col-md-10 col-md-offset-1">
      <div class="box">
	<div class="box-header">
	   <h2><a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>Details</h2>
	      <ul class="nav tab-menu nav-tabs" id="myTab">
	        <li class="active"><a href = "#overview" style="font-size: 12px;">Overview</a></li>
	          <?php
	            if ($loadingerror_count > 0){echo ('<li class><a href="#loadingerrors" style="font-size: 12px;">Loading Error [' . $loadingerror_count . ']</a></li>');}
	            //if ($linkingissues_count > 0){echo ('<li class><a href="#linkingissues">Linking Issues  [' . $linkingissues_count . ']</a></li>');}
	            if ($texterrors_count > 0){echo ('<li class><a href="#texterrors" style="font-size: 12px;">Text Error [' . $texterrors_count . ']</a></li>');}
	            if ($repetitivetext_count > 0){echo ('<li class><a href="#repetitivetext" style="font-size: 12px;">Repetitive Text [' . $repetitivetext_count . ']</a></li>');}
	            if ($SEOSEM_count > 0){echo ('<li class><a href="#seosem" style="font-size: 12px;">SEO / SEM [' . $SEOSEM_count . ']</a></li>');}
	            if ($spellingerrors_count > 0){echo ('<li class><a href="#spellingerrors" style="font-size: 12px;">Spelling Error [' . $spellingerrors_count . ']</a></li>');}
	            if ($total_dup != 0){echo ('<li class><a href="#duplicatemeta" style="font-size: 12px;">Duplicate Meta [' . $total_dup . ']</a></li>');}
		  ?>
	      </ul>
	</div>
    
	
	<div class="box-content">
	    <div id="myTabContent" class="tab-content">	    
		<div class="tab-pane active" id="overview">
					</br>
					</br>
					</br>
					<div class="row">
						
						<!--            DOMAIN TYPE                   -->	
					       <div class="col-lg-4 col-sm-4 col-xs-4 col-xxs-12">
							<div class="smallstat box">
								<i class="fa <?php if ($multi_domain == 'true'){echo 'fa-cogs';}else{echo 'fa-cog';} ?> green"></i>
								<span class="title">Domain Type</span>
								<span class="value">
								<?php
								if ($multi_domain == 'true'){echo 'Multi-Domain';}
								else{echo 'Single Domain';}
								//if (in_array('zip_',$allurl)){echo 'Umbrella Domain';}
								?>
								</span>
								<?php if(isset($multi_opposite[0]['code'])) { ?>
								<a href="/g5quality_2.0/details/report.php?id=<?php echo($multi_opposite[0]['code']); ?>" class="more">
									<span><?php echo $multi_nav; ?></span>
									<i class="fa fa-chevron-right"></i>
								</a>
								<?php } else{echo '<div style="height:30px;"></div>';} ?>
								
								
							</div> 
						</div><!--/col-->
						
						
						<!--            ENVIRONMENT TYPE                -->
						<div class="col-lg-4 col-sm-4 col-xs-4 col-xxs-12">
					
							<div class="smallstat box" >
								<i class="fa  fa-globe" style="background-color:#34383C;"></i>
								<span class="title">Environment</span>
								<span class="value">
								<?php
								if (strpos($domain, '.g5demo.com'))
								{$current_env = 'Demo'; echo 'Demo';}
								elseif (strpos($domain, '.redesign.g5cloud.me'))
								{$current_env = 'Redesign'; echo 'Redesign';}
								elseif (strpos($domain, '.redesign2.g5cloud.me'))
								{ $current_env = 'Redesign2'; echo 'Redesign2';}
								elseif (strpos($domain, '.real-staging.g5search.com'))
								{$current_env = 'Real-Staging'; echo 'Real-Staging';}
								elseif (strpos($domain, '.pluto.g5search.com'))
								{$current_env = 'Pluto'; echo 'Pluto';}
								else
								{$current_env = 'Production'; echo 'Production';}
								?>
								</span>
								
								<div style="height:1px;"></div>
								
										<?php		
										$env_counter = 0;
										foreach($environment_codes as $one_code)
										{
										   $env = $one_code['env'];
										   $env_code = $one_code['code'];
										   $url_env = strtolower($env);
										   
										   if ($env != $current_env)
										   { $env_counter = $env_counter + 1;
											
										echo '<a href="/g5quality_2.0/details/report.php?id=' . $env_code . '" class="more" >
											<span>Switch To ' . $env . '</span>
											<i class="fa fa-chevron-right"></i>
										      </a>';
										   }
										
										}
										if ($env_counter == 0) { echo '<div style="height:30px;"></div>';} ?>
										
								
							</div>
					
						</div><!--/col-->
						
						
						<!--            CRAWL TYPE                   -->
						<div class="col-lg-4 col-sm-4 col-xs-4 col-xxs-12">
							<div class="smallstat box"> 
								<i class="fa <?php if ($is_mobile == 'true'){echo 'fa-mobile';} else{echo 'fa-desktop';} ?> green"></i>
								<!-- fa-mobile-phone -->
								<span class="title">Crawl Type</span>
								<span class="value">
								<?php
								if ($is_mobile == 'true'){echo 'Mobile';}
								else{echo 'Desktop';}
								?>
								</span>
								<?php if(isset($mobile_opposite[0]['code']))  { ?>
								<a href="/g5quality_2.0/details/report.php?id=<?php echo($mobile_opposite[0]['code']); ?>" class="more">
									<span><?php echo $mobile_nav; ?></span>
									<i class="fa fa-chevron-right"></i>
								</a>
								<?php } else{echo '<div style="height:30px;"></div>';} ?>
								
								
							</div>

						</div><!--/col-->
						</br>
						
						<!--            HOME PAGE LOAD TIME             -->				
						<div class="col-lg-6 col-sm-6 col-xs-6 col-xxs-12">
							<div class="smallstat box">
								<i class="fa fa-home" style="background-color:#34383C;"></i>
								 
								<span class="title">Home Page</span>
								<span class="value">
										
									 <?php
										if (empty($allurl))
										{
										      echo '-';
										}
										else
										{
												$adjusted_domain = substr($domain, 0, -1);
												$no_www_domain = str_replace('www.', '', $adjusted_domain);
												$only_one = 0;

												        foreach($allurl as $oneurl)
													    {
														$url = $oneurl['ID'];
													
															if ($only_one < 1)
															{
																if ($url == $domain || $url == $adjusted_domain || $url == $no_www_domain)
																{
																	$load = $oneurl['LoadTime'];
																	 echo ($load . ' s');
																	 $only_one++;
																	 
																}
															}
													    }
										}				    
										?>
 
								</span>
								<a href="#loadtimes" class="more">
									<span>All Load Times</span>
									<i class="fa fa-chevron-right"></i>
								</a>	
							</div>
						</div><!--/col-->
						
					
					        <!--            DOMAIN HISTORY                  -->
						<div class="col-lg-6 col-sm-6 col-xs-6 col-xxs-12">
							<div class="smallstat box">
								<div class="linechart-overlay green">
									<div class="linechart">
										
										<?php
										$loopcount = 0;
										$length = count($BugTotals);
										if ($length > 1)
										{
										    foreach ($BugTotals as $key => $value)
										    {
										         if ($loopcount < $length - 1)
											 {
										         echo ( $value . ',');
											 }
											 else
											 {
											 echo ($value);				
											 }
											 $loopcount = $loopcount + 1;
										    }
										}
										else
										{
											echo ('1,1,1');					
										}
										?>
										
									</div>
								</div>	
								<span class="title"><?php echo $month . ' ' . $day . ' ' . $year . ' ' . $hour . ':' . $minute . ' ' . $ampm; ?></span>
								<span class="value">
								<?php
								if ($current_run == 1 || $current_run == 21 || $current_run == 31 || $current_run == 41)
								{echo ($current_run . '<sup>st</sup> Run');}
								elseif ($current_run == 2 || $current_run == 22 || $current_run == 32 || $current_run == 42)
								{echo ($current_run . '<sup>nd</sup> Run');}
								elseif ($current_run == 3 || $current_run == 23 || $current_run == 33 || $current_run == 43)
								{echo ($current_run . '<sup>rd</sup> Run');}
								elseif ($current_run == '')
								{echo ('Not Logged');}
								else
								{echo ($current_run . '<sup>th</sup> Run');}
								?>
								</span>
								<a href="#totals" class="more">
									<span>Domain History</span>
									<i class="fa fa-chevron-right"></i>
								</a>
							</div>
						</div><!--/col-->


					</div><!--/row-->
					
										
					
							    
			</br>
			</br>
			</br>

					
					
					
                </center>					    
		</div><!-- end overview -->
	    

		<div class="tab-pane" id="loadingerrors">
							    		
						</br></br>
						<div class="box-content">
					             <div style="overflow-x:scroll;">			
							<table class="table table-striped table-bordered bootstrap-datatable datatable">
							  <thead>
								  <tr>
									  <th><h2><strong>URL</strong></h2></th>
									  <th><h2><strong>Source URL</strong></h2></th>
									  <th><h2><strong>Error Type</strong></h2></th>

								  </tr>
							  </thead>   
							  <tbody>
							    <?php
							    foreach($errors as $single_error)
								    {
									$type = $single_error['type'];
									$url = $single_error['URL'];
									$url_cut = substr($url, 0, 60);
									$message = $single_error['message'];
									$sourceurl = $single_error['SourceUrl'];
									$sourceurl_cut = substr($sourceurl, 0, 60);
									if($is_mobile == "true")
									{
										$url = "/g5quality_2.0/view_mobile.php?url=" . $single_error['URL'];
										$sourceurl = "/g5quality_2.0/view_mobile.php?url=" . $single_error['SourceUrl'];
					                                }
									 if($type == "LoadingError")
									 {
									    ?>
									    <tr>
									    <td><a target="_blank" href="<?php echo $url; ?>"><?php echo $url; ?></a></td>
									    <td><a target="_blank" href="<?php echo $sourceurl; ?>"><?php echo $sourceurl; ?></a></td>
									    <td><?php echo $message;?></td>
									    </tr>
									    <?php
									 }
 
								    }?>
							  </tbody>
							</table>
						     </div>
						</div>
						
				
		</div>
		
		<!--
		<div class="tab-pane" id="linkingissues">
		    </br></br>
						<div class="box-content">
							<table class="table table-striped table-bordered bootstrap-datatable datatable">
							  <thead>
								  <tr>
									  <th><h2><strong>URL</strong></h2></th>
									  <th><h2><strong>Source URL</strong></h2></th>
									  <th><h2><strong>Error Type</strong></h2></th>

								  </tr>
							  </thead>   
							  <tbody>
							    <?php 
							    foreach($errors as $single_error)
								    {
									$type = $single_error['type'];
									$url = $single_error['URL'];
									$url_cut = substr($url, 0, 60);
									$message = $single_error['message'];
									$sourceurl = $single_error['SourceUrl'];
									$sourceurl_cut = substr($sourceurl, 0, 60);
									if($is_mobile == "true")
									{
										$url = "http://192.168.3.168/g5quality_2.0/view_mobile.php?url=" . $single_error['URL'];
										$sourceurl = "http://192.168.3.168/g5quality_2.0/view_mobile.php?url=" . $single_error['SourceUrl'];
					                                }
									 if($type == "LinkingIssue")
									 {
									   ?>
									    <tr>
									    <td><a target="_blank" href="<?php echo $url; ?>"><?php echo $url_cut; ?></a></td>
									    <td><a target="_blank" href="<?php echo $sourceurl; ?>"><?php echo $sourceurl_cut; ?></a></td>
									    <td><?php echo $message;?></td>
									    </tr>
									    <?php
									 }
 
								    }?>
							  </tbody>
							</table>
						</div>
		</div>
		-->
		
		
		<div class="tab-pane" id="texterrors">
		    </br></br>
						<div class="box-content">
					            <div style="overflow-x:scroll;">
							<table class="table table-striped table-bordered bootstrap-datatable datatable">
							  <thead>
								  <tr>
									  <th><h2><strong>URL</strong></h2></th>
									  <!-- <th><h2><strong>Source URL</strong></h2></th> -->
									  <th><h2><strong>Error Type</strong></h2></th>

								  </tr>
							  </thead>   
							  <tbody>
							    <?php
							    foreach($errors as $single_error)
								    {
									$type = $single_error['type'];
									$url = $single_error['URL'];
									$url_cut = substr($url, 0, 60);
									$message = $single_error['message'];
									//$sourceurl = $single_error['SourceUrl'];
									//$sourceurl_cut = substr($sourceurl, 0, 60);
									if($is_mobile == "true")
									{
										$url = "http://192.168.3.168/g5quality_2.0/view_mobile.php?url=" . $single_error['URL'];
										//$sourceurl = "http://192.168.3.168/g5quality_2.0/view_mobile.php?url=" . $single_error['SourceUrl'];
					                                }
									 if($type == "TextError")
									 {
									    ?>
									    <tr>
									    <td><a target="_blank" href="<?php echo $url; ?>"><?php echo $url; ?></a></td>
									    <!-- <td><a target="_blank" href="<?php //echo $sourceurl; ?>"><?php //echo $sourceurl_cut; ?></a></td> -->
									    <td><?php echo $message;?></td>
									    </tr>
									    <?php
									 }
 
								    }?>
							  </tbody>
							</table>
						    </div>
						</div>
		</div>
		
		<div class="tab-pane" id="repetitivetext">
		    </br></br>
						<div class="box-content">
					            <div style="overflow-x:scroll;">
							<table class="table table-striped table-bordered bootstrap-datatable datatable">
							  <thead>
								  <tr>
									  <th><h2><strong>URL</strong></h2></th>
									  <!-- <th><h2><strong>Source URL</strong></h2></th> -->
									  <th><h2><strong>Error Type</strong></h2></th>
								  </tr>
							  </thead>   
							  <tbody>
							    <?php
							    foreach($errors as $single_error)
								    {
									$type = $single_error['type'];
									$url = $single_error['URL'];
									$url_cut = substr($url, 0, 60);
									$message = $single_error['message'];
									//$sourceurl = $single_error['SourceUrl'];
									//$sourceurl_cut = substr($sourceurl, 0, 60);
									if($is_mobile == "true")
									{
										$url = "http://192.168.3.168/g5quality_2.0/view_mobile.php?url=" . $single_error['URL'];
										//$sourceurl = "http://192.168.3.168/g5quality_2.0/view_mobile.php?url=" . $single_error['SourceUrl'];
					                                }
									 if($type == "RepetitiveText")
									 {
									    ?>
									    <tr>
									    <td><a target="_blank" href="<?php echo $url; ?>"><?php echo $url; ?></a></td>
									    <!-- <td><a target="_blank" href="<?php //echo $sourceurl; ?>"><?php //echo $sourceurl_cut; ?></a></td> -->
									    <td><?php echo $message;?></td>
									    </tr>
									    <?php
									 }
 
								    }?>
							  </tbody>
							</table>
						    </div>
						</div>
		</div>
		
		<div class="tab-pane" id="seosem">
		    </br></br>
						<div class="box-content">
					            <div style="overflow-x:scroll;">
							<table class="table table-striped table-bordered bootstrap-datatable datatable">
							  <thead>
								  <tr>
									  <th><h2><strong>URL</strong></h2></th>
									  <!-- <th><h2><strong>Source URL</strong></h2></th> -->
									  <th><h2><strong>Error Type</strong></h2></th>

								  </tr>
							  </thead>   
							  <tbody>
							    <?php
							    foreach($errors as $single_error)
								    {
									$type = $single_error['type'];
									$url = $single_error['URL'];
									$url_cut = substr($url, 0, 60);
									$message = $single_error['message'];
									//$sourceurl = $single_error['SourceUrl'];
									//$sourceurl_cut = substr($sourceurl, 0, 60);
									if($is_mobile == "true")
									{
										$url = "http://192.168.3.168/g5quality_2.0/view_mobile.php?url=" . $single_error['URL'];
										//$sourceurl = "http://192.168.3.168/g5quality_2.0/view_mobile.php?url=" . $single_error['SourceUrl'];
					                                }
									 if($type == "TitleError" || $type == "LinkingIssue" || $type == "GA_E")
									 {
									    ?>
									    <tr>
									    <td><a target="_blank" href="<?php echo $url; ?>"><?php echo $url; ?></a></td>
									    <!-- <td><a target="_blank" href="<?php //echo $sourceurl; ?>"><?php //echo $sourceurl_cut; ?></a></td> -->
									    <td><?php echo $message;?></td>
									    </tr>
									    <?php
									 }
 
								    }?>
							  </tbody>
							</table>
						    </div>
						</div>
		</div>
		
		<div class="tab-pane" id="spellingerrors">
							    		
						</br></br>
						<div class="box-content">
					            <div style="overflow-x:scroll;">
							<table class="table table-striped table-bordered bootstrap-datatable datatable">
							  <thead>
								  <tr>
									  <th><h2><strong>URL</strong></h2></th>
									  <!-- <th><h2><strong>Source URL</strong></h2></th> -->
									  <th><h2><strong>Error Type</strong></h2></th>
								  </tr>
							  </thead>   
							  <tbody>
							    <?php
							    foreach($errors as $single_error)
								    {
								    $random_spell_code = mt_rand();
									$type = $single_error['type'];
									$url = $single_error['URL'];
									$url_cut = substr($url, 0, 60);
									$message = $single_error['message'];
									//$sourceurl = $single_error['SourceUrl'];
									//$sourceurl_cut = substr($sourceurl, 0, 60);
									if($is_mobile == "true")
									{
										$url = "http://192.168.3.168/g5quality_2.0/view_mobile.php?url=" . $single_error['URL'];
										//$sourceurl = "http://192.168.3.168/g5quality_2.0/view_mobile.php?url=" . $single_error['SourceUrl'];
					                                }
									
									 if($type == "SpellingError")
									 {

									 	if (in_array($message, $unique_words)) 
							     		{
							     			foreach ($unique_words_count as $oneword) 
											{
												$a_word = $oneword['word'];
												$counter = $oneword['count'];
												if ($a_word == $message && $counter < $count_limiter)
												{
								     				?>
								     				<tr>
													    <td><a target="_blank" href="<?php echo $url; ?>"><?php echo $url; ?></a></td>
													    <!-- <td><a target="_blank" href="<?php //echo $sourceurl; ?>"><?php //echo $sourceurl_cut; ?></a></td> -->
													    <td>

													    	<?php
													    	$no_display = false;
													    	
														    	for ($gg = 0; $gg < count($dictionary); $gg++)
																{				
																    if($message == $dictionary[$gg]['word'] && $code == $dictionary[$gg]['report_code'])
																    	{
																    		$no_display = true;
																    		break 1;

																		}
																
																}

															if ($no_display == false)
															{
																?>
																		<span class = "spell-id-<?php echo $code; echo '-'; echo $message; ?>">
																	    	<button style = "padding-top: 0px; padding-bottom: 0px;"  class="btn btn-sm btn-warning pull-right noty" data-noty-options='{"text":"Vote recieved. <b><?php echo $message; ?></b> considered for dictionary update","layout":"center","type":"success","animateOpen": {"opacity": "show"}}' onclick="add_word('<?php echo $code; ?>','<?php echo $random_spell_code; ?>','<?php echo $message; ?>');">
																	    		<i class="fa fa-arrow-down"></i>
																	    	</button>
																	    </span>

																<?php
															}
													    	?>

														    <span id="downvoter-<?php echo $code; echo '-'; echo $message; ?>" class="pull-right" ></span>

														    	<?php echo $message; ?>
													        
													    </td>
									    			</tr>
								     				<?php
								     			}
							     			}
										}
										else
										{
											?>
											<tr>
											    <td><a target="_blank" href="<?php echo $url; ?>"><?php echo $url; ?></a></td>
											    <!-- <td><a target="_blank" href="<?php //echo $sourceurl; ?>"><?php //echo $sourceurl_cut; ?></a></td> -->
											    <td>
											    	<span class = "spell-id-<?php echo $code; echo '-'; echo $message; ?>">
												    	<button style = "padding-top: 0px; padding-bottom: 0px;"  class="btn btn-sm btn-warning pull-right noty" data-noty-options='{"text":"Vote recieved. <b><?php echo $message; ?></b> considered for dictionary update","layout":"center","type":"success","animateOpen": {"opacity": "show"}}' onclick="add_word('<?php echo $code; ?>','<?php echo $random_spell_code; ?>','<?php echo $message; ?>');">
												    		<i class="fa fa-arrow-down"></i>
												    	</button>
												    </span>

												    <span id="downvoter-<?php echo $code; echo '-'; echo $random_spell_code; ?>" class="pull-right" ></span>

												    	<?php echo $message; ?>
											        
											    </td>
										    </tr>
											<?php
										}

									 }
								    }?>
							  </tbody>
							</table>
						    </div>
						</div>
						
				
		  </div>
		
		  <div class="tab-pane" id="duplicatemeta">
							    		
						</br></br>
						<div class="box-content">
					            <div style="overflow-x:scroll;">
							<table class="table table-striped table-bordered bootstrap-datatable datatable">
					                 
							  <thead>
								  <tr>
									  <th><h2><strong>URL</strong></h2></th>
									  <th><h2><strong>Source URL</strong></h2></th>
									  <th><h2><strong>Meta decription</strong></h2></th>

								  </tr>
							  </thead>   
							  <tbody>
							    <?php

							    for ($yy = 0; $yy < count($dup_meta_kings); $yy++)
					                    { ?>	
									    <tr>
									    <td>
										
									          				  
										<?php 
										for ($ii = 0; $ii < count($reset_2_dupmeta); $ii++)
										{
															
										        
										    if ($reset_2_dupmeta[$ii]['Meta'] == $dup_meta_kings[$yy])
										    {
											if($is_mobile == "true")
										        {
										            			
											    echo '<a target= "_blank" href = "http://192.168.3.168/g5quality_2.0/view_mobile.php?url=' . $reset_2_dupmeta[$ii]['ID'] . '">' . $reset_2_dupmeta[$ii]['ID'] . '</a>';
											    echo '</br>';
											    
										        }
											else
											{
										           			
										            echo '<a target= "_blank" href = ' . $reset_2_dupmeta[$ii]['ID'] . '>' . $reset_2_dupmeta[$ii]['ID'] . '</a>';
											    echo '</br>';
											    
											}
										    }
										    
										    
										} 
										?>
										      
										
									    </td>
									    <td>
										<?php 
										for ($ii = 0; $ii < count($reset_2_dupmeta); $ii++)
										{
															
										        
										    if ($reset_2_dupmeta[$ii]['Meta'] == $dup_meta_kings[$yy])
										    {
											if($is_mobile == "true")
										        {				
										            echo '<a target = "_blank" href = "http://192.168.3.168/g5quality_2.0/view_mobile.php?url=' . $reset_2_dupmeta[$ii]['SourceID'] . '">' . $reset_2_dupmeta[$ii]['SourceID'] . '</a>';
											    echo '</br>';
											}
											else
											{
											    echo '<a target = "_blank" href = ' . $reset_2_dupmeta[$ii]['SourceID'] . '>' . $reset_2_dupmeta[$ii]['SourceID'] . '</a>';
											    echo '</br>';				
											}
										    }
										} 
										?>
										
									    </td>
									    <td><?php echo $dup_meta_kings[$yy]; ?></td>
									    </tr>
					                     <?php }
							     ?>
							  </tbody>
							  
							</table>
						    </div><!-- horizontal scroll -->
						</div>
						
				
		  </div>
		  
            </div>
	</div>
       </div>
					<!-- All URL Information -->
					<div class="box">
						<div class="box-header">
							<h2><a id="collapseURLs" href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a><?php echo $totalurl; ?> URLs</h2>
							<ul class="nav tab-menu nav-tabs" id="myTab">
								<li class="active"><a href="#loadtimes">Load Times</a></li>
								
					
							</ul>
						</div>
					   <div class="box-content">
						<div class="tab-content">
					                <div class="tab-pane active" id="loadtimes">
								</br></br>
										<div class="box-content">
												<table class="table table-striped table-bordered bootstrap-datatable datatable">
												  <thead>
													  <tr>
														  <th><h2><strong>URL</strong></h2></th>
														  <th><h2><strong>Load Time</strong></h2></th>
														  
													  </tr>
												  </thead>   
												  <tbody>
												    <?php
												    foreach($allurl as $oneurl)
													    {
														$url = $oneurl['ID'];
														$url_cut = substr($url, 0, 80);
														if($is_mobile == "true")
														{
														$url = "http://192.168.3.168/g5quality_2.0/view_mobile.php?url=" . $oneurl['ID'];
														}
														$load = $oneurl['LoadTime'];
													
														
														
														 
														    ?>
														    <tr>
														    <td><a target="_blank" href="<?php echo $url; ?>"><?php echo $url_cut; ?></a></td>
														    <td><?php echo $load; ?></td>
														    
														    </tr>
														    <?php
														 
					 
													    }?>
												  </tbody>
												</table>
										</div>
							</div>
							
							
						</div>
					   </div>
					</div>
	    
	    
	    
	    
	 <?php
		if($total_runs > 1){
	 ?>
	                               <!-- Domain History Chart -->
                                
					<div class="box">
						<div class="box-header">
							<h2><a id="collapsehistory" href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a> Domain History </h2>
							<ul class="nav tab-menu nav-tabs" id="myTab">
								<li class="active"><a href="#totals">Totals</a></li>
								
							</ul>
						</div>
					   <div class="box-content">
						<div class="tab-content">
					                <div class="tab-pane active" id="totals">
								<div id="BugTotals" style="height:400px" ></div>
							</div>
							
						</div>
					   </div>
					</div>
	    <?php
		}		
	    ?>	
			
	    </br></br>
       </div><!--/col-->
       
<!-- This script collapses certain panels onload 
<script>
$("document").ready(function() {
    setTimeout(function() {
        $("#collapsehistory").trigger('click');
	$("#collapseURLs").trigger('click');
    },2);
});
</script>
    -->
    
<script>
  $("document").ready(function() {
       document.getElementById("duplicatemeta")

</script>
       

</div><!-- end content-->





	
<script src="/g5quality_2.0/assets/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
	window.jQuery || document.write("<script src='/g5quality_2.0/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
</script>
<script src="/g5quality_2.0/assets/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/g5quality_2.0/assets/js/bootstrap.min.js"></script>
		
<!-- page scripts -->
<script src="/g5quality_2.0/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="/g5quality_2.0/assets/js/jquery.noty.min.js"></script>
<script src="/g5quality_2.0/assets/js/jquery.sparkline.min.js"></script>

<script src="/g5quality_2.0/assets/js/jquery.dataTables.min.js"></script>
<script src="/g5quality_2.0/assets/js/dataTables.bootstrap.min.js"></script>
	
<!-- theme scripts -->
<script src="/g5quality_2.0/assets/js/custom.min.js"></script>
<script src="/g5quality_2.0/assets/js/core.min.js"></script>
	
<!-- inline scripts related to this page -->
<script src="/g5quality_2.0/assets/js/pages/table.js"></script>


<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="/g5quality_2.0/assets/js/excanvas.min.js"></script><![endif]-->
<script src="/g5quality_2.0/assets/js/jquery.knob.modified.min.js"></script>
<script src="/g5quality_2.0/assets/js/jquery.easy-pie-chart.min.js"></script>
<script src="/g5quality_2.0/assets/js/jquery.flot.min.js"></script>
<script src="/g5quality_2.0/assets/js/jquery.flot.pie.min.js"></script>
<script src="/g5quality_2.0/assets/js/jquery.flot.stack.min.js"></script>
<script src="/g5quality_2.0/assets/js/jquery.flot.resize.min.js"></script>
<script src="/g5quality_2.0/assets/js/jquery.flot.time.min.js"></script>
<script src="/g5quality_2.0/assets/js/jquery.flot.axislabels.js"></script>
<script src="/g5quality_2.0/assets/js/jquery.autosize.min.js"></script>
<script src="/g5quality_2.0/assets/js/jquery.placeholder.min.js"></script>
<script src="/g5quality_2.0/assets/js/moment.min.js"></script>
<script src="/g5quality_2.0/assets/js/daterangepicker.min.js"></script>

<!-- inline scripts related to this page -->
<script src="/g5quality_2.0/assets/js/pages/charts-other.js"></script>


<!-- Written Javascripts -->
<script src= "/g5quality_2.0/assets/js/Home_Refresh.js" type = "text/javascript"> </script>
<script src="/g5quality_2.0/assets/js/Update_Data.js" type = "text/javascript"> </script>

</body>
 </html>