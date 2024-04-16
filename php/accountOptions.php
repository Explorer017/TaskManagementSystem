<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if ($_POST["signOut"] == "signOut") {
        session_start();
        $_SESSION["credentials"] = null;
        $_SESSION = array();
        session_destroy();
        header("location: index.php");
    }
}