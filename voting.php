<?php

/* This will be the voting file. That is, it handles requests for songs.

When someone votes a song, it will store all the relevant information of that vote: time, ip, library, song.

The voting will not be done directly with this file. Using ajax, we'll do it.

Required:
    lib         :library id
    song_id     :song id
Returns:
    0           :vote counted
    1           :error, didn't receive proper arguments
    2           :can't vote due to constraints
*/

if (isset($_POST['lib']) && isset($_POST['song_id']))
{
    /* Add a positive vote to the song in the library 
       One check:
            -one vote per song per ip
    */

    require_once "connection.php";

    $ip=$_SERVER['REMOTE_ADDR'];

}
else
{
    echo 1;
    exit;
}
?>
