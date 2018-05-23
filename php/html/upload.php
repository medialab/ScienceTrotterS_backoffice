<?php

$sPath = UPLOAD_PATH.$_GET['hash'];

if (!file_exists($sPath)) {
	http_response_code(404);
}

$finfo = finfo_open( FILEINFO_MIME_TYPE );
$mtype = finfo_file( $finfo,  $sPath);
finfo_close( $finfo );

header('Content-Type: '.)
echo file_get_contents($sPath);

unlink($sPath);
exit;