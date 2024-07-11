<?php
require_once("./model/AuthorsModel.php");
class AuthorsController
{
    public function editProfile()
    {
        if (isset($_REQUEST["user_id"])) {
            $author = AuthorsModel::GetAuthor($_REQUEST["user_id"]);

            $VIEW = "./view/ProfileEdit.phtml";
            require("./template/layout.phtml");
        }
    }

    public function confirmEditProfile()
    {
        $user_id = $_REQUEST["user_id"];
        $fullname = $_REQUEST["fullname"];
        $bio = $_REQUEST["bio"];
        $interests = $_REQUEST["interests"];
        $file = $_FILES["updated-pic"];

        $result = AuthorsModel::UpdateAuthor($user_id, $fullname, $file, $bio, $interests);

        header("Location: index.php?action=edit-profile&isLogin&user_id=".$user_id);
    }

    public function cancelEditProfile() 
    {
        $user = new UsersModel();
        $user->user_id = $_REQUEST["user_id"];
        $author = AuthorsModel::GetAuthor($user->user_id);
        $papers = PapersModel::GetAllPapers();

        $VIEW = "./view/HomePage.phtml";
        require("./template/layout.phtml");
    }
}
