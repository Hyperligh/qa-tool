<!DOCTYPE html>
<html>
<head>
<title> Sensei | G5</title>
<meta name="viewport" content="width=device-width">
<meta http-equiv="Content-type" value="text/html"; charset="UTF-8">

<link rel="shortcut icon" href="../Custom_Pieces/favicon.ico">

    <script src="../Javascripts/SubmitGroup.js" type = "text/javascript"></script>
    <script src="../Javascripts/AddLinks.js" type = "text/javascript"></script>
    <script src="../Javascripts/DeleteLinks.js" type = "text/javascript"></script>
    
<!-- start: CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/retina.min.css" rel="stylesheet">
    <link href="../assets/css/print.css" rel="stylesheet" type="text/css" media="print"/>
    <link href="../CSS/custom_style.css" rel="stylesheet">

<!-- end: CSS -->
    
    <!-- jquery and javascript -->
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <script src="../assets/js/respond.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    
    
</head>
<body>
    <header class="navbar">
        <div class="container" >
	    <a href="../../" id="main-menu-toggle" class="pull-left"><i><img style="width:26px;"  src="/g5quality_2.0/Temple_Icon.png"></i></a>
        <div/>
    </header>
    

	    <div id="content" class="col-sm-12">
			<div class="row">
				<div class="login-box" style="width: 300px; margin-top:50px; margin-bottom:50px;">
					
					<div class="header" >
						Create Test Group
					</div>
					
					
					<form class="form-horizontal register" id="GetGroup" name="GetGroup" method="post">
						<fieldset class="col-sm-12">
							
							<div class="form-group">
							  	<div class="controls row">
									<div class="input-group col-sm-12" style="width: 100%;">
										<input type="text" class="form-control" id="groupname"/>
									</div>	
							  	</div>
							</div>

							
							<div class="row">

								<button type="button" value = 'Submit' class="btn btn-primary col-xs-12" style="background-color:#78cd51;" onclick="process();" >Add It!</button>
							
							</div>
						</fieldset>	

					</form>
					<center>
					    <a href = "../Content/home.php" ><img style="max-width:100%;  height: 60px;" src="../Custom_Pieces/QA_Sensei.png"></a>
					</center>	
				</div>
			</div><!--/row-->
		<center>
		    <tr>
			<td>
			    <span id ="status"></span>
			</td>
		    </tr>
		</center>
		
		<?php
		
		require_once("../PHPfunctions/DatabaseRequests.php");//query file
		require_once("../Content/Group.php");// html for a single panel
		
		$group = new group();
		$request = new request();
		$allgroups = $request->allgroups();

		
		// loop through results adding a new group for each one in DB
		    while ($row = $allgroups->fetch_assoc())
		    {
			$code = $row['Code'];
			$groupname = $row['Group_Name'];
			
			//generate each panel
			$group->generate($code, $groupname);
		    }
		?>

		

	    </div><!-- end of content -->


    

	<script src="../assets/js/jquery-1.4.3.min.js"> </script>
	<script src="../assets/js/jquery-1.9.0.min.js"> </script>
	<script src="../assets/js/pages/widgets.js"></script>
	<script src="../assets/js/pages/charts-flot.js"></script>
	<script src="../assets/js/jquery-2.0.3.min.js"></script>
	<script src="../assets/js/jquery-1.10.2.min.js"></script>
	<script src="../assets/js/jquery.noty.min.js"></script>
	<script src="../assets/js/jquery.raty.min.js"></script>
	<script src="../assets/js/jquery.gritter.min.js"></script>
	<script src="../assets/js/raphael.min.js"></script>
	<script src="../assets/js/justgage.1.0.1.min.js"></script>
	<script src="../assets/js/custom.min.js"></script>
	<script src="../assets/js/core.min.js"></script>
	<script src="../assets/js/pages/ui-elements.js"></script>
	<script src="../assets/js/pages/charts-flot.js"></script>
	<script src="../assets/js/pages/table.js"></script>
	<script src="../assets/js/jquery-migrate-1.2.1.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script src="../assets/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="../assets/js/jquery.ui.touch-punch.min.js"></script>
	<script src="../assets/js/jquery.sparkline.min.js"></script>
	<script src="../assets/js/fullcalendar.min.js"></script>
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../assets/js/excanvas.min.js"></script><![endif]-->
	<script src="../assets/js/jquery.flot.min.js"></script>
	<script src="../assets/js/jquery.flot.pie.min.js"></script>
	<script src="../assets/js/jquery.flot.stack.min.js"></script>
	<script src="../assets/js/jquery.flot.resize.min.js"></script>
	<script src="../assets/js/jquery.flot.time.min.js"></script>
	<script src="../assets/js/jquery.autosize.min.js"></script>
	<script src="../assets/js/jquery.placeholder.min.js"></script>
	<script src="../assets/js/moment.min.js"></script>
	<script src="../assets/js/daterangepicker.min.js"></script>
	<script src="../assets/js/jquery.easy-pie-chart.min.js"></script>
	<script src="../assets/js/jquery.dataTables.min.js"></script>
	<script src="../assets/js/dataTables.bootstrap.min.js"></script>
	<script src="../assets/js/pages/index.js"></script>
	


</body>
</html>

    
    
    
    
    
    
    
