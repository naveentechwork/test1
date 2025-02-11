<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'functions.php';

function updateShop($id, $name, $address, $owner, $mobile, $imagePath = null) {
    $pdo = getDBConnection();

    if ($pdo === null) {
        return 'Database connection failed';
    }

    // Prepare the SQL query to update shop data, including the image if it's provided
    $sql = "UPDATE shops SET name = :name, address = :address, owner = :owner, mobile = :mobile" . 
           ($imagePath ? ", image = :image" : "") . " WHERE id = :id";
    
    // Prepare the statement and bind parameters
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':owner', $owner);
    $stmt->bindParam(':mobile', $mobile);
    
    if ($imagePath) {
        $stmt->bindParam(':image', $imagePath);
    }

    // Execute the query and return a success or error message
    if ($stmt->execute()) {
        return ''; // No error message
    } else {
        return 'Failed to update shop. Please try again.';
    }
}

// Handle image upload
function handleImageUpload($image) {
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
                return $uniqueName;  // Return the image path
            } else {
                return null;  // Return null if the upload fails
            }
        }
    }
    return null;  // Return null if no image is uploaded or invalid file
}

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    // Sanitize and validate inputs
    $id = $_POST['id'];
    $name = trim($_POST['shopName']);
    $address = trim($_POST['shopAddress']);
    $owner = trim($_POST['shopOwner']);
    $mobile = trim($_POST['shopMobile']);

    // Handle image upload
    $image = isset($_FILES['image']) ? $_FILES['image'] : null;
    $imagePath = handleImageUpload($image);  // Process image upload

    // Validate input (basic validation, you can expand it)
    if (!empty($name) && !empty($address) && !empty($owner) && !empty($mobile)) {
        // Call the function to update the shop
        $result = updateShop($id, $name, $address, $owner, $mobile, $imagePath);
        
        // If the update is successful, redirect to the previous page
        if ($result === '') {
            // Redirect back to the shop details page
            header("Location: shop-details.php?id=" . $id);
            exit();
        } else {
            $result = 'Error: ' . $result;
        }
    } else {
        $result = 'All fields are required.';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Shop</title>
    <link rel="stylesheet" href="style_app.css">
</head>
<body>
    <div class="container1" style="padding:10px;">
        <h1>Edit Shop</h1>

        <?php
        // Display the result message if form is submitted
        if (isset($result)) {
            echo '<p>' . htmlspecialchars($result) . '</p>';
        }
        
        // Check if an ID is provided, if so, fetch the shop data
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $shop = getShopById($id);

            if ($shop) {
                // Show form with existing shop data
        ?>
                <form id="editShopForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($shop['id']); ?>">
                    
                    <label for="shopName">Shop Name</label>
                    <input type="text" id="shopName" name="shopName" value="<?php echo htmlspecialchars($shop['name']); ?>" required>

                    <label for="shopAddress">Shop Address</label>
                    <input type="text" id="shopAddress" name="shopAddress" value="<?php echo htmlspecialchars($shop['address']); ?>" required>
                    
                    <label for="shopOwner">Owner</label>
                    <input type="text" id="shopOwner" name="shopOwner" value="<?php echo htmlspecialchars($shop['owner']); ?>" required>
                    
                    <label for="shopMobile">Mobile</label>
                    <input type="text" id="shopMobile" name="shopMobile" value="<?php echo htmlspecialchars($shop['mobile']); ?>" required>

                    <!-- Add image upload input -->
                    <img src="uploads/<?=$shop['image']?>" style="width:100px;">
                    <label for="image">Shop Image (Optional)</label>
                    <input type="file" id="image" name="image" accept="image/*">

                    <button type="submit">Save Changes</button>
                </form>
                
        <?php
            }  
        } 
        ?>
    </div>
</body>
</html>
