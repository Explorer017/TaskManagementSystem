<?php

function newList($list_name){
    // get user id from stored creds
    $userID = getUIDFromCreds();
    // create list
    $db = new SQLite3("../db/database.db");
    $sql = "INSERT INTO Lists (list_name, list_completed)  VALUES (:l_name, 0);";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(":l_name", $list_name, SQLITE3_TEXT);

    $stmt->execute();
    /*
    if($stmt){
        return true;
    }
    return false; */

    // get id of last list inserted
    $sql = "SELECT last_insert_rowid() AS 'list_id';";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute();

    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    $listID = $resultArray[0]['list_id'];

    // create assignment in UserLists table
    $sql = "INSERT INTO UserLists (user_id, list_id, collaborator, observer) VALUES (:uid, :lid, 0, 0)";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(":uid", $userID, SQLITE3_TEXT);
    $stmt->bindValue(":lid", $listID, SQLITE3_TEXT);
    $stmt->execute();

    if($stmt){
        return $listID;
    }
    return false;

}

function deleteList($listID){
    // verify the user is allowed to access this list
    session_start();
    $userID = getUIDFromCreds();
    echo $listID." ". $userID;
    if(!checkUserListAccess($listID, $userID)){
        return false;
    }

    $db = new SQLite3("../db/database.db");
    $sql = 'DELETE FROM Lists WHERE list_id = :lid;';
    $stmt = $db->prepare($sql);

    $stmt->bindValue(":lid", $listID, SQLITE3_INTEGER);
    $stmt->execute();

    $sql = 'DELETE FROM UserLists WHERE list_id = :lid;';
    $stmt = $db->prepare($sql);

    $stmt->bindValue(":lid", $listID, SQLITE3_INTEGER);
    $stmt->execute();

    $sql = 'DELETE FROM Tasks WHERE list_id = :lid;';
    $stmt = $db->prepare($sql);

    $stmt->bindValue(":lid", $listID, SQLITE3_INTEGER);
    $stmt->execute();

    if($stmt){
        return true;
    }
    return false;
}

function addUserToListAsCollaborator($listID, $collabUserID){
    // verify the user is allowed to access this list
    session_start();
    $userID = getUIDFromCreds();
    if(!checkUserListAccess($listID, $userID)){
        return false;
    }

    $db = new SQLite3("../db/database.db");
    $sql = "INSERT INTO UserLists (user_id, list_id, collaborator, observer) VALUES (:uid, :lid, 1, 0)";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(":uid", $collabUserID, SQLITE3_INTEGER);
    $stmt->bindValue(":lid", $listID, SQLITE3_INTEGER);
    $stmt->execute();

    if($stmt){
        return true;
    }
    return false;

}

function leaveList($listID){
    // verify the user is allowed to access this list
    session_start();
    $userID = getUIDFromCreds();
    if(!checkCollabListAccess($listID, $userID)){
        return false;
    }

    $db = new SQLite3("../db/database.db");
    $sql = "DELETE FROM UserLists WHERE list_id = :lid AND user_id = :uid AND (observer = 1 OR collaborator = 1)";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(":lid", $listID, SQLITE3_INTEGER);
    $stmt->bindValue(":uid", $userID, SQLITE3_INTEGER);
    $stmt->execute();

    if($stmt){
        return true;
    }
    return false;

}