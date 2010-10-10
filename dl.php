<?php
    /* Download a playlist given a library.
     *      Basically the exact opposite of the parser
     *      I probably should combine the two, but quick
     *      and dirty, then finesse.
     *
     *      Need library id and how many songs to downlod (e.g., top 10, 20 ,etc)
     *      MUST MAKE INDEX OF song.votes
     */

if (isset($_POST["library_id"]) && isset($_POST["number"]))
    {
        require_once "general.php";
        require_once "connection.php";
        require_once "variables.php";
        
        $library_id = $_POST["library_id"];
        $criteria = array( "library_info.id" => $library_id );
        $fields = array("song.title" => 1
    }
?>
