<?php

    //configuration
    require("../includes/config.php");
    
    // user reached page via POST (as by submitting a form via POST)
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $id = $_SESSION["id"];
        
        if (!empty($_POST["capacity"]))
        {
            $result = query("UPDATE users SET tank = ? WHERE id = $id", $_POST["capacity"]);
        }
        if ((!empty($_POST["capacity"])) && ($result === false))
        {
                apologize("Error communicating with database.  Try updating again.");
        }
        
        if (!empty($_POST["mpg"]))
        {
            $result1 = query("UPDATE users SET mpg = ? WHERE id = $id", $_POST["mpg"]);
        }
        if ((!empty($_POST["mpg"])) && ($result1 === false))
        {
                apologize("Error communicating with database.  Try updating again.");
        }
        
        if (!empty($_POST["fuel-level"]))
        {
            $result2 = query("UPDATE users SET fuel_level = ? WHERE id = $id", $_POST["fuel-level"]);
        }
        if ((!empty($_POST["fuel-level"])) && ($result2 === false))
        {
                apologize("Error communicating with database.  Try updating again.");
        }
        else
        {
            redirect("/");
        }
    }
    
?>    
