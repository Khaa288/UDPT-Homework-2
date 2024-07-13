<?php
require_once("./model/PapersModel.php");
require_once("./model/TopicsModel.php");
require_once("./model/AuthorsModel.php");
require_once("./model/ParticipantsModel.php");
require_once("./model/ConferencesModel.php");

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
        $papers_count = PapersModel::CountPapers();
        $VIEW = "./view/SearchPage.phtml";
        require("./template/layout.phtml");
    }

    public function ajaxSearch()
    {
        $value = $_REQUEST["value"];
        $searchBy = $_REQUEST["searchBy"];
        $offset = $_REQUEST["offset"];
        $rowsPerPage = $_REQUEST["rowsPerPage"];

        $papers = PapersModel::searchPaper($value, $searchBy, $offset, $rowsPerPage);
        echo $papers;
    }

    public function get()
    {
        $paper_id = $_REQUEST["paper_id"];
        $paper = PapersModel::GetPaper($paper_id);
        $paper_participants = PapersModel::GetPaperParticipants($paper_id);

        $VIEW = "./view/PaperDetail.phtml";
        require("./template/layout.phtml");
    }

    public function create()
    {
        $authors = AuthorsModel::GetAllAuthors();
        $conferences = ConferencesModel::GetAllConferences();
        $topics = TopicsModel::GetAllTopics();

        $VIEW = "./view/CreatePaperPage.phtml";
        require("./template/layout.phtml");
    }

    public function confirmCreate()
    {
        $authors = $_REQUEST["authorsName"];
        $authorsRole = $_REQUEST["authorsRole"];
        $conference_id = $_REQUEST["conference_name"];
        $topic_id = $_REQUEST["topic_name"];
        $title = $_REQUEST["title"];
        $abstract = $_REQUEST["abstract"];
        $publisher = $_REQUEST["publisher"];

        // Handle authors response
        $author_string_list = substr($authors, 0, -2);
        $authorRole_string_list = substr($authorsRole, 0, -2);

        $author_list = explode(', ', $author_string_list);
        $authorRole_list = explode(', ', $authorRole_string_list);

        // Process
        // Add new Paper
        $newPaper = PapersModel::CreatePaper($title, $author_string_list, $abstract, $conference_id, $topic_id, $publisher);
        
        // Add Participants
        for($i = 0; $i < count($author_list); $i++) {
            $author = AuthorsModel::GetAuthorByName($author_list[$i]);
            $author_role = $authorRole_list[$i];
            $latest_paper = PapersModel::GetLatestPaper();

            ParticipantsModel::AddParticipation($author->author_id, $latest_paper->paper_id, $author_role);
        }

        header("Location: index.php?action=home");
    }

    public function assign()
    {
        $paper_id = $_REQUEST["paper_id"];

        $user_id = $_REQUEST["user_id"];
        $author = AuthorsModel::GetAuthor($user_id);
        $author_string_list = $_REQUEST["author_string_list"];

        if (!str_contains($author_string_list, $author->fullname)) {
            $author_string_list = $author_string_list . ", " . $author->fullname;
            PapersModel::AssignPaper($paper_id, $user_id, $author_string_list);
        }

        header("Location: index.php?action=home");
    }
}
