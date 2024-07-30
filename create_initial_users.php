<?php
include 'config.php';

function createUser($username, $password, $email, $role, $conn) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $hashed_password, $email, $role);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "User $username created successfully.<br>";
    } else {
        echo "Error creating user $username.<br>";
    }
}

createUser('admin', 'admin_password', 'admin@example.com', 'admin', $conn);
createUser('student', 'student_password', 'student@example.com', 'student', $conn);

$conn->close();
?>
