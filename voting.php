<?php

/* This will be the voting file. That is, it handles voting songs up or down.

When someone votes a song, it will store all the relevant information of that vote: time, ip, library, song.

The voting will not be done directly with this file. Using ajax, we'll do it.

Required:
    lib         :library id
    song_id     :song id
Returns:
    0           :vote counted
    1           :error, didn't receive proper arguments
*/

if (isset($_POST['lib']) && isset($_POST['song_id']))
{
    /* Add a positive vote to the song in the library */

    require_once "connection.php";


}
else
{
    echo 1;
    exit;
}
?>
