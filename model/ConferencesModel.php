<?php
require_once("./config/dbconnect.php");
class ConferencesModel
{
    public $conference_id;
    public $name;
    public $abbreviation;
    public $rank;
    public $start_date;
    public $end_date;
    public $type;
    
    function __construct() {
        $this->conference_id = 0;
        $this->name = "";
        $this->abbreviation = "";
        $this->rank = "";
        $this->start_date = "";
        $this->end_date = "";
        $this->type = "";
    }

    public static function GetAllConferences()
    {
        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        $query = "SELECT * from conferences";

        $result = $mysqli->query($query);
        $conferences_list = array();

        if ($result) {
            foreach ($result as $row) {
                $c = new ConferencesModel();
                $c->abbreviation = $row["abbreviation"];
                $c->name = $row["name"];
                $c->conference_id = $row["conference_id"];
                $conferences_list[] = $c;
            }
        }
        $mysqli->close();
        return $conferences_list;
    }
}
?>