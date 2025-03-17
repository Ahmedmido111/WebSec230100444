<?php
// ...existing code...

/* Database connection */
$cfg['Servers'][$i]['host'] = 'localhost';
$cfg['Servers'][$i]['user'] = 'your_db_user';
$cfg['Servers'][$i]['password'] = 'your_db_password';
$cfg['Servers'][$i]['dbname'] = 'your_db_name';

// Create connection
$conn = new mysqli($cfg['Servers'][$i]['host'], $cfg['Servers'][$i]['user'], $cfg['Servers'][$i]['password'], $cfg['Servers'][$i]['dbname']);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ...existing code...
?>
