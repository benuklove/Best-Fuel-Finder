<div id="home">
    <table class="table table-striped">
        <thead><tr>
            <th>Useable Fuel Tank Capacity</th><th>Miles Per Gallon</th><th>Current Fuel Level<br>("1" = 1/8 of a tank)</th>

        </tr></thead>
        <tbody>
        <?php foreach ($settings as $setting): ?>
        <tr>
        <td><?= $setting["Useable Tank Capacity"] ?></td>
        <td><?= $setting["MPG"] ?></td>
        <td><?= $setting["Current Fuel Level"] ?></td>
        <td href="update.php">Update Numbers</td>
        </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
<div>
    <strong><a href="logout.php">Log Out</a></strong>
</div>
