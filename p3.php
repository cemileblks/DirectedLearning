<?php
session_start();
include 'redir.php';
require_once 'login.php';

echo <<< _HEAD1
<html>
<body>
_HEAD1;

include 'menuf.php';

// Column names in the database and friendly names
$dbfs = array("natm", "ncar", "nnit", "noxy", "nsul", "ncycl", "nhdon", "nhacc", "nrotb", "mw", "TPSA", "XLogP");
$nms = array("n atoms", "n carbons", "n nitrogens", "n oxygens", "n sulphurs", "n cycles", "n H donors", "n H acceptors", "n rot bonds", "mol wt", "TPSA", "XLogP");

echo <<< _MAIN1
<pre>
This is the Statistics Page
</pre>
_MAIN1;

// Handle form submission
if (isset($_POST['tgval'])) {
    $tgval = $_POST['tgval'];
    $chosen = 0;

    // Find the index of the selected field
    for ($j = 0; $j < sizeof($dbfs); ++$j) {
        if ($dbfs[$j] == $tgval) {
            $chosen = $j;
            break;
        }
    }

    // Output selected field
    printf("Statistics for %s (%s)<br />\n", htmlspecialchars($dbfs[$chosen]), htmlspecialchars($nms[$chosen]));

    // Connect with PDO
    try {
        $pdo = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the query (use safe column name)
        $column = $dbfs[$chosen];
        $query = "SELECT AVG($column) AS avgval, STD($column) AS stdval FROM Compounds";
        $stmt = $pdo->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            printf("Average: %.3f &nbsp;&nbsp; Standard Deviation: %.3f <br />", $row['avgval'], $row['stdval']);
        } else {
            echo "No data returned.";
        }

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

// Show the form with radio buttons
echo '<form action="p3.php" method="post"><pre>';
for ($j = 0; $j < sizeof($dbfs); ++$j) {
    $checked = ($j == 0) ? 'checked' : '';
    printf(' %15s <input type="radio" name="tgval" value="%s" %s />', $nms[$j], $dbfs[$j], $checked);
    echo "\n";
}
echo '<input type="submit" value="OK" />';
echo '</pre></form>';

echo <<< _TAIL1
</body>
</html>
_TAIL1;

?>
