<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit;
}
