<?php

    // configuration
    require("../includes/config.php");
    
    $id = $_SESSION["id"];
    $rows = query("SELECT * FROM users WHERE id = $id");
    $settings = [];
    foreach ($rows as $row)
    {
        $settings[] = [
                "Useable Tank Capacity" => $row["tank"],
                "MPG" => $row["mpg"],
                "Current Fuel Level" => $row["fuel_level"]
            ];
    }
  
    render("settings.php", ["settings" => $settings, "title" => "Home"]);
?>
