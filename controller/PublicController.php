<?php
class PublicController
{
    public function index()
    {
        $topics = TopicsModel::ListTopicPapers();
        
        $VIEW = "./view/PublicPage.phtml";
        require("./template/layout.phtml");
    }

    public function home() 
    {
        $author = AuthorsModel::GetAuthor($_COOKIE["isLogin"]);
        $papers = PapersModel::GetAllPapers();

        $VIEW = "./view/HomePage.phtml";
        require("./template/layout.phtml");
        return;
    }
}
?>
