<?php
    // If the session vars aren't set, try to set them with a cookie or redirect to login page
    if (!isset($_SESSION['user_id'])) {
        if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
            $_SESSION['user_id'] = $_COOKIE['user_id'];
            $_SESSION['username'] = $_COOKIE['username'];
        } else {
            header("Location: " . PG_LOGIN);
        }
    }
?>