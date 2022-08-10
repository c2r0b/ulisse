<?
// destroy session
session_start();
session_destroy();

// redirect to the homepage
header("location: redirect.php?page=login");
