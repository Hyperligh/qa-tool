
function demo_only()
{
	var single_or_multi = document.getElementById('multi_domain').checked;

		if (single_or_multi == true)
		{
			$("#demo_only").fadeIn();
			document.getElementById("demo_only").innerHTML = '<span class="label label-default" style="background-color:#34383C;"><i class ="fa fa-exclamation-triangle yellow" style="padding-right:20px;"></i>Development Environments Only<i class ="fa fa-exclamation-triangle yellow" style="padding-left:20px;"></i></span>';
			document.getElementById("set-check").innerHTML = "true";
		}
		else
		{
			document.getElementById("demo_only").clear;
			document.getElementById("set-check").innerHTML = "false";
			$("#demo_only").fadeOut();
		}

}

function mobiletest()
{
	var desktop_or_mobile = document.getElementById('desk-mobile').checked;

		if (desktop_or_mobile == true)
		{
			document.getElementById("set-check-mobile").innerHTML = "true";
		}
		else
		{
			document.getElementById("set-check-mobile").innerHTML = "false";
		}
}