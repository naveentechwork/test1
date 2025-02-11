<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection functions
include 'functions.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get form values
  $name = $_POST['workerName'];
  $address = $_POST['workerAddress'];
  $mobile = $_POST['workerMobile'];
  $image = $_FILES['workerImage']; // Image file input

  // Validate the required fields
  if (empty($name) || empty($address) || empty($mobile)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill out all fields.']);
    exit;
  }

  // Validate and upload the image if provided
  $imagePath = null;
  if (!empty($image['name'])) {
    // Define the allowed file types and size
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB max size

    // Validate the image type and size
    if (!in_array($image['type'], $allowedTypes)) {
      echo json_encode(['status' => 'error', 'message' => 'Invalid image type. Only JPG, PNG, and GIF are allowed.']);
      exit;
    }

    if ($image['size'] > $maxSize) {
      echo json_encode(['status' => 'error', 'message' => 'Image size exceeds the maximum allowed size of 5MB.']);
      exit;
    }

    // Generate a unique file name to avoid overwriting
    $imagePath = 'uploads/' . uniqid() . '-' . basename($image['name']);

    // Move the uploaded file to the server's upload directory
    if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
      echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
      exit;
    }
  }

  // Insert the worker data into the database
  $pdo = getDBConnection();
  $sql = "INSERT INTO worker_list (manager_id, password, name, address, image, join_date, mobile) 
          VALUES (1, 123, ?, ?, ?, NOW(), ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$name, $address, $imagePath, $mobile]);

  // Return a JSON response
  echo json_encode(['status' => 'success', 'message' => 'Worker added successfully']);
  exit;
}
?>
