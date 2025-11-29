<?php
// Session management and authentication helpers
session_start();
// For security, call session_regenerate_id(true) after successful login (see login.php)

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is an admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Check if user is a client
function isClient() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'client';
}

// Redirect to login page if not logged in
function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: login.php"); // Use absolute path if needed
        exit();
    }
}

// Redirect to index if already logged in (except on register page)
function redirectIfLoggedIn() {
    // Do not redirect if on register page
    if (basename($_SERVER['PHP_SELF']) === 'register.php') {
        return;
    }
    if (isLoggedIn()) {
        header("Location: index.php"); // Use absolute path if needed
        exit();
    }
}
?>