<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Error: This email is already registered.";
    } else {
        // Insert into users table
        $stmt = $conn->prepare("INSERT INTO users (role, first_name, last_name, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $role, $first_name, $last_name, $email, $password);

        if ($stmt->execute()) {
            $user_id = $conn->insert_id;

            // Insert into role-specific table
            if ($role == 'farmer') {
                $stmt = $conn->prepare("INSERT INTO farmers (user_id, first_name, last_name, email) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $user_id, $first_name, $last_name, $email);
            } elseif ($role == 'buyer') {
                $stmt = $conn->prepare("INSERT INTO buyers (user_id, first_name, last_name, email) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $user_id, $first_name, $last_name, $email);
            } elseif ($role == 'expert') {
                $career = $_POST['career'];
                $stmt = $conn->prepare("INSERT INTO experts (user_id, career, first_name, last_name, email) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $user_id, $career, $first_name, $last_name, $email);
            }

            if ($stmt->execute()) {
                echo "Registration successful! Await admin approval.";
            } else {
                echo "Error inserting into role-specific table: " . $stmt->error;
            }
        } else {
            echo "Error inserting into users table: " . $stmt->error;
        }
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InumaConnect</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <h1>Welcome to InumaConnect</h1>
<form method="POST">
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="last_name" placeholder="Last Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <select name="role" id="role" required>
        <option value="farmer">Farmer</option>
        <option value="buyer">Buyer</option>
        <option value="expert">Expert</option>
    </select>
    <div id="career-field" style="display: none;">
        <input type="text" name="career" placeholder="Career (for Experts)">
    </div>
    <button type="submit">Register</button>
</form>

<script>
    const roleSelect = document.getElementById('role');
    const careerField = document.getElementById('career-field');
    
    roleSelect.addEventListener('change', function() {
        if (this.value === 'expert') {
            careerField.style.display = 'block';
        } else {
            careerField.style.display = 'none';
        }
    });
</script>
</body>
</html>
