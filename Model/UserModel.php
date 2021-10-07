<?php

namespace ppa\Model;

class UserModel extends Database {
    
    public function isEmailInDB($email)
    {
        $sql = "SELECT * FROM user WHERE email = :email";
        $pdo = $this->linkDB();

        try {
            $res = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $res->execute(array(':email' => $email));
            return $res->fetch();
        } catch (\PDOException $e) {
            new \Blog\Library\ErrorMsg("Fehler beim Schreiben der Daten.", $e); 
            die;
        }
    }

    public function addUser($mail, $name, $pwd){
        $guid = $this->createUUID();
        $encrypedpsw = password_hash($pwd, PASSWORD_ARGON2I);

        $sql = "INSERT INTO user (`id`, `username`, `email`, `pw`) VALUES (:guid, :name, :mail, :encrypedpsw)";

        $pdo = $this->linkDB();

        try {
            $res = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $res->execute(array(':guid' => $guid, ':name' => $name, ':mail' => $mail, ':encrypedpsw' => $encrypedpsw));
        } catch (\PDOException $e) {
            new \Blog\Library\ErrorMsg("Fehler beim Schreiben der Daten.", $e); 
            die;
        }
    }
}