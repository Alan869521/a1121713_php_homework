<?php
setcookie("uID", "", time()-3600);
header("Location: login.php");
?>