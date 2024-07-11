<?php
require_once("./model/PapersModel.php");
require_once("./model/TopicsModel.php");

class PapersController
{
    public function list()
    {
        $topics = TopicsModel::ListTopicPapers();
        
        $VIEW = "./view/PublicPage.phtml";
        require("./template/layout.phtml");
    }

    public function search()
    {
        $VIEW = "./view/SearchPage.phtml";
        require("./template/layout.phtml");
    }

    public function ajaxSearch()
    {
        $value = $_REQUEST["value"];
        $searchBy = $_REQUEST["searchBy"];

        $papers = PapersModel::searchPaper($value, $searchBy);
        echo $papers;
    }

    public function get(){
        $paper_id = $_REQUEST["paper_id"];
        $paper = PapersModel::GetPaperById($paper_id);

        $VIEW = "./view/PaperDetail.phtml";
        require("./template/layout.phtml");
    }

    public function create() 
    {
        $VIEW = "./view/CreatePaperPage.phtml";
        require("./template/layout.phtml");
    }
}
?>