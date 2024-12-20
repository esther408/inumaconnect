<?php
session_start();
if ($_SESSION['role'] !== 'farmer') {
    header("Location: ../authentication/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>Welcome Farmer!</h1>
    <p>Manage your products and orders here.</p>
    <a href="../authentication/logout.php">Logout</a>
</body>
</html>
