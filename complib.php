<?php
session_start();
require_once 'login.php';
echo <<<_HEAD1
<html>
<body>
_HEAD1;

// THE CONNECTION AND QUERY SECTIONS NEED TO BE MADE TO WORK FOR PHP 8 USING PDO... //
try {
   // create a new PDO instance
   $pdo = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
   // set PDO error mode to exception
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   echo "Connected successfully";
   // SQL query
   $query = "SELECT * FROM Manufacturers";
   $result = $pdo->query($query);
   $rows = $result->rowCount();  // count number of rows
   $mask = 0;

   foreach ($result as $row) {
      $mask = (2 * $mask) + 1;  // build the binary mask
   }

   $_SESSION['supmask'] = $mask;
   $pdo = null; // close connection

} catch (PDOException $e) {
   // Handle database connection errors
   echo "Connection failed:" . $e->getMessage();
}

echo <<<_EOP
<script>
   function validate(form) {
   fail = ""
   if(form.fn.value =="") fail = "Must Give Forname "
   if(form.sn.value == "") fail += "Must Give Surname"
   if(fail =="") return true
       else {alert(fail); return false}
   }
</script>
<form action="indexp.php" method="post" onSubmit="return validate(this)">
  <pre>
       First Name<input type="text" name="fn"/>
       Second Name <input type="text" name="sn"/>
                   <input type="submit" value="go" />
</pre></form>
_EOP;

echo <<<_TAIL1
</pre>
</body>
</html>
_TAIL1;
