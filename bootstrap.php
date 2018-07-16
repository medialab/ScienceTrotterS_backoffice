<?php
// Récupération du Nom de la page
$sPage = empty($_GET['name']) ? 'index' : $_GET['name'];

// Récupération de l'extention de la page
$sExt = empty($_GET['extension']) ? 'html' : $_GET['extension'];

// Récupération de l'extention des fichiers
$sExtFile = ( $sExt == 'html' ) ? 'tpl' : $sExt;

//$sContent = '';
// Functions
      require_once('./lib/functions.php');
//---


// Smarty
        require_once('./lib/smarty/Smarty.class.php');
        $smarty = new Smarty();
        $smarty->assign('sPage', $sPage);
        $smarty->assign('sExt', $sExt);
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

// Initialisation De L' Api
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
        if ($sExt === 'js') {
            header('Content-Type: application/javascript');        
        }
        else{
            header('Content-Type: text/'.$sExt);        
        }

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


$viewPath = '';
$libPath = './php/';

// Les Fichiers à Charger
$files = explode('/', $sExt.'/'.$sPage);

$f = '';
$dFileCnt = 0;
$sContent = '';
$tplFiles = [];

foreach ($files as $file) {
    if (strlen($f)) {
        $f .= '/';
    }

    $f .= $file;

    // Chargement Du PHP
    if (file_exists($libPath.$f.'.php')) {
        require_once($libPath.$f.'.php');
    }
    
    // Chargement Du TPL
    if ($dFileCnt > 0 && file_exists(realpath('.').'/templates/'.$viewPath.$f.'.tpl')) {
        $tplFiles[] = $viewPath.$f.'.tpl';
    }
    else{
    }

    $dFileCnt++;
}

$smarty->assign([
    '_CSS_FILES' => $_CSS_FILES, 
    '_JS_FILES' => $_JS_FILES
]);

// Execution Des TPLs
foreach ($tplFiles as $f) {
    $sContent .= $smarty->fetch($f);
}

$smarty->assign('sPageContent', $sContent);

// Gestion Du Fil D'arianne
if (!empty($aFilDArianne)) {
    $smarty->assign('aFilDArianne', $aFilDArianne);
}
else{
    $smarty->assign('aFilDArianne', []);
}

// Chargement du TPL
if ( file_exists('./templates/'.$sExt.'.'.$sExtFile) ) {
    $sContent = $smarty->fetch($sExt.'.'.$sExtFile);
}

// Suppression Des Notifications En Session
unset($_SESSION['session_msg']);

// Affichage Du Contenu
echo $sContent;

