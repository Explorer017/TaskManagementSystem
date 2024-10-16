<?php require 'components/NewTaskPopup.php';
$listid = isset($_GET['listid']) ? $_GET['listid'] : false;


if ($_SERVER["REQUEST_METHOD"] == "POST" and $_GET['type'] == 'complete'){
    if(!empty($_GET['taskid'])){
        markTaskAsCompleted($_GET['taskid']);
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" and $_GET['type'] == 'uncomplete'){
    if(!empty($_GET['taskid'])){
        markTaskAsUncompleted($_GET['taskid']);
        header('Location: index.php?listid='.$listid);
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" and $_GET['type'] == 'newsubtask'){
    if(!empty($_GET['taskid'])){
        echo "new subtask ". $_GET['taskid'];
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" and $_GET['type'] == 'completesubtask'){
    if(!empty($_GET['subtaskid'])){
        markSubtaskAsCompleted($_GET['subtaskid']);
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" and $_GET['type'] == 'uncompletesubtask'){
    if(!empty($_GET['subtaskid'])){
        markSubtaskAsUncompleted($_GET['subtaskid']);
    }
}
?>
<h1>
    <?php if (getListNameFromID($listid) != false && array_key_exists('list_name', getListNameFromID($listid)[0])) echo getListNameFromID($listid)[0]['list_name']?>
</h1>

<?php if ($listid != false):?>

<?php if(checkUserListAccess(htmlspecialchars($listid), getUIDFromCreds())): ?>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#NewTaskModal">
        New Task
    </button>
<?php else: ?>
    A list created by <b><?php echo getListOwnerFullName($listid)?></b><br/>
<?php endif;?>
<?php if(checkIfListHasCollaborators($listid)): ?>
    This is a shared list
<?php endif;?>

<br/><br/>
<!-- Tabs for toggling between complete and uncompleted tasks -->
<div>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-todo-tab" data-bs-toggle="pill" data-bs-target="#pills-todo" type="button" role="tab" aria-controls="pills-todo" aria-selected="true">ToDo</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-done-tab" data-bs-toggle="pill" data-bs-target="#pills-done" type="button" role="tab" aria-controls="pills-done" aria-selected="false">Completed</button>
        </li>
    </ul>
</div>


<div class="tab-content" id="pills-tabContent">
    <!-- uncompleted task list -->
    <div class="tab-pane fade show active" id="pills-todo" role="tabpanel" aria-labelledby="pills-todo-tab" tabindex="0">
        <div class="p-3">
            <ol>
                <?php
                if (getUncompletedTasksFromList($listid) != null):
                foreach (getUncompletedTasksFromList($listid) as $item):?>
                    <ul>
                        <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?taskid=".$item['task_id']."&type=complete&listid=".$listid;?>" method="post" id="complete<?php echo $item['task_id']?>"></form>
                        <!--<form id="newsubtask" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?taskid=".$item['task_id']."&type=newsubtask&listid=".$listid;?>" method="post" name="newsubtask" style="height: 0px; width: 0px"></form>-->
                            <div class="row">
                                <button class='btn btn-primary col' style="flex: none; width: 10%; min-width: 100px; height: 10%; min-height: 100px;" type='submit' name="<?php echo $item['task_id']?>" form="complete<?php echo $item['task_id']?>">✅</button>
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5><?php echo $item['task_name']?></h5>
                                        </div>
                                        <br/>
                                        <?php if (checkUserListAccess($listid, getUIDFromCreds())):?>
                                        <div>
                                            <div class="btn-group">
                                                <button class="btn btn-danger btn-sm" onclick="window.location.href='deleteTask.php?taskid=<?php echo $item['task_id'];?>'">Delete</button>
                                                <button class="btn btn-secondary btn-sm" onclick="window.location.href='modifyTask.php?taskid=<?php echo $item['task_id'];?>'">Modify</button>
                                            </div>
                                        </div>
                                        <?php endif;?>
                                        <?php if(isset($item['assigned_collaborator_id'])){
                                                echo "<i> Assigned to <b>".getUsernameFromUID($item['assigned_collaborator_id']).'</b></i> <br/>';
                                                if (checkUserListAccess($listid, getUIDFromCreds())):?>
                                                    <div><button class="btn btn-danger btn-sm" onclick="window.location.href='assignUser.php?taskid=<?php echo $item['task_id'];?>&unassign=true'">Unassign</button></div>
                                                <?php endif;
                                            } else if (getListCollaborators($listid) != null): ?>
                                            <div class="p-1"><button class="btn btn-primary btn-sm" onclick="window.location.href='assignUser.php?taskid=<?php echo $item['task_id'];?>'">Assign User</button></div>
                                        <?php endif;?>
                                        <i><?php if(isset($item['task_due_date'])){
                                                echo "Due ".$item['task_due_date'];
                                            } else {
                                                echo 'No due date';
                                            }?></i> <br/>
                                        <i><?php if(($item['task_priority']) != 0){
                                                echo 'Priority '.$item['task_priority'];
                                            } else {
                                                echo 'No priority';
                                            }?></i>
                                    </div>
                                    <div class="">
                                        <ul class="btn-group p-1" id="pills-tab-subtask<?php echo $item['task_id']?>" role="tablist">
                                                <button class="btn btn-outline-primary active btn-sm" id="pills-subtask-uncomplete-<?php echo $item['task_id']?>-tab" data-bs-toggle="pill" data-bs-target="#pills-subtask-uncomplete-<?php echo $item['task_id']?>" type="button" roll="tab">ToDo</button>
                                                <button class="btn btn-outline-secondary btn-sm" id="pills-subtask-complete-<?php echo $item['task_id']?>-tab" data-bs-toggle="pill" data-bs-target="#pills-subtask-complete-<?php echo $item['task_id']?>" type="button" roll="tab">Done</button>
                                        </ul>
                                    </div>
                                    <div class="tab-content" id="pills-tabContent-subtask">
                                        <div class="tab-pane fade show active" id="pills-subtask-uncomplete-<?php echo $item['task_id']?>" role="tabpanel" aria-labelledby="pills-subtask-uncomplete-<?php echo $item['task_id']?>-tab" tabindex="0">
                                            <div class="flex-grow-1 align-middle"><i class="align-middle">Subtasks:</i></div>
                                            <?php if(getUncompletedSubtasksFromTask($item['task_id']) != null): ?>
                                            <div class="d-flex flex-row pb-1">
                                                <div>
                                                    <?php if (!checkObserverListAccess($listid, getUIDFromCreds())):?> <button class="btn btn-primary btn-sm" type="submit" onclick="window.location.href='newSubTask.php?taskid=<?php echo $item['task_id']?>'">New Subtask</button> <?php endif;?>
                                                </div>
                                            </div>
                                                <?php $bgcolour = true;?>
                                                <?php foreach (getUncompletedSubtasksFromTask($item['task_id']) as $subtask): ?>
                                                    <form id="completeSubtask<?php echo $subtask['sub_task_id']?>" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?subtaskid=".$subtask['sub_task_id']."&type=completesubtask&listid=".$listid;?>" method="post" name="completeSubtask" style="height: 0px; width: 0px" hidden></form>
                                                    <div class=" d-flex flex-row rounded"  <?php if($bgcolour) {
                                                                                                echo 'style="background-color: darkgrey"';
                                                                                                $bgcolour = false;
                                                                                                } else{
                                                                                                $bgcolour = true;
                                                                                            }?>>
                                                            <div class="p-1">
                                                                <button form="completeSubtask<?php echo $subtask['sub_task_id']?>" class="btn btn-primary btn-sm" type="submit">✅</button>
                                                            </div>
                                                            <div class="p-1 flex-grow-1">
                                                                <i><b><?php echo $subtask["sub_task_name"];?></b></i>
                                                            </div>
                                                            <div class="p-1">
                                                                <i><?php if(isset($subtask['sub_task_due_date'])){
                                                                        echo "Due ".$subtask['sub_task_due_date'];
                                                                    } else {
                                                                        echo 'No due date';
                                                                    }?></i>
                                                            </div>
                                                                <div class="p-1 pe-2">
                                                                    <i><?php if(($subtask['sub_task_priority']) != 0){
                                                                            echo 'Priority '.$subtask['sub_task_priority'];
                                                                        } else {
                                                                            echo 'No priority';
                                                                        }?></i>
                                                                </div>
                                                    </div>
                                                <?php endforeach;?>
                                            <?php else:?>
                                            <div class="d-flex">
                                                <i class="flex-grow-1 col">No subtasks</i>
                                                <?php if (!checkObserverListAccess($listid, getUIDFromCreds())):?> <button class="btn btn-primary btn-sm" type="submit" onclick="window.location.href='newSubTask.php?taskid=<?php echo $item['task_id']?>'">New Subtask</button> <?php endif;?>
                                            </div>
                                            <?php endif;?>
                                        </div>
                                        <div class="tab-pane fade show" id="pills-subtask-complete-<?php echo $item['task_id']?>" role="tabpanel" aria-labelledby="pills-subtask-complete-<?php echo $item['task_id']?>-tab" tabindex="0">
                                            <?php if(getCompletedSubtasksFromTask($item['task_id']) != null): ?>
                                                <div class="d-flex flex-row pb-1">
                                                    <div class="flex-grow-1 align-middle"><i class="align-middle">Subtasks:</i></div>
                                                    <div>
                                                        <button class="btn btn-primary btn-sm" type="submit" onclick="window.location.href='newSubTask.php?taskid=<?php echo $item['task_id']?>'">New Subtask</button>
                                                    </div>
                                                </div>
                                                <?php $bgcolour = true;?>
                                                <?php foreach (getCompletedSubtasksFromTask($item['task_id']) as $subtask): ?>
                                                    <form id="uncompleteSubtask<?php echo $subtask['sub_task_id']?>" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?subtaskid=".$subtask['sub_task_id']."&type=uncompletesubtask&listid=".$listid;?>" method="post" name="completeSubtask" style="height: 0px; width: 0px" hidden></form>
                                                    <div class=" d-flex flex-row rounded"  <?php if($bgcolour) {
                                                        echo 'style="background-color: darkgrey"';
                                                        $bgcolour = false;
                                                    } else{
                                                        $bgcolour = true;
                                                    }?>>
                                                        <div class="p-1">
                                                            <button form="uncompleteSubtask<?php echo $subtask['sub_task_id']?>" class="btn btn-secondary btn-sm" type="submit"><i class="bi bi-arrow-counterclockwise"></i></button>
                                                        </div>
                                                        <div class="p-1 flex-grow-1">
                                                            <i><b><?php echo $subtask["sub_task_name"];?></b></i>
                                                        </div>
                                                        <div class="p-1">
                                                            <i><?php if(isset($subtask['sub_task_due_date'])){
                                                                    echo "Due ".$subtask['sub_task_due_date'];
                                                                } else {
                                                                    echo 'No due date';
                                                                }?></i>
                                                        </div>
                                                        <div class="p-1 pe-2">
                                                            <i><?php if(($subtask['sub_task_priority']) != 0){
                                                                    echo 'Priority '.$item['sub_task_priority'];
                                                                } else {
                                                                    echo 'No priority';
                                                                }?></i>
                                                        </div>
                                                    </div>
                                                <?php endforeach;?>
                                            <?php else:?>
                                                <div>
                                                    <?php if (!checkObserverListAccess($listid, getUIDFromCreds())):?> <button class="btn btn-primary btn-sm" type="submit" onclick="window.location.href='newSubTask.php?taskid=<?php echo $item['task_id']?>'">New Subtask</button> <?php endif;?>
                                                </div>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </ul>
                <?php endforeach;
                else:?>
                <h2>You have no Tasks!</h2>
                Create a task using the new task button, or ask the list administrator.
                <?php endif;?>
            </ol>
        </div>
    </div>
    <!-- Completed task list -->
    <div class="tab-pane fade" id="pills-done" role="tabpanel" aria-labelledby="pills-done-tab" tabindex="0">
        <div class="p-3">
            <ol>
                <?php
                if (getCompletedTasksFromList($listid) != null):
                foreach (getCompletedTasksFromList($listid) as $item):?>
                    <ul>
                        <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?taskid=".$item['task_id']."&type=uncomplete&listid=".$listid;?>" method="post">
                            <div class="row">
                                <button class='btn btn-secondary col' style="flex: none; width: 10%; min-width: 100px; height: 10%; min-height: 100px;" type='submit' name=<?php echo $item['task_id']?>><i class="bi bi-arrow-counterclockwise"></i></button>
                                <div class="col">
                                    <h5><?php echo $item['task_name']?></h5>
                                    <br/>
                                    <i><?php if(isset($item['task_due_date'])){
                                            echo "Due ".$item['task_due_date'];
                                        } else {
                                            echo 'No due date';
                                        }?></i> <br/>
                                    <i><?php if(($item['task_priority']) != 0){
                                            echo 'Priority '.$item['task_priority'];
                                        } else {
                                            echo 'No priority';
                                        }?></i>
                                </div>
                            </div>
                        </form>
                    </ul>
                <?php endforeach;
                else:?>
                    <h2>There are no completed tasks!</h2>
                <?php endif;?>
            </ol>
        </div>
    </div>
</div>
<?php else: ?>
<h1>No List selected!</h1>
Create or select a list using the sidebar!
<?php endif;?>