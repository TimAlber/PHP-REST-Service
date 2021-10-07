<?php

namespace ppa\Controller;

use ppa\Model\UserModel;

class UserController
{
    private $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function validateUserLogin(){ // kann genutzt werden um den client bei der api anzumelden
        $output = $this->userModel->isEmailInDB(filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING));
        if ($output){ // email is in nach email= übergeben werden
            $hash = $output["pw"];
            if (password_verify(filter_input(INPUT_GET, 'pwd', FILTER_SANITIZE_STRING), $hash)) { // passwort muss nach pwd= übergben werden (beides mit GET)
                $_SESSION["email"] = $_GET["email"];
                $_SESSION["userID"] = $this->userModel->isEmailInDB($_GET["email"])["id"];
                $successMsg =["success"=>"true", "message" => "Logged in successfully.", "status" =>"200", "userID" => $this->userModel->isEmailInDB($_GET["email"])["id"]];
                echo json_encode($successMsg);

            } else { // pwd incorrect
                $Msg =["success"=>"false", "message" => "Logging in failed.", "status" =>"200"];
                echo json_encode($Msg);
                session_unset();
                session_destroy();
            }
        } else { // email not in db
            $Msg =["success"=>"false", "message" => "Logging in failed.", "status" =>"200"];
            echo json_encode($Msg);
            session_unset();
            session_destroy();
        }
        die;
    }

    public function logout(){ // kann ausgeführt werden um den client von der API abzumelden
        session_unset();
        session_destroy();
        $Msg =["success"=>"true", "message" => "User was logged out successfully.", "status" =>"200"];
        echo json_encode($Msg);
    }

    public function signup(){ // kann genutzt werden um einen Nutzer hinzuzufügen, email, username und password müssen alle 3 übergeben werden (mit GET)
        $mail = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING);
        $name = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
        $pwd =  filter_input(INPUT_GET, 'pwd', FILTER_SANITIZE_STRING);

        $output = $this->userModel->isEmailInDB(filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING));
        if($output){
            $errorMsg =["success"=>"false", "message" => "Email already exists.", "status" =>"403"];
            echo json_encode($errorMsg);
            die;
        }

        $this->userModel->addUser($mail, $name, $pwd);
        $successMsg =["success"=>"true", "message" => "Signed Up successfully.", "status" =>"200"];
        echo json_encode($successMsg);
    }
}