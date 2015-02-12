<?php
/************************************************
	The Search PHP File
************************************************/


/************************************************
	MySQL Connect
************************************************/

if(isset($db) == false){
            require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }

/************************************************
	Search Functionality
************************************************/
// Define Output HTML Formating
$html = '';
$html .= '<li class="result">';
$html .= '<a href="urlString">';
$html .= '<div>';
$html .= '<h6>DomainString</h6>';
$html .= '<small>timeString</small>';
$html .= '</div>';
$html .= '</a>';
$html .= '</li>';

// Get Search
$search_string = preg_replace("/[^A-Za-z0-9-.]/", " ", $_POST['query']);
$search_string = $db->real_escape_string($search_string);

// Check Length More Than One Character
if (strlen($search_string) >= 1 && $search_string !== ' ') {
	// Build Query
	$query = 'SELECT Domain, time, code FROM que_log WHERE (status = "done" or status = "purgatory") AND Domain LIKE "%'.$search_string.'%" OR time LIKE "%'.$search_string.'%" order by time desc';
	// Do Search
	$result = $db->query($query);
	while($results = $result->fetch_array()) {
		$result_array[] = $results;
	}

	// Check If We Have Results
	if (isset($result_array)) {
		foreach ($result_array as $result) {
		    
			
			$time_stamp = $result['time'];
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
			$timestring = $month . ' ' . $day . ' ' . $year . ' ' . $hour . ':' . $minute . ' ' . $ampm;

			// Format Output Strings And Hightlight Matches
			$display_Domain = preg_replace("/".$search_string."/i", "<b class='highlight'>".$search_string."</b>", $result['Domain']);
			$display_time = preg_replace("/".$search_string."/i", "<b class='highlight'>".$search_string."</b>", $timestring);
			$display_url = '/g5quality_2.0/details/report.php?id='.urlencode($result['code']).'&lang=en';

			// Insert Domain
			$output = str_replace('DomainString', $display_Domain, $html);

			// Insert time
			$output = str_replace('timeString', $display_time, $output);

			// Insert URL
			$output = str_replace('urlString', $display_url, $output);

			// Output
			echo($output);
		}
	}else{

		// Format No Results Output
		$output = str_replace('urlString', 'javascript:void(0);', $html);
		$output = str_replace('DomainString','<b>Ran Domains Do Not Contain "'.$search_string.'"</b>', $output);
		$output = str_replace('timeString', 'search by date or domain', $output);

		// Output
		echo($output);
	}
}
?>


