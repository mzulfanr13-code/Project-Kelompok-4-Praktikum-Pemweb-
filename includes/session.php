<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: /login.php");
        exit;
    }
}

function require_admin()
{
    require_login();

    if ($_SESSION['role'] !== 'admin') {
        header("Location: /index.php");
        exit;
    }
}


function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function is_admin(): bool
{
    return isset($_SESSION['role']) &&
           $_SESSION['role'] === 'admin';
}