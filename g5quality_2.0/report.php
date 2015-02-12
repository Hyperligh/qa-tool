<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/templates/header.html"); 
$code = $_GET['id'];

?>

    
<div id="content" class="col-lg-12 col-sm-12 col-lg-offset-0 col-sm-offset-0">
    <a href = "/g5quality_2.0/home.php" ><h1><center>Quality <em>Ninja</em> </center></h1></a>
	</br></br>
	</br></br>
	
<div class="col-md-6">
    <div class="box">
	<div class="box-header">
		<h2><i class="fa fa-th"></i><span class="break"></span>Tabs</h2>
		<ul class="nav tab-menu nav-tabs" id="myTab">
		    <li class="active"><a href="ui-elements.html#info">Info</a></li>
		    <li><a href="ui-elements.html#custom">Custom</a></li>
		    <li><a href="ui-elements.html#messages">Messages</a></li>
		</ul>
	</div>
	<div class="box-content">
	    <div id="myTabContent" class="tab-content">
		<div class="tab-pane active" id="info">
									<p>

										Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.   
									</p>

		</div>
		<div class="tab-pane" id="custom">
									<p>
										Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.   
									</p>
		</div>
		<div class="tab-pane" id="messages">
									<p>
										Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.   
									</p>
									<p>
										Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer
									</p>
		</div>
	    </div>
	</div>
    </div>
</div><!--tabs-->

</div>
</body>
		   
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/templates/scripts.html"); ?>                                
 </html>