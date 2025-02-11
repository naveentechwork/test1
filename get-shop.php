<?php
// Include database connection functions
include 'functions.php';

header('Content-Type: application/json');

// Fetch the shop list from the database
$pdo = getDBConnection();
$sql = "SELECT * FROM shops";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$shops = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If shops exist, return them as a JSON response
if ($shops) {
    echo json_encode(['status' => 'success', 'shops' => $shops]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No shops found']);
}
?>
