<?php
 ####### START DATABASE INSERT #######>
   /* Put songs into right library
   * --Check for: 
   *       - song doesn't already exist
   *           --> search for same id and lib_id
   */
   require_once "connection.php";
   require_once "general.php";
   require_once "variables.php";
                                                                                          
   $error_code=0;
                                                                                          
   $library_id = $_POST["library_id"];
   $criteria = array("lib_id" => $library_id);
   foreach ($track_array as $i)
       {
           $criteria["id"] = $song_info["id"];
           $song_info = array(
                   "metadata" => array(
                       "title" => $i["title"], 
                       "album" => $i["album"], 
                       "artist" => $i["artist"], 
                       "location" => $i["location"],
                       "identifier" => $i["identifier"]
                       ),
                   "id" => substr(sha1($i["title"] . $i["album"] . $i["artist"]), 0, 10),
                   "lib_id" => $library_id
                   );
           $obj = $db->SONG->findOne($criteria);
           if (is_null($obj))
               {
                   $db->SONG->save($song_info, array("safe" => TRUE));
                   if (!is_null(get_value(lastError(), "err")))
                       {
                           $error_code = 1;
                       }
               }
       }
   $connection->close();
   ####### END DATABASE INSERT #######
                                                                                          
   echo $error_code;
   exit;
 
?>
