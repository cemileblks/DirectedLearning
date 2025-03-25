<?php
session_start();
include 'redir.php';

// Clear session BEFORE any output
$fn = isset($_SESSION['forname']) ? htmlspecialchars($_SESSION['forname']) : 'User';

// Clear session variables
$_SESSION = array();

// Expire session cookie
if (session_id() != "" || isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 2592000, '/');
}

// Destroy the session
session_destroy();
?>
<html>
<body>
<?php include 'menuf.php'; ?>
<pre>
    Goodbye <?php echo $fn; ?>;
    You have now exited Complib.
</pre>
</body>
</html
