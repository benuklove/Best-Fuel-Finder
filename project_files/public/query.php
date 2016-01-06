<?php
    
    // Takes in an array of lat/lngs and gives back json of all fuelstops on route
    
    require(__DIR__ . "/../includes/config.php");
    
    // ensure proper usage
    if (empty($_POST["coords"]))
    {
        http_response_code(400);
        print("darn");
        exit;
    }
    
    $jsarray = $_POST["coords"];

    // split array into arrays of latitude and longitude
    $num = count($jsarray) / 2;
    $lats = array_slice($jsarray, 0, $num);
    $lngs = array_slice($jsarray, $num);

    // iterate over traveled path lats and lngs
    for ($i = 0; $i < $num; $i++)
    {
        // get all fuelstops where latitude and longitude are within approximately 2 miles of traveled path
        $rows = query("SELECT * FROM fuelstops WHERE ((abs(latitude - $lats[$i]) < .05) && (abs(longitude - $lngs[$i]) < (((-.008638 * $lats[$i] ^ 2) - .079 * $lats[$i] + 69.995)/800)))");
        $duplicatestops[$i] = $rows;
    }
    
    // remove empty elements and duplicate fuelstops
    $nodupes = [];
    $duplicatestops = array_values(array_filter($duplicatestops));
    $size = count($duplicatestops);
    for ($j = 0; $j < $size; $j++)
    {
        if (in_array($duplicatestops[$j], $nodupes) === FALSE)
        {
            $nodupes[$j] = $duplicatestops[$j];
        }
    }
    $nodupes = array_values($nodupes);
    
    $allStopsonRoute = [];
    foreach($nodupes as $item) {
        $allStopsonRoute[] = [
            "code" => $item[0]["code"],
            "store_id" => $item[0]["store_id"],
            "brand" => $item[0]["brand"],
            "latitude" => $item[0]["latitude"],
            "longitude" => $item[0]["longitude"],
            "price" => $item[0]["price"],
            "location" => $item[0]["location"],
        ];
    }

// output places as JSON (pretty-printed for debugging convenience)
    header("Content-type: application/json");
    print(json_encode($allStopsonRoute, JSON_PRETTY_PRINT));

?>
