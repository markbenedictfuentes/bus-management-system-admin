<?php
session_start();

if (!isset($_GET['file'])) {
    die("No file specified.");
}

$file = basename($_GET['file']); 

$documentRoot = $_SERVER['DOCUMENT_ROOT']; 

$filepath = $documentRoot . "/private/visitors/uploads/complaints/" . $file;

if (!file_exists($filepath)) {
    die("File not found at: " . $filepath);
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $filepath);
finfo_close($finfo);

header("Content-Type: " . $mime);
header("Content-Disposition: attachment; filename=\"" . $file . "\"");
header("Content-Length: " . filesize($filepath));
readfile($filepath);
exit();
?>
