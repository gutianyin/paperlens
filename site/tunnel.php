<?php
$link = $_GET['link'];
echo file_get_contents(htmlspecialchars($link));
?>