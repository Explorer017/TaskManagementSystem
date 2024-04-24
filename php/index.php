<?php
    require ('functions/DatabaseFunctions.php');
    require ('functions/TaskFunctions.php');
    session_start();
    $name = getFirstName();
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
<?php require "../css/css.php"?>
<?php require "components/pagetop.php"?>

<main class="d-flex flex-nowrap h-100">
    <div class="d-flex flex-column flex-shrink-0 p-3 background align-items-stretch h-100" style="width: 350px">
        <h2>Task Management System</h2>
        <hr/>
        <!--
        <ul class="nav nav-pills flex-column mb-auto overflow-y-auto">
            <li class="nav-item nav-link active">List 1</li>
            <li class="nav-link text-white">List 2</li>
            <li class="nav-link text-white">List 3</li>
        </ul>
        -->
        <?php require 'components/ListList.php'?>

        <div class="mt-auto">
            <hr/>
            <h3>Hello, <?php echo $name?></h3>
            <form class="d-flex flex-row mb-3 justify-content-between" method="post" action="accountOptions.php">
                <button class="btn btn-secondary" style="width: 40%" type="submit" name="signOut" value="signOut">Sign Out</button>
            </form>
        </div>
    </div>

    <div class="mx-auto w-50 m-5">
        <?php require 'components/TaskList.php'?>
    </div>
</main>