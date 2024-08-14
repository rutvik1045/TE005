<?php
include('admin/config/dbcon.php'); 

header('Content-Type: application/json');

// Function to truncate description to a maximum of 20 words
function truncateDescription($description, $wordLimit = 4) {
    $words = explode(' ', $description);
    if (count($words) > $wordLimit) {
        $words = array_slice($words, 0, $wordLimit);
        return implode(' ', $words) . '...';
    }
    return $description;
}

// Check database connection
if (!$conn) {
    echo json_encode(array('error' => 'Database connection failed.'));
    exit();
}

// Query to get the popular posts (you might want to adjust this to your logic)
$query = "
    SELECT p.id, p.title, p.description, p.img
    FROM blog_page p
    ORDER BY p.id DESC
    LIMIT 3
";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    $error = mysqli_error($conn);
    echo json_encode(array('error' => 'Database query failed: ' . $error));
    exit();
}

// Prepare the posts array
$posts = array();
while ($row = mysqli_fetch_assoc($result)) {
    $description = truncateDescription($row['description']);

    $posts[] = array(
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $description,
        'image' => 'admin/uploads/' . $row['img'] // Concatenating the path correctly
    );
}

// Return the posts as JSON
echo json_encode($posts);
?>
