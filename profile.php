<?php
session_start();
include 'config.inc.php';

// ...existing code...

// Fetch user roles and permissions from the database
$userId = $_SESSION['user_id'];
$query = "SELECT roles.name AS role, permissions.name AS permission 
          FROM user_roles 
          JOIN roles ON user_roles.role_id = roles.id 
          JOIN role_permissions ON roles.id = role_permissions.role_id 
          JOIN permissions ON role_permissions.permission_id = permissions.id 
          WHERE user_roles.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$roles = [];
$permissions = [];
while ($row = $result->fetch_assoc()) {
    $roles[] = $row['role'];
    $permissions[] = $row['permission'];
}

// Display roles and permissions
echo "Roles: " . implode(", ", $roles) . "<br>";
echo "Permissions: " . implode(", ", $permissions);

// ...existing code...
?>
