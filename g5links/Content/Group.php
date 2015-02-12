<?php
class group{
    
    function generate($code, $groupname)
    {
    $counter = 1;

		
		require_once("../PHPfunctions/DatabaseRequests.php");//query file
		$request = new request();
		$current_links = $request->openlinks($code);
		$row_cnt = $current_links->num_rows + 1;
 ?>
                                            <center>
                                                <span id="accordion-group">
                                                <div class="box-content" style="width: 80%;" id="canceller-<?php echo $code; ?>">
                                                    
                                                        
                                                    
							<div class="panel-group">
							    <!-- Delete Group Button  -->
                                                       
							    <div class="panel panel-default">
								    <!-- Delete Group Button  -->
								    <div >
									  <button   href='' style='padding-bottom:12.5px; padding-top:12px; padding-left:15px; padding-right:15px;' class="btn btn-danger pull-right" onclick="cancel_group('<?php echo $code; ?>');"><i style='padding-top:4px; color:#34383c; font-size: 17px;' class="fa fa-times-circle"></i></button>
								    </div>
								    
								    <!-- Open All Links  -->
								    <div >
									  <button   href='' style='padding-bottom:15px; padding-top:15px; padding-left:15px; padding-right:15px;' class="btn btn-success pull-left" onclick="open_links('<?php echo $code; ?>');"><i style='padding-top:4px; color:#34383c;'><img src="../Custom_Pieces/Ying-Yang.png"></i></button>
								    </div>
								    
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $code ?>">
								    <div class="panel-heading" style="height: 50px; background-color:#34383c;">
								      <div  style=" padding-top:3px; color: white; font-family: 'Lato', sans-serif; font-weight: 400; font-size:16px;">
									  <?php echo $groupname; ?>
								      </div>
								    </div>
								</a>
							      <div id="collapse<?php echo $code ?>" class="panel-collapse collapse">
								<div class="panel-body">
                                                                    <form id="GetLink-<?php echo $code ?>" name="GetLink-<?php echo $code ?>" method="post">
                                                                        <fieldset>
                                                                            <textarea id="links-<?php echo $code ?>" class="diss-form" placeholder="Enter URLs here" style="width: 90%; border:solid 2px #34383c;"></textarea>
									    </br><div style="height: 3px;"></div>
									    <div class="col-md-5"></div>
										<button id="submitlinks-<?php echo $code ?>" type="button" class="btn btn-sm btn-success col-md-2" onclick="addlinks('<?php echo $code ?>');">
										    <i class="fa fa-chevron-down" style="padding-right: 10px; color:#34383c;"></i>
											<span style="color:#34383c;" > Create Links </span>
										    <i class="fa fa-chevron-down" style="padding-left: 10px; color:#34383c;"></i>
										</button>
									    <div class="col-md-5"></div>
									</fieldset>
                                                                    </form>
									<span id="invisible-navigator-<?php echo $code; ?>"></span>
                                                                    <ul class="col-lg-12 col-md-12 col-xs-12" style="word-break: break-all; list-style-type: none;" >
									<span id="link-cluster-ajax-<?php echo $code ?>">
                                                                        <?php 
                                                                        
                                                                        require_once("../PHPfunctions/DatabaseRequests.php");//query file
                                                                        require_once("../Content/Links.php");// html for a single panel
                                                                        
                                                                        $links = new links();
                                                                        $request = new request();
                                                                        $chooselinks = $request->rightlinks();
                                                                        

                                                                        // loop through results adding a new group for each one in DB
                                                                            while ($row = $chooselinks->fetch_assoc())
                                                                            {
										
                                                                                $groupcode = $row['Code'];
                                                                                $url_code = $row['URL_Code'];
                                                                                $newlinks = $row['URL'];

                                                                                if ($groupcode == $code)
                                                                                {
                                                                                    //generate each panel
                                                                                    $links->generate($groupcode, $newlinks, $url_code, $counter);
                                                                                    $counter = $counter + 1;
                                                                                }

                                                                            }
                                                                            
                                                                        ?>
									</span>
									
                                                                    </ul>
                                            
                                                                </div>
								<div class="col-md-5"></div>
								    <button id="deletelinks-<?php echo $code ?>" type="button" class="btn btn-sm btn-danger col-md-2" onclick="deletelinks('<?php echo $code ?>');">
									<i class="fa fa-chevron-up" style="padding-right: 10px; color:#34383c;"></i>
									    <span style="color:#34383c;" > Delete Links </span>
									<i class="fa fa-chevron-up" style="padding-left: 10px; color:#34383c;"></i>
								    </button>
								<div class="col-md-5"></div>
								<div style="height: 31px;"></div>
								
							      </div>
							    </div>
							</div>
						</div>
                                                </span>
                                            </center>
                                                </br><!-- put some space between groups-->
                                                
            
    
    
<?php
    }
}
?>