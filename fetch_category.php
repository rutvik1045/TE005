<?php
include('admin/config/dbcon.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$query = "SELECT * FROM category ORDER BY ID DESC;";
$query_run = mysqli_query($conn, $query);

$result_array = array();

if (mysqli_num_rows($query_run) > 0) {
    while ($row = mysqli_fetch_assoc($query_run)) {
        $result_array[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($result_array);
} else {
    header('Content-Type: application/json');
    echo json_encode(array());
}
?>
