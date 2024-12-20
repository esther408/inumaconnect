<?php
include 'db.php';

$result = $conn->query("SELECT id, first_name, last_name, email FROM users WHERE approved = 0");
while ($row = $result->fetch_assoc()) {
    echo "<div>";
    echo "<p>Name: " . $row['first_name'] . " " . $row['last_name'] . "</p>";
    echo "<p>Email: " . $row['email'] . "</p>";
    echo "<form method='POST' action='approve_user.php'>";
    echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
    echo "<button type='submit'>Approve</button>";
    echo "</form>";
    echo "</div>";
}
?>


