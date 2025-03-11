<?php
session_start();

function isAuthenticated()
{
	return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

if (isAuthenticated()) {
	header("Location: pages/layouts/app.php?page=dashboard");
	exit();
} else {
	header("Location: pages/auth/login.php");
	exit();
}
