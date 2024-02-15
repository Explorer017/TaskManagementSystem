<?php
    session_start();
    function CheckIfSignedIn(){
        if(isset($_SESSION["credentials"])){
            return true;
        }
        return false;
    }
    if (!CheckIfSignedIn()){
        header("Location: LogIn.php");
    }
?>
<h1>If you are seeing this, you are logged in!</h1>