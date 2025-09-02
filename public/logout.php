<?php

    include "../libs/load.php";

    if (isset($_GET['logout'])) {
        if (Session::isset("session_token")) {
            $Session = new UserSession(Session::get("session_token"));
            if ($Session->removeSession()) {
                Session::destroy();
                header("Location: " . $_SERVER["REQUEST_URI"] . "../");
                exit;
            } else {
                Session::destroy();
                header("Location: " . $_SERVER["REQUEST_URI"] . "../");
                exit;
            }
        }
    }
    Session::destroy();
    header("Location: " . $_SERVER["REQUEST_URI"] . "../");
    exit;
?>