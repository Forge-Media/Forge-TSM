<?php
$dir    = dirname(__FILE__);
$files1 = scandir($dir);

echo json_encode($files1);

?>