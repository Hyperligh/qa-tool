$(document).ready(function()
                  {
          
                    // Give results to php file in back ground async
                    function search() {
                            var query_value = $('input#searchbar').val();
                            $('b#search-string').html(query_value);
                            if(query_value !== ''){
                                    $.ajax({
                                            type: "POST",
                                            url: "/g5quality_2.0/search.php",
                                            data: { query: query_value },
                                            cache: false,
                                            success: function(html){
                                                    $("ul#results").html(html);
                                            }
                                    });
                            }return false;
                        }

                    // eliminate missed key detection w/ timeout
                    $("input#searchbar").live("keyup", function(e) {
                      
                            // Set Timeout
                            clearTimeout($.data(this, 'timer'));
            
                            // Set Search String
                            var search_string = $(this).val();
            
                            // Do Search
                            if (search_string == '') {
                                    $("ul#results").fadeOut();
                                    $('h4#results-text').fadeOut();
                            }else{
                                    $("ul#results").fadeIn();
                                    $('h4#results-text').fadeIn();
                                    /*This determines the rate at which php
                                     *makes queries to the database
                                     *so throttle this appropriately */
                                    $(this).data('timer', setTimeout(search, 300));  
                            };
                        });
                  });