<form class="form-horizontal" id = "domain_info" name = "domain_info" method = "post">
	<center>
	<fieldset class="col-md-8 col-md-offset-2">		
		</br>
		<div class="controls row">  
			<?php 
				//Multi_domain form html
				include($_SERVER['DOCUMENT_ROOT'] . "/ninja/home/submit_domain/html/controls/multi_domain.html");
				//Mobile toggle form html
				include($_SERVER['DOCUMENT_ROOT'] . "/ninja/home/submit_domain/html/controls/mobile.html");
			?> 
		</div>
			<?php
				//G5 demo toggle
				include($_SERVER['DOCUMENT_ROOT'] . "/ninja/home/submit_domain/html/controls/demo.html");
			?>

        </br>
        </br>
	        <?php 
	        	//Submit button
	        	include($_SERVER['DOCUMENT_ROOT'] . "/ninja/home/submit_domain/html/submit.html");
	        ?>
        </fieldset>
    </center>
</form>