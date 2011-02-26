<?php
    /* I'm not sure exactly how I'll do the crawler
     * but this page will be a start, at least
     *
     * I'm thinking that I'll update the hottest songs
     * every day and save them to a database
     * in their own collection
     */

//Billboard's data is easily extracted from the chart page -- just iterate through all the pages
//      Also, it is possible to extract it from the json
//      However, the info is truncated at some legnth
//      hence, it isn't the best...though it is already formatted in a JSON object
//      so to do that way = just need to spruce up the text string
//      to make it available to "json_decode" in php
//Shazam's also pretty obvious
$websites = array(
                "billboard" => array(
                                    "url" => "http://www.billboard.com/charts/hot-100",
                                    "extensions" => array(
                                                        "1",
                                                        "11",
                                                        "21",
                                                        "31",
                                                        "41",
                                                        "51",
                                                        "61",
                                                        "71",
                                                        "81",
                                                        "91"),
                                    #"data" => '//*[@id="charts-data"]/script',
                                    "title" => '//*[@class="ptitle"]',
                                    "artist" => '//*[@class="partist"]'),
                "shazam" => array(
                                    "url" => "http://www.shazam.com/music/web/tagchart",
                                    "data" => '//tr[@class="funky"]/td[@class="funky"]/a'));

########## BILLBOARD TOP 100 ################
//Get Billboard's data
$doc = new DOMDocument();
$doc->validateOnParse=true;
foreach ($websites["billboard"]["extensions"] as $page)
    {
        @$doc->loadHTML(file_get_contents($websites["billboard"]["url"] . "?begin=" . $page));
        $xpath = new DOMXPath($doc);
        
        #$entries = $xpath->query($websites["billboard"]["data"]);
        #$dump = $entries->item(0)->nodeValue;

        $artists= $xpath->query($websites["billboard"]["artist"]);
        foreach ($artists as $artist)
            {
                $artists_dump[]=$artist->nodeValue;
            }
        $titles = $xpath->query($websites["billboard"]["title"]);
        foreach ($titles as $title)
            {
                $titles_dump[]=$title->nodeValue;
            }
    }

//I wish there were a function like python's zip, but I don't feel like I need to code it...yet.
for ($i=0;$i<sizeof($artists_dump);$i++)
    {
        $billboard[]=array("title"=>$titles_dump[$i],"artist"=>$artists_dump[$i]);
    }

######### END BILLBOARD ########
#$a = substr(strstr($dump,'{'), 0,-strlen(strrchr($dump, ';')));
#$b_json=json_decode($a,true);
#for($i=0;$i<sizeof($b_json["items"]);$i++)
#    {
#        #print_r($b_json["items"][$i]);
#        $billboard_songs[$i]["title"] = $b_json["items"][$i]["title"];
#        $billboard_songs[$i]["artist"] = $b_json["items"][$i]["artist"];
#    }
#unset($b_json);

############# SHAZAM TOP 20 TAG #################
// Now Shazam's turn
$doc = new DOMDocument();
$doc->validateOnParse=true;
@$doc->loadHTML(file_get_contents($websites["shazam"]["url"]));
$xpath = new DOMXPath($doc);
$entries = $xpath->query($websites["shazam"]["data"]);
foreach ($entries as $entry)
    {
        $songs[]=$entry->attributes->item(2)->value;
    }
for ($i=0;$i<sizeof($songs);$i+=2)
    {
        $shazam[]=array("title"=>$songs[$i],"artist"=>$songs[$i+1]);
    }
############## END SHAZAM ###############

//Well, I'll merge the arrays and delete duplicates
$ownage = array_map("unserialize", array_unique(array_map("serialize", array_merge($billboard, $shazam))));

?>
