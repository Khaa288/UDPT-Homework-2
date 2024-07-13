<?php
require_once("./config/dbconnect.php");
class ParticipantsModel {
    public $paper_name;
    public $author_name;
    public $role;
    public $date_added;
    public $status;

    function __construct() {
        $this->paper_name = "";
        $this->author_name = "";
        $this->role = "";
        $this->date_added = "";
        $this->status = "";
    }

    public static function AddParticipation($author_id, $paper_id, $role) {
        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        $query = "
            INSERT INTO participation 
            VALUES(
                ".$author_id.",
                ".$paper_id.",
                '".$role."',
                '".date("Y-m-d H:i:s")."',
                'show'
            )
        ";
        $result = $mysqli->query($query);
        $mysqli->close();
        return $result;
    }
}
?>