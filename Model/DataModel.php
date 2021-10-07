<?php

namespace ppa\Model;

class DataModel extends Database {

    public function setUserPW($userID, $newPWD){
        $newPW = password_hash($newPWD, PASSWORD_ARGON2I);
        $sql = "UPDATE user
                SET pw = :newPW
                WHERE id= :userID";

        $pdo = $this->linkDB();

        try {
            $res = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $res->execute(array(':userID' => $userID, ':newPW' => $newPW));
        } catch (\PDOException $e) {
            new \ppa\Library\Msg("Fehler beim Schreiben der Daten.", $e); 
            die;
        }
    }

    public function getUserName($userID){
        $sql = "SELECT username FROM user WHERE id= :userID";

        $pdo = $this->linkDB();

        try {
            $res = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $res->execute(array(':userID' => $userID));
        } catch (\PDOException $e) {
            new \ppa\Library\Msg("Ihre Anfrage konnte nicht verarbeitet werden", $e); 
            die;
        }

        return $res->fetch(\PDO::FETCH_OBJ);
    }

    public function getAllCategorys($userID)
    {
        $sql = "SELECT * FROM category WHERE userId= :userID";

        $pdo = $this->linkDB();

        try {
            $res = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $res->execute(array(':userID' => $userID));
        } catch (\PDOException $e) {
            new \ppa\Library\Msg("Ihre Anfrage konnte nicht verarbeitet werden", $e); 
            die;
        }

        return $res->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getOneCategory($categoryid){
        $sql = "SELECT * FROM category Where id= :categoryid";

        $pdo = $this->linkDB();

        try {
            $res = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $res->execute(array(':categoryid' => $categoryid));
        } catch (\PDOException $e) {
            new \ppa\Library\Msg("Ihre Anfrage konnte nicht verarbeitet werden", $e); 
            die;
        }

        return $res->fetchAll(\PDO::FETCH_OBJ);
    }

    public function instertNewData($logValue, $logDate, $categoryID)
    {
        $guid = $this->createUUID();

        $sql = "INSERT INTO data (`id`, `categoryId`, `logValue`, `logDate`) VALUES (:guid, :categoryID, :logValue, :logDate)";
        #date used not CURRENT_TIMESTAMP

        $pdo = $this->linkDB();

        try {
            $res = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $res->execute(array(':guid' => $guid, ':categoryID' => $categoryID, ':logValue' => $logValue, ':logDate' => $logDate));
        } catch (\PDOException $e) {
            new \ppa\Library\Msg("Fehler beim Schreiben der Daten.", $e); 
            die;
        }
    }

    public function deleteData($toDeleteCategoryId){
        $sql = "DELETE FROM data WHERE id= :toDeleteCategoryId";

        $pdo = $this->linkDB();

        try {
            $res = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $res->execute(array(':toDeleteCategoryId' => $toDeleteCategoryId));
        } catch (\PDOException $e) {
            new \ppa\Library\Msg("Fehler beim Schreiben der Daten.", $e); 
            die;
        }
    }

    public function addCategory($userid, $description, $unit){

        $guid = $this->createUUID();

        $sql = "INSERT INTO category (`id`, `userId`, `description`, `unit`) VALUES (:guid, :userid, :description, :unit)";

        $pdo = $this->linkDB();

        try {
            $res = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $res->execute(array(':guid' => $guid, ':userid' => $userid, ':description' => $description, ':unit' => $unit));
        } catch (\PDOException $e) {
            new \ppa\Library\Msg("Fehler beim Schreiben der Daten.", $e); 
            die;
        }

    }

    public function getTheFilteredData($userID, $time, $filter) 
    {
        $filterarray = array(':userID' => $userID);
        $sql = "SELECT * FROM data JOIN category ON category.id = categoryId WHERE userId = :userID";

        if ($filter != "all"){
            $sql .= " AND categoryId = :filter";
            $filterarray = array(':userID' => $userID, ':filter' => $filter);
        }

        if ($time == "1W"){
            $sql .= " AND DATE(logDate) BETWEEN SUBDATE(CURDATE(), INTERVAL 1 WEEK) AND CURDATE()";     
        } else if ($time = "1M"){
            $sql .= " AND DATE(logDate) BETWEEN SUBDATE(CURDATE(), INTERVAL 1 MONTH) AND CURDATE()";  
        } else if ($time = "3M"){
            $sql .= " AND DATE(logDate) BETWEEN SUBDATE(CURDATE(), INTERVAL 3 MONTH) AND CURDATE()";  
        }

        $pdo = $this->linkDB();

        try {
            $res = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $res->execute($filterarray);
        } catch (\PDOException $e) {
            new \ppa\Library\Msg("Ihre Anfrage konnte nicht verarbeitet werden", $e); 
            die;
        }

        return $res->fetchAll(\PDO::FETCH_OBJ);
    }
}