<?php
session_start();
require 'functions/TaskFunctions.php';
require 'functions/DatabaseFunctions.php';
$taskId = $_GET['taskid'];
$subTaskName = $subTaskDueDate = $subTaskPriority = "";
$taskNameError = $dueDateError = $priorityError = "";
$isFormValid = true;

if ($_SERVER["REQUEST_METHOD"] == "POST" and $_GET['type'] == 'newsubtask'){
    if (empty($_POST["TaskName"]) or $_POST["TaskName"] == "none"){
        $taskNameError = "Task Name is required";
        $isFormValid = false;
    } else {
        $subTaskName = htmlspecialchars($_POST["TaskName"]);
    }
    if (empty($_POST["TaskDueDate"]) or $_POST["TaskDueDate"] == "none"){
        //$dueDateError = "Due Date is required";
        //$isFormValid = false;
        $subTaskDueDate = null;
    } else {
        $subTaskDueDate = htmlspecialchars($_POST["TaskDueDate"]);
    }
    if (empty($_POST["TaskPriority"]) or $_POST["TaskPriority"] == "none"){
        $priorityError = "Task Priority is required";
        $isFormValid = false;
    } else {
        $subTaskPriority = htmlspecialchars($_POST["TaskPriority"]);
    }

    if($isFormValid){
        $task = newSubTask($taskId, $subTaskName, 0, $subTaskDueDate, $subTaskPriority);
        Header("Location: index.php?listid=".getListIDFromTaskID($taskId));
    }
}
?>

<?php require "../css/css.php"?>
<?php require "components/pagetop.php"?>
<div class="container">
    <h1>New Subtask</h1>
    <h3 class="fs-4">In task "<?php echo getTaskName($taskId)?>"</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?taskid=".$_GET['taskid']."&type=newsubtask";?>" method="post">
        <div class="">
            <div class="mb-3">
                <label for="TaskName" class="form-label">Task Name</label>
                <input type="text" class="form-control" id="TaskName" name="TaskName"/>
                <p><?php echo $taskNameError?></p>
            </div>
            <div class="row">
                <div class="mb-3 col">
                    <label for="TaskDueDate" class="form-label">Due Date</label>
                    <input type="date" class="form-control" id="TaskDueDate" name="TaskDueDate" value=""/>
                    <p><?php echo $dueDateError?></p>
                </div>
                <div class="mb-3 col">
                    <label for="TaskPriority" class="form-label">Priority</label>
                    <select class="form-select" id="TaskPriority" name="TaskPriority">
                        <option selected value="0">No Priority</option>
                        <option value="1">Highest</option>
                        <option value="2">Medium</option>
                        <option value="3">Lowest</option>
                    </select>
                </div>
                <p><?php echo $priorityError?></p>
            </div>
            <button type="submit" class="btn btn-primary" value="createTask" name="action">Create Subtask</button>
        </div>
    </form>
</div>