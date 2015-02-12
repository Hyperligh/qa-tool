<?php

class accordian{
    

   function generate($multi, $mobile, $domain, $code, $time, $status, $URLs_Ran, $percent_done, $differenceInSeconds, $number_loading_errors, $number_title_errors, $number_text_errors, $number_repetitive_errors, $number_linking_issues, $number_spelling_errors, $number_duplicate_meta, $number_ga_errors)
   {  
      

      $BugTotal = $number_loading_errors + $number_title_errors + $number_text_errors + $number_repetitive_errors + $number_linking_issues + $number_spelling_errors + $number_duplicate_meta + $number_ga_errors;
      $status_color = '';
      $icon = '';
      if($status == 'done'){
            $status_color = '#78CD51';
            $icon = 'fa fa-check-circle';
        }
      elseif($status == 'running'){
            $status_color = '#FABB3D';
            $icon = 'fa fa-spinner';
        }
      elseif($status == 'waiting'){
            $status_color = '#FABB3D';
            $icon = 'fa fa-sign-in';
        }
      else
	{
	   $status_color = '#FF5454';
           $icon = 'fa fa-ban';
	}
        
// reformat the time stamp into a more readable format
/*
$time_stamp = $time;
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
*/
        
//multi
if ($multi == 'true'){
   $multi_or_single = '<i class="fa fa-cogs"></i>';
   $m_or_s_pr = 'padding-right:7px;';
   $m_or_s_pl = 'padding-left:7px;';
   }
else{
   $multi_or_single = '<i class="fa fa-cog"></i>';
   $m_or_s_pr = 'padding-right:8px;';
   $m_or_s_pl = 'padding-left:8px;';
   }
//mobile
if ($mobile == 'true'){
   $desk_or_mob = '<i class="fa fa-mobile"></i>';
   $desk_or_mob_pad_r = 'padding-right:11px;';
   $desk_or_mob_pad_l = 'padding-left:10px;';
   }
else{
   $desk_or_mob = '<i class="fa fa-desktop"></i>';
   $desk_or_mob_pad_r = 'padding-right:7.5px;';
   $desk_or_mob_pad_l = 'padding-left:7.5px;';
   }
        
// progress
$percent_done = floor($percent_done);

// li_width
$CatTotal = 0;
$number_SEOSEM_concern = $number_title_errors + $number_linking_issues + $number_ga_errors;

if ($number_loading_errors > 0){$CatTotal = $CatTotal + 1;}
if ($number_SEOSEM_concern > 0){$CatTotal = $CatTotal + 1;}
if ($number_text_errors > 0){$CatTotal = $CatTotal + 1;}
if ($number_repetitive_errors > 0){$CatTotal = $CatTotal + 1;}
if ($number_spelling_errors > 0){$CatTotal = $CatTotal + 1;}
if ($number_duplicate_meta > 0){$CatTotal = $CatTotal + 1;}

$li_width = 0;
if($CatTotal > 0){
$li_width = (100/$CatTotal);
}
?>


<!-- AJAX CANCEL BUTTON  -->
<div style='padding-right:5px;' id="canceller-<?php echo $code; ?>">
   <?php if($status == "running"){ ?>
      <button   href='' style='padding-bottom:8px;' class="btn btn-danger pull-left" onclick="cancel_domain('<?php echo $code; ?>');"><i style='padding-top:4px;' class="fa fa-ban"></i></button>
   <?php  } ?>
</div>
<!-- AJAX CANCEL BUTTON -->

   <div class="panel-group" id="accordion_box" style='padding-bottom:6px;'>
      <div class="panel panel-default" id = "addpanel">
         
         
         
         <div class = "pull-right">
            
            <div class="date" style="line-height: 32px;">
               <span id="newstatus-<?php echo $code; ?>">
                  <span style="<?php if ($status == 'running'){echo'padding-right: 4.5px; padding-left: 4.5px;';} else{echo 'padding-right: 8px; padding-left: 8px;';}?> padding-top:10px; padding-bottom:10px; color: #34383C; background-color:<?php echo $status_color; ?>" class="label label-default"><?php if ($status == 'running'){echo'<img src="html_pieces/ajax-loader.gif">';} else{echo '<i class="'.$icon.'"></i>';}?></span></span>
                  <span style="<?php echo $desk_or_mob_pad_r; echo $desk_or_mob_pad_l; ?> padding-top:10px; padding-bottom:10px; background-color:#34383C;" class="label label-default"><?php echo $desk_or_mob; ?></span><span style="<?php echo $m_or_s_pl; echo $m_or_s_pr; ?> padding-top:10px; padding-bottom:10px; background-color:#34383C;" class="label label-default"><?php echo $multi_or_single; ?></span><span style="padding-top:10px; padding-bottom:10px; background-color:#34383C;" class="label label-default"><?php echo $time; ?></span>
                  
                  
                  
            </div>
         </div>
               <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $code; ?>">
                                             
                  <div class="panel-heading">
                      
                     <h4 class="panel-title">
                           <?php echo $domain; ?>
                     </h4>
                      
                  </div>
               </a>

               
                <div id="collapse<?php echo $code; ?>" class="panel-collapse collapse">
                     <div class="panel-body">
                        
                        <div class="col-md-4">
                            <div class="smallstat box">
			        <div class="pull-left" id="percent-<?php echo $code; ?>" style="width: 60%; border: 2px solid <?php if($status == 'Cancelled'){echo 'rgb(255, 84, 84);';}else{echo 'rgb(120, 205, 81);';} ?> border-top-left-radius: 3px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; overflow: hidden; display: inline-block; padding: 1px; margin: 0px 10px 5px 5px;" >
                                 <div id="percentbar-<?php echo $code; ?>" style="height: 40px; text-align: center; vertical-align: middle; color: rgb(255, 255, 255); width: <?php echo $percent_done; ?>%; border-top-left-radius: 2px; border-top-right-radius: 2px; border-bottom-right-radius: 2px; border-bottom-left-radius: 2px; background-color: <?php if($status == 'Cancelled'){echo 'rgb(255, 84, 84)';}else{echo 'rgb(120, 205, 81)';} ?>;"></div>
                                </div>

            

                                <span class="title">Total URLs</span>
                                <span class="value" id='urls_ran-<?php echo $code; ?>'><?php echo $URLs_Ran; ?></span>
                            </div>
                        </div><!--/col-->
                                             <div class="col-md-1"></div>
                                                <div class="col-md-2" style = "padding-bottom: 30px;">
                                                  <a class="quick-button" href = "/g5quality_2.0/details/report.php?id=<?php echo $code; ?>">
                                                        <i class="fa fa-bug"></i>
                                                        <p>View Details</p>
                                                        <span class="notification" style="background-color:<?php if($status == 'Cancelled'){echo 'rgb(255, 84, 84)';}else{echo 'rgb(120, 205, 81)';} ?>;" id='bug_total-<?php echo $code; ?>'><?php echo $BugTotal; ?></span>
                                                    </a>
                                                </div><!--/col-->
                                             <div class="col-md-1"></div>

                        <div class="col-md-4 ">
                            <div class="smallstat box">
                                <i class="fa fa-clock-o" style="background-color: <?php if($status == 'Cancelled'){echo 'rgb(255, 84, 84)';}else{echo 'rgb(120, 205, 81)';} ?>;"></i> 
                                <span class="title">Time Elapsed</span>
                                <span class="value"><?php echo gmdate("H:i:s", $differenceInSeconds); ?></span>
                            </div>
                        </div><!--/col-->
                        </br></br>
                        </br></br>
                        </br></br>

                        
                        <!-- will change when functionchanges -->
                        <!-- add if statement for each additional function -->
                        <div class="box-content">
                           <ul class="stats">
                              
                              
                                 <li id='broke-<?php echo $code; ?>' style=" width: <?php if ($number_loading_errors >0) {echo $li_width.'%;';}else{echo ('0%; border: 0px;');} ?> ">
                                 <h1>
                                 <?php echo $number_loading_errors; ?>
                                 </h1>
                                 <h5>
                                    Broken Pages
                                 </h5>
                                 </li>
                                 
                                 <li id='texterror-<?php echo $code; ?>'style="width: <?php if ($number_text_errors >0) {echo $li_width.'%;';}else{echo ('0%; border: 0px;');} ?> ">
                                    <h1>
                                       <?php echo $number_text_errors; ?>
                                    </h1>
                                    <h5>
                                       Text Errors
                                    </h5>
                                 </li>
                                 
                                 <li id="spelling-<?php echo $code;?>"style="width: <?php if ($number_spelling_errors >0) {echo $li_width.'%;';}else{echo ('0%; border: 0px;');} ?> ">
                                    <h1>
                                       <?php echo $number_spelling_errors; ?>
                                    </h1>
                                    <h5>
                                       Spelling Errors
                                    </h5>
                                 </li>
                                 
                                 <li id="repetitive-<?php echo $code;?>"style="width: <?php if ($number_repetitive_errors >0) {echo $li_width.'%;';}else{echo ('0%; border: 0px;');} ?> ">
                                    <h1>
                                       <?php echo $number_repetitive_errors; ?>
                                    </h1>
                                    <h5>
                                       Repetitive Text Errors
                                    </h5>
                                 </li>
                                 
                                 <li id='seosem-<?php echo $code; ?>'style="width: <?php if ($number_SEOSEM_concern >0) {echo $li_width.'%;';}else{echo ('0%; border: 0px;');} ?> ">
                                 <h1>
                                 <?php echo $number_SEOSEM_concern; ?>
                                 </h1>
                                 <h5>
                                    SEO / SEM Concerns
                                 </h5>
                                 </li>
                                 

                                 
                                 <li id="meta-<?php echo $code;?>"style="width: <?php if ($number_duplicate_meta >0) {echo $li_width.'%;';}else{echo ('0%; border: 0px;');} ?>">
                                    <h1>
                                       <?php echo $number_duplicate_meta; ?>
                                    </h1>
                                    <h5>
                                       Duplicate Meta Descriptions
                                    </h5>
                                 </li>
                              
                              <?php if($BugTotal == 0)
                              { ?>
                                 <li id="delete-noerror-<?php echo $code;?>" style="width: 100%;" >
                                    <h1>
                                       No Errors Found
                                    </h1>
                                    <h5>
                                    </h5>
                                 </li>
                              <?php }
                              ?>
                              
                            </ul>
                        
                           
                        </div>
                        
                        
                           
                       
                        
                    </div><!--/row-->
                </div>
            </div> <!-- pannel 1-->
         </div>



<?php
   }

}
?>


