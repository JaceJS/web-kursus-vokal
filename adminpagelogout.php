<?php
session_start();
session_destroy();
header("Location: adminpagelogin.php");
exit;
?>