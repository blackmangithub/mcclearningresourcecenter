<?php
include('authentication.php');

$query = isset($_POST['query']) ? $_POST['query'] : '';

if (!empty($query)) {
    $sql = "SELECT *
            FROM book
            WHERE title LIKE ?
            GROUP BY title, author, copyright_date, isbn";

    $stmt = mysqli_prepare($con, $sql);

    $searchTerm = "%{$query}%";
    mysqli_stmt_bind_param($stmt, 's', $searchTerm);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $results = array();

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
    }

    echo json_encode(['results' => $results]);

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['results' => []]);
}

mysqli_close($con);
?>
