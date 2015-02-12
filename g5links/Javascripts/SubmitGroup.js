function process()
{

	var pass_code = "nothing=1";

	 $.ajax({
            type: "POST",
            url: "../PHPfunctions/generate_code.php",
            data: pass_code,
            cache: false,
            success: function(new_id){
                
         var unique_id = new_id;

	// create the xml object
	var XMLobject = new XMLHttpRequest();

	var PHPfile = "../PHPfunctions/SubmitGroup.php";
	var Group = escape(document.getElementById("groupname").value);

	var User_Variables = "groupname=" + Group + "&code=" + unique_id;
	XMLobject.open("POST",PHPfile,true);
	//Set contect type header
	XMLobject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	//acccess the onreadystate event
	XMLobject.onreadystatechange = function()
	{
     	if(XMLobject.readyState ==4 && XMLobject.status  ==200)
		{
			$("#status").fadeIn();
			var return_data = XMLobject.responseText;
			document.getElementById("status").innerHTML = return_data;
		}

	}
	//send the data to te PHP file
	XMLobject.send(User_Variables);
	document.GetGroup.reset();
	
	setTimeout(function()
	{
		document.getElementById('status').clear;
		$("#status").fadeOut();
  	}, 
  		3000);
    //Jquery For New Panel Item
    AddGroup(Group, unique_id);
	
	
    }
    }); 

}

function AddGroup(Group, new_code)
{
	
	var pass_code = "groupname=" + Group + "&code=" + new_code;
	
	 $.ajax({
            type: "POST",
            url: "../PHPfunctions/GroupAJAX.php",
            data: pass_code,
            cache: false,
            success: function(new_group){
		
		$('#accordion-group').prepend(new_group);
		
	    }
	    });
	
	
	
}





