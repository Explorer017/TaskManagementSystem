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
}
?>
<h1>
    <?php if (getListNameFromID($listid) != false) echo getListNameFromID($listid)[0]['list_name']?>
</h1>

<?php if ($listid != false):?>

<?php if(checkUserListAccess(htmlspecialchars($listid), getUIDFromCreds())): ?>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#NewTaskModal">
    New Task
</button>
<?php else: ?>
A list created by <b><?php echo getListOwnerName($listid)?></b>
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
                        <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?taskid=".$item['task_id']."&type=complete&listid=".$listid;?>" method="post">
                            <div class="row">
                                <button class='btn btn-primary col' style="flex: none; width: 10%; min-width: 100px; height: 10%; min-height: 100px;" type='submit' name=<?php echo $item['task_id']?>>✅</button>
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5><?php echo $item['task_name']?></h5>
                                        </div>
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
                                    <?php if(getUncompletedSubtasksFromTask($item['task_id']) != null): ?>
                                        <i>Subtasks:</i>
                                        <?php $bgcolour = true;?>
                                        <?php foreach (getUncompletedSubtasksFromTask($item['task_id']) as $subtask): ?>
                                            <div class=" d-flex flex-row rounded"  <?php if($bgcolour) {
                                                                                        echo 'style="background-color: darkgrey"';
                                                                                        $bgcolour = false;
                                                                                        } else{
                                                                                        $bgcolour = true;
                                                                                    }?>>
                                                    <div class="p-1">
                                                        <button>done</button>
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
                                    <?php endif;?>
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
                                <button class='btn btn-secondary col' style="flex: none; width: 10%; min-width: 100px; height: 10%; min-height: 100px;" type='submit' name=<?php echo $item['task_id']?>>Uncomplete</button>
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