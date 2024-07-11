<?php
function connect()
{	
    require('./config.inc.php');
    $mysqli = new mysqli("localhost", $db_user, $db_password, $db_name );
    if ($mysqli->connect_errno )
    {
        die( "Couldn't connect to MySQL" );
    }
    return $mysqli;
}
?>
