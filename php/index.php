<?php
    require ('functions/DatabaseFunctions.php');
    require ('functions/TaskFunctions.php');
    $name = "test";
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
        <ul class="nav nav-pills flex-column mb-auto overflow-y-auto">

        <?php
            foreach (getListsForUser() as $listID){
                echo '<li class="nav-link text-white">' . getListNameFromID($listID[0])[0][0] . '</li>';
            }

        ?>
        </ul>

        <div class="mt-auto">
            <hr/>
            <h3>Hello, <?php echo $name?></h3>
            <div class="d-flex flex-row mb-3 justify-content-between">
                <button class="btn btn-secondary" style="width: 40%">Sign Out</button>
                <button class="btn btn-secondary" style="width: 40%">Account Options</button>
            </div>
        </div>
    </div>
    <div class="p-3">
        <?php require 'components/List.php';

        ?>
    </div>
</main>