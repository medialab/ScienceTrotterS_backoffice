<?php

$sPath = UPLOAD_PATH.$_GET['hash'];
var_dump($hash);
var_dump($sPath);


if (!file_exists($sPath)) {
	http_response_code(404);
	exit;
}

$finfo = finfo_open( FILEINFO_MIME_TYPE );
$mtype = finfo_file( $finfo,  $sPath);
finfo_close( $finfo );

header('Content-Type: '.$mtype);
echo file_get_contents($sPath);

//unlink($sPath);
exit;