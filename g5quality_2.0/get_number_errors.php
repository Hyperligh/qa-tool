<?php
session_start();
$code = $_POST['code'];

if(isset($code))
   {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/pull.php");
    $request = new request();
 


    /*

    //Initiate Array
    $variable_array = array();
    //Push Code to array
     array_push($variable_array, $code);
    
    //Get number of broken pages
        //assign returned variable
        $number_loading_errors = $request->number_loading_errors_ajax($code);
        //Push result to array
        array_push($variable_array, $number_loading_errors);
        
    //Get urls ran
        //Call database query and set variable
        $URLs_Return = $request->urls_ran_func($code); 
        array_push($variable_array, $URLs_Return);
       
    //Get urls remain
        //Call database query and set variable
        $URLs_Remain = $request->urls_remain_func($code); 
        array_push($variable_array, $URLs_Remain);
       
    //Get status
        //Call database query and set variable
        $status_return = $request->status($code);
        array_push($variable_array, $status_return);
        
    //Get number of errors in title tag
        //assign returned variable
        $number_title_errors = $request->number_title_errors($code);
        //Push result to array
        array_push($variable_array, $number_title_errors);

-

    //Get number repetitive text errors
        $number_repetitive_errors = $request->number_repetitive_errors($code);
        //Push result to array
        array_push($variable_array, $number_repetitive_errors);
-

    //Get number text errors
        $number_text_errors = $request->number_text_errors($code);
        //Push result to array
        array_push($variable_array, $number_text_errors);

-

    //Get number linking issues
        $number_linking_issues = $request->number_linking_issues($code);
        //Push result to array
        array_push($variable_array, $number_linking_issues);
-

    //Get number spelling errors
        $number_spelling_errors = $request->number_spelling_errors($code);
        //Push result to array
        array_push($variable_array, $number_spelling_errors);
        
    //Get number of duplicate meta's
        $number_duplicate_meta = $request->number_meta($code);
        //Push result to array
        array_push($variable_array, $number_duplicate_meta);
        
    //Get number of GA errors
        $number_ga_errors = $request->number_ga_errors($code);
        //Push result to array
        array_push($variable_array, $number_ga_errors);
        
        echo json_encode($variable_array);

*/
    //Initiate Array
    $variable_array = array();
    $recent_domains = $request->single_recent_domain($code);
           
//Loop through results. Create a new accordian for each result
    while($row = $recent_domains->fetch_assoc())
    {
    
     //Queue log result variables
        $code = $row['code'];
        array_push($variable_array, $code);

        $number_loading_errors = $row['number_loading_errors'];         //Number broken pages, 500 errs etc
        array_push($variable_array, $number_loading_errors);

        $URLs_Ran = $row['URLs_Ran'];
        array_push($variable_array, $URLs_Ran);

        $URLs_Remaining = $row['URLs_Remaining'];
        array_push($variable_array, $URLs_Remaining);

        $status = $row['status'];
        array_push($variable_array, $status);

        $number_title_errors = $row['number_title_errors'];             //Number of errors in title found
        array_push($variable_array, $number_title_errors);

        $number_repetitive_errors = $row['number_repetitive_errors'];   //Number of of errors like this
        array_push($variable_array, $number_repetitive_errors);

        $number_text_errors = $row['number_text_errors'];               //Erorrs such as &amp;
        array_push($variable_array, $number_text_errors);

        $number_linking_issues = $row['number_linking_issues'];         //Custom to G5. Things like "General" should not be in URL
        array_push($variable_array, $number_linking_issues);

        $number_spelling_errors = $request->number_adjust_spell_error($code);   // spelling errors needs custom work 
        array_push($variable_array, $number_spelling_errors);                   // users generate a dictionary all processed server side thru php

        $number_duplicate_meta = $request->number_meta($code);                  // logic determins number in php server side
        array_push($variable_array, $number_duplicate_meta);

        $number_ga_errors = $row['number_ga_errors'];                   //Sites missing google analytics codes
        array_push($variable_array, $number_ga_errors);


        for($i = 0; $i < count($variable_array);$i++){
            if($variable_array[$i] == null){
                $variable_array[$i] = 0;
            }
        }

        $domain = $row['Domain'];
        $time = $row['time'];
        $status = $row['status'];
        $URLs_Ran = $row['URLs_Ran'];
        $URLs_Remaining = $row['URLs_Remaining'];
        $multi = $row['multidomain'];
        $mobile = $row['mobile'];

    }


        echo json_encode($variable_array);



   }
   else
   {
    echo "no code";
   }

?>

