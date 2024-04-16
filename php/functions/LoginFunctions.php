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

function SignUp($first_name, $last_name, $username, $email, $password)
{
    $signedIn = false;
    $db = new SQLite3("../db/database.db");
    $SQL = "INSERT INTO Users(user_name, email, password_hash, first_name, last_name) VALUES (:uname, :email, :password, :fname, :lname)";
    $stmt = $db->prepare($SQL);
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bindValue(":uname", $username, SQLITE3_TEXT);
    $stmt->bindValue(":email", $email, SQLITE3_TEXT);
    $stmt->bindValue(":password", $passwordHash, SQLITE3_TEXT);
    $stmt->bindValue(":fname", $first_name, SQLITE3_TEXT);
    $stmt->bindValue(":lname", $last_name, SQLITE3_TEXT);
    $result = $stmt->execute();
    if ($result) {
        $signedIn = true;
        return $passwordHash;
    }
    return $signedIn;
}
