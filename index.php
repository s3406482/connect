<?php 
session_start();
?>


<?php 
	include_once('php/header.php');?>
<div id = 'content'>
                <?php
                        if(isset($_SESSION["message"]))
                        {
                                echo $_SESSION["message"];
                                $_SESSION["message"] = NULL;

                        }
                ?>
	
</div>
