<?php
      ####### FILTER PLAYLIST W/ HOT SONGS #######
      /* I need to to the following:
       *    -calculate similarities from hot songs to playlist
       *    -pick ones that are close
       *    -output those correct songs
       */

      $hot_songs = include("crawler.php");
      $track_array = include("parser.php");
      #$id = 0;
      foreach ($hot_songs as $song)
        {
            #print("\nSong #" . $id . " = " . $song["title"] . ":" . $song["artist"] .  "\n");
            $b=strlen($song["title"]);
            $d=strlen($song["artist"]);
            $i = 0;
            foreach ($track_array as $track)
                {
                    #print("\n\t\tEntering " . $track["title"] . ":" . $track["artist"] . "\n");
                    $a=strlen($track["title"]);
                    $c=strlen($track["artist"]);
                    if ($a>$b)
                        {
                            $normal = $song["title"];
                            $trunc = substr($track["title"], 0, $b);
                        }
                    elseif ($a<$b)
                        {
                            $trunc = substr($song["title"], 0, $a);
                            $normal = $track["title"];
                        }
                    else
                        {
                            $normal = $song["title"];
                            $trunc = $track["title"];
                        }
                    if ($c>$d)
                        {
                            $normal_a = $song["artist"];
                            $trunc_a = substr($track["artist"], 0, $d);
                        }
                    elseif ($c<$d)
                        {
                            $trunc_a = substr($song["artist"], 0, $c);
                            $normal_a = $track["artist"];
                        }
                    else
                        {
                            $normal_a= $song["artist"];
                            $trunc_a = $track["artist"];
                        }
                    similar_text($trunc,$normal, $sim);
                    similar_text($trunc_a,$normal_a,$sim_a);
                    if ($sim > 75 && $sim_a > 75)
                        {
                            #print("\t\t\tMatch: " . $track["title"] . " = " . $song["title"] . "\n");
                            #print_r($track_array);
                            #print("\t\t\ti = " . $i);
                            $good[] = $track;
                            unset($track_array[$i]);
                            $track_array = array_values($track_array);
                            break;
                        }
                $i+=1;
                }
            #$id++;
        }
      return $good;
?>
