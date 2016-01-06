<?php
    
    // Takes in fueltop database codes and mileages to each fuelstop, from origin
    // Calculates optimal fuelstops
    
    require(__DIR__ . "/../includes/config.php");
    
    $settings = [];
    
    // ensure proper usage
    if (empty($_POST["analyzethis"]))
    {
        http_response_code(400);
        print("uh oh");
        exit;
    }
    
        $id = $_SESSION["id"];
        $cols = query("SELECT * FROM users WHERE id = $id");
        foreach ($cols as $col)
        {
            $settings[] = [
                "eighths" => $col["fuel_level"],
                "capacity" => $col["tank"],
                "mpg" => $col["mpg"]
            ];
        }
    
    $ajarray = $_POST["analyzethis"];
    
    $num = (count($ajarray) - 1) / 2;
    $codearr = array_slice($ajarray, 0, $num);
    $distarrs = array_slice($ajarray, $num);
    
    $distarrslen = count($distarrs);
    $distarr = [];
    for ($i = 0; $i < $distarrslen; $i++)
    {
        $distarr[$i] = floatval(preg_replace("/[,]/", "", (strstr($distarrs[$i], " mi", $before_needle = true))));
    }
    
    $statetaxes = array (
        "AL" => 0.19, "AR" => 0.225, "AZ" => 0.26, "CA" => 0.45, "CO" => 0.205, "CT" => 0.503, "DE" => 0.22, "FL" => 0.3367,
        "GA" => 0.29, "IA" => 0.325, "ID" => 0.32, "IL" => 0.427, "IN" => 0.16, "KS" => 0.26, "KY" => 0.216, "LA" => 0.2,
        "MA" => 0.24, "MD" => 0.3285, "ME" => 0.312, "MI" => 0.282, "MN" => 0.285, "MO" => 0.17, "MS" => 0.18, "MT" => 0.2775,
        "NC" => 0.36, "ND" => 0.23, "NE" => 0.261, "NV" => 0.27, "NH" => 0.222, "NJ" => 0.175, "NM" => 0.21, "NY" => 0.4005,
        "OH" => 0.28, "OK" => 0.13, "OR" => 0.00, "PA" => 0.642, "RI" => 0.33, "SC" => 0.16, "SD" => 0.28, "TN" => 0.17,
        "TX" => 0.20, "UT" => 0.245, "VA" => 0.202, "VT" => 0.31, "WA" => 0.445, "WI" => 0.329, "WV" => 0.346, "WY" => 0.24 );

    $codelen = count($codearr);
    $unsorted = [];

    for ($codeindex = 0; $codeindex < $codelen; $codeindex++)
    {
        $rows = query("SELECT * FROM fuelstops WHERE code = ?", $codearr[$codeindex]);
        foreach ($rows as $row)
        {
            $unsorted[] = [
                "code" => $row["code"],
                "store_id" => $row["store_id"],
                "brand" => $row["brand"],
                "latitude" => $row["latitude"],
                "longitude" => $row["longitude"],
                "price" => $row["price"] - $statetaxes[$row["state"]],
                "location" => $row["location"],
                "state" => $row["state"],
                "mileage" => $distarr[$codeindex]
            ];    
        }
    }
    
    function array_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();
        
        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                }
                else {
                    $sortable_array[$k] = $v;
                }
            }
            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }
            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }
        return $new_array;
    }
    $firstsorted = array_sort($unsorted, 'price', SORT_ASC);
    $sorted = [];
    foreach($firstsorted as $member) {
        $sorted[] = $member;
    }
    
    $eighths = $settings[0]["eighths"];
    $level = $eighths * 0.125;
    $capacity = $settings[0]["capacity"];
    $mpg = $settings[0]["mpg"];
    $milestodest = $distarr[count($distarr) - 1];
    $selectstops = [];
    
    /**
     *  Returns array of selected stops to optimize fuel price/gallons.
     *  Arguments are: array of stop codes on route, array of distances to each stop,
     *  current fuel level, and tank capacity
     */
    function optimalstops($stop_array, $distances, $fuellevel, $capacity, $mpg, $milestodestination, $selectstops)
    {
        static $selectstops = [];
        $miles = $distances[count($distances) - 1];
        $milescango = $fuellevel * $capacity * $mpg;
        $arrleng = count($stop_array);
        for ($si = 0; $si < $arrleng; $si++)
        {
            if (($milescango - $milestodestination) >= 120)
            {
                $selectstops[count($selectstops)] = "No more fuel needed";
                return $selectstops;
            }
            else
            {
                // account for mileage traveled to each fueling location
                $selstopcount = count($selectstops);
                if ($selstopcount > 0)
                {
                    $laststopat = $selectstops[$selstopcount - 1]["mileage"];
                }
                else
                {
                    $laststopat = 0.0;
                }
                // If i can get to the cheapest, add to new array and update fuel level, milestodest, and milescango
                if ($milescango > ($stop_array[$si]["mileage"] - $laststopat))
                {
                    $selectstops[$selstopcount] = $stop_array[$si];
                    $milestraveled = $stop_array[$si]["mileage"];
                    
                    // partial fill-ups require more analysis (an option to add later)
                    $fuel = 1.00;
                    $milesleft = $miles - $milestraveled;
                    $milescango = $fuel * $capacity * $mpg;
                    $lengthofarray = count($stop_array);
                    for ($index = $si; $index < ($lengthofarray - 1); $index++)
                    {
                        $stop_array[$si] = $stop_array[$si + 1];
                    }
                    unset($stop_array[$lengthofarray - 1]);
                    $stop_array2 = $stop_array;
                    $stop_array3 = [];
                    foreach ($stop_array2 as $member)
                    {
                        if ($member["mileage"] > $milestraveled)
                        {
                            $stop_array3[] = $member;
                        }
                    }
                    foreach ($stop_array3 as $stop)
                    {
                        // Just make sure we're not filling up every ten miles over increasing price areas
                        $whatsleft = count($stop_array3);
                        if (($whatsleft > 1) && (($stop["mileage"] - $milestraveled) > 300.0))
                        {
                            $stop_array4[] = $stop;
                        }
                    }
                    break;
                }
                else
                {
                    if ($si == ($arrleng - 1))
                    {
                        $selectstops = "Call roadside assistance";
                    }
                }
            }
        }
        if (($milesleft + 120) > $milescango)
        {
            optimalstops($stop_array4, $distances, $fuel, $capacity, $mpg, $milesleft, $selectstops);
        }
        if (($milesleft + 120) < $milescango)
        {
            return $selectstops;
        }
    return $selectstops;
    }

    $selectedstops = optimalstops($sorted, $distarr, $level, $capacity, $mpg, $milestodest, $selectstops);
    
    header("Content-type: application/json");
    print(json_encode($selectedstops, JSON_PRETTY_PRINT));
    
?>
