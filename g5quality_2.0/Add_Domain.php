<?php
class load_domains{
    public function __construct() {

}
    function call_domains(){
        //Call Database 
	if(isset($db) == false)
	{
        require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
        $dbclass = new db_class();
        $db = $dbclass->db_call();
	}
        //End Call Database
        
        $sql = "SELECT code, time, Domain, status from que_log order by time desc limit 15";
        $count = 0;
   
   if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

$array_codes = array();
$array_times = array();
$array_status = array();
$status = "";
$time = "";
while($row = $result->fetch_assoc()){
    $status = "";
    
        $code = $row['code'];
	$time = $row['time'];
	$status = $row['status'];
	
        array_push($array_codes, $code);
        array_push($array_times, $time);
	array_push($array_status, $status);    
}

require_once("queries.php");
$query_class = new initiate_queries();
foreach($array_codes as $code)
{
    
    $single_time = $array_times[$count];
    $single_status = $array_status[$count];
    $count = $count + 1;
    
    
    
//GET VALUES
$domain = $query_class->get_domain($code, $db);
$urlcount = $query_class->count_urls($code, $db);
$broken_page_count = $query_class->broken_pages($code, $db);
$text_error_count = $query_class->text_errors($code, $db);
$repetitive_text_count = $query_class->repetitive_text($code, $db);
$spelling_errors_count = $query_class->spelling_errors($code, $db);
$broken_zip_count = $query_class->broken_zip_count($code, $db);
$linking_issues = $query_class->linking_issues($code, $db);
$error_form_count = $query_class->error_on_form($code, $db);
$duplicate_meta = $query_class->dup_meta($code, $db);

$label_html = "";
if($single_status == "done"){
    $label_html = "<span class=\"label label-info pull-right sl_status\">Done</span>";
}
else if($single_status == "queued"){
     $label_html = "<span class=\"label label-warning pull-right sl_status\">Queued</span>";
}
else if($single_status == "waiting"){
     $label_html = "<span class=\"label label-important pull-right sl_status\">Waiting</span>";
}
else if($single_status == "running"){
     $label_html = "<span class=\"label label-success pull-right sl_status\">Running</span>";
}

?>
 <div class="row-fluid">
    <div class="accordion-group">
	    <div class="accordion-heading">
	    <div class="row-fluid">
		    <a href="#collapse<?php echo $count?>2" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle"><?php echo $domain; ?>
			      
			
			      
			      <div class = "pull-right">
				
			  
			      </div>
		    </a>

				
		    
		</div>
	
	    </div>
	    <div class="accordion-body collapse" id="collapse<?php echo $count;?>2" style="height: 0px;">
		    <div class="accordion-inner">
		    <div class="row-fluid">
		    </br>
		    <h4 class="heading"><a href="<?php echo $domain ?>"><?php echo $domain; ?></a></h4>
		    </br>
		    </br>
		    <h4 class="heading">URLs   <h5><?php echo $urlcount;?></h5>   </h4>
		    </br>
		    <h4 class="heading">Broken Pages   <h5><?php echo $broken_page_count;?></h5>   </h4>
		    </br>
		    <h4 class="heading">Text Errors   <h5><?php echo $text_error_count; ?></h5>   </h4>
		    </br>
		    <h4 class="heading">Repetitive Text   <h5><?php echo $repetitive_text_count; ?></h5>   </h4>
		     </br>
		    <h4 class="heading">Spelling Errors   <h5><?php echo $spelling_errors_count; ?></h5>   </h4>
		    </br>
		     <h4 class="heading">Broken Zip Codes   <h5><?php echo $broken_zip_count; ?></h5>   </h4>
		     </br>
		     <h4 class="heading">Linking Issues   <h5><?php echo $linking_issues; ?></h5>   </h4>
		     </br>
		      <h4 class="heading">Error On Form Field   <h5><?php echo $error_form_count; ?></h5>   </h4>
		     </br>
		      </br>
		      <h4 class="heading">Duplicate Meta Descriptions   <h5><?php echo $duplicate_meta; ?></h5>   </h4>
		     </br>
		     
		    </div>
		    </div>
		    <div class="row-fluid">
		    <strong> <a href="/g5quality/details/domain.php?id=<?php echo $code; ?>"> View more details  </a></strong>
		    </br>
		    </br>
		      <strong>  <a href="/g5quality/webgraph/view.php?id=<?php echo $code; ?>"> Web Graph </a> </strong>
			</br>
			</br>
	<strong> <a href="/g5quality/overview.php?id=<?php echo $code; ?>"> Overview  </a></strong>
		    
		    
		      </div>
		     </br>
		     </br>
		     
		     
	    </div>
    </div>
    <div class = "pull-right">
    <a href="/g5quality/deleteajax.php?id_val=<?php echo $code; ?>"><i class="icon-trash"></i></a>
    <?php echo $label_html?>
    </br>
    <?php echo $single_time; ?>
    </div>
</div>

    
    </br>
				
 <?php                                    
}



    }
    
    
    
}
?>