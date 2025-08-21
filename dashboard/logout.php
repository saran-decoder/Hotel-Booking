<?php

    include "../libs/load.php";

    if (isset($_GET['logout'])) {
        if (Session::isset("session_token")) {
            $Session = new UserSession(Session::get("session_token"));
            if ($Session->removeSession()) {
                Session::destroy();
                header("Location: index");
                exit;
            } else {
                Session::destroy();
                header("Location: index");
                exit;
            }
        }
    }
    Session::destroy();
    header("Location: index");
    exit;
?>