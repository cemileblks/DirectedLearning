<?php
session_start();
require_once 'login.php';
echo <<<_HEAD
<html>
<head>
<title>let_me_in2.php</title>
<link rel="stylesheet" href="https://bioinfmsc8.bio.ed.ac.uk/Als_stylesheet_IWD.css">
</head>
<body>
<div style="padding-left: 75px;">
_HEAD;
// echo "<br/>Currently using the $database as user $username and password $password... " ;
$shortpass = substr($password,1,3) ;
echo "<div style=\"padding-left: 75px;\">
  <h1>Database access request result</h1>
  ";

echo "Attempting to connect you:
<ul><li>to the <b>$database</b> database</li>
<li> as user <b>$username</b> </li>
<li> with a password that contains the string <i>$shortpass</i> </li></ul>";
try {
        $dsn = "mysql:host=127.0.0.1;dbname=$database;charset=utf8mb4";
        $conn = new PDO($dsn, $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "\nConnected you successfully to the <b>$database</b> database!<br/>
            \n<p>Hah, <b><font color=\"red\">I know your MySQL password now</font></b>, as you typed <i>...",$shortpass,"...</i> into the box...</p>
            \nInitiating example query (selecting all rows where <code>cathv2.cat</code> is 2)...";
            $sql = 'SELECT DISTINCT * FROM cathv2 WHERE cat = ?';
            $stmt = $conn->prepare($sql) ;
            $stmt->execute([2]);
$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
$num_rows = count($arr);
echo "\n<br/>There were $num_rows rows of data returned from <code>$database</code> using the query I have coded in...<br/>" ;

if ($num_rows > 0) {
    // 'cat' 'arch' 'topol' 'homol' 'repchn' 'name'
  echo "\n<br/><table><tr><th>ID</th><th>cat</th><th>arch</th><th>topol</th><th>homol</th><th>repchn</th><th>name</th></tr>";
  // output data of each row
foreach ($arr as $record) {
    echo "\n<tr>" ;
    foreach ($record as $key => $value) {
        echo "<td>$value</td>" ;
    }
    echo "</tr>" ;
}
echo "\n</table>" ;
} else {
exit('No rows selected, sorry!');
}
$stmt = null;
} catch(PDOException $e) {
          echo "<br/><br/><b><font color=\"red\">Connection failed</font></b>:<br/>" . $e->getMessage();
}

echo <<<_TAIL
</body>
</html>
_TAIL;
session_destroy() ;
?>
