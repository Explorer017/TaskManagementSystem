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

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    New Task
</button>

<br/><br/>
<ol>
<?php
    foreach (getUncompletedTasksFromList($_GET['listid']) as $item):?>
        <li>
            <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?taskid=".$item['task_id']."&type=complete&listid=".$_GET['listid'];?>" method="post">
                <div class="">
                    <button class='btn btn-primary' type='submit' name=<?php echo $item['task_id']?>>✅</button>
                    <?php echo $item['task_name']?>
                </div>
            </form>
            </li>
    <?php endforeach; ?>
</ol>
