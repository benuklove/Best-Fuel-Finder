<form action="update.php" method="post">
<div id="settings">
    <table class="table table-striped">
        <thead>
            <tr>
            <th>Useable Fuel Tank Capacity</th><th>Miles Per Gallon</th><th>Current Fuel Level<br>("1" = 1/8 of a tank)</th>
            </tr>
        </thead>
            <tbody>
                <?php foreach ($settings as $setting): ?>
                <tr>
                    <td><?= $setting["Useable Tank Capacity"] ?></td>
                    <td><?= $setting["MPG"] ?></td>
                    <td><?= $setting["Current Fuel Level"] ?></td>
                <?php endforeach ?>
                </tr>
                <div class="form-group">
                    <tr>
                        <td><input class="form-control" name="capacity" placeholder="New" type="number" min="40" max="500"/></td>
                        <td><input class="form-control" name="mpg" placeholder="New" type="number" step="0.01" min="3.00" max="50.00"/></td>
                        <td><input class="form-control" name="fuel-level" placeholder="New" type="number" min="1" max="8"/></td>
                        <td><button type="submit" class="btn btn-default">Update</button></td>
                    </tr>    
                </div>
            </tbody>
    </table>
</form>
<div id="sometext">If the numbers above are correct...</div>
<div id="gotomap"><strong><a href="map.html">Go to the map!</a></strong></div>
</div>
<div>
    <strong><a href="logout.php">Log Out</a></strong>
</div>
