<?php
require_once("./model/UsersModel.php");
require_once("./model/AuthorsModel.php");
require_once("./model/PapersModel.php");
class LoginController {
    public function login()
    {   
        if (isset($_REQUEST["isLogin"])) {
            $username = $_REQUEST["username"];
            $password = $_REQUEST["password"];
            
            $user = UsersModel::GetUser($username, $password);

            if ($user) {
                $user = UsersModel::GetUser($username, $password);
                $author = AuthorsModel::GetAuthor($user->user_id);
                $papers = PapersModel::GetAllPapers();

                switch($user->role) {
                    case "admin": 
                        $VIEW = "./view/AdminPage.phtml";  
                        break;
                    case "member": 
                        $VIEW = "./view/HomePage.phtml";
                        break;
                    default:
                        $VIEW = "./view/HomePage.phtml";
                }
                require("./template/layout.phtml");
                return;
            }

            else {
                header("Location: index.php?action=login");
            }
        }

        $VIEW = "./view/LoginPage.phtml";
        require($VIEW);
    }

    public function logout () 
    {
        header("Location: index.php?action=login");
    }
}
?>