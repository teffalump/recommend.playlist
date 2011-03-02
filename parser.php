<?php

/* 
    Parser -- this will parse the playlist and put the info in the database
    Requires:
        library_id      :library id from POST
        playlist        :array info of file
    Returns:
        0       :songs inserted
        1       :error inserting song(s) into database
        2       :file too big
        3       :not xml file
*/

if (!empty($_FILES["playlist"]) && ($_FILES["playlist"]["error"] == 0 ) && isset($_POST["library_id"]))
    {
       /*
        XSPF parser.
            3 parts:
                -Check that uploaded file is not too big and it's an xml file
                -Parse xml file to extract song information
                -Insert info into database
       */
       ######### FILE TYPE AND SIZE #######
       require_once "variables.php";

       if ((filesize($_FILES["playlist"]["tmp_name"]) > MAX_PLAYLIST_SIZE))
            {
                echo 2;
                exit;
            }
       else
            {
                if ($finfo = finfo_open(FILEINFO_MIME_TYPE))
                    {
                        $mime = finfo_file($finfo, $_FILES["playlist"]["tmp_name"]);
                        finfo_close($finfo);
                        if ($mime !== "application/xml")
                            {
                                echo 3;
                                exit;
                            }
                    }
            }
       ######### END FILE TYPE AND SIZE #######
       ######### XSPF PARSER ########
       $xspf_paths = array(
            "track_Start" => array("PLAYLIST", "TRACKLIST", "TRACK"),
            "track_Artist" => array("PLAYLIST", "TRACKLIST", "TRACK", "CREATOR"),
            "track_Title" => array("PLAYLIST", "TRACKLIST", "TRACK", "TITLE"),
            "track_Album" => array("PLAYLIST", "TRACKLIST", "TRACK", "ALBUM"),
            "track_Location" => array("PLAYLIST", "TRACKLIST", "TRACK", "LOCATION"),
            "track_Identifier" => array("PLAYLIST", "TRACKLIST", "TRACK", "IDENTIFIER")
            );

       $track_array = array();
       $id = 0;

       function startTag($parser, $tag)
            {
                global $current_tag;
                $current_tag[] = $tag;
            }

       function endTag($parser, $tag)
            {
                global $current_tag, $id;
                $current_tag = array_slice($current_tag, 0, -1);
                if ($tag == "TRACK")
                    {
                        $id++;
                    }
            }

       function contents($parser, $data)
            {
                global $current_tag, $track_array, $id, $xspf_paths;
                switch ($current_tag)
                    {
                        case $xspf_paths["track_Location"]:
                            $track_array[$id]["location"] = $data;
                            break;

                        case $sxpf_pathsp["track_Identifier"];
                            $track_array[$id]["identifier"] = $data;
                            break;

                        case $xspf_paths["track_Title"]:
                            $track_array[$id]["title"] = $data;
                            break;

                        case $xspf_paths["track_Artist"]:
                            $track_array[$id]["artist"] = $data;
                            break;

                        case $xspf_paths["track_Album"]:
                            $track_array[$id]["album"] = $data;
                            break;
                     }
             }
        $xml_parser = xml_parser_create();
        xml_set_element_handler($xml_parser, "startTag", "endTag");
        xml_set_character_data_handler($xml_parser, "contents");

        $file = @fopen($_FILES["playlist"]["tmp_name"], "r");
        if ($file)
            {
                while ($line = fgets($file, 4096))
                    {
                          if  (!(xml_parse($xml_parser, $line)))
                                {
                                     die("Error on line " . xml_get_current_line_number($xml_parser));
                                }
                    }
            }
        else
            {
                die("Failed to open file");
            }
        xml_parser_free($xml_parser);
        fclose($file);
        unlink($_FILES["playlist"]["tmp_name"]);
        unset($_FILES["playlist"]);
      ####### END XSPF PARSER #########
      return $track_array;
     }
?>
