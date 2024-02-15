<?php
function SignIn($username, $password){
    $signedIn = false;
    $db = new SQLite3("../db/database.db");
    $SQL = "SELECT password_hash FROM Users WHERE user_name = :uname";
    $stmt = $db->prepare($SQL);

    $stmt->bindValue(":uname", $username, SQLITE3_TEXT);

    $result = $stmt->execute();
    while($row = $result->fetchArray()){
        $resultArray [] = $row;
    }
    if (!isset($resultArray)){
        return false;
    }

    if(password_verify($password, $resultArray[0][0])){
        return $resultArray[0][0];
    }
    return false;
}