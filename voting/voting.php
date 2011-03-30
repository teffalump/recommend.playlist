<?php

/* This will be the voting file. That is, it handles recommendations for songs.

Function:
    
    -When someone votes a song, it will store the vote.

The voting will not be done directly with this file. Using ajax, I'll do it.

Required:
    lib         :library id -- a passphrase or some other identifier, haven't decided which
    song_id     :song id -- again, some sort of identifier, haven't decided how
Returns:
    0           :vote counted
    1           :error, database update error
    2           :can't vote due to constraints
    3           :parameters not set
*/

if (isset($_POST['lib']) && isset($_POST['song_id']))
{
    /* Add a positive vote to the song in the library 
       One check:
            -one vote per song per ip
    */

    require_once "connection.php";
    require_once "variables.php";
    require_once "general.php";

    $ip=$_SERVER['REMOTE_ADDR'];
    $lib = $_POST['lib'];
    $song_id = $_POST['song_id'];

    $criteria = array("lib_id" => $lib_id, "id" => $song_id, "ip_addr" => $ip);
    $obj = $db->SONG->findOne($criteria);
    if (is_null($obj))
    {
        //Add a vote for that song and increment total number of votes
        unset($criteria["ip_addr"]);
        $change = array("$addToSet" => 
                            array("ip_addr" => $ip ) 
                        "$inc" => 
                            array("votes" => 1)
                                );
        $db->SONG->update($criteria, $change, array("safe"=>true));
        
        //Check if ip was added = successful vote
        if (is_null(get_value(lastError(), "err")))
        {
            echo 0;
            exit;
        }
        else
        {
            echo 1;
            exit;
        }
    }
    else
    {
        echo 2;
        exit;
    }
}
else
{
    echo 3;
    exit;
}
?>
