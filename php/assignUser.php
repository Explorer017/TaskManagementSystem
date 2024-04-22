<?php
require 'components/pagetop.php';
require 'functions/LoginFunctions.php';
require 'functions/TaskFunctions.php';
require 'functions/DatabaseFunctions.php';
require 'functions/ListFunctions.php';

session_start();
$task_id = $_GET['taskid'];
$list_id = getListIDFromTaskID($task_id);
?>
<div class="container pt-5">
    <h3>Assign user to task '<?php echo getTaskName($task_id);?>'</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?listid=".$list_id;?>">
        <label for="collabUsername">Collaborator Username</label>
        <div class="row">
            <div class="col">
                <select id='collabUsername' class="form-select" name="collabUsername">
                    <option selected disabled value="">Select a collaborator</option>
                    <?php foreach(getListCollaborators($list_id) as $collabs):?>
                        <option value="<?php echo getUsernameFromUID($collabs);?>"><?php echo getUsernameFromUID($collabs);?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col">
                <input type="submit" value="Assign to task" class="btn btn-primary"/>
            </div>
        </div>
    </form>
</div>
