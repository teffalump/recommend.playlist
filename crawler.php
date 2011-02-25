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
                                    "data" => '//*[@id="charts-data"]/script'),
                "shazam" => array(
                                    "url" => "http://www.shazam.com/music/web/tagchart",
                                    "data" => '//tr[@class="funky"]/td[@class="funky"]/a'));

//Get Billboard's data info in json object
$doc = new DOMDocument();
$doc->validateOnParse=true;
@$doc->loadHTML(file_get_contents($websites["billboard"]["url"]));
$xpath = new DOMXPath($doc);
$entries = $xpath->query($websites["billboard"]["data"]);
$dump = $entries->item(0)->nodeValue;
$a = substr(strstr($dump,'{'), 0,-strlen(strrchr($dump, ';')));
$b_json=json_decode($a,true);
for($i=0;$i<sizeof($b_json["items"]);$i++)
    {
        $billboard_songs[$i]["title"] = $b_json["items"][$i]["title"];
        $billboard_songs[$i]["artist"] = $b_json["items"][$i]["artist"];
    }
unset($b_json);
// Now in an array
// Now do something with the data..

// Now Shazam's turn
$doc = new DOMDocument();
$doc->validateOnParse=true;
@$doc->loadHTML(file_get_contents($websites["shazam"]["url"]));
$xpath = new DOMXPath($doc);
$entries = $xpath->query($websites["shazam"]["data"]);
$songs = array();
foreach ($entries as $entry)
    {
        $songs[]=$entry->attributes->item(2)->value;
    }
$shazam=array();
for ($i=0;$i<sizeof($songs);$i+=2)
    {
        $shazam[]=array("title"=>$songs[$i],"artist"=>$songs[$i+1]);
    }
//Now in an array
//Now do something with the data..
//Well, I'll merge the arrays and delete duplicates
$ownage = array_map("unserialize", array_unique(array_map("serialize", array_merge($billboard_songs, $shazam))));
?>
