<?php
    /* This file will return songs given a library and some search criteria
     * I need to decide how I'll display it, but I'll do that later.
     *
    */
if (isset($_POST["library_id"]) && isset($_POST["song_search"]))
    {
        require_once "connection.php";
        require_once "variables.php";

        $library_id = $_POST["library_id"];
        $search = "/" . $_POST["song_search"] . "/i";
        $criteria = array("lib_id" => $library_id,
                          '$or' => array(
                                        "title" => $search,
                                        "artist" => $search,
                                        "album" => $search
                                        ));
        $fields = array("title" => 1,
                        "artist" => 1,
                        "album" => 1,
                        "_id" => 0);
        
        $cursor = $db->SONG->find($criteria, $fields);
        foreach ( $cursor as $songs )
            {
                foreach ( $songs as $song )
                    {
                        $data[]=array("title" => $song["title"],
                                      "artist" => $song["artist"],
                                      "album" => $song["artist"]);
                    }
            }
        $connection->close();
        echo json_encode($data);
    }
?>
