<?php
require 'components/pagetop.php';
require 'functions/LoginFunctions.php';
require 'functions/TaskFunctions.php';
require 'functions/DatabaseFunctions.php';
require 'functions/ListFunctions.php';

session_start();
$task_id = $_GET['taskid'];
$list_id = getListIDFromTaskID($task_id);

$collabuser = "";
$collabuserError = "";
$is_form_valid = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['collabUserID'])) {
        $is_form_valid = false;
        $collabuserError = "User is required";
    }
    else {
        $collabuser = $_POST['collabUserID'];
    }
    if ($is_form_valid) {
        assignTaskToUser($task_id, $collabuser);
        Header("Location: index.php?listid=$list_id");
    }
} else if (array_key_exists('unassign', $_GET)) {
    unassignTaskToUser($task_id);
    Header("Location: index.php?listid=$list_id");
}
?>
<div class="container pt-5">
    <h3>Assign user to task '<?php echo getTaskName($task_id);?>'</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?taskid=".$task_id;?>">
        <label for="collabUsername">Collaborator Username</label>
        <div class="row">
            <div class="col">
                <select id='collabUserID' class="form-select" name="collabUserID">
                    <option selected disabled value="">Select a collaborator</option>
                    <?php foreach(getListCollaborators($list_id) as $collaborator):?>
                        <option value="<?php echo $collaborator['user_id'];?>"><?php echo getUsernameFromUID($collaborator['user_id']);?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col">
                <input type="submit" value="Assign to task" class="btn btn-primary"/>
            </div>
        </div>
    </form>
</div>
