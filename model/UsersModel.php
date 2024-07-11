<?php
require_once("./config/dbconnect.php");
class UsersModel
{
    public $username;
    public $role;
    public $user_id;

    function __construct()
    {
        $this->username = "";
        $this->role = "";
        $this->user_id = 0;
    }

    public static function GetUser($username, $password) {
        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        $query = "SELECT * FROM users WHERE username = '".$username."' and password = '".$password."'"; 
        $result = $mysqli->query($query);

        if($result) 
        {
            foreach($result as $row) {
                $user = new UsersModel();
                $user->username = $row["username"];
                $user->role = $row["user_type"];
                $user->user_id = $row["user_id"];
            }
            
            $mysqli->close();
            return $user;
        }

        $mysqli->close();
        return null;
    } 
}
?>