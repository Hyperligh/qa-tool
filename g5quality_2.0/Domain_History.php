<?php

$real_path = $_SERVER['DOCUMENT_ROOT'];

require_once($real_path . "/g5quality_2.0/Data/pull.php");

$get_data = new request();

//Duplicate query I think?
//Using the array 'all_code' defined in 'report.php'
//$old_codes = $get_data->code_history($domain, $multi_domain);


foreach ($all_code as $code_value)
    {
	// $count = $count + 1;
        $wanted_code = $code_value['code'];
        $old_domain_info = $get_data->domain_history($wanted_code);
	$number_dup_meta = $get_data->number_meta($wanted_code);
        $Total = 0;
        foreach ($old_domain_info as $error_count)
        {
            $type = $error_count['type'];
            if($type == "LoadingError" || $type == "LinkingIssue" || $type == "TextError" || $type == "RepetitiveText" || $type == "TitleError" || $type == "SpellingError" || $type == "GA_E")
            {$Total = $Total + 1;}
        }
        $BugTotals[] = $Total + $number_dup_meta;
    }
    

?>

<script>
    
$(document).ready(function(){
		
		var totals = [
                                <?php
                                    foreach ($BugTotals as $key => $value)
                                    {
                                        echo ('[' . ($key + 1) . ',' . $value . '],' );
                                    }
                            
                                ?>
                              ];

		var plot = $.plot($("#BugTotals"),
			   [ { data: totals} ], {
				   series: {
					   lines: { show: true,
								lineWidth: 2,
								fill: true, fillColor: { colors: [ { opacity: 0.5 }, { opacity: 0.2 } ] }
							 },
					   points: { show: true, 
								 lineWidth: 2 
							 },
					   shadowSize: 0
				   },
				   grid: { hoverable: true, 
						   clickable: true, 
						   tickColor: "#e0e0e0",
						   borderWidth: 0
						 },
				   colors: ["#78CD51"],
					xaxis: {show:true, axisLabelPadding:10, axisLabel: 'Previous Domain Runs', ticks:6, tickDecimals: 0},
					yaxis: {show:true, axisLabelPadding:10, axisLabel: 'Number of Bugs', ticks:6, tickDecimals: 0},

				 });

		function showTooltip(x, y, contents) {
			$('<div id="tooltip">' + contents + '</div>').css( {
				position: 'absolute',
				
				top: y + 5,
				left: x + 5,
				border: '1px solid #fdd',
				padding: '2px',
				'background-color': '#dfeffc',
				opacity: 0.80
			}).appendTo("body").fadeIn(200);
		}

		var previousPoint = null;
		$("#BugTotals").bind("plothover", function (event, pos, item) {
			$("#x").text(pos.x.toFixed(2));
			$("#y").text(pos.y.toFixed(2));

				if (item) {
					if (previousPoint != item.dataIndex) {
						previousPoint = item.dataIndex;

						$("#tooltip").remove();
						var x = item.datapoint[0].toFixed(2),
							y = item.datapoint[1].toFixed(2);

						showTooltip(item.pageX, item.pageY,
									item.series.label + " of " + x + " = " + y);
					}
				}
				else {
					$("#tooltip").remove();
					previousPoint = null;
				}
		});

// change axis label colors
//$('.yaxisLabel').css('color',"#A8A8A8");
//$('.xaxisLabel').css('color',"#A8A8A8");


});
</script>





