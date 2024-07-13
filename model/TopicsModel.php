<?php
require_once("./config/dbconnect.php");
class TopicsModel
{
    public $topic_id;
    public $topic_name;
    public $papers;
    
    function __construct() {
        $this->topic_id = "";
        $this->topic_name = "";
        $this->papers = array();
    }

    private static function GetTopicPapers($topic_id) {
        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        $papers = "SELECT * FROM papers WHERE topic_id = ".$topic_id." LIMIT 5";
        $papers_result = $mysqli->query($papers);
        $paper_list = array();

        if ($papers_result) {
            foreach($papers_result as $row) {
                $pp = new PapersModel();
                $pp->paper_id = $row["paper_id"];
                $pp->title = $row["title"];
                $pp->author_string_list = $row["author_string_list"];
                $paper_list[] = $pp;
            }
        }

        $mysqli->close();
        return $paper_list;
    }

    public static function ListTopicPapers() {
        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        $topics = "SELECT * FROM topics";
        $topics_result = $mysqli->query($topics);
        $topics_list = array();
        if ($topics_result) 
        {            
            foreach ($topics_result as $row) {
                $tp = new TopicsModel();
                $tp->topic_id = $row["topic_id"];
                $tp->topic_name = $row["topic_name"];   
                $tp->papers = self::GetTopicPapers($row["topic_id"]);
                
                $topics_list[] = $tp; //add an item into array
            }
        }
        $mysqli->close();
        return $topics_list;
    }

    public static function GetAllTopics()
    {
        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        $query = "SELECT * from topics";

        $result = $mysqli->query($query);
        $topics_list = array();

        if ($result) {
            foreach ($result as $row) {
                $t = new TopicsModel();
                $t->topic_name = $row["topic_name"];
                $t->topic_id = $row["topic_id"];
                $topics_list[] = $t;
            }
        }
        $mysqli->close();
        return $topics_list;
    }
}
?>