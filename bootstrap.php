<?php

$sPage = empty($_GET['name']) ? 'index' : $_GET['name'];
$sExt = empty($_GET['extension']) ? 'html' : $_GET['extension'];
$sExtFile = ( $sExt == 'html' ) ? 'tpl' : $sExt;
$sContent = '';
// Functions
      require_once('./lib/functions.php');
//---


// Smarty


        require_once('./lib/smarty/Smarty.class.php');
        $smarty = new Smarty();
# exit();

        // $smarty->force_compile = true;
//---

// Bdd
        // if ( !in_array($sExt, ['css', 'js']) ) {
                // $aDatabase = [
                //      'server' => '',
                //      'database' => '',
                //      'user' => '',
                //      'password' => ''
                // ];
                // $sMysqlPdoDsn = ( 'mysql:host='. $aDatabase['server'] .';dbname='. $aDatabase['database'] );
                // $oMysqlPdo = new PDO( $sMysqlPdoDsn, $aDatabase['user'], $aDatabase['password'], [] );
                // $oMysqlPdo->exec( 'SET CHARACTER SET utf8;' );
        // }
//---

session_start();
ApiMgr::init();

// On vérifie que l'utilisateur aie le droit d'accéder à  cette page
    require( "./access.php" );
    if ( in_array($sExt, $aRestrictExtension) ){
        // On est dans une extension controlée
            if( empty($_SESSION['user']['token']) && !in_array( "{$sPage}.{$sExt}", $aAccessUtilisateur ) ){
                $ext = strtoupper($sExt);
                if (!empty($aAccess[$ext]['redirection'])) {
                    $redir = $aAccess[$ext]['redirection'];
                }
                elseif (!empty($aAccessUtilisateur['redirection'])) {
                    $redir = $aAccessUtilisateur['redirection'];
                }
                else{
                    $redir = '/';
                }

                /*var_dump($sExt);
                var_dump($aAccess, $aAccessUtilisateur);
                var_dump("Redirection: ".$redir);
                exit;*/

                if ('/'.$sPage.'.'.$sExt !== $redir) {
                    header( 'location: '.$redir );
                    exit();
                }
            }
        // ---
    }
// ---

if (in_array($sExt, ['js', 'css'])) {
    if (file_exists('./templates/'.$sExt.'/'.$sPage.'.'.$sExtFile)) {
        header('Content-Type: text/'.$sExt);
        echo file_get_contents('./templates/'.$sExt.'/'.$sPage.'.'.$sExtFile);
        exit;
    }
    else{
        header('HTTP/1.1: 404');
    }
    exit;
}
else {
    header('Content-Type: text/html');
}

$libPath = './php/';
$viewPath = '';
$files = explode('/', $sExt.'/'.$sPage);

$i = 0;
$f = '';
$sContent = '';
$tplFiles = [];
foreach ($files as $file) {
    if (strlen($f)) {
        $f .= '/';
    }

    $f .= $file;

        /*var_dump($libPath.$f.'.php');*/
    if (file_exists($libPath.$f.'.php')) {
        /*var_dump($libPath.$f.'.php');*/
        require_once($libPath.$f.'.php');
    }

    
    if ($i > 0 && file_exists('./templates/'.$viewPath.$f.'.tpl')) {
        $tplFiles[] = $viewPath.$f.'.tpl';
    }

    $i++;
}

foreach ($tplFiles as $f) {
    $sContent .= $smarty->fetch($f);
}

$smarty->assign('sPageContent', $sContent);

if (!empty($aFilDArianne)) {
    $smarty->assign('aFilDArianne', $aFilDArianne);
}
else{
    $smarty->assign('aFilDArianne', []);
}

if ( file_exists('./templates/'.$sExt.'.'.$sExtFile) ) {
    $sContent = $smarty->fetch($sExt.'.'.$sExtFile);
}


echo $sContent;
#exit();
