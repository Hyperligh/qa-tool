<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/ninja/home/html/head.html"); ?>
	<body>
		<?php include($_SERVER['DOCUMENT_ROOT'] . "/ninja/home/html/header.html"); ?>

	    <div  id="content" class="col-lg-12 col-sm-12 col-lg-offset-0 col-sm-offset-0">
		
		    <?php 
		    include($_SERVER['DOCUMENT_ROOT'] . "/ninja/home/html/logo.html"); //Ninja logo and container
		    //include($real_path . "/ninja/home/html/logo.html"); //Outage block
		    include($_SERVER['DOCUMENT_ROOT'] . "/ninja/home/submit_domain/form.php");
		    //A bunch of br tags that were lying around
		    include($_SERVER['DOCUMENT_ROOT'] . "/ninja/home/html/br.html");
		    //The div that hold the "Most recent Domains" Title
		    include($_SERVER['DOCUMENT_ROOT'] . "/ninja/home/html/most_recent_div.html");

		    ?>



	    
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
    
	
     //Make queries based off this info
     
    
	$domain_class = new domain_overview();
	$domain_class->total_errors($code);
    
	$number_title_errors = $domain_class->number_title_errors;
	$number_loading_errors = $domain_class->number_loading_errors;
	$number_repetitive_errors = $domain_class->number_repetitive_errors;
	$number_text_errors = $domain_class->number_text_errors;
	$number_linking_issues = $domain_class->number_linking_issues;
	$number_spelling_errors = $domain_class->number_spelling_errors;
	$number_ga_errors = $domain_class->number_ga_errors;
     
        $elapsed_time = $request->time_elapsed($code);
	$number_duplicate_meta = $request->number_meta($code);
    
        $timeFirst  = strtotime($time);
        $timeSecond = strtotime($elapsed_time);
        $differenceInSeconds = $timeSecond - $timeFirst;


    //Now we can load the accordians.
        
        $accordian->generate($multi, $mobile, $domain, $code, $time, $status, $URLs_Ran, $percent_done, $differenceInSeconds, $number_loading_errors, $number_title_errors, $number_text_errors, $number_repetitive_errors, $number_linking_issues, $number_spelling_errors, $number_duplicate_meta, $number_ga_errors);
     
    }
?>
</span>

            
                           
                        </div>
                    </div>
                </div>

    </div>  <!-- komodo thinks this is a hanging div, isn't. END OF BODY CONTENT DIV -->



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


