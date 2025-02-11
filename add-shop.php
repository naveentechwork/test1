<?php
// Include the functions file to handle DB connection and insertion
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $name = isset($_POST['shopName']) ? $_POST['shopName'] : '';
    $address = isset($_POST['shopAddress']) ? $_POST['shopAddress'] : '';
    $owner = isset($_POST['shopOwner']) ? $_POST['shopOwner'] : '';
    $mobile = isset($_POST['shopMobile']) ? $_POST['shopMobile'] : '';
    
    // Handle image upload
    $image = isset($_FILES['image']) ? $_FILES['image'] : null;
    $imagePath = '';

    // Validate the input
    if (empty($name) || empty($address) || empty($owner) || empty($mobile)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill out all fields.']);
        exit();
    }

    // Check if an image was uploaded
    if ($image && $image['error'] == 0) {
        // Check file type and size (optional)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($image['type'], $allowedTypes)) {
            // Generate a unique file name to avoid overwriting
            $uploadDir = 'uploads/';  // Specify the upload directory
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);  // Create the directory if it doesn't exist
            }

            $uniqueName = uniqid() . '-' . basename($image['name']);
            $uploadFilePath = $uploadDir . $uniqueName;

            // Move the uploaded file to the upload directory
            if (move_uploaded_file($image['tmp_name'], $uploadFilePath)) {
                $imagePath = $uploadFilePath;  // Save the path to the image
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid image format.']);
            exit();
        }
    }

    // Call the function to add the shop to the database (including image path if uploaded)
    $response = addShop($name, $address, $owner, $mobile, $uniqueName);

    // Return a JSON response
    echo json_encode(['status' => 'success', 'message' => $response]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
