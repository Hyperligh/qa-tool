<?php
session_start();

$real_path = $_SERVER['DOCUMENT_ROOT'];
require_once($real_path . "/g5quality_2.0/templates/header-home.html");
?>

    <div  id="content" class="col-lg-12 col-sm-12 col-lg-offset-0 col-sm-offset-0">
	
	<div class="block" style="height: 250px;">
	    <div class="centered">
		<a href = "/g5quality_2.0/home.php" ><img src="QA_Ninja_Logo.png"></a>
	    </div>  
	</div>
    
	<!-- This code block is for alerting users to outages ect. 
	<div class="alert alert-danger" style="text-align: center">
	    Brief Outage. Updating...
	</div>
	-->
	

        <!-- This is the form where user enters the domain to test -->
	     <!-- Current Params: 1. Mobile  2. Multi-Domain -->

        <form class="form-horizontal" id = "domain_info" name = "domain_info" method = "post">
          <center>
            <fieldset class="col-md-8 col-md-offset-2">
		<!-- this feature is currently not available
                <div class="form-group"> 
                    <div class="controls">
                        <label class="control-label" for="browser_type">Browser Type:
                               <select name = "browser_type" id = "browser_type" class="form"-control">
                                 <option value="Firefox">Firefox</option>
                                 <option value="Chrome">Chrome</option>
                                 <option value="IE-10">IE-10</option>
                                 <option value="IE-9">IE-9</option>
                                 <option value="IE-8">IE-8</option>
                               </select>
                        </label>
                    </div>
                </div>
		-->
		
		</br>
		<div class="controls row">  
		    <div class="col-lg-3 col-md-3 col-sm-3"></div>
		    <div class="form-group col-lg-3 col-md-3 col-sm-3">
			<i title="Enables the crawling of multiple domains" data-rel="tooltip" style="font-size: 24px; padding-left: 50px;" class="fa fa-cogs"></i>
			<label class="switch switch-success">
			    <input type="checkbox" class="switch-input" id="multi_domain" onclick="demo_only();">
			    <span class="switch-label" data-on="On" data-off="Off"></span>
			    <span class="switch-handle"></span>
			</label>
		    </div>
		    
		    
		    <div class="form-group col-lg-3 col-md-3 col-sm-3">
			<i title="Crawls from a device point of view" data-rel="tooltip" style="font-size: 30px; padding-left: 50px;" class="fa fa-mobile"></i>
			<label class="switch switch-success">
			    <input type="checkbox" class="switch-input" id="desk-mobile" onclick="mobiletest();">
			    <span class="switch-label" data-on="On" data-off="Off"></span>
			    <span class="switch-handle"></span>
			</label>
		    </div>
		    <div class="col-lg-3 col-md-3 col-sm-3"></div>
		</div>
		
		<span id ="set-check" style="display:none">false</span>
		<span id ="set-check-mobile" style="display:none">false</span>

                                  <tr>
                                    <td>
                                      <span id = "demo_only"></span>
				      <span id ="status" class="label" style="background-color:#78cd51;"></span>
                                    </td>
                                  </tr>

        </br>
        </br>
 
            <div class="form-group">
                    <!--<label class="col-sm-2 control-label" for="domain">Enter Domain:</label>-->
                <div class="controls">
		    <div class="input-group">
			<input class="form-control required" type = "URL" name = "domain" id="domain" onkeypress="submitOnEnter();" placeholder="Enter Your Domain" size="65" />
			<span class="input-group-btn">
			    <button class="btnsub" type ='button' id ="btnSubmit" value ='Submit' onclick='process();' />Submit</button>
			</span>
		    </div>
                </div>
            </div>
            </fieldset>
        </center>
	</form>
</br></br></br></br></br></br></br></br></br></br>

            <div>
                <center><h1> Most Recent Domains </h1></center>
    
            </div>
            </br></br>
	    
                <div class="col-md-10 col-md-offset-1">
                    <div class="box">
                        <div id="domains_box" class="box-content">
                            
<span id="ajax-add-panel">
<?php
//For each of the 30 past ran domains, a new accordian needs to be loaded.

//Start by calling the 30 past ran domains.
require_once($real_path . "/g5quality_2.0/Data/pull.php");
require_once($real_path . "/g5quality_2.0/html_pieces/domain_accordians.php");
require_once('overview_class.php');


$accordian = new accordian();
$request = new request();
$recent_domains = $request->recent_domains();
           
//Loop through results. Create a new accordian for each result
    while($row = $recent_domains->fetch_assoc())
    {
    
     //Queue log result variables
        $code = $row['code'];
        $domain = $row['Domain'];
        $time = $row['time'];
        $status = $row['status'];
        $URLs_Ran = $row['URLs_Ran'];
		$URLs_Remaining = $row['URLs_Remaining'];
		$multi = $row['multidomain'];
		$mobile = $row['mobile'];
	
	//Set purgatory domains to appear as done.
	//They will be changed in the db by the dbms after the user loads the page
	if($status == "purgatory"){
	    $status = "done";
	}
	
	$percent_done =0;
	if($URLs_Ran > 0){
	$percent_done =  ($URLs_Ran/($URLs_Ran + $URLs_Remaining))*100;
	}
    
	
    
	//$domain_class = new domain_overview();
	//$domain_class->total_errors($code);
    
	$number_title_errors = $row['number_title_errors'];				//Number of errors in title found
	$number_loading_errors = $row['number_loading_errors'];			//Number broken pages, 500 errs etc
	$number_repetitive_errors = $row['number_repetitive_errors'];	//Number of of errors like this
	$number_text_errors = $row['number_text_errors'];				//Erorrs such as &amp;
	$number_linking_issues = $row['number_linking_issues'];			//Custom to G5. Things like "General" should not be in URL
	$number_spelling_errors = $request->number_adjust_spell_error($code);		//Like it sounds
	$number_ga_errors = $row['number_ga_errors'];					//Sites missing google analytics codes
    $number_duplicate_meta = $request->number_meta($code);

    
    	//Grabs the last url in all data that matchces the URL code and gets the time
        $elapsed_time = $request->time_elapsed($code);
		//Gets the time from the que log of when it was submitted
        $timeFirst  = strtotime($time);			//Convert start string to timestamp
        $timeSecond = strtotime($elapsed_time);	//Convert end string to timestamp

        	
        //Calculate the difference in time, if and only if both the time start and end are greater than 0
        if($timeFirst > 0 && $timeSecond > 0){
			$differenceInSeconds = $timeSecond - $timeFirst;
		}
		else{
			//DOM: 
			//Here is where you would handle any notfication to the user that there time could not be calculated,
		}


    //Now we can load the accordians. 
		// ^^ There should probably be some more error handline here to check the conditions under which we can load the accordion.
        $accordian->generate($multi, $mobile, $domain, $code, $time, $status, $URLs_Ran, $percent_done, $differenceInSeconds, $number_loading_errors, $number_title_errors, $number_text_errors, $number_repetitive_errors, $number_linking_issues, $number_spelling_errors, $number_duplicate_meta, $number_ga_errors);
     
    }
?>
</span>

            
                           
                        </div>
                    </div>
                </div>

    </div>  <!--  END OF BODY CONTENT DIV -->



<script src= "/g5quality_2.0/assets/js/Home_Refresh.js" type = "text/javascript"> </script>
<script src="assets/js/Update_Data.js" type = "text/javascript"> </script>

	
	<script src="assets/js/jquery-1.4.3.min.js"> </script>
	<script src="assets/js/jquery-1.9.0.min.js"> </script>
	<script src="assets/js/pages/widgets.js"></script>
	<script src="assets/js/pages/charts-flot.js"></script>
	<script src="assets/js/jquery-2.0.3.min.js"></script>
	<script src="assets/js/jquery-1.10.2.min.js"></script>
	<script src="assets/js/jquery.noty.min.js"></script>
	<script src="assets/js/jquery.raty.min.js"></script>
	<script src="assets/js/jquery.gritter.min.js"></script>
	<script src="assets/js/raphael.min.js"></script>
	<script src="assets/js/justgage.1.0.1.min.js"></script>
	<script src="assets/js/custom.min.js"></script>
	<script src="assets/js/core.min.js"></script>
	<script src="assets/js/pages/ui-elements.js"></script>
	<script src="assets/js/pages/charts-flot.js"></script>
	<script src="assets/js/pages/table.js"></script>
	<script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="assets/js/jquery.ui.touch-punch.min.js"></script>
	<script src="assets/js/jquery.sparkline.min.js"></script>
	<script src="assets/js/fullcalendar.min.js"></script>
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="assets/js/excanvas.min.js"></script><![endif]-->
	<script src="assets/js/jquery.flot.min.js"></script>
	<script src="assets/js/jquery.flot.pie.min.js"></script>
	<script src="assets/js/jquery.flot.stack.min.js"></script>
	<script src="assets/js/jquery.flot.resize.min.js"></script>
	<script src="assets/js/jquery.flot.time.min.js"></script>
	<script src="assets/js/jquery.autosize.min.js"></script>
	<script src="assets/js/jquery.placeholder.min.js"></script>
	<script src="assets/js/moment.min.js"></script>
	<script src="assets/js/daterangepicker.min.js"></script>
	<script src="assets/js/jquery.easy-pie-chart.min.js"></script>
	<script src="assets/js/jquery.dataTables.min.js"></script>
	<script src="assets/js/dataTables.bootstrap.min.js"></script>
	<script src="assets/js/pages/index.js"></script>
	<script src="assets/js/jquery.progressbar.js" type = "text/javascript"></script>
	<script src="assets/js/jquery.stopwatch.js"></script>
<script src= "assets/js/Home_Refresh.js" type = "text/javascript"></script>

<script>
    function cancel_domain(domain_code){
        var pass_code = "domain_code=" + domain_code;
        $.ajax({
            type: "POST",
            url: "cancel_domain_post.php",
            data: pass_code,
            cache: false,
            success: function(test){
                $('#canceller-' + domain_code).remove();
                //$('#cancel-' + domain_code).remove();
		$('#newstatus-' + domain_code).empty();
		$('#newstatus-' + domain_code).append("<span style='padding-right:8px; padding-left:8px; padding-top:10px; padding-bottom:10px; color: #34383C; background-color:#FF5454' class='label label-default'><i class='fa fa-ban'></i></span>");
            }
        }); 
    }
</script>

<script>
 window.onload=function() {
            $.ajax({
            type: "POST",
            url: "dbms.php",
            data: "a=nothing",
            cache: false,
            success: function(test){
		
            }
        });
    }    
</script>

<script type="text/javascript">
    
    setTimeout(function submitOnEnter()
    {
	$("#domain").keyup(function(event)
	{
	    if(event.keyCode == 13)
		{
		    $("#btnSubmit").click();
		}
	});
    },1300);
</script>


</body>
</html>


