<?php
require_once("./config/dbconnect.php");
class PapersModel
{
    public $paper_id;
    public $title;
    public $author_string_list;
    public $abstract;
    public $conference_name;
    public $topic_name;
    public $publisher;
    
    function __construct() {
        $this->paper_id = 0;
        $this->title = "";
        $this->author_string_list = "";
        $this->abstract = "";
        $this->conference_name = "";
        $this->topic_name = "";
        $this->publisher = "";
    }

    public static function GetAllPapers() {
        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        $query = 
        "
            SELECT title, author_string_list, abstract, c.name, t.topic_name, u.username
            FROM papers p 
                JOIN conferences c ON p.conference_id = c.conference_id 
                JOIN users u ON p.user_id = u.user_id 
                JOIN topics t ON p.topic_id = t.topic_id    
        ";

        $result = $mysqli->query($query);
        $papers_list = array();

        if ($result) 
        {            
            foreach ($result as $row) {
                $pp = new PapersModel();
                $pp->title = $row["title"];
                $pp->author_string_list = $row["author_string_list"]; 
                $pp->topic_name = $row["topic_name"];  
                $pp->conference_name = $row["name"];
                $pp->abstract = $row["abstract"];
                $pp->publisher = $row["username"];
                
                $papers_list[] = $pp;
            }
        }
        $mysqli->close();
        return $papers_list;
    }
    
    public static function GetPaperById($paper_id) {
        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        $query = "
                SELECT title, author_string_list, abstract, c.name, t.topic_name, u.username
                FROM papers p 
                    JOIN conferences c ON p.conference_id = c.conference_id 
                    JOIN users u ON p.user_id = u.user_id 
                    JOIN topics t ON p.topic_id = t.topic_id
                WHERE p.paper_id = 
                ".$paper_id;
        $result = $mysqli->query($query);

        if ($result) 
        {            
            foreach ($result as $row) {
                $pp = new PapersModel();
                $pp->title = $row["title"];
                $pp->author_string_list = $row["author_string_list"]; 
                $pp->abstract = $row["abstract"];
                $pp->conference_name = $row["name"];
                $pp->topic_name = $row["topic_name"];
                $pp->publisher = $row["username"];    
            }
        }
        $mysqli->close();
        return $pp;
    }

    public static function searchPaper($value, $searchBy) {
        $condition = "WHERE ".$searchBy." LIKE '%$value%' ";

        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        
        $query = 
        "
            SELECT title, author_string_list, abstract, c.name, t.topic_name, u.username
            FROM papers p 
                JOIN conferences c ON p.conference_id = c.conference_id 
                JOIN users u ON p.user_id = u.user_id 
                JOIN topics t ON p.topic_id = t.topic_id    
        ".$condition;

        $response = 
        "
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Authors</th>
                <th>Abstract</th>
                <th>Conference Name</th>
                <th>Topic Name</th>
                <th>Publisher</th>
            </tr>
        </thead>
        <tbody>
        ";

        $result = $mysqli->query($query);

        if ($result) 
        {       
            $i = 1;     
            foreach ($result as $row) {
                $title = $row["title"];
                $author_string_list = $row["author_string_list"]; 
                $abstract = $row["abstract"];
                $conference_name = $row["name"];
                $topic_name = $row["topic_name"];
                $publisher = $row["username"];  
                
                $response = $response."
                    <tr>
                        <td>".$i."</td>
                        <td>".$title."</td>
                        <td>".$author_string_list."</td>
                        <td>".$abstract."</td>
                        <td>".$conference_name."</td>
                        <td>".$topic_name."</td>
                        <td>".$publisher."</td>
                    </tr>
                ";

                $i++;
            }
        }
        $mysqli->close();

        $response = $response."</tbody>";
        return $response;
    }
}
?>
