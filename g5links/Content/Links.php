<?php
class links{
    
    function generate($groupcode, $newlink, $url_code, $counter)
    {
        
 ?>
        <span>&nbsp</span>
   		<span id = "single_link_delete-<?php echo $groupcode; echo '-'; echo $url_code; ?>">      
            <li class="pull-left"> 
            	<span class="hover_delete" >
            		<span class= "counter" ><b>-<?php echo $counter; ?>-</b></span>
            		<span class= "deleter" >
                        <span onclick="deletesinglelink('<?php echo $groupcode; ?>','<?php echo $url_code; ?>');">
                            <i style='color:#ff5454; font-size: 15px;' class="fa fa-times-circle"></i>
                        </span>
                    </span>
            	</span>&nbsp
            	<a target="_blank" id="linker-<?php echo $groupcode; ?>" href="<?php echo $newlink; ?>"><?php echo $newlink; ?></a>
            </li>
            </br>
        </span>
<?php
    }
}



?>