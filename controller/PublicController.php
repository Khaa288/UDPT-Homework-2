<?php

class PublicController
{
    public function index()
    {
        $topics = TopicsModel::ListTopicPapers();
        
        $VIEW = "./view/PublicPage.phtml";
        require("./template/layout.phtml");
    }
}
?>
