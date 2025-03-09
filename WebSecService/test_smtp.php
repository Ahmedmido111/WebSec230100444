<?php

$host = 'smtp.gmail.com';
$port = 587;

$connection = @fsockopen($host, $port, $errno, $errstr, 10);

if (!$connection) {
    echo "Connection to $host on port $port failed: $errstr ($errno)\n";
} else {
    echo "Connection to $host on port $port succeeded.\n";
    fclose($connection);
}
