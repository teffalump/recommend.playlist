<?php

/* 
    Parser -- this will parse the playlist and put the info in the database
*/

if (!empty($_FILES["playlist"]) && ($_FILES["playlist"]["error"] == 0 ))
    {
       /*
        XSPF parser done.

        Still need to do size and type check on upload file.
       */
       ######### XSPF PARSER ########
       $xspf_paths = array(
            "track_Start" => array("PLAYLIST", "TRACKLIST", "TRACK"),
            "track_Artist" => array("PLAYLIST", "TRACKLIST", "TRACK", "CREATOR"),
            "track_Title" => array("PLAYLIST", "TRACKLIST", "TRACK", "TITLE"),
            "track_Album" => array("PLAYLIST", "TRACKLIST", "TRACK", "ALBUM"),
            "track_Location" => array("PLAYLIST", "TRACKLIST", "TRACK", "LOCATION")
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

        xml_parser_free($xml_parser);
        fclose($fp);

       ####### END XSPF PARSER #########
     }
?>
