
    function cancel_group(group_code){
        var pass_code = "group_code=" + group_code;
        $.ajax({
            type: "POST",
            url: "../PHPfunctions/DeleteGroup.php",
            data: pass_code,
            cache: false,
            success: function(test){
                $('#canceller-' + group_code).remove();
                
            }
        }); 
    }
    
    function deletelinks(code)
    {
        var pass_code = "code=" +code;
        $.ajax({
                type: "POST",
                url: "../PHPfunctions/DeleteLinks.php",
                data: pass_code,
                cache: false,
                success: function(){
                    $('#link-cluster-ajax-' + code).empty();
                    
                }
            }); 
    }

    function deletesinglelink(code, url_code)
    {
        var pass_code = "code=" + code + "&url_code=" + url_code; 
        $.ajax({
                type: "POST",
                url: "../PHPfunctions/DeleteSingleLink.php",
                data: pass_code,
                cahce: false,
                success: function(){
                    $('#single_link_delete-' + code + '-' + url_code).remove();
                }
        });
    }