<?php
session_start();
require_once 'login.php';
include 'redir.php';

echo<<<_HEAD1
<html>
<body>
_HEAD1;

include 'menuf.php';

try {
  $pdo = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $query = "SELECT * FROM Manufacturers";
  $stmt = $pdo->query($query);
  $manufacturers = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $rows = count($manufacturers);

} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

// Build the manufacturer bitmask filter from session
$smask = $_SESSION['supmask'];
$firstmn = false;
$mansel = "(";

$sid = [];
$snm = [];
$sact = [];

foreach ($manufacturers as $index => $row) {
    $sid[$index] = $row['id'];
    $snm[$index] = $row['name'];
    $sact[$index] = 0;

    $tvl = 1 << ($row['id'] - 1);
    if (($tvl & $smask) == $tvl) {
        $sact[$index] = 1;
        if ($firstmn) $mansel .= " OR ";
        $firstmn = true;
        $mansel .= "(ManuID = " . $row['id'] . ")";
    }
}
$mansel .= ")";

// Check if the form was submitted
$setpar = isset($_POST['natmax']);

echo <<< _MAIN1
    <pre>
This is the catalogue retrieval Page  
    </pre>
_MAIN1;

if ($setpar) {
    $firstsl = false;
    $conditions = [];

    // Check for each property, and build a WHERE condition
    if ($_POST['natmax'] != "" && $_POST['natmin'] != "") {
        $conditions[] = "(natm > :natmin AND natm < :natmax)";
        $firstsl = true;
    }
    if ($_POST['ncrmax'] != "" && $_POST['ncrmin'] != "") {
        $conditions[] = "(ncar > :ncrmin AND ncar < :ncrmax)";
        $firstsl = true;
    }
    if ($_POST['nntmax'] != "" && $_POST['nntmin'] != "") {
        $conditions[] = "(nnit > :nntmin AND nnit < :nntmax)";
        $firstsl = true;
    }
    if ($_POST['noxmax'] != "" && $_POST['noxmin'] != "") {
        $conditions[] = "(noxy > :noxmin AND noxy < :noxmax)";
        $firstsl = true;
    }

    echo "<pre>";
    if ($firstsl) {
        // Combine conditions with AND
        $whereClause = implode(" AND ", $conditions);
        $query = "SELECT catn FROM Compounds WHERE ($whereClause) AND $mansel";
        echo "Running query:\n$query\n";

        try {
            $stmt = $pdo->prepare($query);

            // Bind values only if they were set
            if (isset($_POST['natmin'], $_POST['natmax'])) {
                $stmt->bindValue(':natmin', $_POST['natmin'], PDO::PARAM_INT);
                $stmt->bindValue(':natmax', $_POST['natmax'], PDO::PARAM_INT);
            }
            if (isset($_POST['ncrmin'], $_POST['ncrmax'])) {
                $stmt->bindValue(':ncrmin', $_POST['ncrmin'], PDO::PARAM_INT);
                $stmt->bindValue(':ncrmax', $_POST['ncrmax'], PDO::PARAM_INT);
            }
            if (isset($_POST['nntmin'], $_POST['nntmax'])) {
                $stmt->bindValue(':nntmin', $_POST['nntmin'], PDO::PARAM_INT);
                $stmt->bindValue(':nntmax', $_POST['nntmax'], PDO::PARAM_INT);
            }
            if (isset($_POST['noxmin'], $_POST['noxmax'])) {
                $stmt->bindValue(':noxmin', $_POST['noxmin'], PDO::PARAM_INT);
                $stmt->bindValue(':noxmax', $_POST['noxmax'], PDO::PARAM_INT);
            }

            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $rowCount = count($results);
            if ($rowCount > 100) {
                echo "Too many results ($rowCount). Max is 100.\n";
            } else {
                foreach ($results as $catn) {
                    echo $catn . "\n";
                }
            }

        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    } else {
        echo "No query given.\n";
    }

    echo "</pre>";
}
// echo "Bitmask (supmask): $smask\n";
// print_r($results);

// Render the input form again
echo <<< _TAIL1
   <form action="p2.php" method="post"><pre>
       Max Atoms      <input type="text" name="natmax"/>    Min Atoms    <input type="text" name="natmin"/>
       Max Carbons    <input type="text" name="ncrmax"/>    Min Carbons  <input type="text" name="ncrmin"/>
       Max Nitrogens  <input type="text" name="nntmax"/>    Min Nitrogens<input type="text" name="nntmin"/>
       Max Oxygens    <input type="text" name="noxmax"/>    Min Oxygens  <input type="text" name="noxmin"/>
                   <input type="submit" value="list" />
</pre></form>

</body>
</html>
_TAIL1;
?>