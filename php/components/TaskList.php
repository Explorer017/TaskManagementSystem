<?php require 'components/NewTaskPopup.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" and $_GET['type'] == 'complete'){
    if(!empty($_GET['taskid'])){
        markTaskAsCompleted($_GET['taskid']);
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" and $_GET['type'] == 'uncomplete'){
    if(!empty($_GET['taskid'])){
        markTaskAsUncompleted($_GET['taskid']);
        header('Location: index.php?listid='.$_GET['listid']);
    }
}
?>
<h1>
    <?php echo getListNameFromID($_GET['listid'])[0]['list_name']?>
</h1>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#NewTaskModal">
    New Task
</button>

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
                foreach (getUncompletedTasksFromList($_GET['listid']) as $item):?>
                    <ul>
                        <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?taskid=".$item['task_id']."&type=complete&listid=".$_GET['listid'];?>" method="post">
                            <div class="row">
                                <button class='btn btn-primary col' style="flex: none; width: 10%; min-width: 100px; height: 10%; min-height: 100px;" type='submit' name=<?php echo $item['task_id']?>>✅</button>
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
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
    <!-- Completed task list -->
    <div class="tab-pane fade" id="pills-done" role="tabpanel" aria-labelledby="pills-done-tab" tabindex="0">
        <div class="p-3">
            <ol>
                <?php
                foreach (getCompletedTasksFromList($_GET['listid']) as $item):?>
                    <ul>
                        <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?taskid=".$item['task_id']."&type=uncomplete&listid=".$_GET['listid'];?>" method="post">
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
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
</div>

