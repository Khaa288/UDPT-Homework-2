<?php
require_once("./config/dbconnect.php");
class AuthorsModel
{
    public $fullname;
    public $image_path;
    public $bio;
    public $interests;

    function __construct()
    {
        $this->fullname = "";
        $this->image_path = "";
        $this->bio = "";
        $this->interests = array();
    }

    public static function GetAuthor($user_id) {
        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");
        $query = "SELECT * FROM authors WHERE user_id = ".$user_id; 
        $result = $mysqli->query($query);

        if($result) 
        {
            foreach($result as $row) {
                $author = new AuthorsModel();
                $author->fullname = $row["full_name"];
                $author->image_path = $row["image_path"];

                $profile_json_text = json_decode($row["profile_json_text"]);
                $author->bio = $profile_json_text->bio;
                
                foreach($profile_json_text->interests as $interests) {
                    $author->interests[] = $interests;
                }
            }
            
            $mysqli->close();
            return $author;
        }

        $mysqli->close();
        return null;
    } 

    public static function UpdateAuthor($user_id, $fullname, $file, $bio, $interests) {
        // Process before update
        if ($file['error'] === UPLOAD_ERR_OK) {
            $update_image_query = "image_path = '/images/".$file['name']."',";
            move_uploaded_file($file['tmp_name'], "./css/images/" . $file['name']);
        }
        else {
            $update_image_query = "";
        }

        foreach($interests as $interest => $val) {
            if (empty($val)) {
                unset($interests[$interest]);
            }
        }

        $profile_json_text = json_encode(
            array(
                "bio" => $bio,
                "interests" => $interests
            )
        );

        $mysqli = connect();
        $mysqli->query("SET NAMES utf8");

        $query = "UPDATE authors 
                  SET full_name = '".$fullname."', 
                      ".$update_image_query."
                      profile_json_text = '".$profile_json_text."' 
                  WHERE user_id = ".$user_id."";
        $result = $mysqli->query($query);

        $mysqli->close();
        return $result;
    } 
}
?>