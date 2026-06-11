<?php
/* logout.php — Hapus sesi dan redirect ke login */

require_once 'includes/session.php';

session_unset();
session_destroy();

header("Location: login.php");
exit;
