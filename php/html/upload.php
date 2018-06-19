<?php

if (empty($_GET['hash'])) {
	http_response_code(404);
	exit;
}

$sPath = UPLOAD_PATH.$_GET['hash'];

$sPath = str_replace('/', DIRECTORY_SEPARATOR, $sPath);

if (!file_exists($sPath)) {
	http_response_code(404);
	exit;
}

$finfo = finfo_open( FILEINFO_MIME_TYPE );
$mtype = finfo_file( $finfo,  $sPath);
finfo_close( $finfo );

header('Content-Type: '.$mtype);
echo file_get_contents($sPath);

unlink($sPath);
exit;