<?php

namespace ppa\Controller;

use ppa\Model\DataModel;

class DataController
{
    private $dataModel;
    
    public function __construct()
    {
        if (!isset($_SESSION["userID"])) {
            $error =["success"=>"false", "message" => "User is not logged in.", "status" =>"403"];
            echo json_encode($error);
            die;
        }
        $this->dataModel = new DataModel();
    }

    public function getCSRFToken(){ // Diese Funktion gibt dem user den CSRF Token mit dem EINMAL(!!!!) die insertDataForCategory Funktion genutzt werden kann
        $token = uniqid();
        $_SESSION["token"] = $token;
        $tokenmsg =["success"=>"true", "message" => "Token was genarated.", "token" => $token, "status" =>"200"];
        echo json_encode($tokenmsg);
    }

    public function getCategorys() {
        $userID = $_SESSION["userID"];
        $categorys = $this->dataModel->getAllCategorys($userID); // muss keine parameter übergeben bekommen (mit GET)
        echo json_encode($categorys);
    }

    public function getOneCategory(){
        $categoryid = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING); // Kategorie ID muss mit "id" übergeben werden (mit GET)
        $oneCategory = $this->dataModel->getOneCategory($categoryid);
        echo json_encode($oneCategory);
    }

    public function insertDataForCategory() {
        $logValue = filter_input(INPUT_GET, 'value', FILTER_SANITIZE_STRING); // das Log Value muss hinter value= übergeben werden
        date_default_timezone_set('Europe/Berlin');
        $logDate = date("Y-m-d H:i:s", filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING)); // das Date muss in secunden seit 1970 übergenben werden
        $categoryID = filter_input(INPUT_GET, 'categoryid', FILTER_SANITIZE_STRING); // die category id muss übergeben werdem (im frontend aus dem json von forhin auslesen)
        $passedToken = filter_input(INPUT_GET, 'csrftoken', FILTER_SANITIZE_STRING); // das CSRF Token muss übergeben werden
        // (mit GET)

        if (!isset($_SESSION["token"])){
            $successMsg =["success"=>"false", "message" => "CSRF Token never generated.", "status" =>"403"];
            echo json_encode($successMsg);
            die;
        }
        if($passedToken != $_SESSION["token"]){
            $successMsg =["success"=>"false", "message" => "Passed CSRF Token was wrong.", "status" =>"403"];
            echo json_encode($successMsg);
            die;
        }

        $this->dataModel->instertNewData($logValue, $logDate, $categoryID);
        $successMsg =["success"=>"true", "message" => "Dataset was added to the Database successfully.", "status" =>"200"];
        echo json_encode($successMsg);
        $_SESSION["token"] = null;
    }

    public function deleteDataSet(){ // kann benutzt werden um data tupel zu löschen, muss nur data id übergeben (mit GET)
        $toDeleteCategoryId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
        $this->dataModel->deleteData($toDeleteCategoryId);

        $successMsg =["success"=>"true", "message" => "Dataset was deleted from the Database successfully.", "status" =>"200"];
        echo json_encode($successMsg);
    }

    public function addNewCategory(){ //kann genutzt werden um eine category hinzuzufügen, muss nur userID, beschreibung und einheit übergeben (mit GET)
        $userid = $_SESSION["userID"];
        $description = filter_input(INPUT_GET, 'description', FILTER_SANITIZE_STRING);
        $unit = filter_input(INPUT_GET, 'unit', FILTER_SANITIZE_STRING);

        $this->dataModel->addCategory($userid, $description, $unit);

        $successMsg =["success"=>"true", "message" => "New Category was added to the Database successfully.", "status" =>"200"];
        echo json_encode($successMsg);
    }

    public function getFilteredData() { // Kann genutzt werden um Daten des angemeldetn users gefilterd auszugeben
        $userID = $_SESSION["userID"];
        $time = filter_input(INPUT_GET, 'time', FILTER_SANITIZE_STRING); // sollte 1W für 1 Woche sein oder 1M für 1 Monat und 3M für 3 Monate, muss immer angegeben werden
        $filter = "";
        if (filter_input(INPUT_GET, 'categoryid', FILTER_SANITIZE_STRING) == "all"){ // solle nur der einzelne string "all" sein. bedeutet das alle datas ausgegeben werden sollen
            $filter = "all";
        } else {
            $filter = filter_input(INPUT_GET, 'categoryid', FILTER_SANITIZE_STRING); // immer nur eine category id übergeben, alles mit GET
        }

        // Hier alles mit GET
        $filterdData = $this->dataModel->getTheFilteredData($userID, $time, $filter);
        echo json_encode($filterdData);
    }

    public function setUserPWD(){ // hier kann das passwort des eingeloggten users gesetzt werden, nur das neue passwort wird benötigt (mit GET)
        $userID = $_SESSION["userID"];
        $newPWD = filter_input(INPUT_GET, 'pwd', FILTER_SANITIZE_STRING);
        $passedToken = filter_input(INPUT_GET, 'csrftoken', FILTER_SANITIZE_STRING);

        if (!isset($_SESSION["token"])){
            $successMsg =["success"=>"false", "message" => "CSRF Token never generated.", "status" =>"403"];
            echo json_encode($successMsg);
            die;
        }
        if($passedToken != $_SESSION["token"]){
            $successMsg =["success"=>"false", "message" => "Passed CSRF Token was wrong.", "status" =>"403"];
            echo json_encode($successMsg);
            die;
        }

        $this->dataModel->setUserPW($userID, $newPWD);
        $successMsg =["success"=>"true", "message" => "Changed Password successfully.", "status" =>"200"];
        echo json_encode($successMsg);
        $_SESSION["token"] = null;
    }

    public function getUserName(){ //hier kann der username des eingeloggten users ausgegen werden, keine parameter benötigt
        $userID = $_SESSION["userID"];
        $username = $this->dataModel->getUserName($userID);
        $successMsg =["success"=>"true", "message" => "Got Username successfully.", "status" =>"200", "name" => $username];
        echo json_encode($successMsg);
    }
}