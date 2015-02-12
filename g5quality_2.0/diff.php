<?php
session_start();

$real_path = $_SERVER['DOCUMENT_ROOT'];
require_once($real_path . "/g5quality_2.0/templates/header-home.html");
?>

    <div  id="content" class="col-lg-12 col-sm-12 col-lg-offset-0 col-sm-offset-0">
	
    	<input class="col-lg-8 form-control required " type="URL" name="domain" id="domain" onkeypress="submitOnEnter();" placeholder="URL 1" size="65">
    	<input class="col-lg-8 form-control required" type="URL" name="domain" id="domain" onkeypress="submitOnEnter();" placeholder="URL 2" size="65">

    	<a class="btn btn-lg" style="height:68px;" onclick="get_dom();">Go</a>

    	<br><br>
   	<div class="test">
        <div id="oldStr" class="text">this is a simple test</div>
        <div id="newStr" class="text">this is not a simple test</div>
    </div>
    <input type="button" value="Test" onclick="launch()" />
    <div class="test">
        <div class="text" id="outputOldStr"></div>
        <div class="text" id="outputNewStr"></div>
    </div>


    <div id="frames">

    </div>

    </div>  <!-- komodo thinks this is a hanging div, isn't. END OF BODY CONTENT DIV -->



<script type="text/javascript">
  $(window).load(function() {
    var f = document.createElement('iframe');
    f.src="http://pacificaseniorliving.com"; f.width=1000; f.height=500; f.id="frame1";
    $('div#frames').append(f);
  });
</script>

<script type="text/javascript">

function get_dom(){

		alert("hi")
        var iframe = document.getElementById("iframe1");
        var iframeDocument = iframe.contentDocument || iframe.contentWindow.document;

        alert(iframeDocument);
}


</script>


	<script src="diff_match_patch_20121119/javascript/diff_match_patch.js"></script>
	<script src="assets/js/jquery-1.4.3.min.js"> </script>
	<script src="assets/js/jquery-1.9.0.min.js"> </script>
	<script src="assets/js/pages/widgets.js"></script>
	<script src="assets/js/pages/charts-flot.js"></script>
	<script src="assets/js/jquery-2.0.3.min.js"></script>
	<script src="assets/js/jquery-1.10.2.min.js"></script>
	<script src="assets/js/jquery.noty.min.js"></script>
	<script src="assets/js/jquery.raty.min.js"></script>
	<script src="assets/js/jquery.gritter.min.js"></script>
	<script src="assets/js/raphael.min.js"></script>
	<script src="assets/js/justgage.1.0.1.min.js"></script>
	<script src="assets/js/custom.min.js"></script>
	<script src="assets/js/core.min.js"></script>
	<script src="assets/js/pages/ui-elements.js"></script>
	<script src="assets/js/pages/charts-flot.js"></script>
	<script src="assets/js/pages/table.js"></script>
	<script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="assets/js/jquery.ui.touch-punch.min.js"></script>
	<script src="assets/js/jquery.sparkline.min.js"></script>
	<script src="assets/js/fullcalendar.min.js"></script>
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="assets/js/excanvas.min.js"></script><![endif]-->
	<script src="assets/js/jquery.flot.min.js"></script>
	<script src="assets/js/jquery.flot.pie.min.js"></script>
	<script src="assets/js/jquery.flot.stack.min.js"></script>
	<script src="assets/js/jquery.flot.resize.min.js"></script>
	<script src="assets/js/jquery.flot.time.min.js"></script>
	<script src="assets/js/jquery.autosize.min.js"></script>
	<script src="assets/js/jquery.placeholder.min.js"></script>
	<script src="assets/js/moment.min.js"></script>
	<script src="assets/js/daterangepicker.min.js"></script>
	<script src="assets/js/jquery.easy-pie-chart.min.js"></script>
	<script src="assets/js/jquery.dataTables.min.js"></script>
	<script src="assets/js/dataTables.bootstrap.min.js"></script>
	<script src="assets/js/pages/index.js"></script>
	<script src="assets/js/jquery.progressbar.js" type = "text/javascript"></script>
	<script src="assets/js/jquery.stopwatch.js"></script>
<script>
var dmp = new diff_match_patch();

function launch() {
    var text1 = document.getElementById('oldStr').innerHTML;
    var text2 = document.getElementById('newStr').innerHTML;
    dmp.Diff_EditCost = 8;

    var d = dmp.diff_main(text1, text2);
    dmp.diff_cleanupEfficiency(d);
    var oldStr = "",
        newStr = "";
    for (var i = 0, j = d.length; i < j; i++) {
        var arr = d[i];
        if (arr[0] == 0) {
            oldStr += arr[1];
            newStr += arr[1];
        } else if (arr[0] == -1) {
            oldStr += "<span class='text-del'>" + arr[1] + "</span>";
        } else {
            newStr += "<span class='text-add'>" + arr[1] + "</span>";
        }
    }
    document.getElementById('outputOldStr').innerHTML = oldStr;
    document.getElementById('outputNewStr').innerHTML = newStr;
}
</script>




</body>
</html>


