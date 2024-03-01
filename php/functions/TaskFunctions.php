<?php

function checkUserListAccess($listID, $userID)
{
    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT list_id FROM  UserLists WHERE user_id = :uid AND list_id = :lid";
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
    if(!checkUserListAccess($listID, $userID)){
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

    return $resultArray;
}

function getCompletedTasksFromList($listID){
    // verify the user is allowed to access this list
    $userID = getUIDFromCreds();
    if(!checkUserListAccess($listID, $userID)){
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

    return $resultArray;
}

function newTask($task_name, $list_id, $order_in_list, $task_due, $task_priority){
    $db = new SQLite3("../db/database.db");
    $sql = "INSERT INTO Tasks(task_name, task_completed, list_id, order_in_list, task_due_date, task_priority) VALUES (:name, :completed, :list, :order, :due, :priority)";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(":name", $task_name, SQLITE3_TEXT);
    $stmt->bindValue(":completed", 0, SQLITE3_INTEGER);
    $stmt->bindValue(":list", $list_id, SQLITE3_INTEGER);
    $stmt->bindValue(":order", $order_in_list, SQLITE3_INTEGER);
    $stmt->bindValue(":due", $task_due, SQLITE3_INTEGER);
    $stmt->bindValue(":priority", $task_priority, SQLITE3_INTEGER);
    $stmt->execute();

    if($stmt){
        return true;
    }
    return false;
}

function markTaskAsCompleted($task_id){
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
?>