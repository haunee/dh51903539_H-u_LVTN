<?php
$targetFolder = $_SERVER['DOCUMENT_ROOT'].'/page/storage/app/public';
$linkFolder = $_SERVER['DOCUMENT_ROOT'].'/page/storage';
symlink($targetFolder,$linkFolder);
echo 'Success';
?>