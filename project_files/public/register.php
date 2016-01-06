<?php

    //configuration
    require("../includes/config.php");
    
    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("register_form.php", ["title" => "Register"]);
    }
    
    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["username"]) || empty($_POST["password"]))
        {
            apologize("You must provide a username and password, silly.");
        }
        if ($_POST["password"] !== $_POST["confirmation"])
        {
            apologize("Password and Confirmation do not match");
        }
        $result = query("INSERT INTO users (username, hash) VALUES (?, ?)", $_POST["username"], crypt($_POST["password"]));
        if ($result !== false)
        {
            $rows = query("SELECT LAST_INSERT_ID() AS id");
            $id = $rows[0]["id"];
            $_SESSION["id"] = $id;
            redirect("index.php");
        }
        else
        {
            apologize("Error: username may already exist");
        }
    }
    
?>    
