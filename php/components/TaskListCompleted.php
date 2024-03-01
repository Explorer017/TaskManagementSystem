<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" and $_GET['type'] == 'uncomplete'){
    if(!empty($_GET['taskid'])){
        markTaskAsUncompleted($_GET['taskid']);
        header('Location: index.php?listid='.$_GET['listid']);
    }
}
?>
<h1>
    <?php echo getListNameFromID($_GET['listid'])[0]['list_name']?>
</h1>
<br/><br/>
<ol>
<?php
    foreach (getCompletedTasksFromList($_GET['listid']) as $item):?>
        <ul>
            <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?taskid=".$item['task_id']."&type=uncomplete&listid=".$_GET['listid'];?>" method="post">
                <div class="row">
                    <button class='btn btn-secondary col' type='submit' name=<?php echo $item['task_id']?>>Uncomplete</button>
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
