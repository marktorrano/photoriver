<?php
ob_start();

echo 'You have successfully signed up. You will be redirected shortly';
header("Refresh: 3; URL=index.php");

?>