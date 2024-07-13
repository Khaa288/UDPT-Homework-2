<?php
require_once("./model/AuthorsModel.php");
class AuthorsController
{
    public function editProfile()
    {
        if (isset($_COOKIE["isLogin"])) {
            $author = AuthorsModel::GetAuthor($_COOKIE["isLogin"]);

            $VIEW = "./view/ProfileEdit.phtml";
            require("./template/layout.phtml");
        }
    }

    public function confirmEditProfile()
    {
        $user_id = $_COOKIE["isLogin"];
        $fullname = $_REQUEST["fullname"];
        $bio = $_REQUEST["bio"];
        $interests = $_REQUEST["interests"];
        $file = $_FILES["updated-pic"];

        $result = AuthorsModel::UpdateAuthor($user_id, $fullname, $file, $bio, $interests);

        header("Location: index.php?action=edit-profile&user_id=".$user_id);
    }
}
