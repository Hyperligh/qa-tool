
function add_word(code, word_code, word)
{
    
	var pass_code = "code=" + code + "&word_code=" + word_code + "&word=" + word;
        $.ajax({
                type: "POST",
                url: "../Update_Dictionary_DB.php",
                data: pass_code,
                cahce: false,
                success: function(){
                    $('.spell-id-' + code + '-' + word).empty();
                }
        });

    
}