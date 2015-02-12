
function validateform()
{
    var domainfield = document.forms["domain_info"]["domain"].value;
    if (domainfield == null || domainfield == "")
    {
        document.getElementById("btnSubmit").disabled = true;
    }
    else
    {
        document.getElementById("btnSubmit").disabled = false;
    }
}