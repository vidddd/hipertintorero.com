<?php
opcache_reset();
echo "33333";

$servername = "localhost";
$username = "drupal2";
$password = "%z25nBv6";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
phpinfo();
?> 