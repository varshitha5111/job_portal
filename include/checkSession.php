<?php 
if(session_status()==PHP_SESSION_NONE){
    session_start();
}
if(!(isset($_SESSION['userId']))){
   echo '<script>windows.location.href="login.php"</script>';
}

?>