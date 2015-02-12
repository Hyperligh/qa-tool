
function testing_time()
{
    var result_pair = Array();

    var pass_code = "";
   //Get most recent thorugh php array
        $.ajax({
        type: "POST",
        url: "/g5quality_2.0/get_most_recent.php",
        data: pass_code,
        cache: false,
        success: function(array_pass){
            var result = eval(array_pass);
            
            for(var domain_code in result)
            {
                var single_code = result[domain_code];
                var domain_code_pass = "code=" + single_code;
                var error_array = [];
		
                
                 $.ajax({
                    type: "POST",
                    url: "/g5quality_2.0/get_number_errors.php",
                    data: domain_code_pass,
                    cache: false,
                    success: function(errors_pass){
                   //alert(errors_pass);
                        var code_and_number = eval(errors_pass);
                        var code_string = "";
                        var error_num_string = "";
						var urls_ran = "";
						var urls_remain = "";
						var domain_status = "";
						var title_error = "";
			
                        for(var returned_var in code_and_number)
                        {
                            if(returned_var == 0)
                            {
                                code_string = code_and_number[returned_var];
                            }
                            else if(returned_var == 1)//broken pages
                            {
                                error_num_string = code_and_number[returned_var];
                                error_array.push(error_num_string);
						    }
						    else if(returned_var == 2)//urls ran
			                            {
			                                urls_ran = code_and_number[returned_var];
			                              
			                            }
						    else if(returned_var == 3)//urls remain
			                            {
			                                urls_remain = code_and_number[returned_var];
			                              
			                            }
						    else if(returned_var == 4)//domain status
			                            {
			                                domain_status = code_and_number[returned_var];
							status_color = '';
							// logic for the color of the status buttons
							if (domain_status == "running"){newcolor = '#FABB3D'; icon = '<img src="html_pieces/ajax-loader.gif">'; adjust = '4.5px';}
							else if (domain_status == "done" | domain_status == "purgatory"){newcolor = '#78CD51';icon = '<i class="fa fa-check-circle"></i>'; adjust = '8px';}
							else if (domain_status == "waiting"){newcolor = '#FABB3D';icon = '<i class="fa fa-sign-in"></i>'; adjust = '8px';}
							else if (domain_status == "Cancelled"){newcolor = '#FF5454';icon = '<i class="fa fa-ban"></i>'; adjust = '8px';}
							else{newcolor = '#FF5454';icon = '<i class="fa fa-ban"></i>'; adjust = '8px';}
			                            }
						    else if(returned_var == 5)//title error
			                            {
			                                title_error = code_and_number[returned_var];
			                              	error_array.push(title_error);
			                            }
						    else if(returned_var == 6)//Repetitive Text error
			                            {
			                                Repetitive_Error = code_and_number[returned_var];
			                           		error_array.push(Repetitive_Error);
			                            }
						    else if(returned_var == 7)//Repetitive Text error
			                            {
			                                TextErrors = code_and_number[returned_var];
			                                error_array.push(TextErrors);
			                            }
						    else if(returned_var == 8)//Linking Issue error
			                            {
			                                LinkingIssues = code_and_number[returned_var];
			                                error_array.push(LinkingIssues);
			                            }
						    else if(returned_var == 9)//Spelling error
			                            {
			                                SpellingErrors = code_and_number[returned_var];
			                                error_array.push(SpellingErrors);
			                            }
						    else if(returned_var == 10)//Duplicate Meta
			                            {
			                                DuplicateMeta = code_and_number[returned_var];
			                                error_array.push(DuplicateMeta);
			                            }
						    else if(returned_var == 11)//GA errors
			                            {
			                                GA_error = code_and_number[returned_var];
			                                error_array.push(GA_error);
			                            }
			    
                        }

                        var BugTotal = 0;

                        for (var i in error_array) {
  							if(!isNaN(error_array[i])){
  								BugTotal = BugTotal + parseInt(error_array[i]);
  							}
						}

        // add up total number of errors



        //var BugTotal = error_num_string + title_error + Repetitive_Error + TextErrors + LinkingIssues + SpellingErrors + DuplicateMeta + GA_error;
	
        // add up total number of catagories with errors
	var SEOSEM = parseInt(title_error) + parseInt(LinkingIssues) + parseInt(GA_error);
	
        var CatTotal = 0;
        if (error_num_string > 0){CatTotal = CatTotal + 1;}
	if (SEOSEM > 0){CatTotal = CatTotal + 1;}
	if (Repetitive_Error > 0){CatTotal = CatTotal + 1;}
	if (TextErrors > 0){CatTotal = CatTotal + 1;}
	if (SpellingErrors > 0){CatTotal = CatTotal + 1;}
	if (DuplicateMeta > 0){CatTotal = CatTotal + 1;}
	
	var li_width = (100/CatTotal);

		// define the code to be inserted into domain_accordians 
		var num_urls_ran = parseInt(urls_ran);
		var num_urls_remain = parseInt(urls_remain);
		var total_URLs = num_urls_ran + num_urls_remain;
		var percent_done = Math.floor((num_urls_ran / (total_URLs))*100);
		if (urls_ran == 0){percent_done = 0;}
		//var HTML_for_no_error = "<h1>No Errors Found</h1>";
		var HTML_for_SEOSEM = "<h1>" + SEOSEM +  "</h1><h5>SEO / SEM Concerns</h5>";
		var HTML_for_loading_error = "<h1>" + error_num_string +  "</h1><h5>Broken Pages</h5>";
		var HTML_for_urls_ran = urls_ran;
		var HTML_for_urls_remain = urls_remain;
		var HTML_for_newcolor = "<span style='padding-right:" + adjust + "; padding-left:" + adjust + "; padding-top:10px; padding-bottom:10px; color: #34383C; background-color:" + newcolor + "' class='label label-default'>" + icon + "</span>"
		var HTML_for_cancel_button = "<button href='' style='padding-bottom:8px;' class='btn btn-danger pull-left' onclick=\"cancel_domain('" + code_string + "');\"><i style='padding-top:4px;' class='fa fa-ban'></i></button>";
		//var HTML_for_title =  "<h1>" + title_error +  "</h1><h5>Title Errors</h5>";
		var HTML_for_text_error =  "<h1>" + TextErrors +  "</h1><h5>Text Errors</h5>";
		var HTML_for_repetitive =  "<h1>" + Repetitive_Error +  "</h1><h5>Repetitive Text Errors</h5>";
                var HTML_for_bug_total = BugTotal;
		var width_adjust = (percent_done/100)*(0.93333 * 150);
		var HTML_for_progress = "<div style='height: 40px; text-align: right; vertical-align: middle; color: rgb(255, 255, 255); width: " + width_adjust + "px; border-top-left-radius: 3px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; background-color: rgb(120, 205, 81);'></div>"
		var HTML_for_spelling_error = "<h1>" + SpellingErrors +  "</h1><h5>Spelling Errors</h5>";
		var HTML_for_duplicate_meta = "<h1>" + DuplicateMeta +  "</h1><h5>Duplicate Meta Description</h5>";


			// loading errors
			if (error_num_string > 0) {
			    $("#broke-" + code_string).empty();
			    $("#broke-" + code_string).append(HTML_for_loading_error);
			    document.getElementById("broke-" + code_string).style.width= li_width + "%";
			}
			else{
			    document.getElementById("broke-" + code_string).style.width= "0%";
			    document.getElementById("broke-" + code_string).style.border= "0px";
			}
			
			// Text errors
			if (TextErrors > 0) {
			    $("#texterror-" + code_string).empty();
			    $("#texterror-" + code_string).append(HTML_for_text_error);
			    document.getElementById("texterror-" + code_string).style.width= li_width + "%";
			}
			else{
			    document.getElementById("texterror-" + code_string).style.width= "0%";
			    document.getElementById("texterror-" + code_string).style.border= "0px";
			}
			
			// Spelling errors
			if (SpellingErrors > 0) {
			    $("#spelling-" + code_string).empty();
			    $("#spelling-" + code_string).append(HTML_for_spelling_error);
			    document.getElementById("spelling-" + code_string).style.width= li_width + "%";
			}
			else{
			    document.getElementById("spelling-" + code_string).style.width= "0%";
			    document.getElementById("spelling-" + code_string).style.border= "0px";
			}
			
			//For Repeptitive
			if (Repetitive_Error > 0) {
			    $("#repetitive-" + code_string).empty();
			    $("#repetitive-" + code_string).append(HTML_for_repetitive);
			    document.getElementById("repetitive-" + code_string).style.width= li_width + "%";
			}
			else{
			    document.getElementById("repetitive-" + code_string).style.width= "0%";
			    document.getElementById("repetitive-" + code_string).style.border= "0px";
			}
			
			//For seo / sem
			if (SEOSEM > 0) {
			    $("#seosem-" + code_string).empty();
			    $("#seosem-" + code_string).append(HTML_for_SEOSEM);
			    document.getElementById("seosem-" + code_string).style.width= li_width + "%";
			    document.getElementById("seosem-" + code_string).style.boarder= '1px solid #e9ebec;';
			}
			else{
			    document.getElementById("seosem-" + code_string).style.width= "0%";
			    document.getElementById("seosem-" + code_string).style.border= "0px";
			}
			
			/* linking issues
			if (LinkingIssues > 0){
			    $("#linking-" + code_string).empty();
			    $("#linking-" + code_string).append(HTML_for_linking_issue);
			    document.getElementById("linking-" + code_string).style.width= li_width + "%";
			    document.getElementById("linking-" + code_string).style.boarder= '1px solid #e9ebec;';
			}
			else{
			    document.getElementById("linking-" + code_string).style.width= "0%";
			    document.getElementById("linking-" + code_string).style.border= "0px";
			} */
			
			// Duplicate Meta
			if (DuplicateMeta > 0) {
			    $("#meta-" + code_string).empty();
			    $("#meta-" + code_string).append(HTML_for_duplicate_meta);
			    document.getElementById("meta-" + code_string).style.width= li_width + "%";
			    document.getElementById("meta-" + code_string).style.boarder= '1px solid #e9ebec;';
			}
			else{
			    document.getElementById("meta-" + code_string).style.width= "0%";
			    document.getElementById("meta-" + code_string).style.border= "0px";
			}
			
			// number of urls
			$("#urls_ran-" + code_string).empty();
			$("#urls_ran-" + code_string).append(HTML_for_urls_ran);
			
			// processing status
			$("#newstatus-" + code_string).empty();
			$("#newstatus-" + code_string).append(HTML_for_newcolor);
			if(domain_status == "running")
			{
			   $("#canceller-" + code_string).empty();
			   $("#canceller-" + code_string).append(HTML_for_cancel_button);
                        }
			else
			{
			    $("#canceller-" + code_string).empty();
			}
			
			// Total Bugs
			$("#bug_total-" + code_string).empty();
                        $("#bug_total-" + code_string).append(HTML_for_bug_total);
			
			//for progress bar
			document.getElementById('percentbar-' + code_string).style.width = percent_done + "%";
		    
			// case for no found errors
			
			if (CatTotal > 0) {
			    $("#delete-noerror-" + code_string).remove();  
			}

                    }
                 });    
            }
        }
    });
    
}       