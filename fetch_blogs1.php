<?php
// fetch_blogs.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // Number of blogs per page
$offset = ($page - 1) * $limit;

// Fetch blogs
$sql = "SELECT * FROM blogs LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$blogs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $blogs[] = $row;
    }
}

// Get total count
$countSql = "SELECT COUNT(*) as total FROM blogs";
$countResult = $conn->query($countSql);
$totalCount = $countResult->fetch_assoc()['total'];

$conn->close();

$response = [
    'blogs' => $blogs,
    'totalCount' => $totalCount
];

header('Content-Type: application/json');
echo json_encode($response);
?>
