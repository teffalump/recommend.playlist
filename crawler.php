<?php
    /* I'm not sure exactly how I'll do the crawler
     * but this page will be a start, at least
     *
     * I'm thinking that I'll update the hottest songs
     * every day and save them to a database
     * in their own collection
     */

//Billboard's data is easily extracted from the chart page -- since it is all retrieved, just not displayed
//      Also, it is already formatted in a JSON object - so I'll just need to spruce up the text string
//      to make it available to "json_decode" in php
//Shazam's also pretty obvious
$websites = array(
                "billboard" => array(
                                    "url" => "http://www.billboard.com/charts/hot-100",
                                    "data" => "html | body.charts | div#wrapper | div#load-container | div#content-container | div#content-wrapper | dive#charts-data.eval-script | script"),
                "shazam" => array(
                                    "url" => "http://www.shazam.com/music/web/tagchart",
                                    "title_tag" => "HTML | BODY | DIV#GRID | DIV #MAIN | TABLE | tbody | tr | td | div#idchart | table | tbody | tr.funky | td.funky | a.blue | strong",
                                    "artist_tag" => "HTML | BODY | DIV#GRID | DIV #MAIN | TABLE | tbody | tr | td | div#idchart | table | tbody | tr.funky | td.funky | a.grey | span");
?>
