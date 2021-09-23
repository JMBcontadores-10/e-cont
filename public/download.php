<?php

$filepath = __DIR__ . $_SERVER['REQUEST_URI'];
if (file_exists($filepath)) {
    header('Content-Type: ' . (new finfo())->file($filepath, FILEINFO_MIME));
    readfile($filepath);
    return;
}

header('HTTP/1.0 404 Not found', true, 404);
