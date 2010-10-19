<?php
    /* Download a playlist given a library.
     *      Basically the exact opposite of the parser
     *      I probably should combine the two, but quick
     *      and dirty, then finesse. Also, I should see if I want
     *      to make it so only the creator can download the playlist
     *      with the "metadata.location" field -- or at all?.
     *
     *      Need library id and how many songs to downlod (e.g., top 10, 20 ,etc)
     *      MUST MAKE INDEX OF votes 
     */

if (isset($_POST["library_id"]) && isset($_POST["number"]))
    {
        require_once "general.php";
        require_once "connection.php";
        require_once "variables.php";
        
        ######## RETRIEVE SONGS #######
        $library_id = $_POST["library_id"];
        $limit = $_POST["number"];
        $criteria = array( "lib_id" => $library_id,
                           "votes" => array( '$gt' => 0 )
                            );
        $fields = array("metadata" => 1,
                        "_id" => 0);

        //Return the songs' metadata sorted by vote total (must have at least one vote) and limited to the top 10,20,30, etc
        //This returns a cursor, the query has not actually been processed
        $cursor = $db->SONG->find( $criteria, $fields )->sort(array( "votes" => -1))->limit($limit);
        ####### END RETRIEVE SONGS ###########
        ####### OUTPUT FILE TO DOWNLOAD ########
        ob_start()
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<playlist version=\"1\" xmlns=\"http://xspf.org/ns/0/\">\n";
        echo "\t<tracklist>\n";

        //Now the query has been processed
        foreach ( $cursor as $songs )
            {
                foreach ($songs as $song)
                    {
                        echo "\t\t<track>\n";
                        echo "\t\t\t<location>{$song['location']}\n";
                        echo "\t\t\t<identifier>{$song['identifier']}\n";
                        echo "\t\t\t<title>{$song['title']}\n";
                        echo "\t\t\t<creator>{$song['artist']}\n";
                        echo "\t\t\t<album>{$song['album']}\n";
                        echo "\t\t</track>\n";
                    }
            }
        echo "\t</tracklist>\n";
        echo "</playlist>";

        $content = ob_get_contents();
        ob_end_clean();
        $connection->close();


        // We'll be outputting an xspf file
        header('Expires: 0');
        header('Content-type: application/xml');
        header('Content-length: ' . strlen($content));
        header('Content-Disposition: attachment; filename="top_{$limit}_playlist.xspf"');
        echo $content;
        ##### END OUTPUT FILE TO DOWNLOAD #####

        exit;
    }
?>
