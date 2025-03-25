<?php
session_start();                   // Start or resume the current session
require_once 'login.php';          // Load database credentials
include 'redir.php';               // Redirect if user isn't logged in

echo <<< _HEAD1
<html>
<body>
_HEAD1;

include 'menuf.php';               // Include the navigation menu

try {
  // create a new PDO instance
  $pdo = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
  // set PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $query = "SELECT * FROM Manufacturers";
  $stmt = $pdo->query($query);
  $manufacturers = $stmt->fetchAll(PDO::FETCH_ASSOC); // Get all rows as associative arrays
  $rows = count($manufacturers);

} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

// Read the current selection bitmask from the session
$smask = $_SESSION['supmask'];

$sid = [];   // Manufacturer IDs
$snm = [];   // Manufacturer names
$sact = [];  // Which ones are currently active (selected)

// Loop through manufacturers and build selection arrays
foreach ($manufacturers as $index => $row) {
  $sid[$index] = $row['id'];
  $snm[$index] = $row['name'];
  $sact[$index] = 0;

  $tvl = 1 << ($sid[$index] - 1);       // Calculate the bit for this supplier
  if (($tvl & $smask) == $tvl) {
      $sact[$index] = 1;               // Mark as selected if the bit is set
  }
}

if(isset($_POST['supplier'])) 
   {
     $supplier = $_POST['supplier'];
     $nele = sizeof($supplier);
      for($k = 0; $k <$rows; ++$k) {
       $sact[$k] = 0;
       for($j = 0 ; $j < $nele ; ++$j) {
	 if(strcmp($supplier[$j],$snm[$k]) == 0) $sact[$k] = 1;
       }
     }
     $smask = 0;
     for($j = 0 ; $j < $rows ; ++$j)
       {
	 if($sact[$j] == 1) {
	   $smask = $smask + (1 << ($sid[$j] - 1));
	 }
       }
     $_SESSION['supmask'] = $smask;
   }
   echo 'Currently selected Suppliers: ';
   for($j = 0 ; $j < $rows ; ++$j)
      {
    	if($sact[$j] == 1) {
	  echo $snm[$j] ;
	  echo " ";
	}
      }
    echo  '<br><pre> <form action="p1.php" method="post">';
    for($j = 0 ; $j < $rows ; ++$j)
      {
    	echo $snm[$j];
	echo' <input type="checkbox" name="supplier[]" value="';
	echo $snm[$j];
        echo'"/>';
	echo"\n";
      }
echo <<<_TAIL1
 <input type="submit" value="OK" />
</pre></form>
</body>
</html>
_TAIL1;
?>
