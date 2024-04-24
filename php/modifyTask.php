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

$task = getTaskInfo($taskid);
$taskNameError = "";
$form_is_valid = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (empty($_POST["TaskName"]) or $_POST["TaskName"] == "none"){
        $taskNameError = "Task Name is required";
        $form_is_valid = false;
    } else {
        $taskName = htmlspecialchars($_POST["TaskName"]);
    }
    if (empty($_POST["TaskDueDate"]) or $_POST["TaskDueDate"] == "none"){
        //$dueDateError = "Due Date is required";
        //$isFormValid = false;
        $dueDate = null;
    } else {
        $dueDate = htmlspecialchars($_POST["TaskDueDate"]);
    }
    if (empty($_POST["TaskPriority"]) or $_POST["TaskPriority"] == "none"){
        /*
        $priorityError = "Task Priority is required";
        $isFormValid = false;
        */
        $priority = null;

    } else {
        $priority = htmlspecialchars($_POST["TaskPriority"]);
    }

    if ($form_is_valid){
        modifyTask($taskid, $taskName, $dueDate, $priority);
        header('Location: index.php?listid='.$listid);
    }
}
?>

<div class="position-absolute top-50 start-50 translate-middle align-items-center">
    <div class="text-center">
        <h1>Modify Task '<?php echo $task["task_name"]?>'</h1>
        <br/><br/>
    </div>
    <div class="d-flex justify-content-center">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?taskid=".$_GET['taskid'];?>" method="post">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="TaskName" class="form-label">Task Name</label>
                    <input type="text" class="form-control" id="TaskName" name="TaskName" value="<?php echo $task['task_name']?>"/>
                    <label><?php echo $taskNameError?></label>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="TaskDueDate" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="TaskDueDate" name="TaskDueDate" value="<?php echo $task['task_due_date']?>"/>
                    </div>
                    <div class="col">
                        <label for="TaskPriority" class="form-label">Priority</label>
                        <select class="form-select" id="TaskPriority" name="TaskPriority">
                            <?php if ($task['task_priority'] == 0){
                                echo '<option selected value="0">No Priority</option>';
                            } else {
                                echo '<option value="0">No Priority</option>';
                            }
                            if ($task['task_priority'] == 1){
                                echo '<option value="1" selected>Highest</option>';
                            } else{
                                echo '<option value="1">Highest</option>';
                            }
                            if ($task['task_priority'] == 2){
                                echo '<option value="2" selected>Medium</option>';
                            } else {
                                echo '<option value="2">Medium</option>';
                            }
                            if ($task['task_priority'] == 3){
                                echo '<option value="3" selected>Lowest</option>';
                            } else {
                                echo '<option value="3">Lowest</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="text-center p-5">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php?listid=<?php echo $listid?>'">Back</button>
                <button type="submit" class="btn btn-primary" value="createTask" name="action">Modify Task</button>
            </div>
        </form>
    </div>
</div>