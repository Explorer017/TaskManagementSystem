<?php
$taskName = $dueDate = $priority = "";
$taskNameError = $dueDateError = $priorityError = "";
$isFormValid = true;

if ($_SERVER["REQUEST_METHOD"] == "POST" and $_GET['type'] == 'new'){
    if (empty($_POST["TaskName"]) or $_POST["TaskName"] == "none"){
        $taskNameError = "Task Name is required";
        $isFormValid = false;
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

    if($isFormValid){
        $task = newTask($taskName,$_GET['listid'],0, $dueDate, $priority);
    }
}
?>

<div class="modal fade" id="NewTaskModal" tabindex="-1" aria-labelledby="NewTaskTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="NewTaskTitle">New Task</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?listid=".$_GET['listid']."&type=new";?>" method="post">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="TaskName" class="form-label">Task Name</label>
                    <input type="text" class="form-control" id="TaskName" name="TaskName"/>
                </div>
                <div class="row">
                    <div class="mb-3 col">
                        <label for="TaskDueDate" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="TaskDueDate" name="TaskDueDate" value=""/>
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" value="createTask" name="action">Create Task</button>
            </div>
            </form>
        </div>
    </div>
</div>