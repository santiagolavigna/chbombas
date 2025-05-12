<?php

ob_start();

$p = $_REQUEST['p'] ?? '';

switch ($p) {
    case 'bombas':
        require_once('includes/load_for_api.php');
        echo json_encode(getBombas());
        break;

    default:
        require_once('includes/load.php');

        $session = new Session();
        $msg = $session->msg();
        if ($session->isUserLoggedIn(true)) {
            $user = current_user();
        }

        $thisPage = "?p=" . $p;

        $REQ = explode("|", str_replace("?p=", "", $thisPage));
        if (count($REQ) === 2) {
            $onlyModule = $REQ[0];
            $onlyPage = $REQ[1];
        } else {
            $onlyPage = $REQ[0];
        }

        if ($session->isUserLoggedIn(true)) {
            if (isset($_SESSION['PAGINA_ANTERIOR'])) {
                $_SESSION['PAGINA_ANTERIOR_2'] = $_SESSION['PAGINA_ANTERIOR'];
            }
            if (isset($_SESSION['PAGINA_ACTUAL'])) {
                $_SESSION['PAGINA_ANTERIOR'] = $_SESSION['PAGINA_ACTUAL'];
            }
            $_SESSION['PAGINA_ACTUAL'] = $thisPage;
        }

        include_once('layouts/_head.php');

        if ($session->isUserLoggedIn(true)) {
            include_once('layouts/_header.php');
            include_once('layouts/_sidebarGenerator.php');
        }

        if ($session->isUserLoggedIn(true)) {
            echo '<div class="page"> <div id="principalPage" class="container-fluid">';
            include_once('layouts/_pageLoader.php');
            echo '</div></div>';
        } else {
            include_once('layouts/_pageLoader.php');
        }

        include_once('layouts/_footer.php');
        break;
}
?>
