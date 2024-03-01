<?php require 'components/NewTaskPopup.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" and $_GET['type'] == 'complete'){
    if(!empty($_GET['taskid'])){
        markTaskAsCompleted($_GET['taskid']);
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
<ol>
<?php
    foreach (getUncompletedTasksFromList($_GET['listid']) as $item):?>
        <ul>
            <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?taskid=".$item['task_id']."&type=complete&listid=".$_GET['listid'];?>" method="post">
                <div class="row">
                    <button class='btn btn-primary col' type='submit' name=<?php echo $item['task_id']?>>✅</button>
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
