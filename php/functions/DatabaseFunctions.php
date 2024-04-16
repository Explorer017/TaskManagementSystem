<?php

function getUIDFromCreds(){
    if (!isset($_SESSION['credentials'])){
        return false;
    }
    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT user_id FROM Users WHERE password_hash = :creds";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":creds", $_SESSION['credentials'], SQLITE3_TEXT);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if (!isset($resultArray)){
        return false;
    }
    return $resultArray[0]['user_id'];
}

function getOwnedListsForCurrentUser(){
    $uid = getUIDFromCreds();

    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT list_id FROM  UserLists WHERE user_id = :uid AND collaborator = 0 AND observer = 0";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":uid", $uid, SQLITE3_TEXT);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if (!isset($resultArray)){
        return false;
    }
    return $resultArray;
}

function getCollabListsForCurrentUser(){
    $uid = getUIDFromCreds();

    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT list_id FROM  UserLists WHERE user_id = :uid AND collaborator = 1 AND observer = 0";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":uid", $uid, SQLITE3_TEXT);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if (!isset($resultArray)){
        return false;
    }
    return $resultArray;
}

function getListNameFromID($listID){

    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT list_name FROM Lists WHERE list_id = :lid";
    $stmt = $db->prepare($SQL);
    $stmt->bindValue(":lid", $listID, SQLITE3_TEXT);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if (!isset($resultArray)){
        return false;
    }
    return $resultArray;
}

function LookupUIDFromName($username){

    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT user_id FROM Users WHERE user_name = :username";
    $stmt = $db->prepare($SQL);
    $stmt->bindValue(":username", $username, SQLITE3_TEXT);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if (!isset($resultArray)){
        return false;
    }
    return $resultArray[0]['user_id'];
}

?>