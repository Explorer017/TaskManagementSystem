<?php

function checkUserListAccess($listID, $userID)
{
    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT list_id FROM  UserLists WHERE user_id = :uid AND list_id = :lid AND observer = 0 AND collaborator = 0";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":lid", $listID, SQLITE3_INTEGER);
    $stmt->bindValue(":uid", $userID, SQLITE3_INTEGER);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if (!isset($resultArray)) {
        return false;
    }
    if ($listID == $resultArray[0]["list_id"]){
        return true;
    }
}

function checkCollabListAccess($listID, $userID)
{
    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT list_id FROM  UserLists WHERE user_id = :uid AND list_id = :lid AND observer = 0 AND collaborator = 1";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":lid", $listID, SQLITE3_INTEGER);
    $stmt->bindValue(":uid", $userID, SQLITE3_INTEGER);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if (!isset($resultArray)) {
        return false;
    }
    if ($listID == $resultArray[0]["list_id"]){
        return true;
    }
}

function getListName($listID)
{
    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT list_name FROM  Lists WHERE list_id = :lid";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":lid", $listID, SQLITE3_INTEGER);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if (!isset($resultArray)) {
        return false;
    }
    return $resultArray[0]["list_name"];
}

function getUncompletedTasksFromList($listID){
    // verify the user is allowed to access this list
    $userID = getUIDFromCreds();
    if(!checkUserListAccess($listID, $userID) AND !checkCollabListAccess($listID, $userID) AND !checkObserverListAccess($listID, $userID)) {
        return false;
    }

    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT * FROM Tasks WHERE list_id = :lid AND task_completed = 0 ORDER BY order_in_list ";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":lid", $listID, SQLITE3_INTEGER);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }

    if(isset($resultArray)){
        return $resultArray;
    }
    return null;
}

function getCompletedTasksFromList($listID){
    // verify the user is allowed to access this list
    $userID = getUIDFromCreds();
    if(!checkUserListAccess($listID, $userID)  AND !checkCollabListAccess($listID, $userID) AND !checkObserverListAccess($listID, $userID)){
        return false;
    }

    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT * FROM Tasks WHERE list_id = :lid AND task_completed = 1 ORDER BY order_in_list ";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":lid", $listID, SQLITE3_INTEGER);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }

    if(isset($resultArray)){
        return $resultArray;
    }
    return null;
}

function newTask($task_name, $list_id, $order_in_list, $task_due, $task_priority){

    $db = new SQLite3("../db/database.db");
    $sql = "INSERT INTO Tasks(task_name, task_completed, list_id, order_in_list, task_due_date, task_priority) VALUES (:name, :completed, :list, :order, :due, :priority)";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(":name", $task_name, SQLITE3_TEXT);
    $stmt->bindValue(":completed", 0, SQLITE3_INTEGER);
    $stmt->bindValue(":list", $list_id, SQLITE3_INTEGER);
    $stmt->bindValue(":order", $order_in_list, SQLITE3_INTEGER);
    $stmt->bindValue(":due", $task_due, SQLITE3_TEXT);
    $stmt->bindValue(":priority", $task_priority, SQLITE3_INTEGER);
    $stmt->execute();

    if($stmt){
        return true;
    }
    return false;
}

function getListIDFromTaskID($task_id){
    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT list_id FROM Tasks WHERE task_id = :task_id";
    $stmt = $db->prepare($SQL);
    $stmt->bindValue(":task_id", $task_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if (!isset($resultArray)) {
        return false;
    }
    return $resultArray[0]["list_id"];
}

function markTaskAsCompleted($task_id){

    // verify the user is allowed to access this list
    $userID = getUIDFromCreds();
    $listID = getListIDFromTaskID($task_id);
    if(!checkUserListAccess($listID, $userID)){
        return false;
    }

    $db = new SQLite3("../db/database.db");
    $sql = "UPDATE Tasks SET task_completed=1 WHERE task_id=:tid";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(":tid", $task_id, SQLITE3_INTEGER);
    $stmt->execute();

    if($stmt){
        return true;
    }
    return false;
}

function markTaskAsUncompleted($task_id){

    // verify the user is allowed to access this list
    $userID = getUIDFromCreds();
    $listID = getListIDFromTaskID($task_id);
    if(!checkUserListAccess($listID, $userID)){
        return false;
    }

    $db = new SQLite3("../db/database.db");
    $sql = "UPDATE Tasks SET task_completed=0 WHERE task_id=:tid";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(":tid", $task_id, SQLITE3_INTEGER);
    $stmt->execute();

    if($stmt){
        return true;
    }
    return false;
}

function getTaskName($task_id){
    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT task_name FROM Tasks WHERE task_id = :task_id";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":task_id", $task_id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    $row = $result->fetchArray();

    return $row["task_name"];
}

// subtask functions
function getUncompletedSubtasksFromTask($task_id){

    // verify the user is allowed to access this list
    $userID = getUIDFromCreds();
    $listID = getListIDFromTaskID($task_id);
    if(!(checkUserListAccess($listID, $userID) || checkCollabListAccess($listID, $userID) || checkObserverListAccess($listID, $userID))){
        return false;
    }

    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT * FROM SubTasks WHERE task_id = :task_id AND sub_task_completed = 0";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":task_id", $task_id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if(isset($resultArray)){
        return $resultArray;
    }
    return null;
}

function getCompletedSubtasksFromTask($task_id){

    // verify the user is allowed to access this list
    $userID = getUIDFromCreds();
    $listID = getListIDFromTaskID($task_id);
    if(!(checkUserListAccess($listID, $userID) || checkCollabListAccess($listID, $userID) || checkObserverListAccess($listID, $userID))){
        return false;
    }

    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT * FROM SubTasks WHERE task_id = :task_id AND sub_task_completed = 1";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":task_id", $task_id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if(isset($resultArray)){
        return $resultArray;
    }
    return null;
}

function getTaskIDFromSubtaskID($subtask_id){
    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT task_id FROM SubTasks WHERE sub_task_id = :subtask_id";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":subtask_id", $subtask_id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if(isset($resultArray)){
        return $resultArray[0]["task_id"];
    }
    return null;
}

function markSubtaskAsCompleted($subtask_id){

    // verify the user is allowed to access this list
    $userID = getUIDFromCreds();
    $taskID = getTaskIDFromSubtaskID($subtask_id);
    $listID = getListIDFromTaskID($taskID);
    if(!(checkUserListAccess($listID, $userID) || checkCollabListAccess($listID, $userID))){
        return false;
    }

    $db = new SQLite3("../db/database.db");
    $SQL = "UPDATE SubTasks SET sub_task_completed=1 WHERE sub_task_id=:stid";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":stid", $subtask_id, SQLITE3_INTEGER);
    $stmt->execute();

    if($stmt){
        return true;
    }
    return false;

}

function newSubTask($task_id, $subtask_name, $subtask_order, $subtask_due_date, $subtask_priority){

    // verify the user is allowed to access this list
    $userID = getUIDFromCreds();
    $listID = getListIDFromTaskID($task_id);
    if(!(checkUserListAccess($listID, $userID) || checkCollabListAccess($listID, $userID))){
        return false;
    }

    $db = new SQLite3("../db/database.db");
    $SQL = "INSERT INTO SubTasks (sub_task_name, sub_task_completed, task_id, order_in_task, sub_task_due_date, sub_task_priority) VALUES (:subtask_name, 0, :task_id, :order_in_task, :subtask_due_date, :subtask_priority)";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":subtask_name", $subtask_name, SQLITE3_TEXT);
    $stmt->bindValue(":task_id", $task_id, SQLITE3_INTEGER);
    $stmt->bindValue(":order_in_task", $subtask_order, SQLITE3_INTEGER);
    $stmt->bindValue(":subtask_due_date", $subtask_due_date, SQLITE3_TEXT);
    $stmt->bindValue(":subtask_priority", $subtask_priority, SQLITE3_INTEGER);

    $result = $stmt->execute();
    if($result){
        return true;
    }
    return false;
}

function markSubtaskAsUncompleted($subtask_id){

    // verify the user is allowed to access this list
    $userID = getUIDFromCreds();
    $taskID = getTaskIDFromSubtaskID($subtask_id);
    $listID = getListIDFromTaskID($taskID);

    if(!(checkUserListAccess($listID, $userID) || checkCollabListAccess($listID, $userID))){
        return false;
    }
    $db = new SQLite3("../db/database.db");
    $SQL = "UPDATE SubTasks SET sub_task_completed=0 WHERE sub_task_id=:stid";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":stid", $subtask_id, SQLITE3_INTEGER);

    $stmt->execute();
    if($stmt){
        return true;
    }
    return false;
}

function getTaskAssignedUser($taskid){

    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT assigned_collaborator_id FROM Tasks WHERE task_id = :task_id";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":task_id", $taskid, SQLITE3_INTEGER);

    $result = $stmt->execute();
    $row = $result->fetchArray();
    if(isset($row)){
        return $row["assigned_collaborator_id"];
    }
    return null;
}

function assignTaskToUser($task_id, $collaborator_id){

    // verify the user is allowed to access this list
    $userID = getUIDFromCreds();
    $listID = getListIDFromTaskID($task_id);
    if(!checkUserListAccess($listID, $userID)){
        return false;
    }

    $db = new SQLite3("../db/database.db");
    $SQL = 'UPDATE Tasks SET assigned_collaborator_id=:cid WHERE task_id = :tid';
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":tid", $task_id, SQLITE3_INTEGER);
    $stmt->bindValue(":cid", $collaborator_id, SQLITE3_INTEGER);

    $result = $stmt->execute();
    if($result){
        return true;
    }
    return false;
}

function unassignTaskToUser($task_id){

    // verify the user is allowed to access this list
    $userID = getUIDFromCreds();
    $listID = getListIDFromTaskID($task_id);
    if(!checkUserListAccess($listID, $userID)){
        return false;
    }

    $db = new SQLite3("../db/database.db");
    $SQL = 'UPDATE Tasks SET assigned_collaborator_id=null WHERE task_id = :tid';
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":tid", $task_id, SQLITE3_INTEGER);

    $result = $stmt->execute();
    if($result){
        return true;
    }
    return false;
}

function checkObserverListAccess($listID, $userID)
{
    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT list_id FROM  UserLists WHERE user_id = :uid AND list_id = :lid AND observer = 1 AND collaborator = 0";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":lid", $listID, SQLITE3_INTEGER);
    $stmt->bindValue(":uid", $userID, SQLITE3_INTEGER);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if (!isset($resultArray)) {
        return false;
    }
    if ($listID == $resultArray[0]["list_id"]){
        return true;
    }
}

function deleteTask($taskid, $listid)
{
    // verify the user is allowed to access this list
    session_start();
    $userID = getUIDFromCreds();
    if(!checkUserListAccess($listid, $userID)){
        return false;
    }

    $db = new SQLite3("../db/database.db");
    $SQL = 'DELETE FROM Tasks WHERE task_id = :tid';
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":tid", $taskid, SQLITE3_INTEGER);

    $result = $stmt->execute();
    if($result){
        // delete associated subtasks
        $SQL = 'DELETE FROM SubTasks WHERE task_id = :tid';
        $stmt = $db->prepare($SQL);

        $stmt->bindValue(":tid", $taskid, SQLITE3_INTEGER);

        $result = $stmt->execute();
        if($result){
            return true;
        }
    }
    return false;
}

function getTaskInfo($taskid){

    // verify the user is allowed to access this list
    session_start();
    $userID = getUIDFromCreds();
    $listID = getListIDFromTaskID($taskid);
    if(!checkUserListAccess($listID, $userID)){
        return false;
    }


    $db = new SQLite3("../db/database.db");
    $SQL = 'SELECT * FROM Tasks WHERE task_id = :tid';
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":tid", $taskid, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray();
    if(isset($row)){
        return $row;
    }
    return false;
}

function modifyTask($task_id, $task_name, $task_due, $task_priority){

    $db = new SQLite3("../db/database.db");
    $sql = "UPDATE Tasks SET task_name = :name, task_due_date = :due, task_priority = :priority WHERE task_id = :tid";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(":name", $task_name, SQLITE3_TEXT);
    $stmt->bindValue(":due", $task_due, SQLITE3_TEXT);
    $stmt->bindValue(":priority", $task_priority, SQLITE3_INTEGER);
    $stmt->bindValue(":tid", $task_id, SQLITE3_INTEGER);
    $stmt->execute();

    if($stmt){
        return true;
    }
    return false;
}

?>