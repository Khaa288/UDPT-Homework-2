<?php
session_start();
require_once("./controller/PublicController.php");
require_once("./controller/PapersController.php");
require_once("./controller/LoginController.php");
require_once("./controller/AuthorsController.php");

$action = "";
if (isset($_REQUEST["action"]))
{    
    $action = $_REQUEST["action"];
}
 
switch ($action)
{
    case "login":      
        $controller = new LoginController();
        $controller->login();
        break;
    case "logout":      
        $controller = new LoginController();
        $controller->logout();
        break;
    case "home":      
        $controller = new PublicController();
        $controller->home();
        break;
    case "list-papers":      
        $controller = new PapersController();
        $controller->list();
        break;
    case "paper-detail":      
        $controller = new PapersController();
        $controller->get();
        break;
    case "edit-profile":
        $controller = new AuthorsController();
        $controller->editProfile();
        break;
    case "confirm-edit-profile": 
        $controller = new AuthorsController();
        $controller->confirmEditProfile();
        break;
    case "create-paper": 
        $controller = new PapersController();
        $controller->create();
        break;
    case "confirm-create-paper": 
        $controller = new PapersController();
        $controller->confirmCreate();
        break;
    case "assign-self": 
        $controller = new PapersController();
        $controller->assign();
        break;
    case "search": 
        $controller = new PapersController();
        $controller->search();
        break;
    case "ajax-search": 
        $controller = new PapersController();
        $controller->ajaxSearch();
        break;
        
    default:
        $controller = new PublicController();
        $controller->index();
}
?>
