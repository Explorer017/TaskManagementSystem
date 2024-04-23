<?php
    require 'components/pagetop.php';
    require 'functions/LoginFunctions.php';
    require 'functions/TaskFunctions.php';
    require 'functions/DatabaseFunctions.php';
    require 'functions/ListFunctions.php';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if($_POST['type'] == 'delete'){
            deleteList($_POST['listid']);
            header('Location: index.php');
        } elseif($_POST['type'] == 'leave'){
            leaveList($_POST['listid']);
            //header('Location: index.php');
        }
    }
    else if(!isset($_GET['action'])){
        header("Location: index.php");
    }
    //$from = $_GET['from'];
    if($_GET['action'] == 'delete'):?>
    <div class="position-absolute top-50 start-50 translate-middle align-items-center">
        <div class="text-center">
            <h1>Are you sure you want to delete the following list?</h1>
            <h2><?php echo getListName($_GET['listid'])?></h2>
            <i>This cannot be undone! All tasks associated with this list will also be deleted</i>
            <br/><br/>
        </div>
        <div class="d-flex justify-content-center">
            <form method="post" action="listAction.php">
                <input type="hidden" name="type" value="delete"/>
                <input type="hidden" name="listid" value=" <?php echo $_GET['listid']?>"/>
                <button class="btn btn-danger" type="submit" name="submit" value="delete">yes</button>
                <button class="btn btn-secondary" onclick="location.href='index.php'" type="button">no</button>
            </form>
        </div>
    </div>
    <?php elseif($_GET['action'] == 'leave'):?>
        <div class="position-absolute top-50 start-50 translate-middle align-items-center">
            <div class="text-center">
                <h1>Are you sure you want to leave the following list?</h1>
                <h2><?php echo getListName($_GET['listid'])?></h2>
                <i>To re-join this list, you will have to be added back by the administrator!</i>
                <br/><br/>
            </div>
            <div class="d-flex justify-content-center">
                <form method="post" action="listAction.php">
                    <input type="hidden" name="type" value="leave"/>
                    <input type="hidden" name="listid" value=" <?php echo $_GET['listid']?>"/>
                    <button class="btn btn-danger" type="submit" name="submit" value="leave">yes</button>
                    <button class="btn btn-secondary" onclick="location.href='index.php'" type="button">no</button>
                </form>
            </div>
        </div>
    <?php endif;
    require 'components/pagebottom.php';
    ?>