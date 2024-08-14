<?php
include('admin/config/dbcon.php');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

header('Content-Type: application/json');

function truncateDescription($description, $wordLimit = 20) {
    $words = explode(' ', $description);
    if (count($words) > $wordLimit) {
        $words = array_slice($words, 0, $wordLimit);
        return implode(' ', $words) . '...';
    }
    return $description;
}

$tagName = isset($_GET['tag']) ? mysqli_real_escape_string($conn, $_GET['tag']) : '';

if (empty($tagName)) {
    echo json_encode(array('error' => 'Tag name is required'));
    exit();
}

$stmt = $conn->prepare("
    SELECT b.id, b.title, b.description, b.img as blog_image, 
           GROUP_CONCAT(DISTINCT t.name SEPARATOR ', ') as tags, 
           GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') as categories
    FROM blog_page b
    LEFT JOIN tag t ON FIND_IN_SET(t.id, b.tags) > 0
    LEFT JOIN category c ON FIND_IN_SET(c.id, b.category) > 0
    WHERE t.name = ?
    GROUP BY b.id
");
$stmt->bind_param("s", $tagName);
$stmt->execute();
$result = $stmt->get_result();

$blogs = array();
while ($row = $result->fetch_assoc()) {
    $description = truncateDescription($row['description']);

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
