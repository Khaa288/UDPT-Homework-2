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
            SELECT paper_id, title, author_string_list, abstract, c.name, t.topic_name, u.username
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
                $pp->paper_id = $row["paper_id"];
                
                $papers_list[] = $pp;
            }
        }
        $mysqli->close();
        return $papers_list;
    }
    
    public static function GetPaper($paper_id) {
        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        $query = "
                SELECT title, author_string_list, abstract, c.name, t.topic_name, u.username
                FROM papers p 
                    JOIN conferences c ON p.conference_id = c.conference_id 
                    JOIN users u ON p.user_id = u.user_id 
                    JOIN topics t ON p.topic_id = t.topic_id
                WHERE p.paper_id = ".$paper_id;
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

    public static function GetLatestPaper() {
        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        $query = "SELECT * from papers ORDER BY paper_id DESC LIMIT 1"; 
        $result = $mysqli->query($query);

        if ($result) 
        {            
            foreach ($result as $row) {
                $pp = new PapersModel();
                $pp->paper_id = $row["paper_id"];
            }
        }
        $mysqli->close();
        return $pp;
    }

    public static function GetPaperParticipants($paper_id) {
        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        $query = "
                SELECT role, date_added, a.full_name
                FROM participation p JOIN authors a ON p.author_id = a.user_id
                WHERE p.paper_id = ".$paper_id;
        $result = $mysqli->query($query);

        $participants = array();

        if ($result) 
        {            
            foreach ($result as $row) {
                $p = new ParticipantsModel();
                $p->author_name = $row["full_name"];
                $p->role = $row["role"]; 
                $p->date_added = $row["date_added"];

                $participants[] = $p;
            }
        }
        $mysqli->close();
        return $participants;
    }

    public static function CountPapers() {
        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        $query = "SELECT count(*) as rowsNum from papers";
        $result = $mysqli->query($query);

        if ($result) 
        {            
            foreach ($result as $row) {
                $count = $row["rowsNum"];
            }
        }
        
        $mysqli->close();
        return $count;
    }

    public static function SearchPaper($value, $searchBy, $offset, $rowsPerPage) {
        $condition = "WHERE ".$searchBy." LIKE '%$value%' ";

        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        
        $query = 
        "
            SELECT paper_id, title, author_string_list, abstract, c.name, t.topic_name, u.username
            FROM papers p 
                JOIN conferences c ON p.conference_id = c.conference_id 
                JOIN users u ON p.user_id = u.user_id 
                JOIN topics t ON p.topic_id = t.topic_id    
        ".$condition." LIMIT ".$offset.", ".$rowsPerPage;

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
                <th>Detail</th>
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
                $paper_id = $row["paper_id"];
                
                $response = $response."
                    <tr>
                        <td>".$i."</td>
                        <td>".$title."</td>
                        <td>".$author_string_list."</td>
                        <td>".$abstract."</td>
                        <td>".$conference_name."</td>
                        <td>".$topic_name."</td>
                        <td>".$publisher."</td>
                        <td>
                            <a class='btn border-0' href='?action=paper-detail&paper_id=".$paper_id."'>
                                <i class='bi bi-chevron-right'></i>
                            </a>
                        </td>
                    </tr>
                ";

                $i++;
            }
        }
        $mysqli->close();

        $response = $response."</tbody>";
        return $response;
    }

    public static function AssignPaper($paper_id, $author_id, $author_string_list) {
        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        $update_query = 
        "
            UPDATE papers 
            SET author_string_list = '".$author_string_list."'
            WHERE paper_id = 
        ".$paper_id;
        $update_result = $mysqli->query($update_query);

        $insert_query = 
        "
            INSERT INTO participation VALUES (
                ".$author_id.", 
                ".$paper_id.", 
                'member', 
                '".date("Y-m-d H:i:s")."', 
                'show'
            )
        ";
        $insert_result = $mysqli->query($insert_query);

        $mysqli->close();
        if ($update_result && $insert_result) 
        {            
            return true;
        }
        
        return false;
    }

    public static function CreatePaper($title, $author_string_list, $abstract, $conference_id, $topic_id, $user_id) {
        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        $query = "
            INSERT INTO papers (title, author_string_list, abstract, conference_id, topic_id, user_id)
            VALUES(
                '".$title."',
                '".$author_string_list."',
                '".$abstract."',
                ".$conference_id.",
                ".$topic_id.",
                ".$user_id."
            )
        ";
        $result = $mysqli->query($query);
        $mysqli->close();
        return $result;
    }
}
?>
