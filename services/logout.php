<?php
session_start();
session_unset();
session_destroy();
header('Location: /career_progression/pages/auth/login.php');
exit();
