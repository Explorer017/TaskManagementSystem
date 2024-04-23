<?php
require 'components/pagetop.php';
require 'functions/LoginFunctions.php';
require 'functions/TaskFunctions.php';
require 'functions/DatabaseFunctions.php';
require 'functions/ListFunctions.php';

$taskid = null;
if (array_key_exists('taskid', $_GET)){
    $taskid = $_GET['taskid'];
}
$listid = getListIDFromTaskID($taskid);

$form_is_valid = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (!isset($_POST['taskid'])){
        $form_is_valid = false;
    }

    if ($form_is_valid){
        deleteTask($taskid, $listid);
        header('Location: index.php?listid='.$listid);
    }
}
?>

<div class="position-absolute top-50 start-50 translate-middle align-items-center">
    <div class="text-center">
        <h1>Are you sure you want to delete the following Task?</h1>
        <h2><?php echo getTaskName($taskid);?></h2>
        <h2>From list</h2>
        <h2><?php echo getListName($listid);?></h2>
        <i>This cannot be undone!</i>
        <br/><br/>
    </div>
    <div class="d-flex justify-content-center">
        <form method="post" action="deleteTask.php?taskid=<?php echo $_GET['taskid'];?>">
            <input type="hidden" name="taskid" value=" <?php echo $taskid?>"/>
            <button class="btn btn-danger" type="submit" name="submit" value="delete">Yes</button>
            <button class="btn btn-secondary" onclick="location.href='index.php?listid=<?php echo $listid?>'" type="button">no</button>
        </form>
    </div>
</div>