// this javascript function runs when the user clicks submit to send info to the databse

function process()
{
	var pass_code = "nothing=1";
	
	 $.ajax({
            type: "POST",
            url: "/g5quality_2.0/generate_code.php",
            data: pass_code,
            cache: false,
            success: function(new_id){
                
            var unique_id = new_id; 
    
	
	// create the xml object
	var XMLobject = new XMLHttpRequest();
	var PHPfile = "AJAX_actions.php";
	var Domain = document.getElementById("domain").value;
	// to keep domains consistent make sure they all are formated identically with a slash to finish ...
	var domain_size = Domain.length;
	if (Domain[domain_size-1] != '/') {
		Domain = Domain += '/';
	}
	// and http:// ...
	var http = 'http://';
	if (Domain.indexOf("http://") == -1 && Domain.indexOf("https://") == -1) {
		Domain = http.concat(Domain);
	}
	// and www
	if (Domain.indexOf("www.") == -1 && Domain.indexOf("herokuapp") == -1 && Domain.indexOf(".g5dns") == -1) {
		var Domain = Domain.replace('http://', 'http://www.');
	}
	

	Escaped_Domain = escape(Domain);
	var Multi_Domain = document.getElementById("set-check").innerHTML;
	var Mobile = document.getElementById("set-check-mobile").innerHTML;


	//var Browser_Type = document.getElementById("browser_type").value;
	var User_Variables = "domain=" + Escaped_Domain + "&multi_domain=" + Multi_Domain + "&mobile=" + Mobile + "&code=" + unique_id;
	XMLobject.open("POST",PHPfile,true);
	//Set contect type header
	XMLobject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	//acccess the onreadystate event
	XMLobject.onreadystatechange = function()
	{
     	if(XMLobject.readyState ==4 && XMLobject.status  ==200)
		{
			$("#demo_only").fadeIn();
			var return_data = XMLobject.responseText;
			document.getElementById("demo_only").innerHTML = return_data;
		}
	}
	//send the data to te PHP file
	XMLobject.send(User_Variables); // send data to php file
	document.getElementById("demo_only").innerHTML = 'processing...';
	document.getElementById("set-check").innerHTML="false";
	document.getElementById("set-check-mobile").innerHTML="false";
	document.domain_info.reset();
	document.getElementById("demo_only").innerHTML="";
	
	setTimeout(function()
	{
		document.getElementById('status').clear;
		document.getElementById('demo_only').clear;
		$("#demo_only").fadeOut();
  	}, 
  		3000);
	
	//Jquery For New Panel Item
	AddPanel(Domain, unique_id, Multi_Domain, Mobile);
	
	
	    }
	
	    }); 
	

}

// this javascript functions runs when user clicks submit to add the panel

function AddPanel(Domain, new_code, Multi_Domain, Mobile)
{
	
	var pass_code = "domain=" + Domain + "&code=" + new_code + "&multi=" + Multi_Domain + "&mobile=" + Mobile;
	
	 $.ajax({
            type: "POST",
            url: "/g5quality_2.0/ajax_pieces/ajax_accordion.php",
            data: pass_code,
            cache: false,
            success: function(new_accordion){
		
		$('#ajax-add-panel').fadeIn();
		$('#ajax-add-panel').prepend(new_accordion);
		
		
	    }
	    });
	
	
	
}


