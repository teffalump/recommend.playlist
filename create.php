<?php
    /* This file will create a playlist page. Gotta link this with parser, too.
     *
     *  so, provides an expiration time, passphrase
     *
     *  Must assert uniqueness of id
     *
    */

if (isset($_POST["passphrase"]) && isset($_POST["expires"]))
    {
        require_once "general.php";
        require_once "connection.php";
        require_once "variables.php";

        $expire = $_POST["expires"];
        $hash = implode(generateHash($_POST["passphrase"]));
        if (isset($_POST["title"]))
            {
                $title = $_POST["title"];
            }
        else
            {
                $title = NULL; 
            }
            
        if (isset($_POST["description"]))
            {
                $description = $_POST["description"];
            }
        else
            {
                $description = NULL;
            }
        #$ip = $_SERVER["REMOTE_ADDR"];              //I don't want spam creations, but I don't like to save ips...uggh
        $data = array(
                            "id" => substr(md5(uniqid(mt_rand(), true)), 0, 25),
                            "expires" => $expire,
                            "title" => $title,
                            "password_hash" => $hash,
                            "description"   => $description
                    );
        $db->LIBRARY->save($data);
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
?>
