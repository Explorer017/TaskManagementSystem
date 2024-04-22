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
        return null;
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
        return null;
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
        return [[]];
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

function getListOwnerFullName($listid){
    $db = new SQLite3("../db/database.db");
    $SQL = 'SELECT Users.first_name || " " || Users.last_name  || " (" || Users.user_name || ")" AS "list_owner", UserLists.user_id FROM UserLists INNER JOIN Users on Users.user_id = UserLists.user_id WHERE list_id = :listid AND observer = 0 AND collaborator = 0';
    $stmt = $db->prepare($SQL);
    $stmt->bindValue(":listid", $listid, SQLITE3_TEXT);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if (!isset($resultArray)){
        return null;
    }
    return $resultArray[0]['list_owner'];
}

function getFirstName(){
    $userid = getUIDFromCreds();

    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT first_name FROM Users WHERE user_id = :userid";
    $stmt = $db->prepare($SQL);
    $stmt->bindValue(":userid", $userid, SQLITE3_TEXT);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if (!isset($resultArray)){
        return null;
    }
    return $resultArray[0]['first_name'];
}

function getUsernameFromUID($uid){

    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT user_name FROM Users WHERE user_id = :uid";

    $stmt = $db->prepare($SQL);
    $stmt->bindValue(":uid", $uid, SQLITE3_INTEGER);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }

    if (!isset($resultArray)){
        return null;
    }
    return $resultArray[0]['user_name'];
}

function getUsersFullName($uid){

    $db = new SQLite3("../db/database.db");
    $SQL = 'SELECT first_name || " " || last_name  || " (" || user_name || ")" AS "name" FROM Users WHERE user_id = :userid';

    $stmt = $db->prepare($SQL);
    $stmt->bindValue(":userid", $uid, SQLITE3_INTEGER);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }

    if (!isset($resultArray)){
        return null;
    }
    return $resultArray[0]['name'];
}

?>