<?php
include('admin/config/dbcon.php'); 

header('Content-Type: application/json');

// Function to truncate description to a maximum of 40 words
function truncateDescription($description, $wordLimit = 20) {
    $words = explode(' ', $description);
    if (count($words) > $wordLimit) {
        $words = array_slice($words, 0, $wordLimit);
        return implode(' ', $words) . '...'; // Adding '...' to indicate truncation
    }
    return $description;
}

$query = "
    SELECT b.id, b.title, b.description, b.img as blog_image, 
           GROUP_CONCAT(DISTINCT t.name SEPARATOR ', ') as tags, 
           GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') as categories
    FROM blog_page b
    LEFT JOIN tag t ON FIND_IN_SET(t.id, b.tags) > 0
    LEFT JOIN category c ON FIND_IN_SET(c.id, b.category) > 0
    GROUP BY b.id DESC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    $error = mysqli_error($conn);
    echo json_encode(array('error' => 'Database query failed: ' . $error));
    exit();
}

$blogs = array();
while ($row = mysqli_fetch_assoc($result)) {
    $description = truncateDescription($row['description']); // Apply truncation here

    $blogs[] = array(
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $description,
        'blog_image' => $row['blog_image'],
        'tags' => $row['tags'] ? $row['tags'] : 'No Tags',
        'categories' => $row['categories'] ? $row['categories'] : 'No Categories'
    );
}

echo json_encode($blogs);
?>
