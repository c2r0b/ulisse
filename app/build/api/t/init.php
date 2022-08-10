<?
include_once "../../basic.php";

// deny access if not logged in or the account type is incorrect
if (!account() || $_SESSION['type'] != 2)	exit;

// connect to DB
$conn = connect();
