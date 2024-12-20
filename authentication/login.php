<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists and is approved
    $stmt = $conn->prepare("SELECT id, password, role, approved FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $role, $approved);
        $stmt->fetch();

        if ($approved == 0) {
            echo "Your account is awaiting admin approval.";
        } elseif (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role;

            // Redirect to the appropriate dashboard
            if ($role == 'farmer') {
                header("Location: ../dashboard/farmer_dashboard.php");
            } elseif ($role == 'buyer') {
                header("Location: ../dashboard/buyer_dashboard.php");
            } elseif ($role == 'expert') {
                header("Location: ../dashboard/expert_dashboard.php");
            }
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "User does not exist.";
    }
    $stmt->close();
}
$conn->close();
?>
<!-- HTML Login Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <form method="POST">
        <h2>Login</h2>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
