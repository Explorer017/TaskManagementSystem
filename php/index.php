<?php
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
